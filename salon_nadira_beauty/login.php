<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if ($username == "admin" && $password == "admin123") {
        $_SESSION["admin"] = $username;
        header("Location: /salon_nadira_beauty/views/dashboard_admin.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Salon Nadira Beauty</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <h2 style="text-align:center;">Login Admin</h2>

        <form method="POST" style="
    width: 350px;
    margin: 25px auto;
    padding: 25px;
    box-sizing: border-box;
    background: white;
    border-radius: 10px;
">

    <label style="display:block; margin-bottom:8px;">
        Username
    </label>

    <input type="text" name="username" required
    style="
        width:100%;
        padding:12px;
        margin-bottom:20px;
        box-sizing:border-box;
        border:1px solid #ccc;
        border-radius:6px;
    ">

    <label style="display:block; margin-bottom:8px;">
        Password
    </label>

    <input type="password" name="password" required
    style="
        width:100%;
        padding:12px;
        margin-bottom:25px;
        box-sizing:border-box;
        border:1px solid #ccc;
        border-radius:6px;
    ">

    <button type="submit" class="tombol"
    style="width:100%; padding:12px; margin:0;">
        Login
    </button>

    <p style="color:red; text-align:center;">
        <?= htmlspecialchars($error) ?>
    </p>

</form>

        <div style="text-align:center;">
            <a href="index.php" class="tombol">Kembali ke Beranda</a>
        </div>
    </main>
</body>
</html>