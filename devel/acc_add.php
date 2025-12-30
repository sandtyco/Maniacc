<?php
// devel/acc_add.php
session_start();
if(!isset($_SESSION['login_user'])){
    header("location: ../index.php");
    exit;
}

include '../function.php';

$status_msg = "";

// Cek apakah tombol SIMPAN diklik
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Panggil fungsi add_account dari function.php
    $input_data = [
        'platform' => $_POST['platform'],
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'url'      => $_POST['url'],
        'notes'    => $_POST['notes']
    ];

    if (add_account($input_data)) {
        $status_msg = "<div style='border:1px solid #00ff00; padding:10px; margin-bottom:10px;'>
                        > SYSTEM REPORT: DATA ENCRYPTED & STORED SUCCESSFULLY.
                       </div>";
    } else {
        $status_msg = "<div style='border:1px solid red; color:red; padding:10px; margin-bottom:10px;'>
                        > SYSTEM ERROR: FAILED TO WRITE DATA.
                       </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Target - ManiAcc</title>
	<link rel="shortcut icon" href="https://img.freepik.com/premium-photo/neon-green-padlock-with-digital-binary-code-cybersecurity-concept-black-background_824086-2521.jpg">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="wrapper">
        
        <?php include '../include/sidebar.php'; ?>

        <div class="main-content">
            <h1>[ NEW ENTRY ]</h1>
            <p>> INPUT NEW TARGET INFORMATION CAREFULLY.</p>
            <hr style="border-color: #004400; margin-bottom: 20px;">
            
            <?= $status_msg; ?>
            <div class="dashboard-layout">
                <div class="column-left">
                    <form action="" method="POST" style="max-width: 600px;">
                        
                        <label>> PLATFORM_NAME (e.g. Facebook, BCA)</label>
                        <input type="text" name="platform" required autocomplete="off" placeholder="...">

                        <label>> USERNAME_ID / EMAIL</label>
                        <input type="text" name="username" required autocomplete="off" placeholder="...">

                        <label>> SECRET_PASSWORD</label>
                        <input type="text" name="password" required autocomplete="off" placeholder="...">
                        <label>> TARGET_URL (Optional)</label>
                        <input type="text" name="url" placeholder="https://...">

                        <label>> ADDITIONAL_NOTES</label>
                        <textarea name="notes" rows="4" style="background:black; color:#00ff00; border:1px solid #004400; width:100%; padding:10px; font-family:inherit;"></textarea>

                        <br><br>
                        <button type="submit">> EXECUTE SAVE</button>
                    </form>
                </div>

                <div class="column-right">
                    <img src="https://user-images.githubusercontent.com/121780473/221089621-1c766a9e-0e15-4f2a-aaae-d1cad68f2235.gif" alt="System Monitoring GIF" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
</body>
</html>