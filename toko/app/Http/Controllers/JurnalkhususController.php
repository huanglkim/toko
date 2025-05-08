<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accjurnalkhusus;
use App\Models\Accperkiraan;
use App\Models\Users;
use Illuminate\Support\Facades\Validator;
use App\Models\Accjurnal;
use Yajra\DataTables\DataTables;

class JurnalkhususController extends Controller
{
    public function PiutangKaryawan()
    {
        $KasKeluar = Accjurnalkhusus::where('tipe', 'PK')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        $User = Users::Active()
            ->orderBy('nama', 'ASC')
            ->get();
        $UserPiutang = Users::Active()
            ->where('piutang', '!=', 0)
            ->orderBy('nama', 'ASC')
            ->get();

        $KasBank = Accperkiraan::select('kode_acc', 'nama_acc', 'id')
            ->where('kas', 1)
            ->orWhere('bank', 1)
            ->get();


        return view('jurnalkhusus.piutangkaryawan', compact(['KasKeluar', 'User', 'KasBank', 'UserPiutang']));
    }
    public function Biaya()
    {
        $KasKeluar = Accjurnalkhusus::where('tipe', 'KK')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        $KasBank = Accperkiraan::select('kode_acc', 'nama_acc', 'id')
            ->where('kas', 1)
            ->orWhere('bank', 1)
            ->get();

        $KodeAcc = Accperkiraan::select('kelompok', 'kode_acc', 'nama_acc', 'id')
            ->where('kelompok', 6)
            ->where('tipe', 'D')
            ->get();

        return view('jurnalkhusus.biaya', compact(['KasKeluar', 'KasBank', 'KodeAcc']));
    }
    public function jurnalmemorial()
    {
        $KodeAcc = Accperkiraan::select('kode_acc', 'nama_acc', 'id')
            ->where('tipe', 'D')
            ->get();
        return view('jurnalkhusus.jurnalmemorial', compact(['KodeAcc']));
    }
    public function KasMasuk()
    {
        $KasBank = Accperkiraan::select('kode_acc', 'nama_acc', 'id')
            ->where('kas', 1)
            ->orWhere('bank', 1)
            ->get();

        $KodeAcc = Accperkiraan::select('kode_acc', 'nama_acc', 'id')
            ->where('tipe', 'D')
            ->get();

        return view('jurnalkhusus.kasmasuk', compact(['KasBank', 'KodeAcc']));
    }
    public function tabeljurnalkhusus(Request $request)
    {
        $tipe = $request->tipe;
        $jurnalkhusus = Accjurnalkhusus::with('Accperkiraan', 'Accperkiraanlawan')
            ->select('Accjurnalkhusus.*')
            ->where('tipe', $tipe);
        return DataTables::of($jurnalkhusus)
            ->editColumn('jumlah', function ($jurnalkhusus) {
                return Rupiah0($jurnalkhusus->jumlah);
            })
            ->editColumn('created_at', function ($jurnalkhusus) {
                return TanggalJam($jurnalkhusus->created_at);
            })
            ->editColumn('tanggal', function ($jurnalkhusus) {
                return datetotanggal($jurnalkhusus->tanggal);
            })
            ->addColumn('aksi', function ($jurnalkhusus) {
                $edit = '<button onclick="edit(' . $jurnalkhusus->id . ')" class="btn  btn-xs btn-warning"><i
                class="fas fa-pencil-alt"></i> EDIT</button>';
                $hapus = ' <button onclick="hapus(' . $jurnalkhusus->id . ')" class="btn  btn-xs btn-danger"><i
                class="fas fa-trash"></i> HAPUS</button>';

                return $edit . $hapus;
            })
            ->rawColumns(['aksi', 'tanggal'])
            ->make(true);
    }
    public function KasKeluar()
    {
        $KasBank = Accperkiraan::select('kode_acc', 'nama_acc', 'id')
            ->where('kas', 1)
            ->orWhere('bank', 1)
            ->get();
        $KodeAcc = Accperkiraan::select('kode_acc', 'nama_acc', 'id')
            ->where('tipe', 'D')
            ->get();

        return view('jurnalkhusus.kaskeluar', compact(['KasBank', 'KodeAcc']));
    }
    public function TambahJurnalKas(Request $request)
    {
        if ($request->kode_acc == $request->kode_lawan) {
            $data = [
                'success' => 0,
                'pesan' => 'KODE Dari dan Untuk Tidak Boleh SAMA'
            ];
            return $data;
        }
        $validator = Validator::make($request->all(), [
            'jumlah'       => 'required',
            'tanggal'       => 'required',
            'keterangan'       => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        $input = $request->except('id', 'tanggal');
        $tipe = $request->tipe;
        $input['invoice'] = $tipe . kodejurnal(1);
        $input['user_id'] = Auth()->User()->id;
        $input['tanggal'] = tanggaltodate($request->tanggal);

        if (Accjurnalkhusus::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'BERHASIL INPUT KAS'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'GAGAL INPUT KAS'
        ];
        return $data;
    }
    public function EditJurnalKas($id)
    {
        $Accjurnalkhusus = Accjurnalkhusus::findorfail($id);
        $Accjurnalkhusus['tanggal'] = datetotanggal($Accjurnalkhusus->tanggal);
        return $Accjurnalkhusus;
    }
    public function UpdateJurnalKas(Request $request, $id)
    {

        if ($request->kode_acc == $request->kode_lawan) {
            $data = [
                'success' => 0,
                'pesan' => 'KODE Dari dan Untuk Tidak Boleh SAMA'
            ];
            return $data;
        }
        $validator = Validator::make($request->all(), [
            'jumlah'       => 'required',
            'tanggal'       => 'required',
        ]);
        $ack = Accjurnalkhusus::findorfail($id);
        Accjurnal::where('invoice', $ack->invoice)->delete();
        if ($validator->fails()) {
            return $validator->errors();
        }
        $input = $request->except('id', 'tanggal');
        $input['user_id'] = Auth()->User()->id;
        $input['tanggal'] = tanggaltodate($request->tanggal);

        if ($ack->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'BERHASIL UPDATE INPUT KAS'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'GAGAL UPDATE INPUT KAS'
        ];
        return $data;
    }
    public function HapusJurnalKas($id)
    {
        if (Accjurnalkhusus::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Jurnal KAS Berhasil Dihapus'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Jurnal KAS Tidak Berhasil Dihapus'
        ];
        return $data;
    }
    public function jurnalupahkerja()
    {
        $KasKeluar = Accjurnalkhusus::where('tipe', 'KK')
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        $KasBank = Accperkiraan::select('kode_acc', 'nama_acc', 'id')
            ->where('kas', 1)
            ->orWhere('bank', 1)
            ->get();
        return view('jurnalkhusus.upahkerja', compact(['KasKeluar', 'KasBank']));
    }
    public function RincianPiutangKaryawan()
    {
        $RincianPiutang = Accjurnalkhusus::where('tipe', 'PK')
            ->where('karyawan_id', Auth()->User()->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(25);

        return view('jurnalkhusus.rincianpiutangkaryawan', compact(['RincianPiutang']));
    }
}
