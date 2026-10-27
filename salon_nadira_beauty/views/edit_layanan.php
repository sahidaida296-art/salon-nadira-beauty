<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

include __DIR__ . "/../config/databases.php";

$pesan = "";
$id = (int) ($_GET["id"] ?? 0);

// Ambil data layanan
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM layanan WHERE id_layanan = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$layanan = mysqli_fetch_assoc($hasil);

mysqli_stmt_close($stmt);

if (!$layanan) {
    die("Layanan tidak ditemukan.");
}

// Proses menyimpan perubahan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama_layanan"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $harga = (int) ($_POST["harga"] ?? 0);

    if ($nama != "" && $deskripsi != "" && $harga > 0) {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE layanan
             SET nama_layanan = ?, deskripsi = ?, harga = ?
             WHERE id_layanan = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ssii",
            $nama,
            $deskripsi,
            $harga,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            header("Location: admin_layanan.php");
            exit;
        } else {
            $pesan = "Gagal memperbarui layanan.";
            mysqli_stmt_close($stmt);
        }

    } else {
        $pesan = "Nama, deskripsi, dan harga harus diisi dengan benar.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Layanan - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        main {
            max-width: 600px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border-radius: 15px;
            box-sizing: border-box;
        }

        main h2 {
            text-align: center;
        }

        form label {
            display: block;
            margin-top: 15px;
            margin-bottom: 7px;
            font-weight: bold;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #d8a0b5;
            border-radius: 7px;
            box-sizing: border-box;
        }

        form textarea {
            min-height: 100px;
        }

        .pesan {
            color: #c2185b;
            text-align: center;
        }

        .tombol-aksi {
            display: inline-block;
            margin-top: 20px;
            margin-right: 8px;
            float: none;
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Edit Layanan Salon</p>
</header>

<main>

    <h2>Ubah Data Layanan</h2>

    <?php if ($pesan != ""): ?>
        <p class="pesan">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label for="nama_layanan">Nama Layanan</label>

        <input
            type="text"
            id="nama_layanan"
            name="nama_layanan"
            value="<?= htmlspecialchars($layanan["nama_layanan"]) ?>"
            required
        >

        <label for="deskripsi">Deskripsi Layanan</label>

        <textarea
            id="deskripsi"
            name="deskripsi"
            required
        ><?= htmlspecialchars($layanan["deskripsi"] ?? "") ?></textarea>

        <label for="harga">Harga (Rp)</label>

        <input
            type="number"
            id="harga"
            name="harga"
            min="1"
            step="1"
            value="<?= (int)($layanan["harga"] ?? 0) ?>"
            required
        >

        <button
            type="submit"
            class="tombol tombol-aksi"
        >
            Simpan Perubahan
        </button>

        <a
            href="admin_layanan.php"
            class="tombol tombol-aksi"
        >
            Batal
        </a>

    </form>

</main>

</body>
</html>