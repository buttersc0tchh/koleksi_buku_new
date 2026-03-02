<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verifikasi OTP - Purple Admin</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left p-5 text-center">
                            <div class="brand-logo">
                                <img src="{{ asset('assets/images/logo.svg') }}">
                            </div>
                            <h4>Verifikasi Kode OTP</h4>
                            <h6 class="font-weight-light">Masukkan 6 digit kode yang kami kirimkan.</h6>

                            @if ($errors->any())
                                <div class="alert alert-danger py-2 mt-3" style="font-size: 0.8rem;">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success py-2 mt-3" style="font-size: 0.8rem;">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form class="pt-3" method="POST" action="{{ route('verify.otp.submit') }}">
                                @csrf
                                <div class="form-group">
                                    <input type="text" 
                                           name="otp" 
                                           class="form-control form-control-lg text-center font-weight-bold" 
                                           placeholder="000000" 
                                           maxlength="6" 
                                           required 
                                           style="letter-spacing: 8px; font-size: 1.5rem;">
                                </div>
                                
                                <div class="mt-3 d-grid gap-2">
                                    <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">
                                        VERIFIKASI
                                    </button>
                                </div>
                            </form>

                            <div class="text-center mt-4 font-weight-light">
                                Tidak menerima kode? 
                                <form action="{{ route('verify.otp.resend') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 text-primary font-weight-bold" style="text-decoration: none;">
                                        Kirim Ulang
                                    </button>
                                </form>
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-muted small">Kembali ke Login</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>

    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('assets/js/misc.js') }}"></script>
    <script src="{{ asset('assets/js/settings.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
</body>
</html>