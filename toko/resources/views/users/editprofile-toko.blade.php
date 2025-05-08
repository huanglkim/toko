@extends('layout.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Profile Toko') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profiletoko.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nama Toko -->
                        <div class="mb-3">
                            <label for="nama_toko" class="form-label">{{ __('Nama') }}</label>
                            <input id="nama_toko" type="text" class="form-control @error('nama_toko') is-invalid @enderror" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" required autocomplete="nama_toko" autofocus>
                            @error('nama_toko')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="mb-3">
                            <label for="alamat" class="form-label">{{ __('Alamat') }}</label>
                            <textarea id="alamat" class="form-control @error('alamat') is-invalid @enderror" name="alamat">{{ old('alamat', $toko->alamat) }}</textarea>
                            @error('alamat')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Kota -->
                        <div class="mb-3">
                            <label for="kota" class="form-label">{{ __('Kota') }}</label>
                            <textarea id="kota" class="form-control @error('kota') is-invalid @enderror" name="kota">{{ old('kota', $toko->kota) }}</textarea>
                            @error('kota')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- NPWP -->
                        <div class="mb-3">
                            <label for="npwp" class="form-label">{{ __('NPWP') }}</label>
                            <textarea id="npwp" class="form-control @error('npwp') is-invalid @enderror" name="npwp">{{ old('npwp', $toko->npwp) }}</textarea>
                            @error('npwp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div class="mb-3">
                            <label for="telp" class="form-label">{{ __('Nomor HP') }}</label>
                            <input id="telp" type="text" class="form-control @error('telp') is-invalid @enderror" name="telp" value="{{ old('telp', $toko->telp) }}">
                            @error('telp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Nomor WA -->
                        <div class="mb-3">
                            <label for="wa" class="form-label">{{ __('Nomor WA') }}</label>
                            <input id="wa" type="text" class="form-control @error('wa') is-invalid @enderror" name="wa" value="{{ old('wa', $toko->wa) }}">
                            @error('wa')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div class="mb-3">
                            <label for="logo" class="form-label">{{ __('Foto Profil') }}</label>
                            <input id="logo" type="file" class="form-control @error('logo') is-invalid @enderror" name="logo">
                            @error('logo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if ($toko->logo)
                                <div class="mt-2">
                                    <img src="{{ asset('toko/public/storage/' . $toko->logo) }}" alt="Current Profile Picture" width="100">
                                </div>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update Profile Toko') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
