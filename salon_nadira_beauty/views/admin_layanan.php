<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit;
}

include __DIR__ . "/../config/databases.php";

$pesan = "";

// PROSES HAPUS LAYANAN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["hapus_layanan"])) {
    $id = (int) ($_POST["hapus_id"] ?? 0);

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM layanan WHERE id_layanan = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        $pesan = "Layanan berhasil dihapus.";
    } else {
        $pesan = "Layanan gagal dihapus. Mungkin masih digunakan dalam reservasi.";
    }

    mysqli_stmt_close($stmt);
}

// PROSES TAMBAH LAYANAN
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tambah_layanan"])) {
    $nama = trim($_POST["nama_layanan"] ?? "");
    $deskripsi = trim($_POST["deskripsi"] ?? "");
    $harga = (int) ($_POST["harga"] ?? 0);

    if ($nama != "" && $deskripsi != "" && $harga > 0) {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO layanan (nama_layanan, deskripsi, harga)
             VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "ssi", $nama, $deskripsi, $harga);

        if (mysqli_stmt_execute($stmt)) {
            $pesan = "Layanan berhasil ditambahkan!";
        } else {
            $pesan = "Layanan gagal ditambahkan: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    } else {
        $pesan = "Nama, deskripsi, dan harga harus diisi dengan benar.";
    }
}

// AMBIL DAFTAR LAYANAN
$query = mysqli_query(
    $conn,
    "SELECT id_layanan, nama_layanan, deskripsi, harga
     FROM layanan
     ORDER BY id_layanan ASC"
);

if (!$query) {
    die("Gagal mengambil data layanan: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Layanan - Salon Nadira Beauty</title>

    <link rel="stylesheet" href="../style.css">

    <style>
        .form-layanan {
            max-width: 600px;
            margin: 20px auto;
        }

        .form-layanan input,
        .form-layanan textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px;
            border: 1px solid #d8a0b5;
            border-radius: 7px;
            box-sizing: border-box;
        }

        .pesan {
            text-align: center;
            padding: 12px;
            color: #a13d69;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f8d7e5;
            color: #8b2450;
        }

        .aksi {
            white-space: nowrap;
            text-align: center;
        }

        .tombol-edit {
            display: inline-block;
            padding: 7px 14px;
            background: #c2185b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .tombol-hapus {
            padding: 7px 14px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<header>
    <h1>Salon Nadira Beauty</h1>
    <p>Kelola Layanan Salon</p>
</header>

<main>

    <h2>Tambah Layanan Baru</h2>

    <?php if ($pesan != ""): ?>
        <p class="pesan">
            <?= htmlspecialchars($pesan) ?>
        </p>
    <?php endif; ?>

    <form method="POST" class="form-layanan">

        <label for="nama_layanan">Nama Layanan</label>

        <input
            type="text"
            id="nama_layanan"
            name="nama_layanan"
            placeholder="Contoh: Cuci Rambut"
            required
        >

        <label for="deskripsi">Deskripsi Layanan</label>

        <textarea
            id="deskripsi"
            name="deskripsi"
            rows="4"
            placeholder="Masukkan deskripsi layanan"
            required
        ></textarea>

        <label for="harga">Harga (Rp)</label>

        <input
            type="number"
            id="harga"
            name="harga"
            min="1"
            step="1"
            placeholder="Contoh: 15000"
            required
        >

        <button type="submit" name="tambah_layanan" class="tombol">
            Tambah Layanan
        </button>

    </form>

    <h2>Daftar Layanan</h2>

    <div style="overflow-x:auto;">

        <table>
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($row = mysqli_fetch_assoc($query)): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($row["nama_layanan"]) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($row["deskripsi"] ?? "") ?>
                        </td>

                        <td>
                            Rp<?= number_format((float)$row["harga"], 0, ",", ".") ?>
                        </td>

                        <td class="aksi">

                            <a
                                href="edit_layanan.php?id=<?= (int)$row["id_layanan"] ?>"
                                class="tombol-edit"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                style="display:inline-block; margin:0;"
                                onsubmit="return confirm('Yakin ingin menghapus layanan ini?')"
                            >
                                <input
                                    type="hidden"
                                    name="hapus_id"
                                    value="<?= (int)$row["id_layanan"] ?>"
                                >

                                <button
                                    type="submit"
                                    name="hapus_layanan"
                                    class="tombol-hapus"
                                >
                                    Hapus
                                </button>
                            </form>

                        </td>
                    </tr>

                <?php endwhile; ?>

            </tbody>
        </table>

    </div>

    <p style="text-align:center; margin-top:20px;">
        <a href="admin_reservasi.php" class="tombol">
            Kembali ke Data Reservasi
        </a>
    </p>

</main>

</body>
</html>