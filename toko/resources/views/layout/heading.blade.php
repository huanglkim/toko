<div class="row mb-3">
    <div class="col-2 text-center">
        <div class="logo">
            <img src="{{ asset('icon/logo_login.jpg') }}" alt="Company Logo" style="max-height: 80px;">
        </div>
    </div>
    <div class="col-10">
        <div class="details">
            <h3>{{ Toko(1)->nama_toko }}</h3>
            <div>{{ Toko(1)->alamat }}</div>
            <div>{{ Toko(1)->wa }}</div>
        </div>
    </div>
</div>
<hr>