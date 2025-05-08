<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Accjurnal;
use App\Models\Pbhd;
use App\Models\Pbdt;
use App\Models\Popbhd;
use App\Models\Popbdt;

class PembelianController extends Controller
{
    public function pembelian()
    {
        $jumlahpopending = Pbhd::where('status', 1)->count();
        return view('pembelian.pembelian', compact(['jumlahpopending']));
    }

    public function tabelpb(Request $request)
    {
        $status = $request->status;
        $suplier_id = $request->suplier_id;

        $pbhd = Pbhd::with('suplier', 'user', 'useredit') // Load the 'satuan' relationship
            ->select('pbhd.*')        // Select all columns from the 'barang' table
            ->where(function ($query) use ($status) { // Conditional for 'status'
                if ($status != null) {
                    $query->where('status', $status);
                }
            })
            ->when($suplier_id, function ($query, $suplier_id) { // Conditional for 'suplier_id'
                $query->where('suplier_id', $suplier_id);
            });

        return DataTables::of($pbhd)
            ->addColumn('aksi', function ($pbhd) {
                $edit = '<a onclick="editdata(\'' . addslashes($pbhd->uuid) . '\')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ';
                $hapus = '<a onclick="hapus(\'' . addslashes($pbhd->uuid) . '\')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                return $edit . $hapus;
            })
            ->editColumn('jumlah', function ($pbhd) {
                return Rupiah0($pbhd->total);
            })
            ->editColumn('created_at', function ($pbhd) {
                return TanggalJam($pbhd->created_at);
            })
            ->editColumn('tanggal', function ($pbhd) {
                return datetotanggal($pbhd->tanggal);
            })
            ->rawColumns(['aksi'])
            ->setRowClass(function ($pbhd) {
                if ($pbhd->status == 2) {
                    return '';
                } else {
                    return 'table-warning';
                }
            })
            ->make(true);
    }
    public function listpopending()
    {
        $pbhd = Pbhd::where('status', 1)->get();
    }
    public function tambahpbbaru()
    {
        $input = [
            'invoice' => kode(4), // kode po
            'tanggal' => now(),
            'suplier_id' => 1,
            'user_id' => Auth()->User()->id,
            'persenpajak' => globalconfigdata('ppnpb'), // kode po
            'tanggal_kirim' => now(),
            'keterangan' => '-',
            'uuid' => Str::uuid(),
        ];
        $pbhd = Pbhd::create($input);

        return [
            'success' => 1,
            'uuid' => $pbhd->uuid
        ];
    }
    public function editpb($uuid)
    {
        $pbhd = Pbhd::where('uuid', $uuid)->firstOrFail();
        $popbhd = Popbhd::where('status', 2)->orderBY('tanggal', 'DESC')->limit(100)->get();
        return view('pembelian.editpb', compact(['pbhd', 'popbhd']));
    }
    public function importpopbtopb(Request $request)
    {
        $uuid = $request->uuid;
        $id = $request->id;
        $popbhd = Popbhd::where('uuid', $uuid)->first();
        $pbhd = Pbhd::find($id);
        if (!$popbhd || !$pbhd) {
            return [
                'success' => 0,
                'pesan' => 'Data Tidak Ditemukan'
            ];
        }
        if ($popbhd->popbdt->count() <= 0) {
            return [
                'success' => 0,
                'pesan' => 'Data PO KOSONG'
            ];
        }
        DB::beginTransaction();
        try {
            foreach ($popbhd->popbdt as $popbdt) {
                // $qtypo = $popbdt->qty;
                // $qtybeli = $popbdt->pbdt->sum('qty'); // Sum related purchase quantities
                // $qty = $qtypo - $qtybeli;
                $qty = $popbdt->qty;
                if ($qty > 0) {

                    $input['pbhd_id'] = $pbhd->id;
                    $input['popbdt_id'] = $popbdt->id;
                    $input['barang_id'] = $popbdt->barang_id;
                    $input['gudang_id'] = $popbdt->gudang_id;
                    $input['qty'] = $qty;
                    $input['invoice'] = $pbhd->invoice;
                    $input['harga_bruto'] = $popbdt->harga_bruto;
                    $input['potpersen'] = $popbdt->potpersen;
                    $input['potrp'] = $popbdt->potrp;
                    $input['totalpot'] = $popbdt->totalpot;
                    $input['harga_netto'] = $popbdt->harga_netto;
                    $input['ppn'] = $popbdt->ppn;
                    $input['hpp'] = $popbdt->hpp;
                    $input['totalppn'] = $popbdt->totalppn;
                    $input['totalhpp'] = $popbdt->totalhpp;
                    $input['total_harga_netto'] = $popbdt->total_harga_netto;
                    $input['user_id'] = Auth()->User()->id;
                    Pbdt::create($input);
                }
            }
            $updatepb['popbhd_id'] = $popbhd->id;
            $updatepb['suplier_id'] = $popbhd->suplier_id;
            $updatepb['tanggal_kirim'] = $popbhd->tanggal_kirim;
            $updatepb['keterangan'] = $popbhd->keterangan;
            $updatepb['jenisppn'] = $popbhd->jenisppn;
            $updatepb['persenpajak'] = $popbhd->persenpajak;
            $updatepb['ppn'] = $popbhd->ppn;
            $updatepb['dpp'] = $popbhd->dpp;
            $updatepb['total'] = $popbhd->total;
            $updatepb['potongan'] = $popbhd->potongan;
            $updatepb['dp'] = $popbhd->dp;
            $updatepb['hutang'] = $popbhd->dpp + $popbhd->ppn - $popbhd->potongan - $popbhd->dp;
            $pbhd->update($updatepb);
            DB::commit();
            return [
                'success' => 1,
                'pesan' => 'Berhasil Import PO',
            ];
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollback();

            // Return error response
            return [
                'success' => 0,
                'pesan' => 'Error occurred: ' . $e->getMessage(),
            ];
        }
    }
    public function footerpb($id)
    {
        $pbhd = Pbhd::findOrFail($id);

        $dppb = $pbhd->dd;
        $totalakhir = $pbhd->dpp + $pbhd->ppn - $pbhd->potongan;

        $dpFromPopbhd = $pbhd->popbhd->dp ?? null; // Down payment from popbhd relationship
        $dppo = $dpFromPopbhd ?: $pbhd->dp; // Use popbhd->dp if available, otherwise default dp

        // Update DP in input if there's a mismatch
        $input = [];
        if ($dpFromPopbhd && $dpFromPopbhd != $dppb) {
            $input['dp'] = $dpFromPopbhd;
        }

        $totalakhirDp = $totalakhir - $dppo; // Amount that must be paid
        $kas = $pbhd->kas; // Cash payment
        $hutang = $totalakhirDp - $kas; // Outstanding debt

        if ($hutang < 0) {
            $input['kas'] = $totalakhirDp;
            $input['hutang'] = 0;
            $input['status_hutang'] = 2; // Fully paid
        } else {
            $input['hutang'] = $hutang;
            $input['status_hutang'] = 1; // Debt exists
        }

        $pbhd->update($input);

        return view('pembelian.footerpb', compact('pbhd', 'totalakhir'));
    }


    // CART PO
    public function cartpb(Request $request, $id)
    {
        $pbhd = Pbhd::findorfail($id);
        $pbdt = Pbdt::where('pbhd_id', $id)->get();
        return view('pembelian.cartpb', compact(['pbhd', 'pbdt']));
    }

    public function tambahcartpb(Request $request)
    {
        $validationResult = $this->validateCartPbRequest($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $pbhd = Pbhd::find($request->pbhd_id);
        $persenpajak = $pbhd->persenpajak;
        $jenisppn = $pbhd->jenisppn;

        $qty = $request->qty;
        $total_harga = ($request->harga_bruto * $qty) - $request->total_pot;

        $datapajak = hitungpajak($total_harga, $qty, $persenpajak, $jenisppn);

        $input = $this->preparePbdtInput($request, $datapajak);

        try {
            DB::beginTransaction();

            $pbdt = Pbdt::create($input);

            if ($pbdt) {
                $barang = Barang::find($request->cart_barang_id);
                if ($barang) {
                    $barang->harga_beli_terakhir = $datapajak['ppn'] + $datapajak['hpp'];
                    $barang->save();
                }

                DB::commit();
                $totalPpn = $pbhd->pbdt->sum('totalppn');
                $totalDpp = $pbhd->pbdt->sum('totalhpp');
                $pbhd->update([
                    'ppn' => $totalPpn,
                    'dpp' => $totalDpp,
                    'total' => $totalPpn + $totalDpp,
                    'status' => 1,
                ]);
                return [
                    'success' => 1,
                    'pesan' => 'BERHASIL',
                ];
            } else {
                DB::rollBack();
                return [
                    'success' => 0,
                    'pesan' => 'GAGAL menyimpan data PBDT',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => 0,
                'pesan' => 'Error: ' . $e->getMessage(),
            ];
        }
    }


    public function updatecartpb(Request $request, $id)
    {
        $validationResult = $this->validateCartPbRequest($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $pbdt = Pbdt::find($id);
        if (!$pbdt) {
            return [
                'success' => 0,
                'pesan' => 'Data Pbdt tidak ditemukan',
            ];
        }

        $pbhd = Pbhd::find($request->pbhd_id);
        $persenpajak = $pbhd->persenpajak;
        $jenisppn = $pbhd->jenisppn;

        $qty = $request->qty;
        $total_harga = ($request->harga_bruto * $qty) - $request->total_pot;

        $datapajak = hitungpajak($total_harga, $qty, $persenpajak, $jenisppn);

        $input = $this->preparePbdtInput($request, $datapajak);

        try {
            DB::beginTransaction();

            $pbdt->update($input);

            if ($pbdt) {
                $barang = Barang::find($request->cart_barang_id);
                if ($barang) {
                    $barang->harga_beli_terakhir = $datapajak['ppn'] + $datapajak['hpp'];
                    $barang->save();
                }
                $this->updatePbhdTotals($pbhd->id);
                DB::commit();
                return [
                    'success' => 1,
                    'pesan' => 'BERHASIL update data',
                ];
            } else {
                DB::rollBack();
                return [
                    'success' => 0,
                    'pesan' => 'GAGAL memperbarui data PBDT',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => 0,
                'pesan' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    public function updatepajakcartpb(Request $request)
    {
        // Start a database transaction to ensure atomic updates
        DB::beginTransaction();
        try {
            // Find the Pbhd record and update jenisppn and persenpajak
            $pbhd = Pbhd::find($request->pbhd_id);
            $pbhd->update([
                'jenisppn' => $request->jenisppn,
                'persenpajak' => $request->persenpajak, // Update persenpajak as well
                'status' => 1,
            ]);

            // Find all Pbdt records where pbhd_id matches the request
            $pbdts = Pbdt::where('pbhd_id', $request->pbhd_id)->get();
            // Initialize variables to sum the ppn and dpp
            $totalPpn = 0;
            $totalDpp = 0;

            foreach ($pbdts as $pbdt) {
                // Ensure pbhd exists in the current Pbdt record
                if (!$pbdt->pbhd) {
                    // Handle the case where the related pbhd does not exist
                    continue;
                }

                // Recalculate the necessary fields based on the updated values
                // Example value from Pbdt
                $qty = $pbdt->qty;  // Example value from Pbdt
                $persenpajak = $pbhd->persenpajak;  // Get updated persenpajak
                $jenisppn = $pbhd->jenisppn;  // Get updated jenisppn
                $total_harga = ($pbdt->harga_bruto * $qty) - $pbdt->total_pot;

                // Use the hitungpajak function to recalculate the fields based on the new jenisppn and persenpajak
                $datapajak = hitungpajak($total_harga, $qty, $persenpajak, $jenisppn);
                $pbdt->total_harga_netto = $datapajak['totalppn'] + $datapajak['totalhpp'];

                // Update the Pbdt record with the recalculated values
                $pbdt->ppn = $datapajak['ppn'];
                $pbdt->totalppn = $datapajak['totalppn'];
                $pbdt->hpp = $datapajak['hpp'];
                $pbdt->totalhpp = $datapajak['totalhpp'];

                // Save the updated Pbdt record
                $pbdt->save();
                // Add to the total PPN and DPP
                $totalPpn += $datapajak['totalppn'];
                $totalDpp += $datapajak['totalhpp'];
            }
            $this->updatePbhdTotals($pbhd->id);

            DB::commit();

            return [
                'success' => 1,
                'pesan' => 'Successfully updated Jenis PPN and Persen Pajak.',
            ];
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollback();

            // Return error response
            return [
                'success' => 0,
                'pesan' => 'Error occurred: ' . $e->getMessage(),
            ];
        }
    }

    public function hapuscartpb($id)
    {
        $pbdt = Pbdt::find($id);
        if (Pbdt::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'BERHASIL HAPUS DATA',
            ];
            $this->updatePbhdTotals($pbdt->pbhd_id);
            return $data;
        }
    }
    public function getcartpb($id)
    {
        $pbdt = Pbdt::findorfail($id);
        $pbdt['nama_barang'] = $pbdt->barang->nama_barang;
        return $pbdt;
    }
    public function fastupdatepbhd(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $column = $request->column;
            $value = $request->value;
            if ($column == 'tanggal') {
                $value = tanggaltodate($value);
            }
            $pbhd = Pbhd::findorfail($id);

            if (empty($pbhd)) {
                $data = [
                    'success' => 0,
                    'pesan' => 'PO TIDAK DITEMUKAN',
                ];
                return $data;
            }
            if ($pbhd) {
                $input[$column] = $value;
                $input['status'] = 1;
                $pbhd->update($input);
                if ($column == 'suplier_id' || $column == 'kode_acc_kas') {
                    $this->postjurnal($id);
                }

                $data = [
                    'success' => 1,
                    'pesan' => $column . ' Berhasil Diubah',
                ];
                return $data;
            }
            $data = [
                'success' => 0,
                'pesan' => 'ERROR',
            ];
            return $data;
        }
    }

    /// private function
    private function validateCartPbRequest(Request $request)
    {
        // Early check for Pbhd existence
        $pbhd = Pbhd::find($request->pbhd_id);
        if (!$pbhd) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. Tidak ditemukan Header PO'
            ];
        }

        // Early return for validation
        if ($request->harga_bruto <= 0 || $request->qty <= 0 || $request->potpersen > 100) {
            return [
                'success' => 0,
                'pesan' => 'QTY atau Harga Tidak Boleh 0 / POT persen tidak boleh lebih dari 100'
            ];
        }

        // If all validation checks pass, return null (indicating no errors)
        return null;
    }
    private function preparePbdtInput(Request $request, array $datapajak)
    {
        return array_merge($request->all(), [
            'barang_id' => $request->cart_barang_id,
            'tipe' => 'PB',
            'ppn' => $datapajak['ppn'],
            'hpp' => $datapajak['hpp'],
            'totalppn' => $datapajak['totalppn'],
            'totalhpp' => $datapajak['totalhpp'],
            'total_harga_netto' => $datapajak['totalppn'] + $datapajak['totalhpp'],
            'user_id' => Auth()->User()->id,
        ]);
    }
    private function updatePbhdTotals($pbhdId)
    {
        $pbhd = Pbhd::find($pbhdId);

        if (!$pbhd) {
            throw new \Exception("Pbhd with ID $pbhdId not found.");
        }

        $totalPpn = $pbhd->pbdt->sum('totalppn');
        $totalDpp = $pbhd->pbdt->sum('totalhpp');
        $pbhd->update([
            'status' => 1,
            'ppn' => $totalPpn,
            'dpp' => $totalDpp,
            'total' => $totalPpn + $totalDpp,
        ]);
    }
    private function postjurnal($id)
    {
        $pbhd = Pbhd::find($id);

        if (!$pbhd) {
            return 0;
        }
        // Prepare common input data
        $tipe = 'PB';
        $invoice = $pbhd->invoice;
        $tanggal = $pbhd->tanggal;
        $keterangan = $pbhd->keterangan;
        $suplier_id = $pbhd->suplier_id;

        $commonInput = [
            'tipe' => $tipe,
            'invoice' => $invoice,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'suplier_id' => $suplier_id,
        ];

        // Start the transaction
        DB::beginTransaction();

        try {
            Accjurnal::where('invoice', $invoice)->delete();
            // Check if there's a down payment (dp) to create journal entries
            if ($pbhd->ppn > 0) { //persediaan debit (ppn)
                $kode_acc = $pbhd->kode_acc_ppn;
                $kode_lawan = $pbhd->kode_acc_dpp;
                $posisi = 'D';
                $jumlah = $pbhd->ppn;

                // Create the first journal entry (input1)
                $input1 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input1);
            }
            if ($pbhd->dpp > 0) { //persediaan debit (dpp)
                $kode_acc = $pbhd->kode_acc_dpp;
                $kode_lawan = $pbhd->kode_acc_kas;
                $posisi = 'D';
                $jumlah = $pbhd->dpp;

                // Create the first journal entry (input2)
                $input2 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input2);
            }
            if ($pbhd->potongan > 0) { //kredit (potongan)
                $kode_acc = $pbhd->kode_acc_potongan;
                $kode_lawan = $pbhd->kode_acc_dpp;
                $posisi = 'K';
                $jumlah = $pbhd->potongan;

                // Create the first journal entry (input3)
                $input3 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input3);
            }
            if ($pbhd->dp > 0) { // dp kredit
                $kode_acc = $pbhd->kode_acc_dp;
                $kode_lawan = $pbhd->kode_acc_dpp;
                $posisi = 'K';
                $jumlah = $pbhd->dp;

                // Create the first journal entry (input4)
                $input4 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input4);
            }
            if ($pbhd->kas > 0) { //kas kredit
                $kode_acc = $pbhd->kode_acc_kas;
                $kode_lawan = $pbhd->kode_acc_dpp;
                $posisi = 'K';
                $jumlah = $pbhd->kas;

                // Create the first journal entry (input5)
                $input5 = array_merge($commonInput, [
                    'induk' => 1,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input5);
            }

            if ($pbhd->hutang > 0) { // debit (hutang)
                $kode_acc = $pbhd->kode_acc_hutang;
                $kode_lawan = $pbhd->kode_acc_dpp;
                $posisi = 'K';
                $jumlah = $pbhd->hutang;
                // Create the first journal entry (input6)
                $input6 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input6);
            }
            // Commit the transaction if everything is successful
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if any exception occurs
            DB::rollBack();

            // Optionally, log the error or return a message
            throw new \Exception("Error creating Accjurnal entries: " . $e->getMessage());
        }

        return 1;  // Return success
    }
    //cek status po dan jumlah item
    private function cekstatuspo($pbhd_id)
    {
        $pbhd = Pbhd::findOrFail($pbhd_id); // Ensure record exists
        $popbdts = Popbdt::where('popbhd_id', $pbhd->popbhd_id)->with('pbdt')->get();

        foreach ($popbdts as $popbdt) {
            $qty = $popbdt->qty;
            $qtybeli = $popbdt->pbdt->sum('qty'); // Sum related purchase quantities
            $sisa = $qty - $qtybeli;

            if ($sisa > 0) {
                // PO item is not complete
                return [
                    'success' => 0,
                    'pesan' => 'PO BELUM SELESAI DILAKUKAN PEMBELIAN'
                ];
            }
        }

        // All items are complete, update status
        Popbhd::where('id', $pbhd->popbhd_id)->update(['status' => 3]);

        return [
            'success' => 1,
            'pesan' => 'PO SUDAH SELESAI DILAKUKAN PEMBELIAN'
        ];
    }

    public function simpanpb(Request $request)
    {
        $pbhd = Pbhd::find($request->id);
        $input = $request->all();
        $input['tanggal'] = tanggaltodate($request->tanggal);
        $input['status'] = 2;
        $input['useredit_id'] = Auth()->User()->id;
        if (!$pbhd) {
            return [
                'success' => 0,
                'pesan' => 'ERROR, TIDAK DITEMUKAN DATA'
            ];
        }
        if ($pbhd->update($input)) {
            if ($pbhd->popbhd) {
                $this->cekstatuspo($pbhd->id);
            }
            $this->postjurnal($pbhd->id);
            return [
                'success' => 1,
                'pesan' => 'BERHASIL SIMPAN DATA'
            ];
        }
    }
    public function hapuspb($uuid)
    {
        $pbhd = Pbhd::where('uuid', $uuid)->first();
        if (!$pbhd) {
            return [
                'success' => 0,
                'pesan' => 'ERROR, TIDAK DITEMUKAN DATA'
            ];
        }
        $invoice = $pbhd->invoice;
        // Start the transaction
        DB::beginTransaction();

        try {
            //hapus jurnal
            Accjurnal::where('invoice', $invoice)->delete();
            //hapus cart pbdt
            Pbdt::where('pbhd_id', $pbhd->id)->delete();
            //hapus pbhd
            Pbhd::destroy($pbhd->id);
            // Commit the transaction if everything is successful
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if any exception occurs
            DB::rollBack();

            // Optionally, log the error or return a message
            throw new \Exception("Error creating Accjurnal entries: " . $e->getMessage());
        }

        return [
            'success' => 1,
            'pesan' => 'BERHASIL, HAPUS DATA'
        ];
    }
    public function cetaknotapo($uuid)
    {
        $pbhd = Pbhd::where('uuid', $uuid)->first();
        $datacart = Pbdt::where('pbhd_id', $pbhd->id)->get();
        $title = 'NOTA PEMBELIAN';
        return view('pembelian.cetakpo', compact([
            'pbhd',
            'title',
            'datacart'
        ]));
    }
}
