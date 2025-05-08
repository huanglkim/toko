//penanganan
$('#note_dokter').on('change', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            note_dokter: value,
        };
        updatekunjungan(data);
    }
});
$('#diagnosa_dokter').on('change', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            diagnosa_dokter: value,
        };
        updatekunjungan(data);
    }
});
$('#treatment_dokter').on('change', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            treatment_dokter: value,
        };
        updatekunjungan(data);
    }
});
$('#kutu').on('click', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            kutu: value,
        };
        updatekunjungan(data);
    }
});
$('#kutu1').on('click', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        console.log(value);
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            kutu: value,
        };
        updatekunjungan(data);
    }
});
$('#kutu2').on('click', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        console.log(value);
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            kutu: value,
        };
        updatekunjungan(data);
    }
});
$('#jamur1').on('click', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        console.log(value);
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            jamur: value,
        };
        updatekunjungan(data);
    }
});
$('#jamur2').on('click', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        console.log(value);
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            jamur: value,
        };
        updatekunjungan(data);
    }
});
$('#suhu').on('change', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            suhu: value,
        };
        updatekunjungan(data);
    }
});
$('#bb').on('change', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            bb: value,
        };
        updatekunjungan(data);
    }
});
$('#operasi1').on('click', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        //console.log(value);
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            operasi: value,
        };
        updatekunjungan(data);
    }
});
$('#operasi2').on('click', function (e) {
    var value = $(this).val();
    console.log(value);
    if (reqsent == false) {
        //console.log(value);
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            operasi: value,
        };
        updatekunjungan(data);
        var id = document.getElementById('id').value;
        cetaksuratoperasi(id);
    }
});
$('#rawat_inap1').on('click', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        console.log(value);
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            rawat_inap: value,
        };
        updatekunjungan(data);
    }
});
$('#rawat_inap2').on('click', function (e) {
    if (reqsent == false) {
        reqsent = false;
        var estimasi_biaya = document.getElementById('estimasi_biaya').value;
        var estimasi_biaya1 = document.getElementById('estimasi_biaya1').value;
        if (estimasi_biaya <= 0 || estimasi_biaya <= estimasi_biaya1) {
            Swal.fire({
                position: 'top-end',
                title: 'ISI ETIMASI BIAYA TERLEBIH DAHULU',
                icon: 'error',
                showConfirmButton: false,
                timer: 800
            }).then(function () {
                location.reload();
            });
        } else {
            var value = $(this).val();
            var data = {
                '_token': '{{ csrf_token() }}',
                '_method': 'PATCH',
                rawat_inap: value,
                estimasi_biaya: estimasi_biaya,
            };
            updatekunjungan(data);
            var id = document.getElementById('id').value;
            cetaksuratrawatinap(id);
        }
    }
});
$('#kunjungan_ulang1').on('click', function (e) {
    if (reqsent == false) {
        var value = $(this).val();
        console.log(value);
        var data = {
            '_token': '{{ csrf_token() }}',
            '_method': 'PATCH',
            kunjungan_ulang: value,
        };
        updatekunjungan(data);
    }
});
$('#kunjungan_ulang2').on('click', function (e) {
    if (reqsent == false) {
        reqsent = true;
        if ($(this).is(':checked', true)) {
            reqsent = false;
            $(this).val(1);
            var tgl_cek_up = document.getElementById('tgl_cek_up').value;
            var ket_cek_up = document.getElementById('ket_cek_up').value;
            if (tgl_cek_up == '') {
                Swal.fire({
                    position: 'top-end',
                    title: 'ISI TANGGAL ULANG CEK UP TERLEBIH DAHULU',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 1200
                }).then(function () {
                    location.reload();
                });
            } else {
                var value = $(this).val();
                var data = {
                    '_token': '{{ csrf_token() }}',
                    '_method': 'PATCH',
                    kunjungan_ulang: value,
                    tgl_cek_up: tgl_cek_up,
                    ket_cek_up: ket_cek_up,
                };
                updatekunjungan(data);
            }
        } else {
            reqsent = false;
            $(this).val(0);
            var value = $(this).val();
            var data = {
                '_token': '{{ csrf_token() }}',
                '_method': 'PATCH',
                kunjungan_ulang: value,
                tgl_cek_up: null,
                ket_cek_up: null,
            };
            updatekunjungan(data);
        }
    }
});

function updatekunjungan(data) {
    if (reqsent == false) {
        reqsent = true;

        var id = document.getElementById('id').value;
        var url = "{{ url('kunjunganklinik') }}/" + id;
        $.ajax({
            url: url,
            method: "POST",
            data: data,
            success: function (data) {
                reqsent = false;
                if (data.success == 1) {

                } else {
                    Swal.fire({
                        position: 'top-end',
                        title: data.pesan,
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 1900
                    }).then(function () {
                        location.reload();
                    });
                }
            },
            error: function (data) {
                Swal.fire({
                    position: 'top-end',
                    title: 'DATA SALAH',
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 800
                }).then(function () {
                    console.log(data);
                });
                reqsent = false;
            }
        });
    }
}