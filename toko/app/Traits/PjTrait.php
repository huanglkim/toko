<?php

namespace App\Traits;

use App\Models\Pjhd;
use App\Models\Pjdt;
use Illuminate\Http\Request;
use App\Models\Accjurnal;
use Illuminate\Support\Facades\DB;

trait PjTrait
{
    public function editqtycartpj($pjdt_id, $qty)
    {
        if ($qty <= 0) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. Qty Tidak Boleh Kosong'
            ];
        }
        $pjdt = Pjdt::find($pjdt_id);
        if (!$pjdt) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. Cart Tidak ditemukan'
            ];
        }
        $total_harga_bruto = $pjdt->harga_bruto * $qty;
        $potrpsatuan = $pjdt->potrp == 0 ? 0 : $pjdt->potrp / $pjdt->qty;
        $potrp = $potrpsatuan * $qty;
        $hpp = $pjdt->hpp;
        $dpp = $pjdt->dpp;
        $ppn = $pjdt->ppn;
        $harga_netto = $pjdt->harga_netto;
        $total_harga_netto = $harga_netto * $qty;
        $input['qty'] = $qty;
        $input['potrp'] = $potrp;
        $input['total_pot'] = $total_harga_bruto - $total_harga_netto;
        $input['totalhpp'] = $hpp * $qty;
        $input['totaldpp'] = $dpp * $qty;
        $input['totalppn'] = $ppn * $qty;
        $input['harga_netto'] = $harga_netto;
        $input['total_harga_netto'] = $total_harga_netto;
        $pjdt->update($input);
        $this->updatePjhdTotals($pjdt->pjhd_id);
        return [
            'success' => 1,
            'pesan' => 'Sukses. QTY berhasil diubah'
        ];
    }
    /// validasi input ke cart pj
    public function validateCartPjRequest(Request $request)
    {
        // Early check for Pjhd existence
        $pjhd = Pjhd::find($request->pjhd_id);
        if (!$pjhd) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. Tidak ditemukan Header PO'
            ];
        }
        if (!$pjhd->pelanggan) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. Tidak ditemukan Pelanggan'
            ];
        }
        if ($request->qty <= 0) {
            return [
                'success' => 0,
                'pesan' => 'ERROR. Qty Tidak Boleh Kosong'
            ];
        }
        // If all validation checks pass, return null (indicating no errors)
        return null;
    }
    public function postjurnal($id)
    {
        $pjhd = Pjhd::find($id);

        if (!$pjhd) {
            return 0;
        }
        // Prepare common input data
        $tipe = 'PJ';
        $invoice = $pjhd->invoice;
        $tanggal = $pjhd->tanggal;
        $keterangan = $pjhd->keterangan;
        $pelanggan_id = $pjhd->pelanggan_id;

        $commonInput = [
            'tipe' => $tipe,
            'invoice' => $invoice,
            'tanggal' => $tanggal,
            'keterangan' => $keterangan,
            'pelanggan_id' => $pelanggan_id,
        ];

        // Start the transaction
        DB::beginTransaction();

        try {
            Accjurnal::where('invoice', $invoice)->delete();
            // Check if there's a down payment (dp) to create journal entries
            if ($pjhd->ppn > 0) { //persediaan debit (ppn)
                $kode_acc = $pjhd->kode_acc_ppn;
                $kode_lawan = $pjhd->kode_acc_dpp;
                $posisi = 'K';
                $jumlah = $pjhd->ppn;

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
            if ($pjhd->dpp > 0) { //pendapatan debit (dpp)
                $kode_acc = $pjhd->kode_acc_dpp;
                $kode_lawan = $pjhd->kode_acc_kas;
                $posisi = 'K';
                $jumlah = $pjhd->dpp;

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
            if ($pjhd->potongan > 0) { //kredit (potongan)
                $kode_acc = $pjhd->kode_acc_potongan;
                $kode_lawan = $pjhd->kode_acc_dpp;
                $posisi = 'D';
                $jumlah = $pjhd->potongan;

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
            if ($pjhd->bank > 0) { // BANK
                $kode_acc = $pjhd->kode_acc_bank;
                $kode_lawan = $pjhd->kode_acc_dpp;
                $posisi = 'D';
                $jumlah = $pjhd->bank;

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
            if ($pjhd->kas > 0) { //kas 
                $kode_acc = $pjhd->kode_acc_kas;
                $kode_lawan = $pjhd->kode_acc_dpp;
                $posisi = 'D';
                $jumlah = $pjhd->kas;

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

            if ($pjhd->piutang > 0) { // debit (piutang)
                $kode_acc = $pjhd->kode_acc_piutang;
                $kode_lawan = $pjhd->kode_acc_dpp;
                $posisi = 'D';
                $jumlah = $pjhd->piutang;
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
            if ($pjhd->hpp > 0) { // HPP dan persediaan
                //persediaan siap jual
                $kode_acc = $pjhd->kode_acc_hpp;
                $kode_lawan = '5-1100';
                $posisi = 'K';
                $jumlah = $pjhd->hpp;

                // Create the first journal entry (input4)
                $input7 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);

                Accjurnal::create($input7);
                //HPP PENJUALAN
                $kode_acc = '5-1100';
                $kode_lawan = $pjhd->kode_acc_hpp;
                $posisi = 'D';
                $jumlah = $pjhd->hpp;

                // Create the first journal entry (input4)
                $input8 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input8);
            }
            if ($pjhd->admin_mpl > 0 && $pjhd->tipe_admin_mpl == 'include') { //jika ada potongan admin marketplace 
                $kode_acc = $pjhd->kode_acc_admin_mpl;
                $kode_lawan = $pjhd->kode_acc_kas;
                $posisi = 'D';
                $jumlah = $pjhd->admin_mpl;

                // Create the first journal entry (input9)
                $input9 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input9);
                // Create the first journal entry (input10)
                $input10 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_lawan,
                    'kode_lawan' => $kode_acc,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'K' ? $jumlah : 0,
                    'kredit' => $posisi == 'K' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input10);
            }
            if ($pjhd->admin_lain > 0 && $pjhd->tipe_admin_mpl == 'include') { //jika ada potongan admin marketplace 
                $kode_acc = $pjhd->kode_acc_admin_lain;
                $kode_lawan = $pjhd->kode_acc_kas;
                $posisi = 'D';
                $jumlah = $pjhd->admin_lain;

                // Create the first journal entry (input11)
                $input11 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_acc,
                    'kode_lawan' => $kode_lawan,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'D' ? $jumlah : 0,
                    'kredit' => $posisi == 'D' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input11);
                // Create the first journal entry (input12)
                $input12 = array_merge($commonInput, [
                    'induk' => 0,
                    'kode_acc' => $kode_lawan,
                    'kode_lawan' => $kode_acc,
                    'posisi' => $posisi,
                    'jumlah' => $jumlah,
                    'debet' => $posisi == 'K' ? $jumlah : 0,
                    'kredit' => $posisi == 'K' ? 0 : $jumlah,
                ]);
                Accjurnal::create($input12);
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
    public function preparePjdtInput(Request $request, array $datapajak)
    {
        return array_merge($request->all(), [
            'tipe' => 'PJ',
            'ppn' => $datapajak['ppn'],
            'dpp' => $datapajak['dpp'],
            'totalppn' => $datapajak['totalppn'],
            'totaldpp' => $datapajak['totaldpp'],
            'total_harga_netto' => $datapajak['totalppn'] + $datapajak['totaldpp'],
            'user_id' => Auth()->User()->id,
        ]);
    }
    public function updatePjhdTotals($id)
    {
        $pjhd = Pjhd::find($id);
        if (!$pjhd) {
            throw new \Exception("Pjhd with ID $id not found.");
        }

        $totalHpp = $pjhd->pjdt->sum('totalhpp');
        $totalPpn = $pjhd->pjdt->sum('totalppn');
        $totalDpp = $pjhd->pjdt->sum('totaldpp');
        $pjhd->update([
            'status' => 1,
            'hpp' => $totalHpp,
            'ppn' => $totalPpn,
            'dpp' => $totalDpp,
            'total' => $totalDpp + $totalPpn,
        ]);
    }
}
