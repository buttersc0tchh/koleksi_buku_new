<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Login</title>
</head>
<body style="font-family: Arial, sans-serif; background:#f5f5f5; padding:20px;">
    <div style="max-width:500px; margin:auto; background:white; padding:20px; border-radius:8px;">
        <h2 style="text-align:center;">Kode OTP Login Kamu</h2>

        <p>Halo 👋</p>
        <p>Kamu baru saja mencoba login ke aplikasi <b>Koleksi Buku</b>.</p>
        <p>Gunakan kode OTP berikut untuk melanjutkan proses login:</p>

        <h1 style="text-align:center; letter-spacing:6px;">
            {{ $otp }}
        </h1>

        <p style="color:#666;">
            Kode ini bersifat rahasia dan hanya berlaku sementara.  
            Jangan bagikan ke siapa pun.
        </p>

        <hr>
        <p style="font-size:12px; color:#999;">
            Email ini dikirim otomatis oleh sistem.  
            Jika kamu tidak merasa login, abaikan email ini.
        </p>
    </div>
</body>
</html>
