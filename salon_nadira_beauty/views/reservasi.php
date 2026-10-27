<?php
include __DIR__ . "/../config/databases.php";

// Mengambil daftar layanan dan harga dari database
$query_layanan = mysqli_query(
    $conn,
    "SELECT id_layanan, nama_layanan, harga
     FROM layanan
     ORDER BY nama_layanan ASC"
);

if (!$query_layanan) {
    die("Gagal mengambil data layanan: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservasi - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        main {
            max-width: 650px;
            margin: 30px auto;
            padding: 25px;
            background: #fff;
            border-radius: 15px;
            box-sizing: border-box;
        }

        main h2,
        main > p {
            text-align: center;
        }

        form label {
            display: inline-block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        form input,
        form select,
        form textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d8a0b5;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 15px;
            margin-bottom: 18px;
        }

        form textarea {
            resize: vertical;
        }

        .bagian-tengah {
            text-align: center;
            margin: 25px 10px;
        }

        .tombol-reservasi {
            display: inline-block;
            float: none;
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Reservasi Perawatan Kecantikan</p>
</header>

<nav>
    <a href="../index.php">Beranda</a>
    <a href="tentang.php">Tentang Kami</a>
    <a href="layanan.php">Layanan</a>
    <a href="galeri.php">Galeri</a>
    <a href="kontak.php">Kontak</a>
    <a href="reservasi.php">Reservasi</a>
</nav>

<div class="bagian-tengah">
    <a href="../index.php" class="tombol tombol-reservasi">
        Kembali ke Beranda
    </a>
</div>

<div class="bagian-tengah">
    <h3>Butuh Bantuan Reservasi?</h3>

    <p>Hubungi kami melalui WhatsApp untuk informasi lebih lanjut.</p>

    <a
        href="https://wa.me/6282393561936"
        class="tombol tombol-reservasi"
        target="_blank"
        rel="noopener noreferrer"
    >
        Hubungi Kami via WhatsApp
    </a>
</div>

<main>
    <h2>Formulir Reservasi</h2>

    <p>Silakan isi formulir untuk melakukan pemesanan perawatan.</p>

    <form action="proses_reservasi.php" method="POST">

        <label for="nama_pelanggan">Nama Lengkap</label>

        <input
            type="text"
            id="nama_pelanggan"
            name="nama_pelanggan"
            placeholder="Masukkan nama lengkap"
            required
        >

        <label for="no_whatsapp">Nomor WhatsApp</label>

        <input
            type="tel"
            id="no_whatsapp"
            name="no_whatsapp"
            placeholder="Masukkan nomor WhatsApp"
            required
        >

        <label for="id_layanan">Pilih Layanan</label>

        <select id="id_layanan" name="id_layanan" required>
            <option value="">-- Pilih Layanan --</option>

            <?php while ($layanan = mysqli_fetch_assoc($query_layanan)): ?>

                <option value="<?= (int)$layanan['id_layanan'] ?>">
                    <?= htmlspecialchars($layanan['nama_layanan']) ?>
                    - Rp<?= number_format((float)$layanan['harga'], 0, ',', '.') ?>
                </option>

            <?php endwhile; ?>
        </select>

        <label for="tanggal_reservasi">Tanggal Reservasi</label>

        <input
            type="date"
            id="tanggal_reservasi"
            name="tanggal_reservasi"
            min="<?= date('Y-m-d') ?>"
            required
        >

        <label for="pesan">Pesan Tambahan</label>

        <textarea
            id="pesan"
            name="pesan"
            rows="4"
            placeholder="Tuliskan pesan tambahan jika ada"
        ></textarea>

        <button type="submit" class="tombol tombol-reservasi">
            Kirim Reservasi
        </button>

    </form>
</main>

<footer>
    <p>&copy; 2026 Salon Nadira Beauty</p>
</footer>

</body>
</html>