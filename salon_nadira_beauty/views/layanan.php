<?php
include __DIR__ . "/../config/databases.php";

$query_layanan = mysqli_query(
    $conn,
    "SELECT id_layanan, nama_layanan, deskripsi, harga
     FROM layanan
     ORDER BY id_layanan ASC"
);

if (!$query_layanan) {
    die("Gagal mengambil data: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan - Salon Nadira Beauty</title>
    <link rel="stylesheet" href="../style.css">

    <style>
        .daftar-layanan {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .layanan-item {
            width: 250px;
            padding: 20px;
            background: #fff0f5;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 3px 8px rgba(0,0,0,0.12);
            box-sizing: border-box;
        }

        .layanan-item h3 {
            color: #a13d69;
        }

        .harga {
            color: #a13d69;
            font-weight: bold;
            font-size: 18px;
        }

        .tombol-reservasi {
            display: inline-block;
            float: none;
            margin: 20px auto;
        }

        .bagian-reservasi {
            text-align: center;
            margin: 25px 10px;
        }

        main > h2 {
            text-align: center;
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Layanan Perawatan Kecantikan</p>
</header>

<nav>
    <a href="../index.php">Beranda</a>
    <a href="tentang.php">Tentang Kami</a>
    <a href="layanan.php">Layanan</a>
    <a href="galeri.php">Galeri</a>
    <a href="kontak.php">Kontak</a>
</nav>

<div class="bagian-reservasi">
    <h3>Ingin Melakukan Perawatan?</h3>
    <p>Pilih layanan sesuai kebutuhanmu.</p>
    <a href="reservasi.php" class="tombol tombol-reservasi">
        Reservasi Sekarang
    </a>
</div>

<main>
    <h2>Layanan dan Harga</h2>

    <div class="daftar-layanan">

        <?php if (mysqli_num_rows($query_layanan) > 0): ?>

            <?php while ($layanan = mysqli_fetch_assoc($query_layanan)): ?>

                <div class="layanan-item">
                    <h3>
                        <?= htmlspecialchars($layanan['nama_layanan']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($layanan['deskripsi'] ?? '') ?>
                    </p>

                    <p class="harga">
                        Rp<?= number_format((float)$layanan['harga'], 0, ',', '.') ?>
                    </p>
                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>Belum ada layanan yang tersedia.</p>

        <?php endif; ?>

    </div>

    <div class="bagian-reservasi">
        <h3>Sudah Menentukan Pilihan?</h3>
        <p>Silakan lakukan reservasi melalui website kami.</p>
        <a href="reservasi.php" class="tombol tombol-reservasi">
            Buat Reservasi
        </a>
    </div>
</main>

<footer>
    <p>&copy; 2026 Salon Nadira Beauty</p>
</footer>

</body>
</html>