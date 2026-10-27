<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

include "../config/databases.php";

// Proses hapus reservasi
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["hapus_id"])) {
    $id = (int) $_POST["hapus_id"];

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM reservasi WHERE id_reservasi = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: admin_reservasi.php");
    exit;
}

// Proses ubah status reservasi
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ubah_status"])) {
    $id = (int) $_POST["id_reservasi"];
    $status = $_POST["status"] ?? "";

    $status_valid = ["Menunggu", "Dikonfirmasi", "Selesai", "Dibatalkan"];

    if (in_array($status, $status_valid, true)) {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE reservasi SET status = ? WHERE id_reservasi = ?"
        );

        mysqli_stmt_bind_param($stmt, "si", $status, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: admin_reservasi.php");
    exit;
}

// Pencarian berdasarkan nama atau nomor WhatsApp
$pencarian = trim($_GET["cari"] ?? "");

if ($pencarian !== "") {
    $kata_cari = "%" . $pencarian . "%";

    $stmt = mysqli_prepare(
        $conn,
        "SELECT reservasi.*, layanan.nama_layanan
         FROM reservasi
         LEFT JOIN layanan
         ON reservasi.id_layanan = layanan.id_layanan
         WHERE reservasi.nama_pelanggan LIKE ?
            OR reservasi.no_whatsapp LIKE ?
         ORDER BY reservasi.id_reservasi DESC"
    );

    mysqli_stmt_bind_param($stmt, "ss", $kata_cari, $kata_cari);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
} else {
    $query = mysqli_query($conn, "
        SELECT reservasi.*, layanan.nama_layanan
        FROM reservasi
        LEFT JOIN layanan
        ON reservasi.id_layanan = layanan.id_layanan
        ORDER BY reservasi.id_reservasi DESC
    ");
}

if (!$query) {
    die("Gagal mengambil data reservasi: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Data Reservasi - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        .tabel-reservasi {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .tabel-reservasi th,
        .tabel-reservasi td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .tabel-reservasi th {
            background: #f8d7e5;
        }

        .form-pencarian {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .form-pencarian input {
            padding: 10px;
            width: 280px;
            max-width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .tombol-cari {
            padding: 10px 16px;
            background: #a83267;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .tombol-edit {
            display: inline-block;
            background: #198754;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 5px;
        }

        .tombol-hapus {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .tombol-simpan {
            background: #0d6efd;
            color: white;
            border: none;
            padding: 8px 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 5px;
        }

        .pilihan-status {
            padding: 7px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .tombol-bawah {
            display: inline-block;
            padding: 10px 15px;
            margin: 5px;
            color: white;
            background: #a83267;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Halaman Admin Data Reservasi</p>
</header>

<main>

    <h2 style="text-align:center;">Data Reservasi Pelanggan</h2>

    <div style="text-align:right; margin:20px 0;">
        <a href="../logout.php"
           class="tombol-bawah"
           style="background:#dc3545;">
            Logout
        </a>
    </div>

    <!-- Form pencarian -->
    <form method="GET" class="form-pencarian">
        <input
            type="text"
            name="cari"
            placeholder="Cari nama atau nomor WhatsApp..."
            value="<?= htmlspecialchars($pencarian) ?>"
        >

        <button type="submit" class="tombol-cari">
            Cari
        </button>

        <a href="admin_reservasi.php"
           class="tombol-bawah"
           style="margin:0;">
            Reset
        </a>
    </form>

    <p style="text-align:center;">
        <?php if ($pencarian !== ""): ?>
            Hasil pencarian untuk:
            <strong><?= htmlspecialchars($pencarian) ?></strong>
        <?php endif; ?>
    </p>

    <div style="overflow-x:auto;">
        <table class="tabel-reservasi">
            <thead>
                <tr>
                    <th>ID Reservasi</th>
                    <th>Nama Pelanggan</th>
                    <th>No WhatsApp</th>
                    <th>Tanggal Reservasi</th>
                    <th>Pesan</th>
                    <th>Status</th>
                    <th>Layanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

            <?php if (mysqli_num_rows($query) > 0): ?>

                <?php while ($row = mysqli_fetch_assoc($query)): ?>

                    <tr>
                        <td>
                            <?= (int) $row["id_reservasi"] ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row["nama_pelanggan"] ?? "") ?>
                        </td>

                       <td>
    <?php
    $nomor_wa = preg_replace(
        '/\D/',
        '',
        $row["no_whatsapp"] ?? ""
    );

    if (substr($nomor_wa, 0, 1) === "0") {
        $nomor_wa = "62" . substr($nomor_wa, 1);
    }
    ?>

    <?= htmlspecialchars($row["no_whatsapp"] ?? "") ?>

    <?php if ($nomor_wa !== ""): ?>
        <br><br>

        <?php
$nama_pelanggan = $row["nama_pelanggan"] ?? "";
$nama_layanan = $row["nama_layanan"] ?? "Belum dipilih";
$tanggal = $row["tanggal_reservasi"] ?? "";
$status = $row["status"] ?? "Menunggu";

$pesan_wa = "Halo, Kak " . $nama_pelanggan . "!\n\n";
$pesan_wa .= "Kami dari Salon Nadira Beauty ingin memberikan informasi reservasi Kakak.\n\n";
$pesan_wa .= "Layanan: " . $nama_layanan . "\n";
$pesan_wa .= "Tanggal Reservasi: " . $tanggal . "\n";
$pesan_wa .= "Status Reservasi: " . $status . "\n\n";
$pesan_wa .= "Terima kasih telah memilih Salon Nadira Beauty. 🌸";
?>

<?php if ($nomor_wa !== ""): ?>
    <br><br>

    <a
        href="https://wa.me/<?= htmlspecialchars($nomor_wa) ?>?text=<?= rawurlencode($pesan_wa) ?>"
        target="_blank"
        rel="noopener noreferrer"
        style="
            display:inline-block;
            background:#25D366;
            color:white;
            padding:7px 10px;
            border-radius:5px;
            text-decoration:none;
        "
    >
        Kirim Pesan WhatsApp
    </a>
<?php endif; ?>
    <?php endif; ?>
</td>

                        <td>
                            <?= htmlspecialchars($row["tanggal_reservasi"] ?? "") ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row["pesan"] ?? "") ?>
                        </td>

                        <td>
                            <form method="POST">
                                <input
                                    type="hidden"
                                    name="id_reservasi"
                                    value="<?= (int) $row["id_reservasi"] ?>"
                                >

                                <select name="status" class="pilihan-status">

                                    <?php
                                    $daftar_status = [
                                        "Menunggu",
                                        "Dikonfirmasi",
                                        "Selesai",
                                        "Dibatalkan"
                                    ];

                                    $status_sekarang = $row["status"] ?? "Menunggu";
                                    ?>

                                    <?php foreach ($daftar_status as $pilihan): ?>

                                        <option
                                            value="<?= htmlspecialchars($pilihan) ?>"
                                            <?= $status_sekarang === $pilihan ? "selected" : "" ?>
                                        >
                                            <?= htmlspecialchars($pilihan) ?>
                                        </option>

                                    <?php endforeach; ?>

                                </select>

                                <br>

                                <button
                                    type="submit"
                                    name="ubah_status"
                                    value="1"
                                    class="tombol-simpan"
                                >
                                    Simpan
                                </button>
                            </form>
                        </td>

                        <td>
                            <?= htmlspecialchars($row["nama_layanan"] ?? "Layanan tidak ditemukan") ?>
                        </td>

                        <td>
                            <a
                                class="tombol-edit"
                                href="edit_reservasi.php?id=<?= (int) $row["id_reservasi"] ?>"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus reservasi ini?');"
                            >
                                <input
                                    type="hidden"
                                    name="hapus_id"
                                    value="<?= (int) $row["id_reservasi"] ?>"
                                >

                                <button
                                    type="submit"
                                    class="tombol-hapus"
                                >
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>
                    <td colspan="8" style="padding:20px;">
                        Data reservasi tidak ditemukan.
                    </td>
                </tr>

            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <div style="text-align:center; margin:25px 0;">

        <a href="laporan_reservasi.php" class="tombol-bawah">
            Laporan Reservasi
        </a>

        <a href="../index.php" class="tombol-bawah">
            Kembali ke Beranda
        </a>

    </div>

</main>

</body>
</html>