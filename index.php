<?php
// index.php (Login Page)
session_start();
include 'include/conn.php';

// Jika sudah login, langsung lempar ke dashboard
if(isset($_SESSION['login_user'])){
    header("location: devel/dash.php");
    exit;
}

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $input_password = $_POST['password']; // Ambil password input (plain text)

    // 1. Ambil data user dari database berdasarkan username
    $sql = "SELECT username, password FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. Cek apakah user ditemukan DAN verifikasi hash
    if($user && password_verify($input_password, $user['password'])) {
        // Login Berhasil
        $_SESSION['login_user'] = $username;
        header("location: devel/dash.php");
        exit;
    } else {
        // Login Gagal
        $message = "ACCESS DENIED: Invalid Credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ManiAcc - Login</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="https://img.freepik.com/premium-photo/neon-green-padlock-with-digital-binary-code-cybersecurity-concept-black-background_824086-2521.jpg">
    <style>
        body { 
            /* 1. Gambar */
            background-image: url('https://i.makeagif.com/media/10-24-2017/fiozwR.gif');
            
            /* 2. Properti Kunci untuk Full Page & No Repeat */
            background-repeat: no-repeat;
            background-attachment: fixed; /* Penting: Memastikan gambar tetap saat scroll */
            background-size: cover; /* KUNCI: Memastikan gambar mencakup seluruh viewport */
            background-position: center center; /* Memastikan gambar berada di tengah */

            /* 3. Properti Layout (Jika diperlukan untuk menempatkan konten login di tengah) */
            display: flex;
            justify-content: center; /* Memusatkan konten secara horizontal */
            align-items: center; /* Memusatkan konten secara vertikal */
            min-height: 100vh; /* Memastikan body setinggi viewport */
            margin: 0; /* Menghapus margin default browser */
        }

        /* Tambahkan style untuk form atau login-wrapper Anda agar terlihat di atas background */
        .login-wrapper {
            z-index: 10; /* Pastikan konten login berada di lapisan atas */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>[ MANIACC ]</h1>
        <p>MANAGING OF INFORMATION ACCOUNT</p>
        <hr style="border-color: #004400;">
        
        <?php if($message): ?>
            <p style="color: red; border: 1px solid red; padding: 5px;">WARNING: <?= $message ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <label style="float:left;">> USERNAME_</label>
            <input type="text" name="username" required autofocus autocomplete="off">
            
            <br><br>
            
            <label style="float:left;">> PASSWORD_</label>
            <input type="password" name="password" required>
            
            <br><br>
            <button type="submit">INITIATE LOGIN</button>
        </form>
    </div>
</body>
</html>