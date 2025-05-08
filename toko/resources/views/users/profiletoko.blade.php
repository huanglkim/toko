@extends('layout.main')

@section('content')
    <div class="container pt-5">
        <div class="row justify-content-center mb-3">
            <div class="col-md-10">
                <div class="card shadow-sm">
                    <div class="card-header bg-light mb-4">{{ __('Profile Toko') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card shadow-sm text-center">
                                    <div class="card-body">
                                        @if ($toko->logo)
                                            <img src="{{ asset('toko/public/storage/' . $toko->logo) }}" alt="{{ $toko->nama_toko }}"
                                                class="img-fluid rounded-circle"
                                                style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 150px; height: 150px;">
                                                {{ strtoupper(substr($toko->nama_toko, 0, 2)) }}
                                            </div>
                                        @endif
                                        <h5 class="mt-3">{{ $toko->nama_toko }}</h5>
                                        <p class="text-muted">{{ $toko->alamat ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-light">{{ __('Informasi Pribadi') }}</div>
                                    <div class="card-body">
                                        <dl class="row">
                                            <dt class="col-md-4 text-start mb-3">{{ __('Nama') }}</dt>
                                            <dd class="col-md-8 mb-3">{{ $toko->nama_toko }}</dd>

                                            <dt class="col-md-4 text-start mb-3">{{ __('Alamat') }}</dt>
                                            <dd class="col-md-8 mb-3">{{ $toko->alamat }}</dd>

                                            <dt class="col-md-4 text-start mb-3">{{ __('Kota') }}</dt>
                                            <dd class="col-md-8 mb-3">{{ $toko->kota ?? '-' }}</dd>

                                            <dt class="col-md-4 text-start mb-3">{{ __('NPWP') }}</dt>
                                            <dd class="col-md-8 mb-3">{{ $toko->npwp ?? '-' }}</dd>

                                            @if ($toko->telp)
                                                <dt class="col-md-4 text-start mb-3">{{ __('Nomor Hp') }}</dt>
                                                <dd class="col-md-8 mb-3">{{ $toko->telp }}</dd>
                                            @endif

                                            @if ($toko->wa)
                                                <dt class="col-md-4 text-start">{{ __('Nomor WA') }}</dt>
                                                <dd class="col-md-8">{{ $toko->wa }}</dd>
                                            @endif
                                        </dl>
                                        <div class="mt-3">
                                            <a href="{{ route('toko.edit', $toko->id) }}"
                                                class="btn btn-primary">{{ __('Edit Profil Toko') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
