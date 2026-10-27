<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Galeri - Salon Nadira Beauty</title>
    <link rel="stylesheet" href="../style.css">

    <style>
        .galeri {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            padding: 25px 15px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .galeri .galeri-item {
            width: 180px !important;
            max-width: 180px !important;
            flex: 0 0 180px;
            box-sizing: border-box;
            padding: 10px;
            background: #fff0f5;
            border-radius: 12px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .galeri .galeri-item img {
            display: block;
            width: 100% !important;
            max-width: 100% !important;
            height: 130px !important;
            object-fit: contain !important;
            border-radius: 8px;
            margin: 0 auto;
        }

        .galeri .galeri-item p {
            color: #8b3a62;
            font-weight: bold;
            font-size: 13px;
            margin: 10px 0 0;
        }

        main h2,
        main > p {
            text-align: center;
        }

        @media (max-width: 450px) {
            .galeri {
                gap: 12px;
                padding: 15px 8px;
            }

            .galeri .galeri-item {
                width: 150px !important;
                max-width: 150px !important;
                flex-basis: 150px;
            }

            .galeri .galeri-item img {
                height: 110px !important;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Galeri Dokumentasi</p>
</header>

<nav>
    <a href="../index.php">Beranda</a>
    <a href="tentang.php">Tentang Kami</a>
    <a href="layanan.php">Layanan</a>
    <a href="galeri.php">Galeri</a>
    <a href="kontak.php">Kontak</a>
</nav>

<main>
    <h2>Galeri Salon</h2>

    <p>
        Dokumentasi kegiatan dan suasana Salon Nadira Beauty.
    </p>

    <div class="galeri">

        <div class="galeri-item">
            <img src="../image/dokumen%201.jpeg"
                 alt="Dokumentasi Wawancara 1">
            <p>Dokumentasi Wawancara 1</p>
        </div>

        <div class="galeri-item">
            <img src="../image/dokumen%202.jpeg"
                 alt="Dokumentasi Wawancara 2">
            <p>Dokumentasi Wawancara 2</p>
        </div>

        <div class="galeri-item">
            <img src="../image/gambar%20salon.jpeg"
                 alt="Salon Nadira Beauty">
            <p>Salon Nadira Beauty</p>
        </div>

    </div>
</main>

<footer>
    <p>&copy; 2026 Salon Nadira Beauty</p>
</footer>

</body>
</html>