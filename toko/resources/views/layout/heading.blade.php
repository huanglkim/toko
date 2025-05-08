<style>
    .kop-surat {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .logo {
        width: 100px;
        margin-right: 20px;
    }
    .detail-toko {
        flex: 1;
    }
    .nama-toko {
        font-size: 1.5em;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .alamat-toko {
        font-size: 0.9em;
        margin-bottom: 5px;
    }
    .kontak-toko {
        font-size: 0.9em;
    }
    </style>
    
    <div class="kop-surat">
        <img src="{{ asset('toko/public/storage/' . $headerData['logo']) }}" alt="Logo Toko" class="logo">
        <div class="detail-toko">
            <div class="nama-toko">{{ $headerData['nama_toko'] }}</div>
            <div class="alamat-toko">{{ $headerData['alamat'] }}, {{ $headerData['kota'] }}</div>
            <div class="kontak-toko">
                Nomor Telpon: {{ $headerData['telp'] }} || Nomor WhatsApp: {{ $headerData['wa'] }}
            </div>
        </div>
    </div>
    
    <hr>