<?php
session_start();
// Hapus semua session
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>System Shutdown</title>
    <link rel="stylesheet" href="css/style.css">
    <meta http-equiv="refresh" content="3;url=index.php">
</head>
<body>
    <div class="login-container" style="border:none; box-shadow:none; text-align:left;">
        <p>> INITIATING LOGOUT SEQUENCE...</p>
        <p id="msg1" style="display:none;">> CLEARING TEMP FILES... [OK]</p>
        <p id="msg2" style="display:none;">> CLOSING DATABASE CONNECTION... [OK]</p>
        <p id="msg3" style="display:none; color:red;">> SESSION TERMINATED.</p>
        <br>
        <p id="msg4" style="display:none;">> REDIRECTING TO LOGIN...</p>
    </div>

    <script>
        // Script sederhana untuk efek muncul bergantian
        setTimeout(function(){ document.getElementById('msg1').style.display = 'block'; }, 500);
        setTimeout(function(){ document.getElementById('msg2').style.display = 'block'; }, 1200);
        setTimeout(function(){ document.getElementById('msg3').style.display = 'block'; }, 2000);
        setTimeout(function(){ document.getElementById('msg4').style.display = 'block'; }, 2500);
    </script>
</body>
</html>