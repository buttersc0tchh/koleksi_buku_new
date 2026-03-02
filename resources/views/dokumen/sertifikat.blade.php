<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body { font-family: 'Georgia', serif; background-color: #fff; margin: 0; padding: 0; }
        .wrapper {
            padding: 30px;
            height: 92%;
        }
        .outer-border {
            border: 15px solid #7a2d81; 
            height: 100%;
            padding: 10px;
        }
        .inner-border {
            border: 2px solid #7a2d81;
            height: 100%;
            text-align: center;
            padding: 40px;
            background-image: radial-gradient(circle, #ffffff, #fcfaff);
        }
        .cert-title { font-size: 65px; color: #7a2d81; font-weight: bold; margin-top: 20px; }
        .cert-no { letter-spacing: 4px; font-size: 14px; margin-bottom: 21x; }
        .recipient { font-size: 20px; color: #555; }
        .name { 
            font-size: 50px; 
            font-weight: bold; 
            color: #333; 
            margin: 20px 0;
            border-bottom: 2px solid #7a2d81;
            display: inline-block;
            padding: 0 40px;
        }
        .event { font-size: 18px; line-height: 1.6; width: 85%; margin: 0 auto; color: #444; }
        .footer { margin-top: 60px; width: 100%; }
        .footer td { width: 33%; vertical-align: bottom; font-size: 14px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="outer-border">
            <div class="inner-border">
                <div class="cert-title">SERTIFIKAT 2</div>
                <div class="cert-no">NOMOR: 3353/B/UN3.FV/2025</div> 

                <p class="recipient">Diberikan Kepada:</p> 
                <div class="name">Fina Aida</div> 

                <p class="event">
                    Atas Partisipasinya Sebagai <strong>{{ $peran }}</strong>  <br>
                    Dalam acara: <i>{{ $acara }}</i> <br>
                    diselenggarakan oleh Program Studi Kedokteran Gigi Universitas Indonesia pada {{ $tanggal }}. 

                <table class="footer">
                    <tr>
                        <td>
                            Dekan Fakultas Kedokteran Gigi Universitas Indonesia<br><br><br><br>
                            <strong>Prof. Dian Yulie R, Ph.D</strong> 
                        </td>
                        <td></td>
                        <td>
                            Ketua Pelaksana<br><br><br><br>
                            <strong>( Fina Aida )</strong> 
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>