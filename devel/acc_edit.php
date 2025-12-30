<?php
// devel/acc_edit.php
session_start();
if(!isset($_SESSION['login_user'])){
    header("location: ../index.php");
    exit;
}

include '../function.php';

// 1. Cek apakah ada ID yang dikirim lewat URL?
if (!isset($_GET['id'])) {
    header("Location: acc_list.php"); // Jika tidak ada ID, tendang balik ke list
    exit;
}

$id = $_GET['id'];
$row = get_account_by_id($id);

// Jika ID tidak ditemukan di database
if (!$row) {
    die("ERROR: TARGET DATA NOT FOUND IN DATABASE.");
}

$status_msg = "";

// 2. Proses saat tombol UPDATE ditekan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_data = [
        'platform' => $_POST['platform'],
        'username' => $_POST['username'],
        'password' => $_POST['password'],
        'url'      => $_POST['url'],
        'notes'    => $_POST['notes']
    ];

    if (update_account($id, $input_data)) {
        // Refresh data agar yang tampil adalah data terbaru
        $row = get_account_by_id($id); 
        $status_msg = "<div style='border:1px solid #00ff00; padding:10px; margin-bottom:10px;'>
                        > SYSTEM REPORT: TARGET DATA MODIFIED SUCCESSFULLY.
                       </div>";
    } else {
        $status_msg = "<div style='border:1px solid red; color:red; padding:10px; margin-bottom:10px;'>
                        > SYSTEM ERROR: UPDATE FAILED.
                       </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modify Target - ManiAcc</title>
	<link rel="shortcut icon" href="https://img.freepik.com/premium-photo/neon-green-padlock-with-digital-binary-code-cybersecurity-concept-black-background_824086-2521.jpg">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="wrapper">
        
        <?php include '../include/sidebar.php'; ?>

        <div class="main-content">
            <h1>[ MODIFY DATA ]</h1>
            <p>> EDITING TARGET ID: <strong>#<?= $id; ?></strong></p>
            <hr style="border-color: #004400; margin-bottom: 20px;">
            
            <?= $status_msg; ?>

            <form action="" method="POST" style="max-width: 600px;">
                
                <label>> PLATFORM_NAME</label>
                <input type="text" name="platform" value="<?= htmlspecialchars($row['platform_name']); ?>" required>

                <label>> USERNAME_ID</label>
                <input type="text" name="username" value="<?= htmlspecialchars($row['username']); ?>" required>

                <label>> SECRET_PASSWORD</label>
                <input type="text" name="password" value="<?= htmlspecialchars($row['password']); ?>" required>

                <label>> TARGET_URL</label>
                <input type="text" name="url" value="<?= htmlspecialchars($row['url']); ?>">

                <label>> ADDITIONAL_NOTES</label>
                <textarea name="notes" rows="4" style="background:black; color:#00ff00; border:1px solid #004400; width:100%; padding:10px; font-family:inherit;"><?= htmlspecialchars($row['notes']); ?></textarea>

                <br><br>
                <div style="display:flex; gap:10px;">
                    <button type="submit" style="width:auto;">> SAVE CHANGES</button>
                    <a href="acc_list.php" class="btn" style="text-align:center; padding-top:12px; margin:10px 0 0 0;">CANCEL</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>