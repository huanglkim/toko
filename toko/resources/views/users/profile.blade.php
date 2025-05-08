@extends('layout.main')
@section('title', 'User Profile')

@section('css')
    <link href="{{ url('/') }}/lte/plugins/datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet" />
@stop

@section('content')
<section class="content">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row">
                        {{-- Profile Section (Left) --}}
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="{{ asset('/toko/public/storage/' . $profile->foto) }}" alt="Foto Profil"
                                    class="rounded-circle img-fluid border shadow-sm mb-3"
                                    style="width: 150px; height: 150px; object-fit: cover;">
                            
                                <form action="{{ url('/gantifotoprofile') }}" method="POST" enctype="multipart/form-data"
                                    class="d-flex justify-content-center align-items-center gap-2 mt-2 flex-wrap">
                                    @csrf
                                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*" style="max-width: 180px;">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-upload me-1"></i> Update
                                    </button>
                                </form>
                            </div>
                            

                            <hr>

                            <div class="mt-3">
                                <h4 class="fw-bold mb-2 text-center">{{ $profile->nama }}</h4>
                                <p class="text-muted text-center"><strong>Username:</strong> {{ $profile->username }}</p>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fas fa-briefcase me-2 text-primary"></i>{{ $profile->Role->nama_jabatan }}</li>
                                    <li><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ $profile->alamat }}</li>
                                    <li><i class="fas fa-phone me-2 text-primary"></i>{{ $profile->wa }}</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Edit Form Section (Right) --}}
                        <div class="col-md-8">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Edit Profil</h5>
                                </div>
                                <div class="card-body">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            {{ $errors->first() }}
                                        </div>
                                    @endif

                                    <form action="{{ url('/updateprofile/' . $profile->id) }}" method="POST">
                                        @csrf

                                        <div class="mb-3 row">
                                            <label for="nik" class="col-sm-3 col-form-label">N.I.K</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="nik" name="nik"
                                                    class="form-control form-control-sm"
                                                    value="{{ $profile->nik }}">
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label class="col-sm-3 col-form-label">Tempat, Tanggal Lahir</label>
                                            <div class="col-sm-5">
                                                <input type="text" id="tempat_lahir" name="tempat_lahir"
                                                    class="form-control form-control-sm"
                                                    value="{{ $profile->tempat_lahir }}" placeholder="Kota Lahir">
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="text" id="tanggal_lahir" name="tanggal_lahir"
                                                    class="form-control form-control-sm datepicker"
                                                    value="{{ datetotanggal($profile->tanggal_lahir) }}"
                                                    placeholder="DD-MM-YYYY">
                                            </div>
                                        </div>

                                        <div class="mb-3 row">
                                            <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                                            <div class="col-sm-4">
                                                <select id="jenis_kelamin" name="jenis_kelamin"
                                                    class="form-control form-control-sm">
                                                    <option value="laki-laki"
                                                        {{ $profile->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>
                                                        Laki - Laki
                                                    </option>
                                                    <option value="perempuan"
                                                        {{ $profile->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>
                                                        Perempuan
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-save me-1"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> {{-- end row --}}
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section('script')
    <script src="{{ url('/') }}/lte/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script>
        $(function () {
            $(".datepicker").datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true,
            });
        });
    </script>
@stop
