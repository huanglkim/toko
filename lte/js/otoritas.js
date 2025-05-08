$('#otoritasModal').on('shown.bs.modal', function() {
    $('#username').focus()
});
var password = document.getElementById("password");
password.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        loginakses();
    }
});

function otoritas(input) {
    var akses_id = input.getAttribute("data-akses_id");
    var input = input.id;
    if (document.getElementById(input).readOnly == true || document.getElementById(input).disabled == true) {
        var _token = $('#loginoto').data("token");
        $('#otoritasModal form')[0].reset();
        $('#otoritasModal').modal('show');
        $('.modal-title').text('LOGIN HAK AKSES');
        document.getElementById('inputan').value = input;
        document.getElementById('akses_id').value = akses_id;
    }
}

function loginakses() {
    var data = $('#form-otoritas').serialize();
    var url = "{{ Route('loginakses')}}";
    $.ajax({
        url: url,
        method: "POST",
        data: data,
        success: function(data) {
            if (data == 0) {
                document.getElementById('username').value = '';
                document.getElementById('username').focus();
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Username Tidak Ditemukan',
                    showConfirmButton: false,
                    timer: 1200
                })
            } else {
                if (data == 1) {
                    document.getElementById('password').value = '';
                    document.getElementById('password').focus();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Password Tidak Ditemukan',
                        showConfirmButton: false,
                        timer: 1200
                    })
                } else {
                    if (data == 2) {
                        document.getElementById('username').value = '';
                        document.getElementById('username').focus();
                        document.getElementById('password').value = '';
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'User Tidak Memiliki Akses',
                            showConfirmButton: false,
                            timer: 1200
                        })
                    } else {
                        console.log(data);

                        $('#otoritasModal').modal('hide');
                        Swal.fire({
                            position: 'top-end',
                            title: 'HAK AKSES BERHASIL',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 800
                        }).then(function() {
                            var inputan = document.getElementById('inputan').value;
                            document.getElementById(inputan).readOnly = false;
                            document.getElementById(inputan).disabled = false;
                            document.getElementById(inputan).focus();
                        });
                    }
                }
            }
        },
        error: function(data) {
            console.log(data);
        }
    });
}