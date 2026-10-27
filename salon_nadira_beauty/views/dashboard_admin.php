<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/databases.php";

// Menghitung jumlah reservasi
$query_reservasi = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reservasi"
);

$total_reservasi = mysqli_fetch_assoc($query_reservasi)["total"] ?? 0;

// Menghitung jumlah layanan
$query_layanan = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM layanan"
);

$total_layanan = mysqli_fetch_assoc($query_layanan)["total"] ?? 0;

// Menghitung reservasi yang menunggu
$query_menunggu = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reservasi WHERE status = 'Menunggu'"
);

$total_menunggu = mysqli_fetch_assoc($query_menunggu)["total"] ?? 0;

// Menghitung reservasi yang dikonfirmasi
$query_konfirmasi = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM reservasi WHERE status = 'Dikonfirmasi'"
);

$total_konfirmasi = mysqli_fetch_assoc($query_konfirmasi)["total"] ?? 0;

// Mengambil lima reservasi terbaru
$query_terbaru = mysqli_query(
    $conn,
    "SELECT reservasi.nama_pelanggan,
            reservasi.tanggal_reservasi,
            reservasi.status,
            layanan.nama_layanan
     FROM reservasi
     LEFT JOIN layanan
     ON reservasi.id_layanan = layanan.id_layanan
     ORDER BY reservasi.id_reservasi DESC
     LIMIT 5"
);

if (!$query_reservasi || !$query_layanan || !$query_menunggu ||
    !$query_konfirmasi || !$query_terbaru) {
    die("Gagal mengambil data dashboard: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        body {
            background: #fff5f8;
        }

        .dashboard {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }

        .kartu-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 25px 0;
        }

        .kartu {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-top: 5px solid #d96c9d;
        }

        .kartu h3 {
            margin-top: 0;
            color: #7a3153;
        }

        .angka {
            font-size: 35px;
            font-weight: bold;
            color: #c0447b;
        }

        .menu-admin {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 25px 0;
        }

        .menu-admin a {
            display: inline-block;
            background: #a83267;
            color: white;
            padding: 12px 18px;
            text-decoration: none;
            border-radius: 7px;
        }

        .menu-admin a:hover {
            background: #842650;
        }

        .tabel-dashboard {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .tabel-dashboard th,
        .tabel-dashboard td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .tabel-dashboard th {
            background: #f8d7e5;
        }

        .bagian-terbaru {
            overflow-x: auto;
            margin-top: 25px;
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Dashboard Admin</p>
</header>

<main class="dashboard">

    <h2>Selamat Datang di Dashboard Admin</h2>

    <p>
        Kelola reservasi pelanggan dan layanan Salon Nadira Beauty
        melalui halaman ini.
    </p>

    <div class="kartu-container">

        <div class="kartu">
            <h3>Total Reservasi</h3>
            <div class="angka">
                <?= (int) $total_reservasi ?>
            </div>
        </div>

        <div class="kartu">
            <h3>Total Layanan</h3>
            <div class="angka">
                <?= (int) $total_layanan ?>
            </div>
        </div>

        <div class="kartu">
            <h3>Menunggu</h3>
            <div class="angka">
                <?= (int) $total_menunggu ?>
            </div>
        </div>

        <div class="kartu">
            <h3>Dikonfirmasi</h3>
            <div class="angka">
                <?= (int) $total_konfirmasi ?>
            </div>
        </div>

    </div>

    <h2>Menu Pengelolaan</h2>

    <div class="menu-admin">
        <a href="admin_reservasi.php">Data Reservasi</a>

        <a href="admin_layanan.php">Data Layanan</a>

        <a href="laporan_reservasi.php">Laporan Reservasi</a>

        <a href="../index.php">Kembali ke Beranda</a>

        <a href="../logout.php"
           onclick="return confirm('Yakin ingin logout?');">
            Logout
        </a>
    </div>

    <div class="bagian-terbaru">
        <h2>Lima Reservasi Terbaru</h2>

        <table class="tabel-dashboard">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelanggan</th>
                    <th>Layanan</th>
                    <th>Tanggal Reservasi</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($query_terbaru) > 0): ?>

                    <?php $nomor = 1; ?>

                    <?php while ($row = mysqli_fetch_assoc($query_terbaru)): ?>
                        <tr>
                            <td><?= $nomor++ ?></td>

                            <td>
                                <?= htmlspecialchars($row["nama_pelanggan"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["nama_layanan"] ?? "Tidak tersedia") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["tanggal_reservasi"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["status"] ?? "") ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="5" style="text-align:center;">
                            Belum ada reservasi.
                        </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>
    </div