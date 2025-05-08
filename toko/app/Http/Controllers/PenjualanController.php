<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pjhd;
use App\Models\Pjdt;
use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Barang;
use App\Models\Accjurnal;
use App\Models\Marketplace;
use App\Traits\PjTrait;

class PenjualanController extends Controller
{
    use PjTrait;

    public function penjualan()
    {
        $tipe = 'PJ';
        $jumlahpjpending = Pjhd::where('status', 1)->where('tipe', $tipe)->count();
        $judul = 'PJ KREDIT';
        return view('penjualan.penjualan', compact(['jumlahpjpending', 'tipe', 'judul']));
    }
    public function tabelpj(Request $request)
    {
        $status = $request->status;
        $pelanggan_id = $request->pelanggan_id;
        $tipe = $request->tipe;

        $pjhd = Pjhd::with('pelanggan', 'user', 'useredit') // Load the 'satuan' relationship
            ->select('pjhd.*') // Select all columns from the 'barang' table
            ->where(function ($query) use ($status) {
                // Conditional for 'status'
                if ($status != null) {
                    $query->where('status', $status);
                }
            })
            ->where('tipe', $tipe)
            ->when($pelanggan_id, function ($query, $pelanggan_id) {
                // Conditional for 'pelanggan_id'
                $query->where('pelanggan_id', $pelanggan_id);
            });

        return DataTables::of($pjhd)
            ->addColumn('aksi', function ($pjhd) {
                $edit = '<a onclick="editdata(\'' . addslashes($pjhd->uuid) . '\')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ';
                $hapus = '<a onclick="hapus(\'' . addslashes($pjhd->uuid) . '\')" class="btn btn-danger btn-xs bg-danger"><i class="fas fa-trash-alt"></i> </a>';
                return $edit . $hapus;
            })
            ->addColumn('jumlah', function ($pjhd) {
                return Rupiah0($pjhd->total);
            })
            ->editColumn('created_at', function ($pjhd) {
                return TanggalJam($pjhd->created_at);
            })
            ->editColumn('tanggal', function ($pjhd) {
                return datetotanggal($pjhd->tanggal);
            })
            ->rawColumns(['aksi'])
            ->setRowClass(function ($pjhd) {
                if ($pjhd->status == 2) {
                    return '';
                } else {
                    return 'table-warning';
                }
            })
            ->make(true);
    }
    public function tambahpjbaru()
    {
        $input = [
            'invoice' => kode(5), // kode PJ
            'tanggal' => now(),
            'pelanggan_id' => 1,
            'tipe' => 'PJ',
            'user_id' => Auth::id(),
            'persenpajak' => globalconfigdata('ppnpj'), // kode po
            'tanggal_kirim' => now(),
            'keterangan' => '-',
            'uuid' => Str::uuid(),
        ];
        $pjhd = Pjhd::create($input);

        return [
            'success' => 1,
            'uuid' => $pjhd->uuid,
        ];
    }
    public function editpj($uuid)
    {
        $pjhd = Pjhd::where('uuid', $uuid)->firstOrFail();
        if ($pjhd->tipe == 'PJ') {
            return view('penjualan.editpj', compact(['pjhd']));
        }
        if ($pjhd->tipe == 'KSR') {
            return view('penjualan.editksr', compact(['pjhd']));
        }
        if ($pjhd->tipe == 'MPL') {
            $marketplaces = Marketplace::where('status', 1)->get();
            return view('penjualan.editksrmpl', compact(['pjhd', 'marketplaces']));
        }
        return abort(404);
        //$dopjhd = Popjhd::where('status', 2)->orderBY('tanggal', 'DESC')->limit(100)->get();
    }
    // CART PO
    public function cartpj(Request $request, $id)
    {
        $pjhd = Pjhd::findorfail($id);
        $pjdt = Pjdt::where('pjhd_id', $id)->get();
        if ($request->detail == 1) {
            return view('penjualan.cartpj', compact(['pjhd', 'pjdt']));
        }
        return view('penjualan.cartpjsimple', compact(['pjhd', 'pjdt']));
    }

    public function footerpj($id)
    {
        $pjhd = Pjhd::findOrFail($id);

        $dppb = $pjhd->dd;
        $totalakhir = $pjhd->dpp + $pjhd->ppn - $pjhd->potongan;

        // $dpFromPopjhd = $pjhd->popjhd->dp ?? null; // Down payment from popjhd relationship
        // $dppo = $dpFromPopjhd ?: $pjhd->dp; // Use popjhd->dp if available, otherwise default dp

        // Update DP in input if there's a mismatch
        // $input = [];
        // if ($dpFromPopjhd && $dpFromPopjhd != $dppb) {
        //     $input['dp'] = $dpFromPopjhd;
        // }

        $totalakhir; // - $dppo; // Amount that must be paid
        $kas = $pjhd->kas; // Cash payment
        $bank = $pjhd->bank; // tf payment
        $piutang = $totalakhir - $kas - $bank; // Outstanding debt

        if ($piutang < 0) {
            if ($bank > $totalakhir) {
                $input['kas'] = 0;
                $input['bank'] = $totalakhir;
                $input['piutang'] = 0;
            } else {
                $input['kas'] = $totalakhir - $bank;
                $input['piutang'] = 0;
            }
            $input['status_piutang'] = 2; // Fully paid
        } else {
            $input['piutang'] = $piutang;
            $input['status_piutang'] = 1; // Debt exists
        }

        $pjhd->update($input);

        return view('penjualan.footerpj', compact('pjhd', 'totalakhir'));
    }
    public function fastupdatepjhd(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $column = $request->column;
            $value = $request->value;
            if ($column == 'tanggal') {
                $value = tanggaltodate($value);
            }
            $pjhd = Pjhd::findorfail($id);

            if (empty($pjhd)) {
                $data = [
                    'success' => 0,
                    'pesan' => 'PJ TIDAK DITEMUKAN',
                ];
                return $data;
            }
            if ($pjhd) {
                $input[$column] = $value;
                $input['status'] = 1;
                $pjhd->update($input);
                if ($column == 'pelanggan_id' || $column == 'kode_acc_kas' || $column == 'kode_acc_bank') {
                    $this->postjurnal($id);
                }

                $data = [
                    'success' => 1,
                    'pesan' => $column . ' Berhasil Diubah ' . $column,
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

    public function tambahcartpj(Request $request)
    {
        $qty = $request->addcartqty;
        $request['qty'] = $qty;
        $validationResult = $this->validateCartPjRequest($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $pjhd = Pjhd::find($request->pjhd_id);
        $tipe = $pjhd->tipe;

        $group_harga = $pjhd->pelanggan->group;

        $barang = Barang::find($request->addcart_barang_id);
        if (!$barang) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. ITEM TIDAK DITEMUKAN',
            ];
        }
        $hpp = $barang->hpp_satuan <= 0 ? $barang->harga_beli_terakhir * $qty : $barang->hpp_satuan * $qty;
        $totalhpp = $hpp * $qty;
        //return $barang;
        $harga_column = 'harga_jual_dasar' . $group_harga;

        // Retrieve the value
        $harga_jual = $barang->$harga_column;
        if ($harga_jual <= 0) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. Harga 0 Tidak Bisa Jual',
            ];
        }
        $persenpajak = $pjhd->persenpajak;
        $jenisppn = $pjhd->jenisppn;

        $total_harga = $harga_jual * $qty - $request->total_pot;
        $datapajak = hitungpajakjual($total_harga, $qty, $persenpajak, $jenisppn);
        $request['invoice'] = $pjhd->invoice;
        $request['barang_id'] = $request->addcart_barang_id;
        $request['harga_bruto'] = $harga_jual;
        $request['hpp'] = $hpp;
        $request['totalhpp'] = $totalhpp;
        $request['harga_netto'] = $total_harga / $qty;
        $input = $this->preparePjdtInput($request, $datapajak);
        try {
            DB::beginTransaction();
            if ($tipe == 'KSR') {
                $pjdt = Pjdt::where('pjhd_id', $pjhd->id)->where('barang_id', $barang->id)->first();
                if (!$pjdt) {
                    $pjdt = Pjdt::create($input);
                } else {
                    $newqty = $pjdt->qty + $qty;
                    $this->editqtycartpj($pjdt->id, $newqty);
                }
            } else {
                $pjdt = Pjdt::create($input);
            }

            if ($pjdt) {
                $this->updatePjhdTotals($pjhd->id);
                DB::commit();
                return [
                    'success' => 1,
                    'pesan' => 'BERHASIL TAMBAH BARANG',
                ];
            } else {
                DB::rollBack();
                return [
                    'success' => 0,
                    'pesan' => 'GAGAL menyimpan data PJ',
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
    public function getcartpj($id)
    {
        $pjdt = Pjdt::findorfail($id);
        $pjdt['nama_barang'] = $pjdt->barang->nama_barang;
        return $pjdt;
    }
    public function updatecartpj(Request $request, $id)
    {
        $validationResult = $this->validateCartPjRequest($request);
        if ($validationResult !== null) {
            return $validationResult;
        }

        $pjdt = Pjdt::find($id);
        if (!$pjdt) {
            return [
                'success' => 0,
                'pesan' => 'Data Pjdt tidak ditemukan',
            ];
        }

        $pjhd = Pjhd::find($request->pjhd_id);
        $qty = $request->qty;

        $barang = Barang::find($request->cart_barang_id);
        if (!$barang) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. ITEM TIDAK DITEMUKAN',
            ];
        }

        //cari hpp barang
        $hpp = $pjdt->hpp <= 0 ? $barang->harga_beli_terakhir : $pjdt->hpp;
        $totalhpp = $hpp * $qty;

        //tentukan harga jual
        $harga_jual = $request->harga_bruto;
        if ($harga_jual <= 0) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. Harga 0 Tidak Bisa Jual',
            ];
        }
        $persenpajak = $pjhd->persenpajak;
        $jenisppn = $pjhd->jenisppn;

        $total_harga = $harga_jual * $qty - $request->total_pot;
        $datapajak = hitungpajakjual($total_harga, $qty, $persenpajak, $jenisppn);
        $request['hpp'] = $hpp;
        $request['totalhpp'] = $totalhpp;
        $request['harga_netto'] = $total_harga / $qty;
        $input = $this->preparePjdtInput($request, $datapajak);
        // return $pjdt;
        try {
            DB::beginTransaction();

            $pjdt->update($input);

            if ($pjdt) {
                $this->updatePjhdTotals($pjhd->id);
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
    public function hapuscartpj($id)
    {
        $pjdt = Pjdt::find($id);
        if (Pjdt::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'BERHASIL HAPUS DATA',
            ];
            $this->updatePjhdTotals($pjdt->pjhd_id);
            return $data;
        }
    }

    public function hapuspj($uuid)
    {
        $pjhd = Pjhd::where('uuid', $uuid)->first();
        if (!$pjhd) {
            return [
                'success' => 0,
                'pesan' => 'ERROR, TIDAK DITEMUKAN DATA',
            ];
        }
        $invoice = $pjhd->invoice;
        // Start the transaction
        DB::beginTransaction();

        try {
            //hapus jurnal
            Accjurnal::where('invoice', $invoice)->delete();
            //hapus cart pjdt
            Pjdt::where('pjhd_id', $pjhd->id)->delete();
            //hapus pjhd
            Pjhd::destroy($pjhd->id);
            // Commit the transaction if everything is successful
            DB::commit();
        } catch (\Exception $e) {
            // Rollback the transaction if any exception occurs
            DB::rollBack();

            // Optionally, log the error or return a message
            throw new \Exception('Error creating Accjurnal entries: ' . $e->getMessage());
        }

        return [
            'success' => 1,
            'pesan' => 'BERHASIL, HAPUS DATA',
        ];
    }
    public function simpanpj(Request $request)
    {
        $pjhd = Pjhd::find($request->id);
        $input = $request->all();
        $input['tanggal'] = tanggaltodate($request->tanggal);
        $input['status'] = 2;
        $input['useredit_id'] = Auth()->User()->id;
        if (!$pjhd) {
            return [
                'success' => 0,
                'pesan' => 'ERROR, TIDAK DITEMUKAN DATA',
            ];
        }
        if ($pjhd->update($input)) {
            $this->postjurnal($pjhd->id);
            return [
                'success' => 1,
                'pesan' => 'BERHASIL SIMPAN DATA',
                'uuid' => $pjhd->uuid,
            ];
        }
    }

    public function updatepajakcartpj(Request $request)
    {
        // Start a database transaction to ensure atomic updates
        DB::beginTransaction();
        try {
            // Find the Pjhd record and update jenisppn and persenpajak
            $pjhd = Pjhd::find($request->pjhd_id);
            $pjhd->update([
                'jenisppn' => $request->jenisppn,
                'persenpajak' => $request->persenpajak, // Update persenpajak as well
                'status' => 1,
            ]);

            // Find all Pjdt records where pjhd_id matches the request
            $pjdts = Pjdt::where('pjhd_id', $request->pjhd_id)->get();
            // Initialize variables to sum the ppn and dpp
            $totalPpn = 0;
            $totalDpp = 0;

            foreach ($pjdts as $pjdt) {
                // Ensure pjhd exists in the current Pjdt record
                if (!$pjdt->pjhd) {
                    // Handle the case where the related pjhd does not exist
                    continue;
                }

                // Recalculate the necessary fields based on the updated values
                // Example value from Pjdt
                $qty = $pjdt->qty; // Example value from Pjdt
                $persenpajak = $pjhd->persenpajak; // Get updated persenpajak
                $jenisppn = $pjhd->jenisppn; // Get updated jenisppn
                $total_harga = $pjdt->harga_bruto * $qty - $pjdt->total_pot;

                // Use the hitungpajak function to recalculate the fields based on the new jenisppn and persenpajak
                $datapajak = hitungpajakjual($total_harga, $qty, $persenpajak, $jenisppn);
                $pjdt->total_harga_netto = $datapajak['totalppn'] + $datapajak['totaldpp'];

                // Update the Pjdt record with the recalculated values
                $pjdt->ppn = $datapajak['ppn'];
                $pjdt->totalppn = $datapajak['totalppn'];
                $pjdt->dpp = $datapajak['dpp'];
                $pjdt->totaldpp = $datapajak['totaldpp'];

                // Save the updated Pjdt record
                $pjdt->save();
                // Add to the total PPN and DPP
                $totalPpn += $datapajak['totalppn'];
                $totalDpp += $datapajak['totaldpp'];
            }
            $this->updatePjhdTotals($pjhd->id);

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
    public function updateqtycartpj(Request $request, $id)
    {
        $qty = $request->qty;
        $result = $this->editqtycartpj($id, $qty);
        return $result;
    }
    public function cetaknotapj($uuid)
    {
        $pjhd = Pjhd::where('uuid', $uuid)->first();
        if (!$pjhd) {
            abort(403, 'NOTA TIDAK DITEMUKAN');
        }
    
        $datacart = Pjdt::where('pjhd_id', $pjhd->id)->get();
        $title = 'NOTA PENJUALAN (PJ)';
    
        // Ambil data toko, misalnya Toko(1) atau berdasarkan id
        $toko = Toko::first(); // Jika mengambil toko pertama, atau sesuaikan dengan kebutuhan
        $logo = asset($toko->logo); // Mengambil logo dari field `logo` di tabel `toko`
    
        return view('penjualan.cetakpj', compact('pjhd', 'title', 'datacart', 'logo'));
    }
    
}
