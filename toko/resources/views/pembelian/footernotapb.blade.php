<div class="row">
    <div class="col-4">
        <div class="form-row ml-1 pb-1">
            <label for="keterangan" class="col-md-12">Keterangan:</label>
            <textarea class="col-md-10 form-control form-control-sm" name="keterangan" id="keterangan" cols="25" rows="3"
                readonly>{{ $pbhd->keterangan }}</textarea>
        </div>
    </div>
    <!-- Right Section -->
    <div class="col-4">
        <div class="form-row justify-content-end">
            <label for="jenisppn" class="col-5 text-right">Jenis PPN:</label>
            <div class="col-7">
                {{ $pbhd->jenisppn }}
            </div>
        </div>
        <div class="form-row justify-content-end">
            <label for="ppn" class="col-5 text-right">PPN:</label>
            <div class="col-3">
                ({{ $pbhd->persenpajak }}%)
            </div>
            <div class="col-4 text-right">{{ rupiah0($pbhd->ppn + 0) }}</div>
        </div>
        <div class="form-row justify-content-end">
            <label for="dpp" class="col-5 text-right">DPP:</label>
            <div class="text-right col-7">
                {{ Rupiah0($pbhd->dpp + 0) }}
            </div>
        </div>
        <div class="form-row justify-content-end">
            <label for="potongan" class="col-5 text-right">Potongan:</label>
            <div class="text-right col-7">
                {{ Rupiah0($pbhd->potongan + 0) }}
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="form-row justify-content-end">
            <label for="total" class="col-5 text-right">Sub Total:</label>
            <div class="text-right col-7">
                {{ Rupiah0($pbhd->total + 0) }}
            </div>
        </div>
        @php
            $totalakhir = $pbhd->dpp + $pbhd->ppn - $pbhd->potongan;
            $kekurangan = $totalakhir - $pbhd->dp;
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
                ({{ $pbhd->kodekas->nama_acc }})
            </div>
            <div class="text-right col-3">
                {{ Rupiah0($pbhd->dp + 0) }}
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
