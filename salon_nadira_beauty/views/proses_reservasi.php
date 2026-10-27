<?php
include __DIR__ . "/../config/databases.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: reservasi.php");
    exit;
}

// Mengambil data formulir
$nama = trim($_POST["nama_pelanggan"] ?? "");
$wa = trim($_POST["no_whatsapp"] ?? "");
$layanan = filter_var(
    $_POST["id_layanan"] ?? "",
    FILTER_VALIDATE_INT
);
$tanggal = $_POST["tanggal_reservasi"] ?? "";
$pesan = trim($_POST["pesan"] ?? "");

// Memeriksa data wajib
if ($nama === "" || $wa === "" || !$layanan || $layanan < 1) {
    die("Data reservasi belum lengkap. Silakan kembali dan periksa formulir.");
}

// Memeriksa format dan tanggal reservasi
$format_tanggal = DateTime::createFromFormat("!Y-m-d", $tanggal);

if (
    !$format_tanggal ||
    $format_tanggal->format("Y-m-d") !== $tanggal ||
    $tanggal < date("Y-m-d")
) {
    die('Tanggal reservasi tidak valid atau sudah lewat. 
    <a href="reservasi.php">Kembali ke formulir reservasi</a>');
}

// Memastikan layanan tersedia di database
$stmt_layanan = mysqli_prepare(
    $conn,
    "SELECT id_layanan FROM layanan WHERE id_layanan = ?"
);

mysqli_stmt_bind_param($stmt_layanan, "i", $layanan);
mysqli_stmt_execute($stmt_layanan);
mysqli_stmt_store_result($stmt_layanan);

if (mysqli_stmt_num_rows($stmt_layanan) === 0) {
    mysqli_stmt_close($stmt_layanan);
    die('Layanan tidak tersedia. 
    <a href="reservasi.php">Silakan pilih layanan lain</a>');
}

mysqli_stmt_close($stmt_layanan);

// Menyimpan reservasi
$sql = "INSERT INTO reservasi
        (nama_pelanggan, no_whatsapp, id_layanan, tanggal_reservasi, pesan)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($conn));
}

mysqli_stmt_bind_param(
    $stmt,
    "ssiss",
    $nama,
    $wa,
    $layanan,
    $tanggal,
    $pesan
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    ?>

    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reservasi Berhasil</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>

        <header>
            <h1>Salon Nadira Beauty</h1>
            <p>Reservasi Perawatan Kecantikan</p>
        </header>

        <main>
            <h2>Reservasi Berhasil Dikirim!</h2>

            <p>
                Terima kasih telah melakukan reservasi
                di Salon Nadira Beauty.
            </p>

            <a href="reservasi.php" class="tombol">
                Kembali ke Reservasi
            </a>

            <a href="../index.php" class="tombol">
                Kembali ke Beranda
            </a>
        </main>

    </body>
    </html>

    <?php
} else {
    echo "Reservasi gagal: " . htmlspecialchars(mysqli_stmt_error($stmt));
    mysqli_stmt_close($stmt);
}
?>