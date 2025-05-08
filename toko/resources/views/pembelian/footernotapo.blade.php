<div class="row">
    <div class="col-4">
        <div class="form-row ml-1 pb-1">
            <label for="keterangan" class="col-md-12">Keterangan:</label>
            <textarea class="col-md-10 form-control form-control-sm" name="keterangan" id="keterangan" cols="25" rows="3"
                readonly>{{ $popbhd->keterangan }}</textarea>
        </div>
    </div>
    <!-- Right Section -->
    <div class="col-4">
        <div class="form-row justify-content-end">
            <label for="jenisppn" class="col-5 text-right">Jenis PPN:</label>
            <div class="col-7">
                {{ $popbhd->jenisppn }}
            </div>
        </div>
        <div class="form-row justify-content-end">
            <label for="ppn" class="col-5 text-right">PPN:</label>
            <div class="col-3">
                ({{ $popbhd->persenpajak }}%)
            </div>
            <div class="col-4 text-right">{{ rupiah0($popbhd->ppn + 0) }}</div>
        </div>
        <div class="form-row justify-content-end">
            <label for="dpp" class="col-5 text-right">DPP:</label>
            <div class="text-right col-7">
                {{ Rupiah0($popbhd->dpp + 0) }}
            </div>
        </div>
        <div class="form-row justify-content-end">
            <label for="potongan" class="col-5 text-right">Potongan:</label>
            <div class="text-right col-7">
                {{ Rupiah0($popbhd->potongan + 0) }}
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="form-row justify-content-end">
            <label for="total" class="col-5 text-right">Sub Total:</label>
            <div class="text-right col-7">
                {{ Rupiah0($popbhd->total + 0) }}
            </div>
        </div>
        @php
            $totalakhir = $popbhd->dpp + $popbhd->ppn - $popbhd->potongan;
            $kekurangan = $totalakhir - $popbhd->dp;
        @endphp
        <div class="form-row justify-content-end">
            <label for="totalakhir" class="col-5 text-right">Total akhir:</label>
            <div class="text-right col-7">
                {{ Rupiah0($totalakhir + 0) }}
            </div>
        </div>
        <div class="form-row justify-content-end">
            <label for="dp" class="col-5 text-right">Titip (dp):</label>
            <div class="col-4">
                ({{ $popbhd->kodekas->nama_acc }})
            </div>
            <div class="text-right col-3">
                {{ Rupiah0($popbhd->dp + 0) }}
            </div>
        </div>
        <div class="form-row justify-content-end">
            <label for="kekurangan" class="col-5 text-right">Kekurangan:</label>
            <div class="text-right col-7">
                {{ Rupiah0($kekurangan + 0) }}
            </div>
        </div>
    </div>
</div>
