<?php
// devel/dash.php
session_start();
if(!isset($_SESSION['login_user'])){
    header("location: ../index.php");
    exit;
}

// Panggil file fungsi (keluar satu folder dulu ../)
include '../function.php';

// Ambil statistik untuk ditampilkan
$total_akun = count_accounts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>ManiAcc - Dashboard</title>
	<link rel="shortcut icon" href="https://img.freepik.com/premium-photo/neon-green-padlock-with-digital-binary-code-cybersecurity-concept-black-background_824086-2521.jpg">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="wrapper">
        
        <?php include '../include/sidebar.php'; ?>

        <div class="main-content">
            <h1>[ SYSTEM DASHBOARD ]</h1>
            <p>>> WELCOME BACK, COMMANDER <strong><?= strtoupper($_SESSION['login_user']); ?></strong>.</p>
            <hr style="border-color: #004400; margin-bottom: 20px;">
            
            <div class="dashboard-layout">
                
                <div class="column-left">
                    <h1 style="font-size: 20px; margin: 0;">STATUS</h1>
                    <div style="display: flex; gap: 20px; margin-top: 20px;">
                        <div style="border: 1px solid #00ff00; padding: 20px; width: 200px; text-align: center; box-shadow: 0 0 10px #003300;">
                            <h1 style="font-size: 20px; margin: 0;">ANY <?= $total_akun; ?></h1>
                            <span style="font-size: 12px;">ACC STORED</span>
                        </div>
                        
                        <div style="border: 1px solid #00ff00; padding: 20px; width: 200px; text-align: center; box-shadow: 0 0 10px #003300;">
                            <h1 style="font-size: 20px; margin: 0;">SYSTEM</h1>
                            <span style="font-size: 12px;">SECURE</span>
                        </div>

                        <div style="border: 1px solid #00ff00; padding: 20px; width: 200px; text-align: center; box-shadow: 0 0 10px #003300;">
                            <h1 style="font-size: 20px; margin: 0;">DATABASE</h1>
                            <span style="font-size: 12px;">SECURE</span>
                        </div>

                        <div style="border: 1px solid #00ff00; padding: 20px; width: 200px; text-align: center; box-shadow: 0 0 10px #003300;">
                            <h1 style="font-size: 20px; margin: 0;">SERVER</h1>
                            <span style="font-size: 12px;">SECURE</span>
                        </div>
                    </div>

                    <br>
                    <p>> SHORTCUT COMMANDS:</p>
                    <a href="acc_add.php" style="color: #00ff00; text-decoration: underline;">[+] CREATE NEW ENTRY</a>
                    
                </div>
                
                <div class="column-right">
                    <img src="https://user-images.githubusercontent.com/121780473/221089621-1c766a9e-0e15-4f2a-aaae-d1cad68f2235.gif" alt="System Monitoring GIF" style="width: 100%;">
                </div>
                
            </div>
            </div>
    </div>
</body>
</html>