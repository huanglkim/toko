<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'AuthController@login')->name('login');
Route::get('/loginrfid', 'AuthController@loginrfid');
Route::get('/loginrfid', 'AuthController@loginrfid')->name('loginrfid');
Route::post('/postlogin', 'AuthController@postlogin')->name('postlogin');
Route::post('/postloginrfid', 'AuthController@postloginrfid')->name('postloginrfid');
Route::get('/logout', 'AuthController@logout');
Route::get('/testhtml', function () {
    return view('welcome');
});

route::group(['middleware' => ['auth', 'role']], function () {
    //akuntansi laporan
    Route::get('/lapjurnalposting', 'LapAkuntansiController@lapjurnalposting');
    Route::POST('/lpcetakjurnal', 'LapAkuntansiController@lpcetakjurnal');

    //dashboard
    Route::get('/dashadmin', 'DashController@dashadmin');
    Route::POST('detailmenudashboard', 'DashController@detailmenudashboard');
    //menus
    Route::resource('/menu', 'MenuController');
    Route::POST('/tambaheditmenu', 'MenuController@tambaheditmenu');
    Route::post('/menufav', 'MenuController@menufav');
    //role menu
    Route::get('/rolemenu', 'MenuController@RoleMenu');
    Route::get('/tabelmenurole/{id}', 'MenuController@TabelMenuRole');
    Route::POST('/tambahmenu', 'MenuController@TambahMenu');
    Route::DELETE('/hapusmenu/{id}', 'MenuController@HapusMenu');
    //akses jabatan
    Route::get('/roleakses', 'AksesroleController@RoleAkses');
    Route::get('/tabelaksesrole/{id}', 'AksesroleController@TabelAksesRole');
    Route::POST('/tambahakses', 'AksesroleController@TambahAkses');
    Route::DELETE('/hapusakses/{id}', 'AksesroleController@HapusAkses');
    //user profile
    Route::resource('/userprofile', 'UserprofileController');
    Route::POST('/gantifotoprofile', 'UserprofileController@gantifotoprofile');
    Route::POST('/updateprofile/{id}', 'UserprofileController@updateprofile');
    Route::get('/ttduser', 'UserprofileController@ttduser');
    Route::POST('/simpanttduser', 'UserprofileController@simpanttduser');
    Route::get('/editpass', 'UserprofileController@editpass')->name('epass');
    Route::post('/simpaneditpass', 'UserprofileController@simpaneditpass');
    //data user
    Route::resource('/datauser', 'UsersController');
    Route::POST('/datausertabel', 'UsersController@datausertabel');
    //pelanggan
    Route::resource('/pelanggan', 'PelangganController');
    Route::get('/tabelpelanggan/{group}/{status}/{deleted}', 'PelangganController@TabelPelanggan');
    Route::PATCH('/restorepelanggan/{id}', 'PelangganController@Restore');
    Route::post('/importpelanggan', 'PelangganController@importpelanggan');
    Route::get('/pilihpelanggan/{id}', 'PelangganController@Pilih');
    Route::POST('/margepelanggan', 'PelangganController@margepelanggan');
    Route::get('/repairwapelanggan', 'PelangganController@repairwapelanggan');
    Route::get('/repairwapelanggantest/{id}', 'PelangganController@repairwapelanggantest');
    Route::post('/fastupdatepel/{id}', 'PelangganController@fastupdatepel');
    Route::POST('/caripelanggan', 'PelangganController@caripelanggan');
    Route::get('/pelanggan/export', 'PelangganController@show');

    //suplier
    Route::resource('/suplier', 'SuplierController');
    Route::get('/tabelsuplier/{group}/{status}/{deleted}', 'SuplierController@TabelSuplier');
    Route::PATCH('/restoresuplier/{id}', 'SuplierController@Restore');
    Route::POST('/carisuplier', 'SuplierController@carisuplier');
    //gudang
    Route::resource('/gudang', 'GudangController');
    Route::get('/tabelgudang/{deleted}', 'GudangController@TabelGudang');
    Route::PATCH('/restoregudang/{id}', 'GudangController@Restore');
    //satuan
    Route::resource('/satuan', 'SatuanController');
    Route::get('/tabelsatuan/{deleted}', 'SatuanController@TabelSatuan');
    Route::PATCH('/restoresatuan/{id}', 'SatuanController@Restore');
    //jenis barang
    Route::resource('/jenisbarang', 'JenisbarangController');
    Route::get('/tabeljenisbarang/{deleted}', 'JenisbarangController@TabelJenisbarang');
    Route::PATCH('/restorejenisbarang/{id}', 'JenisbarangController@Restore');
    //merk barang
    Route::resource('/merkbarang', 'MerkbarangController');
    Route::get('/tabelmerkbarang/{deleted}', 'MerkbarangController@TabelMerkbarang');
    Route::PATCH('/restoremerkbarang/{id}', 'MerkbarangController@Restore');
    //globalconfig
    Route::resource('/globalconfig', 'GlobalconfigController');
    Route::get('/tabelglobalconfig/{deleted}', 'GlobalconfigController@TabelGlobalconfig');
    Route::PATCH('/restoreglobalconfig/{id}', 'GlobalconfigController@Restore');

    //Barang
    Route::resource('/barang', 'BarangController');
    Route::POST('/tabelbarang', 'BarangController@TabelBarang');
    Route::PATCH('/restorebarang/{id}', 'BarangController@Restore');
    Route::GET('/setupharga', 'BarangController@setupharga');
    Route::GET('/cekharga', 'BarangController@cekharga');
    Route::GET('/stokminimum', 'BarangController@stokminimum');
    Route::POST('/gantihargamasal', 'BarangController@gantihargamasal');
    Route::post('/importbarang', 'BarangController@importbarang');
    Route::post('/updatehargajual', 'BarangController@updatehargajual');
    Route::post('/fastupdatebarang', 'BarangController@fastupdatebarang');
    Route::post('/barangeditharga/{id}', 'BarangController@editharga');
    Route::post('/nonaktifallcek', 'BarangController@nonaktifallcek');
    Route::GET('/opnamebarang', 'BarangController@opnamebarang');
    Route::GET('/caritabelbarang', 'BarangController@caritabelbarang');
    Route::POST('/caribarang', 'BarangController@caribarang');
    Route::POST('/caribarcode', 'BarangController@caribarcode');

    //cetak barcode
    Route::resource('/cetakbarcode', 'CetakbarcodeController');
    Route::get('/tabelcetakbarcode', 'CetakbarcodeController@tabelcetakbarcode');
    Route::get('/clearcetakbarcode', 'CetakbarcodeController@clearcetakbarcode');
    Route::get('/cetakdaripb/{id}', 'CetakbarcodeController@cetakdaripb');
    Route::get('/cetakbar1/{break}', 'CetakbarcodeController@cetakbar1');
    Route::get('/cetakbarharga/{break}', 'CetakbarcodeController@cetakbarharga');
    Route::POST('/ceklistcetakharga', 'CetakbarcodeController@ceklistcetakharga');

    //akutansi kas masuk kas keluar
    Route::get('/kaskeluar', 'JurnalkhususController@KasKeluar');
    Route::get('/kasmasuk', 'JurnalkhususController@KasMasuk');
    Route::POST('/tabeljurnalkhusus', 'JurnalkhususController@tabeljurnalkhusus');
    //jurnal kas
    Route::POST('/tambahjurnalkas', 'JurnalkhususController@TambahJurnalKas');
    Route::patch('/updatejurnalkas/{id}', 'JurnalkhususController@UpdateJurnalKas');
    Route::get('/editjurnalkas/{id}', 'JurnalkhususController@EditJurnalKas');
    Route::DELETE('/hapusjurnalkas/{id}', 'JurnalkhususController@HapusJurnalKas');
    //biaya2
    Route::get('/biaya', 'JurnalkhususController@Biaya');
    Route::get('/piutangkaryawan', 'JurnalkhususController@PiutangKaryawan');
    Route::get('/jurnalupahkerja', 'JurnalkhususController@jurnalupahkerja');
    Route::get('/jurnalmemorial', 'JurnalkhususController@jurnalmemorial');

    //po pembelian
    Route::get('/popb', 'PopbController@popb');
    Route::POST('/tabelpopb', 'PopbController@tabelpopb');
    Route::get('/listpendingpo', 'PopbController@listpendingpo');
    Route::POST('/tambahpobaru', 'PopbController@tambahpobaru');
    Route::get('/editpo/{id}', 'PopbController@editpo');
    //cartpo
    Route::POST('/cartpopb/{id}', 'PopbController@cartpopb');
    Route::POST('/footerpopb/{id}', 'PopbController@footerpopb');
    Route::POST('/tambahcartpopb', 'PopbController@tambahcartpopb');
    Route::get('/getcartpopb/{id}', 'PopbController@getcartpopb');
    Route::PATCH('/updatecartpopb/{id}', 'PopbController@updatecartpopb');
    Route::DELETE('/hapuscartpopb/{id}', 'PopbController@hapuscartpopb');
    Route::POST('/updatepajakcartpo', 'PopbController@updatepajakcartpo');
    Route::POST('/fastupdatepopbhd', 'PopbController@fastupdatepopbhd');
    Route::POST('/simpanpopb', 'PopbController@simpanpopb');
    Route::DELETE('/hapuspopb/{uuid}', 'PopbController@hapuspopb');
    Route::get('/cetaknotapo/{uuid}', 'PopbController@cetaknotapo');

    //pembelian
    Route::get('/pembelian', 'PembelianController@pembelian');
    Route::POST('/tabelpb', 'PembelianController@tabelpb');
    Route::get('/listpendingpb', 'PembelianController@listpendingpb');
    Route::POST('/tambahpbbaru', 'PembelianController@tambahpbbaru');
    Route::get('/editpb/{id}', 'PembelianController@editpb');
    Route::POST('/fastupdatepbhd', 'PembelianController@fastupdatepbhd');
    Route::POST('/simpanpb', 'PembelianController@simpanpb');
    Route::DELETE('/hapuspb/{uuid}', 'PembelianController@hapuspb');
    Route::get('/cetaknotapb/{uuid}', 'PembelianController@cetaknotapb');
    Route::POST('/importpopbtopb', 'PembelianController@importpopbtopb');

    //cart pembelian
    Route::POST('/cartpb/{id}', 'PembelianController@cartpb');
    Route::POST('/footerpb/{id}', 'PembelianController@footerpb');
    Route::POST('/tambahcartpb', 'PembelianController@tambahcartpb');
    Route::get('/getcartpb/{id}', 'PembelianController@getcartpb');
    Route::PATCH('/updatecartpb/{id}', 'PembelianController@updatecartpb');
    Route::DELETE('/hapuscartpb/{id}', 'PembelianController@hapuscartpb');
    Route::POST('/updatepajakcartpb', 'PembelianController@updatepajakcartpb');

    //Penjualan kredit
    Route::get('/penjualan', 'PenjualanController@penjualan');
    Route::POST('/tabelpj', 'PenjualanController@tabelpj');
    Route::get('/listpendingpj', 'PenjualanController@listpendingpj');
    Route::POST('/tambahpjbaru', 'PenjualanController@tambahpjbaru');
    Route::get('/editpj/{id}', 'PenjualanController@editpj');
    Route::POST('/fastupdatepjhd', 'PenjualanController@fastupdatepjhd');
    Route::POST('/simpanpj', 'PenjualanController@simpanpj');
    Route::DELETE('/hapuspj/{uuid}', 'PenjualanController@hapuspj');
    Route::get('/cetaknotapj/{uuid}', 'PenjualanController@cetaknotapj');
    //Route::POST('/importpopbtopb', 'PenjualanController@importpopbtopb');

    //cart penjualan
    Route::POST('/cartpj/{id}', 'PenjualanController@cartpj');
    Route::POST('/footerpj/{id}', 'PenjualanController@footerpj');
    Route::POST('/tambahcartpj', 'PenjualanController@tambahcartpj');
    Route::get('/getcartpj/{id}', 'PenjualanController@getcartpj');
    Route::PATCH('/updatecartpj/{id}', 'PenjualanController@updatecartpj');
    Route::DELETE('/hapuscartpj/{id}', 'PenjualanController@hapuscartpj');
    Route::POST('/updatepajakcartpj', 'PenjualanController@updatepajakcartpj');
    Route::POST('/updateqtycartpj/{id}', 'PenjualanController@updateqtycartpj');

    //Penjualan kasir
    Route::get('/kasir', 'KasirController@kasir');
    Route::POST('/tambahksrbaru', 'KasirController@tambahksrbaru');
    Route::POST('/footerksr/{id}', 'KasirController@footerksr');
    // Route::POST('/tabelpj', 'KasirController@tabelpj');
    // Route::get('/listpendingpj', 'KasirController@listpendingpj');
    // Route::get('/editpj/{id}', 'KasirController@editpj');
    // Route::POST('/fastupdatepjhd', 'KasirController@fastupdatepjhd');
    // Route::POST('/simpanpj', 'KasirController@simpanpj');
    // Route::DELETE('/hapuspj/{uuid}', 'KasirController@hapuspj');
    // Route::get('/cetaknotapj/{uuid}', 'KasirController@cetaknotapj');

    //Penjualan kasir MPL
    Route::get('/kasirmpl', 'KasirMplController@kasir');
    Route::POST('/tambahksrmplbaru', 'KasirMplController@tambahksrbaru');
    Route::POST('/footerksrmpl/{id}', 'KasirMplController@footerksr');
    Route::POST('/hitungbadminmpl', 'KasirMplController@hitungbadminmpl');

    //laporan pembelian
    Route::get('/laporanpembelian', 'LaporanpembelianController@index');
    Route::post('/laporan-pembelian/print', 'LaporanpembelianController@print');

    //laporan hutang
    Route::get('/laporanhutang', 'LaporanhutangController@index');
    Route::post('/laporan-hutang/print', 'LaporanhutangController@print');

    //laporan penjualan
    Route::get('/laporanpenjualan', 'LaporanpenjualanController@index');
    Route::post('/laporan-penjualan/print', 'LaporanpenjualanController@print');

    //laporan hutang
    Route::get('/laporanpiutang', 'LaporanpiutangController@index');
    Route::post('/laporan-piutang/print', 'LaporanpiutangController@print');

    //laporanchart tahunan
    Route::get('/laporanchart', 'LaporanchartController@index');
    Route::post('/laporanchart', 'LaporanchartController@filterPendapatan');

    //laporanchart bulanan
    Route::get('/laporanchartbulan', 'LaporanchartharianController@index');
    Route::post('/laporanchartbulan', 'LaporanchartharianController@filterPendapatan');

    //halaman laporan fav pro
    Route::get('/laporanfavpro', 'LaporanfavproController@index');

    //halaman laporan
    Route::get('/laporan', 'LaporanController@index');

    //halaman laporan penjurnalan
    Route::get('/laporanpenjurnalan', 'LaporanPenjurnalanController@index');
    Route::post('/laporan-penjurnalan/print', 'LaporanPenjurnalanController@print');

    Route::get('/neracasaldo', 'NeracaSaldoController@index');
    Route::post('/neraca-saldo/print', 'NeracaSaldoController@printBulanan');
    Route::post('/neraca-saldo-tahunan/print', 'NeracaSaldoController@printTahunan');
    Route::post('/neraca-saldo/exportBulan', 'NeracaSaldoController@exportToExcelNeraca');
    Route::post('/neraca-saldo-tahunan/exportTahun', 'NeracaSaldoController@exportToExcelTahun');

    Route::get('/neraca', 'NeracaController@index');
    Route::get('/neraca/pendapatan', 'NeracaController@getPendapatan');
    Route::get('/neraca/kewajiban', 'NeracaController@getKewajiban');
    Route::post('/neraca/print', 'NeracaController@printNeraca');
    Route::post('/neraca/exportNeraca', 'NeracaController@exportToExcelNeraca');

    Route::get('/buku-besar', 'BukuBesarController@index');
    Route::post('/bukubesar/print', 'BukuBesarController@printBuku');

    Route::get('/laba', 'LabaKotorBersihController@index');
    Route::post('/laba/print', 'LabaKotorBersihController@printLaba');
    Route::post('/laba/exportLaba', 'LabaKotorBersihController@exportToExcelLaba');
    Route::post('/laba/printTahun', 'LabaKotorBersihController@printTahunLaba');
    Route::post('/laba/exportTahunLaba', 'LabaKotorBersihController@exportToExcelTahunLaba');

    Route::get('/saldoawal', 'SaldoawalperkiraanController@index')->name('saldoawal.index');
    Route::post('/saldoawal/accsa', 'SaldoawalperkiraanController@saldoaccsa')->name('saldoaccsa');

    Route::get('profiletoko', 'ProfileTokoController@show')->name('profiletoko.show');
    Route::get('toko/{id}/edit', 'ProfileTokoController@edit')->name('toko.edit');
    Route::put('profiletoko/update', 'ProfileTokoController@update')->name('profiletoko.update');
});
