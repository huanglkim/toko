<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use App\Models\Satuan;
use Intervention\Image\Facades\Image;
use App\Imports\BarangImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Fotobarang;
use App\Models\Merkbarang;
use App\Models\Jenisbarang;
use DateTime;

class BarangController extends Controller
{
    public function caribarcode(Request $request)
    {
        $caribarcode = $request->input('caribarcode'); // Use input() for better readability
        $data = DB::table('barang')
            ->where('status', 1)
            ->where(function ($query) use ($caribarcode) {
                $query->where('kode', $caribarcode)
                    ->orWhere('barcode', $caribarcode);
            })
            ->first();

        // barcode tidak ditemukan
        if (!$data) {
            return response()->json(['message' => 'Barcode Tidak ditemukan'], 404);
        }
        return response()->json($data);
    }
    public function caribarang(Request $request)
    {
        $cari = $request->caribarang;
        $data = DB::table('barang')
            ->where('status', 1)
            ->where(function ($q) use ($cari) {
                $q->where('nama_barang', 'LIKE', '%' . $cari . '%')
                    ->orwhere('kode_internal', 'LIKE', '%' . $cari . '%')
                    ->orwhere('barcode', 'LIKE', '%' . $cari . '%');
            })
            ->limit(20)
            ->get();
        return $data;
    }
    //datatable cari barang penjualang
    public function caritabelbarang()
    {
        $barang = Barang::where('status', '=', 1);
        return DataTables::of($barang)
            ->editColumn('harga_jual_dasar1', function ($barang) {
                return Rupiah0($barang->harga_jual_dasar1);
            })
            ->addColumn('aksi', function ($barang) {
                return '<a onclick="PilihBarang(' . $barang->id . ')"  class="btn btn-danger btn-xs"><i class="fas fa-pencil-alt"></i> PILIH</a> ' .
                    '<a href="' . url("/mutasibarang") . "/" . $barang->id . '" target="_blank" class="btn btn-success btn-xs"><i class="fas fa-exchange-alt"></i> MT</a> ';
            })
            ->addColumn('aksi2', function ($barang) {
                return '<a onclick="PilihBarang2(' . $barang->id . ')"  class="btn btn-danger btn-xs text-white"><i class="fas fa-pencil-alt"></i> PILIH</a> ';
            })

            ->rawColumns(['harga_jual_dasar1', 'aksi', 'aksi2',])
            // ->setRowClass(function ($barang) {
            //     $tabIndex = '0'; // Set tabindex to 0 for all rows
            //     return 'focusable-row ' . $tabIndex; // Combine the class and tabindex into one string
            // })
            ->make(true);
    }
    public function opnamebarang()
    {
        return view('barang.opnamebarang');
    }

    public function index()
    {
        $satuan = Satuan::orderBy('nama_satuan', 'ASC')->get();
        $merkbarang = Merkbarang::orderBy('nama_merkbarang', 'ASC')->get();
        $jenisbarang = Jenisbarang::orderBy('nama_jenisbarang', 'ASC')->get();
        return view('barang.index', compact([
            'satuan',
            'merkbarang',
            'jenisbarang'
        ]));
    }
    public function stokminimum()
    {
        return view('barang.stokminimum');
    }
    public function cekharga()
    {
        return view('barang.cekharga');
    }
    public function setupharga()
    {
        $satuan = Satuan::orderBy('nama_satuan', 'ASC')->get();
        $merkbarang = Merkbarang::orderBy('nama_merkbarang', 'ASC')->get();
        $jenisbarang = Jenisbarang::orderBy('nama_jenisbarang', 'ASC')->get();
        return view('barang.setupharga', compact([
            'satuan',
            'merkbarang',
            'jenisbarang'
        ]));
    }
    public function TabelBarang(Request $request)
    {
        $status = $request->status;
        $merkbarang = $request->merkbarang;
        $jenisbarang = $request->jenisbarang;

        $barang = Barang::with('satuan', 'merkbarang', 'jenisbarang', 'suplier') // Load the 'satuan' relationship
            ->select('barang.*')        // Select all columns from the 'barang' table
            ->where('status', $status)  // Add the 'status' condition
            ->when($merkbarang, function ($query, $merkbarang) { // Conditional for 'merkbarang_id'
                $query->where('merkbarang_id', $merkbarang);
            })
            ->when($jenisbarang, function ($query, $jenisbarang) { // Conditional for 'jenisbarang_id'
                $query->where('jenisbarang_id', $jenisbarang);
            });

        return DataTables::of($barang)
            ->addColumn('image', function ($barang) {
                $fotos = '';
                $fotobarang = FotoBarang::where('barang_id', $barang->id)->get();
                if ($fotobarang->count() > 0) {
                    foreach ($fotobarang as $fb) {
                        $fotos .= '<a href="' . publicfolder() . '/barang/' . $fb->image . '" data-toggle="lightbox"
                    data-gallery="gallery">
                    <img class="direct-chat-img img-thumbnail img-square img-fluid" 
                    src="' . publicfolder() . 'barang/thumbnail/' . $fb->image . '" alt="FOTO HILANG"> </a>';
                    }
                }

                return $fotos   . '<a href="' . url('/') . '/profilbarang/' . $barang->id . '" class="btn btn-primary direct-chat-img" >
            <i class="fa fa-plus" aria-hidden="true"></i>
            </a>';
            })
            ->addColumn('pilih', function ($barang) {
                return
                    '<input type="checkbox" class="sub_chk1" id="' . $barang->id . '" data-id="' . $barang->id . '"> 
            <label class="form-check-label" for="' . $barang->id . '">
                                    PILIH
                                </label>
            ';
            })
            ->addColumn('aksi', function ($barang) {
                //return '';
                if (Auth()->User()->role_id == 1) {
                    return '<a onclick="OtEdit(' . $barang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ';
                    // .'<a onclick="OtDelete(' . $barang->id . ')" class="btn btn-danger btn-xs bg-dark"><i class="fas fa-trash-alt"></i> Delete</a>';
                }
                return '<a onclick="EditData(' . $barang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> </a> ';
                // '<a onclick="DeleteData(' . $barang->id . ')" class="btn btn-danger btn-xs bg-dark"><i class="fas fa-trash-alt"></i> Delete</a>';
            })
            ->editColumn('harga_jual_dasar1', function ($barang) {
                return Rupiah0($barang->harga_jual_dasar1);
            })
            ->editColumn('harga_jual_dasar2', function ($barang) {
                return Rupiah0($barang->harga_jual_dasar2);
            })
            ->rawColumns(['pilih', 'aksi', 'image'])

            ->make(true);
    }

    public function store(Request $request)
    {
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'nama_barang' => 'required|unique:barang,nama_barang',
        ], [
            'nama_barang.required' => 'Nama Barang harus diisi.', // Custom message for "required"
            'nama_barang.unique'   => 'Nama Barang sudah terdaftar.', // Custom message for "unique"
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        $input = $request->all();
        $input['status_sa'] = 1;
        $input['kode'] = kode(12);
        $input['barcode'] = $request->barcode ?? kode(12);
        $input['kode_internal'] = $request->kode_internal ?? kode(12);
        $input['suplier_id'] = $request->suplier_id ?? 1;

        if ($Barang = Barang::create($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Barang Berhasil Dibuat'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Barang Gagal Dibuat'
        ];
        return $data;
    }
    public function show($id)
    {
        $Barang = Barang::Aktif()->where('id', $id)->first();
        if (!$Barang) {
            $data = [
                'success' => 0,
                'pesan' => 'Barang Tidak ada'
            ];
            return $data;
        }
        if ($Barang) {
            $Barang['satuan'] = $Barang->Satuan->nama_satuan;
            $Barang['success'] = 1;
            return $Barang;
        }
    }
    public function edit($id)
    {
        $Barang = Barang::findorfail($id);
        if ($Barang->suplier_id != null) {
            $Barang['kode_sup'] = $Barang->Suplier->kode;
            $Barang['nama_sup'] = $Barang->Suplier->nama;
        } else {
            $Barang['kode_sup'] = '';
            $Barang['nama_sup'] = '';
        }
        $tgl_beli = $Barang->tgl_beli;
        if ($tgl_beli != null) {
            $dateTime = new DateTime($tgl_beli);
            $Barang['tgl_beli_indo'] = $dateTime->format('dmy');
        } else {
            $Barang['tgl_beli_indo'] = '';
        }
        if (!$Barang) {
            $data = [
                'success' => 0,
                'pesan' => 'Barang Tidak ada'
            ];
            return $data;
        }
        if ($Barang) {
            $Barang['success'] = 1;
            return $Barang;
        }
    }

    public function update(Request $request, $id)
    {
        $Barang = Barang::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'nama_barang'         => 'required|unique:barang,nama_barang,' . $id,
        ], [
            'nama_barang.required' => 'Nama Barang harus diisi.', // Custom message for "required"
            'nama_barang.unique'   => 'Nama Barang sudah terdaftar.', // Custom message for "unique"
        ]);
        $input = $request->all();
        if ($validator->fails()) {
            return $validator->errors();
        }
        if ($Barang->update($input)) {
            $data = [
                'success' => 1,
                'pesan' => 'Barang Berhasil Diubah'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Barang Tidak Berhasil Diubah'
        ];
        return $data;
    }
    public function editharga(Request $request, $id)
    {
        $Barang = Barang::findOrFail($id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'harga1_edit'       => 'required',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }
        $input1['harga1'] = $request->harga1_edit;
        $input1['harga2'] = $request->harga2_edit;
        $input1['harga3'] = $request->harga3_edit;
        if ($Barang->update($input1)) {
            $inputtrait['column'] = 'barang';
            $inputtrait['column_id'] = $Barang->id;
            $inputtrait['detail'] = 'Edit Harga :' . $Barang->nama . ' isi ' . implode(",", $request->except('_token'));
            $Log = $this->AddLogOpr($inputtrait);
            $data = [
                'success' => 1,
                'pesan' => 'Harga Berhasil Diubah'
            ];
            return $data;
        };
        $data = [
            'success' => 0,
            'pesan' => 'Harga Tidak Berhasil Diubah'
        ];
        return $data;
    }
    // public function fastupdatebarang(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $id = $request->id;
    //         $column = $request->column;
    //         $value = $request->value;
    //         //$input = $request->only('harga1', 'harga2', 'nama', 'part_number', 'kendaraan', 'kompatibel');
    //         if ($column == 'suplier_id') {
    //             $suplier = Suplier::where('kode', $value)->first();
    //             if (!$suplier) {
    //                 $data = [
    //                     'success' => 0,
    //                     'pesan' => 'KODE SUPLIER TIDAK DITEMUKAN',
    //                 ];
    //                 return $data;
    //             }
    //             if ($suplier->status == 0) {
    //                 $data = [
    //                     'success' => 0,
    //                     'pesan' => 'SUPLIER TIDAK AKTIF',
    //                 ];
    //                 return $data;
    //             }
    //             $input['suplier_id'] = $suplier->id;
    //         } else {

    //             if ($column == 'tgl_beli') {
    //                 $value = tanggaltodate($value);
    //             }
    //             $input[$column] = $value;
    //         }

    //         // return $input;
    //         $Barang = Barang::findorfail($id);

    //         if (empty($Barang)) {
    //             $data = [
    //                 'success' => 0,
    //                 'pesan' => 'ITEM TIDAK DITEMUKAN',
    //             ];
    //             return $data;
    //         }
    //         if ($Barang) {

    //             $inputtrait['column'] = 'barang';
    //             $inputtrait['column_id'] = $Barang->id;
    //             $inputtrait['detail'] = 'Fast Update Barang :' . $Barang->nama . ' isi ' . implode(",", $request->except('_token'));
    //             $Log = $this->AddLogOpr($inputtrait);
    //             if ($column == 'stok') {
    //                 $inputtrait['detail'] = 'QuickOpname :' . $Barang->nama . ' isi ' . implode(",", $request->except('_token'));
    //                 $stokawal = $Barang->stok;
    //                 $stokfisik = $value;

    //                 if ($stokawal < $stokfisik) { //jika plus == barang_in
    //                     $selisih = $stokfisik - $stokawal;
    //                     $input = [
    //                         'tipe' => 'OPN',
    //                         'tanggal' => now(),
    //                         'invoice' => 'QOPNAME',
    //                         'barang_id' => $Barang->id,
    //                         'qty' => $selisih,
    //                         'hpp' => $Barang->harga_terakhir,
    //                         'totalhpp' => $selisih * $Barang->harga_terakhir,
    //                         'harga' => $Barang->harga_terakhir,
    //                         'total_harga' => $selisih * $Barang->harga_terakhir,
    //                         'user_id' => Auth()->User()->id,
    //                     ];
    //                     $BarangIn = BarangIn::create($input);
    //                 }
    //                 if ($stokawal > $stokfisik) { //jika minus == barang_out
    //                     $selisih = $stokawal - $stokfisik;
    //                     $input = [
    //                         'tipe' => 'OPN',
    //                         'tanggal' => now(),
    //                         'invoice' => 'QOPNAME',
    //                         'barang_id' => $Barang->id,
    //                         'qty' => $selisih,
    //                         'hpp' => $Barang->harga_terakhir,
    //                         'totalhpp' => $selisih * $Barang->harga_terakhir,
    //                         'harga' => $Barang->harga_terakhir,
    //                         'total_harga' => $selisih * $Barang->harga_terakhir,
    //                         'user_id' => Auth()->User()->id,
    //                     ];
    //                     $BarangOut = BarangOut::create($input);
    //                 }
    //             } else {
    //                 $Barang->update($input);
    //             }
    //             $data = [
    //                 'success' => 1,
    //                 'pesan' => 'Barang Berhasil Diubah',
    //             ];
    //             return $data;
    //         }
    //         $data = [
    //             'success' => 0,
    //             'pesan' => 'ERROR',
    //         ];
    //         return $data;
    //     }
    // }
    public function updatehargajual(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $column = $request->column;
            $value = $request->value;
            //$input = $request->only('harga1', 'harga2', 'nama', 'part_number', 'kendaraan', 'kompatibel');
            $input[$column] = $value;
            $Barang = Barang::findorfail($id);
            // $data = [
            //     'success' => 0,
            //     'pesan' => $request->value,
            // ];
            // return $data;
            if (empty($Barang)) {
                $data = [
                    'success' => 0,
                    'pesan' => 'Harga Tidak ditemukan'
                ];
                return $data;
            } else {
                $inputtrait['column'] = 'barang';
                $inputtrait['column_id'] = $Barang->id;
                $inputtrait['detail'] = 'Update Harga Jual :' . $Barang->nama . ' isi ' . implode(",", $request->except('_token'));
                $Log = $this->AddLogOpr($inputtrait);
                $Barang->update($input);
                $data = [
                    'success' => 1,
                    'pesan' => 'Harga JUAL Berhasil Diubah'
                ];
                return $data;
            }
        }
    }
    public function destroy($id)
    {
        $Barang = Barang::findorfail($id);
        $inputtrait['column'] = 'barang';
        $inputtrait['column_id'] = $Barang->id;
        $inputtrait['detail'] = 'HAPUS BARANG :' . $Barang->nama;
        $Log = $this->AddLogOpr($inputtrait);
        if (Barang::destroy($id)) {
            $data = [
                'success' => 1,
                'pesan' => 'Barang Berhasil Dihapus'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Barang Tidak Berhasil Dihapus'
        ];
        return $data;
    }
    public function Restore($id)
    {
        if (Barang::withTrashed()->find($id)->restore()) {
            $data = [
                'success' => 1,
                'pesan' => 'Barang Berhasil DiRestore'
            ];
            return $data;
        }
        $data = [
            'success' => 0,
            'pesan' => 'Barang Tidak Berhasil DiRestore'
        ];
        return $data;
    }
    public function gantihargamasal(Request $request)
    {
        if ($request->ajax()) {
            $ids = explode(",", $request->ids);
            $tipe_ganti_harga = $request->tipe_ganti_harga;
            $persen = $request->persen;
            $rupiah = $request->rupiah;
            foreach ($ids as $id) {
                $Barang = Barang::findorfail($id);
                if ($tipe_ganti_harga == 1) { //naik
                    // $mentahharga3 = $Barang->harga3 == 0 ? 0 : $Barang->harga3 + ($Barang->harga3 * $persen / 100) + $rupiah;
                    // $pembulatan3 = ceil($mentahharga3 / 500) * 500;
                    // $harga3 = ROUND($pembulatan3);
                    $mentahharga_jual_dasar2 = $Barang->harga_jual_dasar2 == 0 ? 0 : $Barang->harga_jual_dasar2 + ($Barang->harga_jual_dasar2 * $persen / 100) + $rupiah;
                    $pembulatan2 = ceil($mentahharga_jual_dasar2 / 500) * 500;
                    $harga_jual_dasar2 = ROUND($pembulatan2);
                    $mentahharga_jual_dasar1 = $Barang->harga_jual_dasar1 == 0 ? 0 + $rupiah : $Barang->harga_jual_dasar1 + ($Barang->harga_jual_dasar1 * $persen / 100) + $rupiah;
                    $pembulatan1 = ceil($mentahharga_jual_dasar1 / 500) * 500;
                    $harga_jual_dasar1 = ROUND($pembulatan1);
                } else { //turun
                    // $mentahharga3 = $Barang->harga3 == 0 ? 0 : $Barang->harga3 - ($Barang->harga3 * $persen / 100) - $rupiah;
                    // $pembulatan3 = ceil($mentahharga3 / 500) * 500;
                    // $harga3 = ROUND($pembulatan3);
                    $mentahharga_jual_dasar2 = $Barang->harga_jual_dasar2 == 0 ? 0 : $Barang->harga_jual_dasar2 - ($Barang->harga_jual_dasar2 * $persen / 100) - $rupiah;
                    $pembulatan2 = ceil($mentahharga_jual_dasar2 / 500) * 500;
                    $harga_jual_dasar2 = ROUND($pembulatan2);
                    $mentahharga_jual_dasar1 = $Barang->harga_jual_dasar1 == 0 ? 0 : $Barang->harga_jual_dasar1 - ($Barang->harga_jual_dasar1 * $persen / 100) - $rupiah;
                    $pembulatan1 = ceil($mentahharga_jual_dasar1 / 500) * 500;
                    $harga_jual_dasar1 = ROUND($pembulatan1);
                }
                $input['harga_jual_dasar1'] = $harga_jual_dasar1;
                $input['harga_jual_dasar2'] = $harga_jual_dasar2;
                $Barang->update($input);
            }
            return response()->json(['success' => "Semua Barang Tercentang berhasil di Ubah Harga"]);
        }
        abort(404);
    }
    public function importbarang(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        // upload ke folder file_siswa di dalam folder public
        $destinationPath = public_path('file_barang');
        $file->move($destinationPath, $nama_file);

        // import data
        Excel::import(new BarangImport, public_path('/file_barang/' . $nama_file));

        return redirect('/barang')->with('sukses', 'Data Barang Berhasil Diimport!');
    }
    // public function tambahqo(Request $request)
    // {
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'qb_nama_barang'       => 'required',
    //         'qb_harga_beli'       => 'required',
    //         'qb_qty_beli'       => 'required',
    //         'qb_harga_jual'       => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return $validator->errors();
    //     }

    //     //barang baru
    //     $input_barang = [
    //         'nama' => $request->qb_nama_barang,
    //         'part_number' => $request->qb_part_number,
    //         'kendaraan' => $request->qb_kendaraan,
    //         'satuan_id' => $request->qb_satuan_id,
    //         'harga1' => $request->qb_harga_jual,
    //         'harga_terakhir' => $request->qb_harga_beli,
    //         'hpp_awal' => $request->qb_harga_beli,
    //         'totalhpp_awal' => $request->qb_harga_beli * $request->qb_qty_beli,
    //         'stok_awal' => $request->qb_qty_beli,
    //     ];

    //     if ($request->kode == null) {
    //         $pel = Barang::withTrashed()->latest('id')->first();
    //         if (empty($pel)) {
    //             $urut = 10001;
    //         } else {
    //             $urut = $pel->id + 10001;
    //         }
    //         $latest_id = 'SB' . $urut;
    //         $input_barang['kode'] = $latest_id;
    //     }
    //     $input_barang['status_sa'] = 0;

    //     $Barang = Barang::create($input_barang);
    //     $inputtrait['column'] = 'barang';
    //     $inputtrait['column_id'] = $Barang->id;
    //     $inputtrait['detail'] = 'Quick Opname :' . $Barang->nama . ' isi ' . implode(",", $request->except('_token'));
    //     $Log = $this->AddLogOpr($inputtrait);
    //     // barang masuk baru
    //     $input_barang_in = [
    //         'tipe' => 'OPN',
    //         'tanggal' => now(),
    //         'invoice' => 'OPNAMEQUICK',
    //         'barang_id' => $Barang->id,
    //         'qty' => $request->qb_qty_beli,
    //         'hpp' => $request->qb_harga_beli,
    //         'totalhpp' => $request->qb_harga_beli * $request->qb_qty_beli,
    //         'harga' => $request->qb_harga_beli,
    //         'total_harga' => $request->qb_harga_beli * $request->qb_qty_beli,
    //         'user_id' => Auth()->User()->id,
    //     ];
    //     $BarangIn = BarangIn::create($input_barang_in);

    //     $data = [
    //         'success' => 1,
    //         'pesan' => 'Berhasil Menambahkan item',
    //         'Idbarang' => $Barang->id,
    //     ];
    //     return $data;
    // }
    public function nonaktifallcek(Request $request)
    {
        $ids = explode(",", $request->ids);
        foreach ($ids as $id) {
            $inventaris = Barang::find($id);
            $input['status'] = 0;
            $inventaris->update($input);
        }
        return response()->json(['success' => "Semua data Tercentang berhasil di NON AKTIFKAN"]);
    }
}

        // if ($status == 3) {        //trash
        //     $barang = DB::table('barang')
        //         ->where('deleted_at', '!=', null);
        //     return DataTables::of($barang)
        //         ->addColumn('image', function ($barang) {
        //             $fotos = '';
        //             $fotobarang = Fotobarang::where('barang_id', $barang->id)->get();
        //             if ($fotobarang->count() > 0) {
        //                 foreach ($fotobarang as $fb) {
        //                     $fotos .= '<a href="' . url('/') . '/bengkel/public/barang/' . $fb->image . '" data-toggle="lightbox"
        //                 data-gallery="gallery">
        //                 <img class="direct-chat-img img-thumbnail img-square img-fluid" 
        //                 src="' . url('/') . '/bengkel/public/barang/thumbnail/' . $fb->image . '" alt="FOTO HILANG"> </a>';
        //                 }
        //             }
        //             return $fotos   . '<a href="' . url('/') . '/profilbarang/' . $barang->id . '" class="btn btn-primary direct-chat-img" >
        //         <i class="fa fa-plus" aria-hidden="true"></i>
        //         </a>';
        //         })
        //         ->addColumn('hargaakhir', function ($barang) {
        //             return Rupiah($barang->harga_terakhir);
        //         })
        //         ->addColumn('hargasatu', function ($barang) {
        //             return Rupiah($barang->harga1);
        //         })
        //         ->addColumn('hargadua', function ($barang) {
        //             return Rupiah($barang->harga2);
        //         })
        //         ->addColumn('hargatiga', function ($barang) {
        //             return Rupiah($barang->harga3);
        //         })
        //         ->addColumn('ceklist', function ($barang) {
        //             return '
        //             <input  type="checkbox" class="sub_chk1" id="cb1' . $barang->id . '"
        //             data-id="' . $barang->id . '">
        //             ';
        //         })
        //         ->addColumn('aksi', function ($barang) {
        //             return '<a onclick="RestoreTrash(' . $barang->id . ')"  class="btn btn-success btn-xs"><i class="fas fa-pencil-alt"></i> RESTORE</a> ';
        //         })
        //         ->addColumn('stokedit', function ($barang) {
        //             return '<input type="text" onchange="fastupdatebarang(this)" class="form-control form-control-sm text-right"
        //             name="stokedit' . $barang->id . '" id="stokedit' . $barang->id . '" data-column="stok" data-id="' . $barang->id . '"
        //             value="' . $barang->stok . '">';
        //             // return RupiahNonRp($barang->harga_terakhir);
        //         })
        //         ->addColumn('kode_sup', function ($barang) {
        //             if ($barang->suplier_id != null) {
        //                 return $barang->Suplier->kode;
        //             }
        //             return '';
        //         })
        //         ->addColumn('data_log', function ($barang) {
        //             // $log = detaillogopr('barang', $barang->id);
        //             // if ($log !== null) {
        //             //     return $log->detail;
        //             // }
        //             return '<a href="' . url("/detaillogsopr") . "/barang/" . $barang->id . '" target="_blank" class="btn btn-success btn-xs"><i class="fas fa-exchange-alt"></i> LOGS</a> ';
        //         })
        //         ->rawColumns(['ceklist', 'data_log', 'kode_sup', 'stokedit', 'nama', 'hargaakhir', 'hargasatu', 'hargadua', 'hargatiga',  'cekharga', 'aksi', 'pilih', 'image'])
        //         ->make(true);
        // }

        // $barang = Barang::with('satuan', 'suplier')
        //     // ->where('nama', 'like', '%' . $nama . '%')
        //     // ->Where('part_number', 'like', '%' .  $part_number . '%')
        //     ->select('barang.*')
        //     ->where('status', '=', $status)
        //     ->where('deleted_at', '=', null);
        // return DataTables::of($barang)
        //     ->addColumn('pilih', function ($barang) {
        //         return '
        //         <input type="checkbox" class="sub_chk" id="chk' . $barang->id . '" data-id="' . $barang->id . '">
        //         <label class="form-check-label" for="chk' . $barang->id . '">Pilih</label>';
        //     })
        //     ->addColumn('hargaakhir', function ($barang) {
        //         return '<input type="text" onchange="fastupdatebarang(this)" class="form-control form-control-sm text-right"
        //         name="hargaakhir' . $barang->id . '" id="hargaakhir' . $barang->id . '" data-column="harga_terakhir" data-id="' . $barang->id . '"
        //         value="' . RupiahNonRp($barang->harga_terakhir) . '">';
        //         // return RupiahNonRp($barang->harga_terakhir);
        //     })
        //     ->addColumn('stokedit', function ($barang) {
        //         return '<input type="text" onchange="fastupdatebarang(this)" class="form-control form-control-sm text-right"
        //         name="stokedit' . $barang->id . '" id="stokedit' . $barang->id . '" data-column="stok" data-id="' . $barang->id . '"
        //         value="' . $barang->stok . '">';
        //         // return RupiahNonRp($barang->harga_terakhir);
        //     })

        //     // ->editColumn('nama', function ($barang) {
        //     //     return '<input type="text" onchange="fastupdatebarang(this)" class="form-control form-control-sm text-right"
        //     //     name="nama' . $barang->id . '" id="nama' . $barang->id . '" data-column="nama" data-id="' . $barang->id . '"
        //     //     value="' . $barang->nama . '">';
        //     // })

        //     ->addColumn('hargasatu', function ($barang) {
        //         return '<input type="text" onchange="fastupdatebarang(this)" class="form-control form-control-sm text-right"
        //         name="hargasatu' . $barang->id . '" id="hargasatu' . $barang->id . '" data-column="harga1" data-id="' . $barang->id . '"
        //         value="' . RupiahNonRp($barang->harga1) . '">';
        //     })
        //     ->addColumn('hargadua', function ($barang) {
        //         return '<input type="text" onchange="fastupdatebarang(this)" class="form-control form-control-sm text-right"
        //         name="hargadua' . $barang->id . '" id="hargadua' . $barang->id . '" data-column="harga2" data-id="' . $barang->id . '"
        //         value="' . RupiahNonRp($barang->harga2) . '">';
        //     })
        //     ->addColumn('hargatiga', function ($barang) {
        //         return '<input type="text" onchange="fastupdatebarang(this)" class="form-control form-control-sm text-right"
        //         name="hargatiga' . $barang->id . '" id="hargatiga' . $barang->id . '" data-column="harga3" data-id="' . $barang->id . '"
        //         value="' . RupiahNonRp($barang->harga3) . '">';
        //     })
        //     ->addColumn('aksi', function ($barang) {
        //         if (Auth()->User()->role_id == 1) {
        //             return '<a href="' . url("/mutasibarang") . "/" . $barang->id . '" target="_blank" class="btn btn-success btn-xs"><i class="fas fa-exchange-alt"></i> LIHAT MUTASI</a> ' .
        //                 '<a onclick="OtEdit(' . $barang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
        //                 '<a onclick="OtDelete(' . $barang->id . ')" class="btn btn-danger btn-xs bg-dark"><i class="fas fa-trash-alt"></i> Delete</a>';
        //         }
        //         return
        //             '<a href="' . url("/mutasibarang") . "/" . $barang->id . '" target="_blank" class="btn btn-success btn-xs"><i class="fas fa-exchange-alt"></i> LIHAT MUTASI</a> ' .
        //             '<a onclick="EditData(' . $barang->id . ')"  class="btn btn-warning btn-xs"><i class="fas fa-pencil-alt"></i> Edit</a> ' .
        //             '<a onclick="DeleteData(' . $barang->id . ')" class="btn btn-danger btn-xs bg-dark"><i class="fas fa-trash-alt"></i> Delete</a>';
        //     })
        //     ->addColumn('cekharga', function ($barang) {
        //         return RupiahNonRp($barang->harga1);
        //     })
        //     ->addColumn('image', function ($barang) {
        //         $fotos = '';
        //         $fotobarang = Fotobarang::where('barang_id', $barang->id)->get();
        //         if ($fotobarang->count() > 0) {
        //             foreach ($fotobarang as $fb) {
        //                 $fotos .= '<a href="' . url('/') . '/bengkel/public/barang/' . $fb->image . '" data-toggle="lightbox"
        //                 data-gallery="gallery">
        //                 <img class="direct-chat-img img-thumbnail img-square img-fluid" 
        //                 src="' . url('/') . '/bengkel/public/barang/thumbnail/' . $fb->image . '" alt="FOTO HILANG"> </a>';
        //             }
        //         }
        //         return $fotos   . '<a href="' . url('/') . '/profilbarang/' . $barang->id . '" class="btn btn-primary direct-chat-img" >
        //         <i class="fa fa-plus" aria-hidden="true"></i>
        //         </a>';
        //     })
        //     ->addColumn('kode_sup', function ($barang) {
        //         if ($barang->Suplier) {
        //             return $barang->Suplier->kode;
        //         }
        //         return '';
        //     })
        //     ->addColumn('ceklist', function ($barang) {
        //         return '
        //         <input  type="checkbox" class="sub_chk1" id="cb1' . $barang->id . '"
        //         data-id="' . $barang->id . '">
        //         ';
        //     })
        //     ->addColumn('data_log', function ($barang) {
        //         // $log = detaillogopr('barang', $barang->id);
        //         // if ($log !== null) {
        //         //     return $log->detail;
        //         // }
        //         return '<a href="' . url("/detaillogsopr") . "/barang/" . $barang->id . '" target="_blank" class="btn btn-success btn-xs"><i class="fas fa-exchange-alt"></i> LOGS</a> ';
        //     })
        //     ->rawColumns(['ceklist', 'data_log', 'kode_sup', 'stokedit', 'nama', 'hargaakhir', 'hargasatu', 'hargadua', 'hargatiga',  'cekharga', 'aksi', 'pilih', 'image'])
        //     ->make(true);