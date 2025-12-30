<?php
// devel/acc_chg.php
session_start();
if(!isset($_SESSION['login_user'])){
    header("location: ../index.php");
    exit;
}

include '../function.php';

$status_msg = "";
$username = $_SESSION['login_user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    if (empty($old_pass) || empty($new_pass) || empty($confirm_pass)) {
        $status_msg = "<div style='border:1px solid red; color:red; padding:10px; margin-bottom:10px;'>
                        > ERROR: Semua kolom sandi harus diisi.
                       </div>";
    } elseif ($new_pass !== $confirm_pass) {
        $status_msg = "<div style='border:1px solid red; color:red; padding:10px; margin-bottom:10px;'>
                        > ERROR: Konfirmasi sandi baru tidak cocok.
                       </div>";
    } else {
        // Panggil fungsi update password
        $result = update_user_password($username, $old_pass, $new_pass);

        if ($result === true) {
            $status_msg = "<div style='border:1px solid #00ff00; padding:10px; margin-bottom:10px;'>
                            > SYSTEM REPORT: PASSWORD SUCCESSFULLY CHANGED. SILAKAN LOGIN KEMBALI.
                           </div>";
            // Setelah berhasil, log out pengguna untuk memaksa login dengan sandi baru
            session_destroy();
            header("refresh:3;url=../index.php"); // Redirect setelah 3 detik
            exit;
        } else {
            $status_msg = "<div style='border:1px solid red; color:red; padding:10px; margin-bottom:10px;'>
                            > AUTH ERROR: " . $result . "
                           </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password - ManiAcc</title>
	<link rel="shortcut icon" href="https://img.freepik.com/premium-photo/neon-green-padlock-with-digital-binary-code-cybersecurity-concept-black-background_824086-2521.jpg">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="wrapper">
        
        <?php include '../include/sidebar.php'; ?>

        <div class="main-content">
            <h1>[ CHANGE ADMIN PASSWORD ]</h1>
            <p>> SECURE YOUR ACCESS: Change your password regularly.</p>
            <hr style="border-color: #004400; margin-bottom: 20px;">
            
            <?= $status_msg; ?>

            <form action="" method="POST" style="max-width: 600px;">
                
                <label>> CURRENT_PASSWORD</label>
                <input type="password" name="old_password" required autocomplete="off" placeholder="Sandi lama...">

                <label>> NEW_PASSWORD</label>
                <input type="password" name="new_password" required autocomplete="off" placeholder="Sandi baru...">
                
                <label>> CONFIRM_NEW_PASSWORD</label>
                <input type="password" name="confirm_password" required autocomplete="off" placeholder="Ulangi sandi baru...">

                <br><br>
                <button type="submit">> EXECUTE CHANGE</button>
            </form>
        </div>
    </div>
</body>
</html>