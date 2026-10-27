<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/databases.php";

// Filter tanggal
$tanggal_awal = $_GET["tanggal_awal"] ?? "";
$tanggal_akhir = $_GET["tanggal_akhir"] ?? "";

$sql = "
    SELECT
        reservasi.id_reservasi,
        reservasi.nama_pelanggan,
        reservasi.no_whatsapp,
        layanan.nama_layanan,
        reservasi.tanggal_reservasi,
        reservasi.pesan,
        reservasi.status
    FROM reservasi
    LEFT JOIN layanan
        ON reservasi.id_layanan = layanan.id_layanan
    WHERE 1=1
";

if ($tanggal_awal !== "") {
    $sql .= " AND reservasi.tanggal_reservasi >= '" .
        mysqli_real_escape_string($conn, $tanggal_awal) . "'";
}

if ($tanggal_akhir !== "") {
    $sql .= " AND reservasi.tanggal_reservasi <= '" .
        mysqli_real_escape_string($conn, $tanggal_akhir) . "'";
}

$sql .= " ORDER BY reservasi.tanggal_reservasi DESC";

$query = mysqli_query($conn, $sql);

if (!$query) {
    die("Gagal mengambil laporan: " . mysqli_error($conn));
}

$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Reservasi - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        .wadah-laporan {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
        }

        .filter {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
            margin-bottom: 25px;
        }

        .filter label {
            display: block;
            margin-bottom: 5px;
        }

        .filter input {
            padding: 9px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .tabel-laporan {
            width: 100%;
            border-collapse: collapse;
        }

        .tabel-laporan th,
        .tabel-laporan td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .tabel-laporan th {
            background: #f8d7e5;
        }

        .tombol-laporan {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background: #a83267;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        @media print {
            .filter,
            .tombol-laporan,
            header .navigasi {
                display: none !important;
            }

            body,
            .wadah-laporan {
                background: white;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Laporan Reservasi Pelanggan</p>
</header>

<main class="wadah-laporan">

    <h2 style="text-align:center;">Laporan Data Reservasi</h2>

    <form method="GET" class="filter">
        <div>
            <label for="tanggal_awal">Dari tanggal</label>
            <input
                type="date"
                id="tanggal_awal"
                name="tanggal_awal"
                value="<?= htmlspecialchars($tanggal_awal) ?>"
            >
        </div>

        <div>
            <label for="tanggal_akhir">Sampai tanggal</label>
            <input
                type="date"
                id="tanggal_akhir"
                name="tanggal_akhir"
                value="<?= htmlspecialchars($tanggal_akhir) ?>"
            >
        </div>

        <button type="submit" class="tombol-laporan">
            Cari Laporan
        </button>

        <a href="laporan_reservasi.php" class="tombol-laporan">
            Reset
        </a>

        <button
            type="button"
            onclick="window.print()"
            class="tombol-laporan"
        >
            Cetak Laporan
        </button>
    </form>

    <p>
        <strong>Jumlah reservasi:</strong>
        <?= $total ?> data
    </p>

    <div style="overflow-x:auto;">
        <table class="tabel-laporan">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelanggan</th>
                    <th>No. WhatsApp</th>
                    <th>Layanan</th>
                    <th>Tanggal</th>
                    <th>Pesan</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($total > 0): ?>
                    <?php $nomor = 1; ?>

                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td><?= $nomor++ ?></td>

                            <td>
                                <?= htmlspecialchars($row["nama_pelanggan"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["no_whatsapp"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["nama_layanan"] ?? "Tidak tersedia") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["tanggal_reservasi"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["pesan"] ?? "") ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row["status"] ?? "") ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            Belum ada data reservasi pada tanggal tersebut.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div style="text-align:center; margin-top:25px;">
        <a href="admin_reservasi.php" class="tombol-laporan">
            Kembali ke Admin Reservasi
        </a>
    </div>

</main>

</body>
</html>