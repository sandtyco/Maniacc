<?php
// include/conn.php
$host = 'localhost';
$db   = 'maniacc';
$user = 'root'; 
$pass = '';     

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Tampilan error gaya console
    die("<body style='background:black;color:red;font-family:monospace;'>SYSTEM ERROR: DATABASE DISCONNECTED.<br>". $e->getMessage() ."</body>");
}
?>