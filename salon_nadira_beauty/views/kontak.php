<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kontak - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        .kontak-container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
        }

        .kontak-container > h2 {
            color: #a94f70;
            text-align: center;
            margin-bottom: 15px;
        }

        .deskripsi-kontak {
            text-align: center;
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .info-kontak {
            background-color: #fff0f5;
            padding: 25px;
            margin: 20px 0;
            border: 1px solid #f0c5d5;
            border-radius: 12px;
            text-align: center;
        }

        .info-kontak h3 {
            color: #a94f70;
            margin-bottom: 15px;
        }

        .info-kontak p {
            line-height: 1.8;
            margin-bottom: 20px;
        }

        .info-kontak .tombol {
            display: inline-block;
            margin-top: 5px;
        }

        .kembali {
            text-align: center;
            margin: 30px 0;
        }

        @media (max-width: 600px) {
            .kontak-container {
                padding: 12px;
            }

            .info-kontak {
                padding: 20px 12px;
            }
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <header>
        <h1>Salon Nadira Beauty</h1>
        <p>Tempat Perawatan Kecantikan Anda</p>
    </header>

    <!-- MENU NAVIGASI -->
    <nav>
        <a href="../index.php">Beranda</a>
        <a href="tentang.php">Tentang Kami</a>
        <a href="layanan.php">Layanan</a>
        <a href="galeri.php">Galeri</a>
        <a href="kontak.php">Kontak</a>
    </nav>

    <!-- KONTEN KONTAK -->
    <main class="kontak-container">

        <h2>Hubungi Kami</h2>

        <p class="deskripsi-kontak">
            Silakan hubungi Salon Nadira Beauty untuk mendapatkan
            informasi mengenai layanan, harga, dan reservasi.
        </p>

        <!-- LOKASI SALON: HANYA SATU BAGIAN -->
        <section class="info-kontak">

            <h3>Lokasi Salon Nadira Beauty</h3>

            <p>
                X6P3+X53, Jl. K.H. Ahmad Razak,<br>
                Tompotika, Kec. Wara,<br>
                Kota Palopo, Sulawesi Selatan 91922
            </p>

            <a
                href="https://www.google.com/maps/search/?api=1&query=X6P3%2BX53%2C%20Jl.%20K.H.%20Ahmad%20Razak%2C%20Tompotika%2C%20Kota%20Palopo%2C%20Sulawesi%20Selatan%2091922"
                target="_blank"
                rel="noopener noreferrer"
                class="tombol">
                📍 Lihat Lokasi di Google Maps
            </a>

        </section>

        <!-- WHATSAPP: SATU BAGIAN -->
        <section class="info-kontak">

            <h3>WhatsApp</h3>

            <p>
                Hubungi kami melalui WhatsApp untuk informasi lebih
                lanjut mengenai layanan Salon Nadira Beauty.
            </p>

            <a
                href="https://wa.me/6282393561936"
                target="_blank"
                rel="noopener noreferrer"
                class="tombol">
                Hubungi via WhatsApp
            </a>

        </section>

        <!-- KEMBALI KE BERANDA -->
        <div class="kembali">

            <a href="../index.php" class="tombol">
                Kembali ke Beranda
            </a>

        </div>

    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2026 Salon Nadira Beauty</p>
    </footer>

</body>
</html>