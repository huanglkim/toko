<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Popbhd;
use App\Models\Popbdt;
use App\Models\Barang;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Accjurnal;

class PopbController extends Controller
{
    public function popb()
    {
        $jumlahpopending = Popbhd::where('status', 1)->count();
        return view('pembelian.popb', compact(['jumlahpopending']));
    }
    public function cetaknotapo($uuid)
    {
        $popbhd = Popbhd::where('uuid', $uuid)->first();
        $datacart = Popbdt::where('popbhd_id', $popbhd->id)->get();
        $title = 'NOTA PO';
        return view('pembelian.cetakpo', compact([
            'popbhd',
            'title',
            'datacart'
        ]));
    }
    public function tabelpopb(Request $request)
    {
        $status = $request->status;
        $suplier_id = $request->suplier_id;

        $popbhd = Popbhd::with('suplier', 'user', 'useredit') // Load the 'satuan' relationship
            ->select('popbhd.*')        // Select all columns from the 'barang' table
            ->where(function ($query) use ($status) { // Conditional for 'status'
                if ($status != null) {
                    $query->where('status', $status);
                }
            })
            ->when($suplier_id, function ($query, $suplier_id) { // Conditional for 'suplier_id'
                $query->where('suplier_id', $suplier_id);
            });

        return DataTables::of($popbhd)
            ->addColumn('aksi', function ($popbhd) {
                $edit = '<a onclick="editdata(\'' . addslashes($popbhd->uuid) . '\')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ';
                $hapus = '<a onclick="hapus(\'' . addslashes($popbhd->uuid) . '\')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                return $edit . $hapus;
            })
            ->editColumn('jumlah', function ($popbhd) {
                return Rupiah0($popbhd->total);
            })
            ->editColumn('created_at', function ($popbhd) {
                return TanggalJam($popbhd->created_at);
            })
            ->editColumn('tanggal', function ($popbhd) {
                return datetotanggal($popbhd->tanggal);
            })
            ->rawColumns(['aksi'])
            ->setRowClass(function ($popbhd) {
                if ($popbhd->status == 1) {
                    return 'table-warning';
                }
                if ($popbhd->status == 3) {
                    return 'table-success';
                }
                return '';
            })
            ->make(true);
    }
    public function listpopending()
    {
        $popbhd = Popbhd::where('status', 1)->get();
    }
    public function tambahpobaru()
    {
        $input = [
            'invoice' => kode(3), // kode po
            'tanggal' => now(),
            'suplier_id' => 1,
            'user_id' => Auth()->User()->id,
            'persenpajak' => globalconfigdata('ppnpb'), // kode po
            'tanggal_kirim' => now(),
            'keterangan' => '-',
            'uuid' => Str::uuid(),
        ];
        $popbhd = Popbhd::create($input);

        return [
            'success' => 1,
            'uuid' => $popbhd->uuid
        ];
    }
    public function editpo($uuid)
    {
        $popbhd = Popbhd::where('uuid', $uuid)->firstOrFail();
        return view('pembelian.editpopb', compact(['popbhd']));
    }
    public function footerpopb($id)
    {
        $popbhd = Popbhd::findorfail($id);
        return view('pembelian.footerpo', compact(['popbhd']));
    }

    // CART PO
    public function cartpopb(Request $request, $id)
    {
        $popbhd = Popbhd::findorfail($id);
        $popbdt = Popbdt::where('popbhd_id', $id)->get();
        return view('pembelian.cartpo', compact(['popbhd', 'popbdt']));
    }

    public function tambahcartpopb(Request $request)
    {
        $validationResult = $this->validateCartPopbRequest($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $popbhd = Popbhd::find($request->popbhd_id);
        $persenpajak = $popbhd->persenpajak;
        $jenisppn = $popbhd->jenisppn;

        $qty = $request->qty;
        $total_harga = ($request->harga_bruto * $qty) - $request->total_pot;

        $datapajak = hitungpajak($total_harga, $qty, $persenpajak, $jenisppn);

        $input = $this->preparePopbdtInput($request, $datapajak);

        try {
            DB::beginTransaction();

            $popbdt = Popbdt::create($input);

            if ($popbdt) {
                $barang = Barang::find($request->cart_barang_id);
                if ($barang) {
                    $barang->harga_beli_terakhir = $datapajak['ppn'] + $datapajak['hpp'];
                    $barang->save();
                }

                DB::commit();
                $totalPpn = $popbhd->popbdt->sum('totalppn');
                $totalDpp = $popbhd->popbdt->sum('totalhpp');
                $popbhd->update([
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
                    'pesan' => 'GAGAL menyimpan data POPBDT',
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


    public function updatecartpopb(Request $request, $id)
    {
        $validationResult = $this->validateCartPopbRequest($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $popbdt = Popbdt::find($id);
        if (!$popbdt) {
            return [
                'success' => 0,
                'pesan' => 'Data Popbdt tidak ditemukan',
            ];
        }

        $popbhd = Popbhd::find($request->popbhd_id);
        $persenpajak = $popbhd->persenpajak;
        $jenisppn = $popbhd->jenisppn;

        $qty = $request->qty;
        $total_harga = ($request->harga_bruto * $qty) - $request->total_pot;

        $datapajak = hitungpajak($total_harga, $qty, $persenpajak, $jenisppn);

        $input = $this->preparePopbdtInput($request, $datapajak);

        try {
            DB::beginTransaction();

            $popbdt->update($input);

            if ($popbdt) {
                $barang = Barang::find($request->cart_barang_id);
                if ($barang) {
                    $barang->harga_beli_terakhir = $datapajak['ppn'] + $datapajak['hpp'];
                    $barang->save();
                }
                $this->updatePopbhdTotals($popbhd->id);
                DB::commit();
                return [
                    'success' => 1,
                    'pesan' => 'BERHASIL update data',
                ];
            } else {
                DB::rollBack();
                return [
                    'success' => 0,
                    'pesan' => 'GAGAL memperbarui data POPBDT',
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

    public function updatepajakcartpo(Request $request)
    {
        // Start a database transaction to ensure atomic updates
        DB::beginTransaction();
        try {
            // Find the Popbhd record and update jenisppn and persenpajak
            $popbhd = Popbhd::find($request->popbhd_id);
            $popbhd->update([
                'jenisppn' => $request->jenisppn,
                'persenpajak' => $request->persenpajak, // Update persenpajak as well
                'status' => 1,
            ]);

            // Find all Popbdt records where popbhd_id matches the request
            $popbdts = Popbdt::where('popbhd_id', $request->popbhd_id)->get();
            // Initialize variables to sum the ppn and dpp
            $totalPpn = 0;
            $totalDpp = 0;

            foreach ($popbdts as $popbdt) {
                // Ensure popbhd exists in the current Popbdt record
                if (!$popbdt->popbhd) {
                    // Handle the case where the related popbhd does not exist
                    continue;
                }

                // Recalculate the necessary fields based on the updated values
                // Example value from Popbdt
                $qty = $popbdt->qty;  // Example value from Popbdt
                $persenpajak = $popbhd->persenpajak;  // Get updated persenpajak
                $jenisppn = $popbhd->jenisppn;  // Get updated jenisppn
                $total_harga = ($popbdt->harga_bruto * $qty) - $popbdt->total_pot;

                // Use the hitungpajak function to recalculate the fields based on the new jenisppn and persenpajak
                $datapajak = hitungpajak($total_harga, $qty, $persenpajak, $jenisppn);
                $popbdt->total_harga_netto = $datapajak['totalppn'] + $datapajak['totalhpp'];

                // Update the Popbdt record with the recalculated values
                $popbdt->ppn = $datapajak['ppn'];
                $popbdt->totalppn = $datapajak['totalppn'];
                $popbdt->hpp = $datapajak['hpp'];
                $popbdt->totalhpp = $datapajak['totalhpp'];

                // Save the updated Popbdt record
                $popbdt->save();
                // Add to the total PPN and DPP
                $totalPpn += $datapajak['totalppn'];
                $totalDpp += $datapajak['totalhpp'];
            }
            $this->updatePopbhdTotals($popbhd->id);

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

    public function hapuscartpopb($id)
    {
        $popbdt = Popbdt::find($id);
        $this->updatePopbhdTotals($popbdt->popbhd_id);
        if (Popbdt::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'BERHASIL HAPUS DATA',
            ];
            return $data;
        }
    }
    public function getcartpopb($id)
    {
        $popbdt = Popbdt::findorfail($id);
        $popbdt['nama_barang'] = $popbdt->barang->nama_barang;
        return $popbdt;
    }
    public function fastupdatepopbhd(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $column = $request->column;
            $value = $request->value;
            if ($column == 'tanggal') {
                $value = tanggaltodate($value);
            }
            $popbhd = Popbhd::findorfail($id);

            if (empty($popbhd)) {
                $data = [
                    'success' => 0,
                    'pesan' => 'PO TIDAK DITEMUKAN',
                ];
                return $data;
            }
            if ($popbhd) {
                $input[$column] = $value;
                $input['status'] = 1;
                $popbhd->update($input);
                if ($column == 'dp' || $column == 'suplier_id' || $column == 'kode_acc_kas') {
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
    private function validateCartPopbRequest(Request $request)
    {
        // Early check for Popbhd existence
        $popbhd = Popbhd::find($request->popbhd_id);
        if (!$popbhd) {
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
    private function preparePopbdtInput(Request $request, array $datapajak)
    {
        return array_merge($request->all(), [
            'barang_id' => $request->cart_barang_id,
            'tipe' => 'POPB',
            'ppn' => $datapajak['ppn'],
            'hpp' => $datapajak['hpp'],
            'totalppn' => $datapajak['totalppn'],
            'totalhpp' => $datapajak['totalhpp'],
            'total_harga_netto' => $datapajak['totalppn'] + $datapajak['totalhpp'],
            'user_id' => Auth()->User()->id,
        ]);
    }
    private function updatePopbhdTotals($popbhdId)
    {
        $popbhd = Popbhd::find($popbhdId);

        if (!$popbhd) {
            throw new \Exception("Popbhd with ID $popbhdId not found.");
        }

        $totalPpn = $popbhd->popbdt->sum('totalppn');
        $totalDpp = $popbhd->popbdt->sum('totalhpp');
        $popbhd->update([
            'status' => 1,
            'ppn' => $totalPpn,
            'dpp' => $totalDpp,
            'total' => $totalPpn + $totalDpp,
        ]);
    }
    private function postjurnal($id)
    {
        $popbhd = Popbhd::find($id);

        if (!$popbhd) {
            return 0;
        }
        // Prepare common input data
        $tipe = 'POPB';
        $invoice = $popbhd->invoice;
        $tanggal = $popbhd->tanggal;
        $keterangan = $popbhd->keterangan;
        $suplier_id = $popbhd->suplier_id;

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
            if ($popbhd->dp > 0) {
                $kode_acc = $popbhd->kode_acc_dp;
                $kode_lawan = $popbhd->kode_acc_kas;
                $posisi = 'D';
                $jumlah = $popbhd->dp;

                // Create the first journal entry (input1)
                $input1 = array_merge($commonInput, [
                    'induk' => 1,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input1);

                // Create the second journal entry (input0)
                $input0 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_lawan,
                    'kode_lawan' => $kode_acc,
                    'jumlah' => $jumlah,
                    'posisi' => $posisi == 'D' ? 'K' : 'D',
                    'debet' => $posisi == 'D' ? 0 : $jumlah,
                    'kredit' => $posisi == 'D' ? $jumlah : 0,
                ]);
                Accjurnal::create($input0);
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
    public function simpanpopb(Request $request)
    {
        $popbhd = Popbhd::find($request->id);
        $input = $request->all();
        $input['tanggal'] = tanggaltodate($request->tanggal);
        $input['status'] = 2;
        if (!$popbhd) {
            return [
                'success' => 0,
                'pesan' => 'ERROR, TIDAK DITEMUKAN DATA'
            ];
        }
        if ($popbhd->update($input)) {
            $this->postjurnal($popbhd->id);
            return [
                'success' => 1,
                'pesan' => 'BERHASIL SIMPAN DATA'
            ];
        }
    }
    public function hapuspopb($uuid)
    {
        $popbhd = Popbhd::where('uuid', $uuid)->first();
        if ($popbhd->pbhd->count() > 0) {
            return [
                'success' => 0,
                'pesan' => 'ERROR, SUDAH ADA PEMBELIAN'
            ];
        }
        if (!$popbhd) {
            return [
                'success' => 0,
                'pesan' => 'ERROR, TIDAK DITEMUKAN DATA'
            ];
        }
        $invoice = $popbhd->invoice;
        // Start the transaction
        DB::beginTransaction();

        try {
            //hapus jurnal
            Accjurnal::where('invoice', $invoice)->delete();
            //hapus cart popbdt
            Popbdt::where('popbhd_id', $popbhd->id)->delete();
            //hapus popbhd
            Popbhd::destroy($popbhd->id);
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
}
