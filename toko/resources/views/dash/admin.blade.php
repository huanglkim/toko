@extends('layout.main')
@section('title', 'Dashboard')
@section('css')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@stop
@section('content')
    <section class="content">
        {{-- <div class="card-body pb-0 pt-1">
            <div class="col-12  col-md-6 d-flex align-items-stretch">
                <div class="card">
                    <div class="card-body pt-0 pb-0 card-outline card-olive">
                        <div class="card-header pt-0 pb-0 ">
                            <p><strong> ID CARD</strong></p>
                        </div>
                        <div class="row">
                            <div class="col-7">
                                <h2 class="lead"><b>Nama : {{ Auth()->User()->nama }}</b></h2>
                                <ul class="ml-4 mb-0 fa-ul text-muted">
                                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-building"></i></span>
                                        Alamat :
                                        {{ Auth()->User()->alamat }}</li>
                                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> Phone
                                        #:
                                        {{ Auth()->User()->wa }}
                                    </li>
                                    <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span>
                                        Sebagai:
                                        {{ Auth()->User()->Role->nama_role }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-5 text-center ">
                                <img src="{{ Auth()->User()->foto == null ? url('/images/user.png') : url(Auth()->User()->foto) }}"
                                    alt="" class="img-circle img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        SPK<br>
                        Hari Ini : {{ $TotalSpkHari }}<br>
                        Bulan Ini : {{ $TotalSpkBulan }} <br>
                        TOTAL : {{ $TotalSpk }} <br>
                    </div>
                    <div class="icon">
                        <i class="ion ion-model-s"></i>
                    </div>
                    <a href="{{ url('/spk') }}" class="small-box-footer">LIST SPK<i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        Kasir Hari Ini<br>
                        CASH : {{ Rupiah0($KasirCashHari) }}<br>
                        TRANSFER : {{ Rupiah0($KasirTfHari) }} <br>
                        PIUTANG : {{ Rupiah0($KasirPiutangHari) }} <br>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ url('/listtransaksi') }}" class="small-box-footer">LIST TRANSAKSI <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <p>Pendapatan Bulan ini</p>
                        Barang : {{ Rupiah0($PjBarangBulanIni) }}<br>
                        Jasa : {{ Rupiah0($PjJasaBulanIni) }} <br>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ url('/listtransaksi') }}" class="small-box-footer">LIST TRANSAKSI <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h2>{{ Rupiah0($PbBulanIni) }}</h2>
                        <p>Pembelian Bulan Ini</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ url('/listpembelian') }}" class="small-box-footer">LIST PEMBELIAN <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <p>
                        DATA NOTIFIKASI JASA SUDAH WAKTUNYA SERVICE ULANG
                    </p>
                    <table class="table table-bordered table-sm table-hover table-responsive-sm">
                        <thead>
                            <tr class="bg-primary">
                                <th>Nama Jasa</th>
                                <th>Keterangan</th>
                                <th>Tanggal Service</th>
                                <th>Kendaraan</th>
                                <th>Pemilik</th>
                                <th>Kirim WA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jasanotif as $jn)
                                @if ($jn->TransaksiOut->PelKendaraan()->exists() && $jn->TransaksiOut->Pelanggan()->exists())
                                    <tr>
                                        <td>{{ $jn->Jasa->nama }}</td>
                                        <td>{{ $jn->ketarangan }}</td>
                                        <td>{{ TanggalIndo($jn->created_at) }}</td>
                                        <td>{{ $jn->TransaksiOut->PelKendaraan->plat_nomor }} (
                                            {{ $jn->TransaksiOut->PelKendaraan->seri }} )</td>
                                        <td>{{ $jn->TransaksiOut->Pelanggan->nama }}/{{ $jn->TransaksiOut->Pelanggan->alamat }}
                                        </td>
                                        <td>{{ $jn->TransaksiOut->Pelanggan->wa }} <a
                                                href="http://wa.me/{{ $jn->TransaksiOut->Pelanggan->wa }}?text={{ datapengaturan('text_notif') }}"
                                                target="_blank" class="btn btn-sm bg-teal"
                                                onclick="notifdone({{ $jn->id }})">
                                                <i class="fab fa-whatsapp"> KIRIM NOTIFIKASI</i>
                                            </a></td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">STOK LIMIT MINIMUM
                        <a href="{{ url('/') }}/lapminimum" class="btn btn-xs btn-primary">Lihat Selengkapnya</a>
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>BARANG</th>
                                    <th>PART NUMBER</th>
                                    <th>STOK</th>
                                    <th>MINIMUM</th>
                                    <th>SATUAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($StokMinimums as $stokminimum)
                                    <tr>
                                        <td>{{ $stokminimum->nama }}</td>
                                        <td>{{ $stokminimum->part_number }}</td>
                                        <td>{{ $stokminimum->stok }}</td>
                                        <td>{{ $stokminimum->minimum }}</td>
                                        <td>{{ $stokminimum->Satuan->nama }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer clearfix">
                    <a href="{{ url('/pembelian') }}/create" class="btn btn-sm btn-info float-left">Tambah Pembelian</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">JUAL RUGI
                        <a href="{{ url('/') }}/lapjualrugi" class="btn btn-xs btn-primary">Lihat Selengkapnya</a>
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>PART</th>
                                    <th>HPP</th>
                                    <th>HARGA 1</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jualrugi as $jr)
                                    <tr>
                                        <td>{{ $jr->nama }}</td>
                                        <td>{{ $jr->part_number }}</td>
                                        <td>{{ Rupiah0($jr->harga_terakhir) }}</td>
                                        <td>{{ Rupiah0($jr->harga1) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- //paling laku --}}
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">STOK PALING LAKU</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Laku</th>
                                    <th>SATUAN</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($PalingLakus as $PalingLaku)
                                    <tr>
                                        <td>{{ $PalingLaku->Barang->nama }}</td>
                                        <td>{{ $PalingLaku->totalqty }}</td>
                                        <td>{{ $PalingLaku->Barang->Satuan->nama }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- //paling laku --}}
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">ASAL KOTA CUSTOMER</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>KOTA</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($KotaPelanggan as $KotaPel)
                                    <tr>
                                        <td>{{ $KotaPel->kota }}</td>
                                        <td>{{ $KotaPel->total }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- //paling laku --}}
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Kunjungan Terbanyak</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table m-0">
                            <thead>
                                <tr>
                                    <th>Pelanggan</th>
                                    <th>Alamat</th>
                                    <th>JUMLAH</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($KunjunganPelanggan as $KuPel)
                                    <tr>
                                        <td>{{ $KuPel->Pelanggan->nama }}</td>
                                        <td>{{ $KuPel->Pelanggan->alamat }}</td>
                                        <td>{{ $KuPel->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
@stop
@section('script')

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            localStorage.clear();
        }, false);
    </script>
    <script type="text/javascript">
        function notifdone(id) {
            var csrf_token = " {{ csrf_token() }} ";
            $.ajax({
                url: "{{ url('/') }}/notifjasadone/" + id,
                type: "POST",
                data: {
                    '_token': csrf_token,
                    'status_notif': 1
                },
                success: function(data) {
                    location.reload();

                },
                error: function(data) {
                    console.log(data);

                }
            });
        }
    </script>
@stop
