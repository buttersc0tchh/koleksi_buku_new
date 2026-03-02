<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Login</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px;">
    <div style="max-width: 500px; margin: auto; background: #fff; padding: 20px; border-radius: 8px;">
        <h2>Halo, {{ $user->name }} 👋</h2>

        <p>Kode OTP kamu untuk login adalah:</p>

        <h1 style="letter-spacing: 5px; text-align: center;">
            {{ $otp }}
        </h1>

        <p>Kode ini hanya berlaku satu kali.</p>
        <p>Kalau kamu tidak merasa login, abaikan email ini ya.</p>

        <hr>
        <small>© {{ date('Y') }} Aplikasi Koleksi Buku</small>
    </div>
</body>
</html>
