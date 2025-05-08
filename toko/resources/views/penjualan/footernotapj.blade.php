<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label for="keterangan" class="col-lg-4 col-form-label">Keterangan:</label>
            <div class="col-lg-8">
                <textarea class="form-control" name="keterangan" id="keterangan" rows="3" readonly>{{ $pjhd->keterangan }}</textarea>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label for="total" class="col-lg-4 col-form-label text-right">Sub Total:</label>
            <div class="col-lg-8 text-right">
                {{ Rupiah0($pjhd->total + 0) }}
            </div>
        </div>
        @php
            $totalakhir = $pjhd->dpp + $pjhd->ppn - $pjhd->potongan;
            $kekurangan = $totalakhir - $pjhd->dp;
        @endphp
        <div class="form-group row">
            <label for="totalakhir" class="col-lg-4 col-form-label text-right">Total Akhir:</label>
            <div class="col-lg-8 text-right">
                {{ Rupiah0($totalakhir + 0) }}
            </div>
        </div>
    </div>
</div>
