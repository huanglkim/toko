<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>LOGIN WU</title>
    <link rel="stylesheet" href="{{ url('/') }}/lte/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="{{ url('') }}/lte/css/login.css">
</head>

<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" action="{{ url('/postlogin') }}" method="POST">
                    @csrf
                    <div class="logo-container">
                        <img src="{{ url('') }}/images/logo.jpg" alt="Logo Perusahaan" class="logo">
                    </div>
                    @if ($errors->any())
                        {!! implode('', $errors->all('<div class="input100">:message</div>')) !!}
                    @endif
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" placeholder="Username" id="username" name="username"
                            autofocus>
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" class="login__input" placeholder="Password" id="password"
                            name="password">
                        <span id="toggle-password">
                            <i class="fas fa-eye" aria-hidden="true" type="button" id="eye"></i>
                        </span>
                    </div>
                    <button class="button login__submit">
                        <span class="button__text">Log In Now</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </form>
                <div class="social-login">
                    <h3></h3>
                    <div class="social-icons">
                        {{-- <a href="#" class="social-login__icon fab fa-instagram"></a>
                        <a href="#" class="social-login__icon fab fa-facebook"></a>
                        <a href="#" class="social-login__icon fab fa-twitter"></a> --}}
                    </div>
                </div>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
    <!-- partial -->

</body>
<script>
    // script.js

    // Mendapatkan elemen tombol dan input password
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');

    // Menambahkan event listener pada tombol
    togglePassword.addEventListener('click', function() {
        // Memeriksa tipe input password
        const type = passwordInput.type === 'password' ? 'text' : 'password';

        // Mengubah tipe input password
        passwordInput.type = type;

        // Mengganti ikon eye sesuai dengan kondisi tipe input
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye" aria-hidden="true"  type="button"></i>' :
            '<i class="fas fa-eye-slash" aria-hidden="true"  type="button"></i>';
    });
</script>

</html>
