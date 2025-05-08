@extends('layout.main')
@section('title', 'User')
@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-solid my-3 col-lg-6">
            <div class="card-body my-2">
                <div class="box-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="post" action="{{ url('/') }}/simpaneditpass">
                        @csrf
                        <div class="form-group">
                            <div class="form-row">
                                <label for="oldpassword" class="col-sm-4 col-form-label">Password Lama</label>
                                <div class="col-sm-8">
                                    <input type="password"
                                        class="form-control form-control-sm @error('oldpassword') is-invalid @enderror"
                                        id="oldpassword" name="oldpassword">
                                    @error('oldpassword')
                                        <div id="errorPassword" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <label for="npassword" class="col-sm-4 col-form-label">Password Baru</label>
                                <div class="col-sm-8">
                                    <input type="password"
                                        class="form-control form-control-sm @error('npassword') is-invalid @enderror"
                                        id="npassword" name="npassword">
                                    @error('npassword')
                                        <div id="errorPassword" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <label for="ncpassword" class="col-sm-4 col-form-label">Konfirm Password Baru</label>
                                <div class="col-sm-8">
                                    <input type="password"
                                        class="form-control form-control-sm @error('ncpassword') is-invalid @enderror"
                                        id="ncpassword" name="ncpassword">
                                    @error('ncpassword')
                                        <div id="errorPassword" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-footer">
                            <button class="btn btn-flat btn-danger" type="submit">Simpan</button>
                            <a href="{{ url('/') }}" class="btn btn-flat btn-primary float-right"
                                type="submit">Kembali</a>
                        </div>
                    </form>

                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>

@stop
