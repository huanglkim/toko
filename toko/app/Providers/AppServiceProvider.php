<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\BaranginObserver;
use App\Observers\AccjurnalkhususObserver;
use App\Models\Accjurnalkhusus;
use App\Models\Barangin;
use App\Models\Toko;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Barangin::observe(BaranginObserver::class);
        Accjurnalkhusus::observe(AccjurnalkhususObserver::class);

        $toko = Toko::first(); // Ambil data toko
        if ($toko) {
            $headerData = [
                'logo' => $toko->logo,
                'nama_toko' => $toko->nama_toko,
                'alamat' => $toko->alamat,
                'kota' => $toko->kota,
                'telp' => $toko->telp,
                'wa' => $toko->wa,
            ];
        } else {
            // Handle jika data toko tidak ditemukan
              $headerData = [
                'logo' => 'images/default_logo.png',
                'nama_toko' => 'Nama Toko Default',
                'alamat' => 'Alamat Default',
                'kota' => 'Kota Default',
                'telp' => 'Telepon Default',
                'wa' => 'WhatsApp Default',
            ];
        }
        View::share('headerData', $headerData); // Bagikan ke semua view
    
    }
}
