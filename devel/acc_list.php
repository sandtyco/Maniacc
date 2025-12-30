<?php
// devel/acc_list.php
session_start();
if(!isset($_SESSION['login_user'])){
    header("location: ../index.php");
    exit;
}

include '../function.php';

// --- TANGKAP KATA KUNCI PENCARIAN ---
$keyword = '';
if (isset($_GET['keyword'])) {
    $keyword = htmlspecialchars($_GET['keyword']); // Amankan input
}

// --- LOGIKA HAPUS DATA ---
if (isset($_GET['del'])) {
    $id_to_delete = $_GET['del'];
    if(delete_account($id_to_delete)) {
        // Jika berhasil hapus, redirect ke list (tanpa parameter hapus)
        header("Location: acc_list.php"); 
        exit;
    }
}

// Ambil semua data akun, sertakan kata kunci pencarian
$data_akun = get_all_accounts($keyword);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database View - ManiAcc</title>
	<link rel="shortcut icon" href="https://img.freepik.com/premium-photo/neon-green-padlock-with-digital-binary-code-cybersecurity-concept-black-background_824086-2521.jpg">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="wrapper">
        
        <?php include '../include/sidebar.php'; ?>

        <div class="main-content">
            <h1>[ DATABASE TARGETS ]</h1>
            <p>> LISTING ALL STORED CREDENTIALS...</p>
            <hr style="border-color: #004400; margin-bottom: 20px;">

            <form action="" method="GET" style="display:flex; margin-bottom: 20px; gap: 10px;">
                <input type="text" name="keyword" 
                       value="<?= $keyword; ?>" 
                       placeholder="> ENTER SEARCH KEYWORD..." 
                       style="flex-grow: 1; margin-top: 0;">
                
                <button type="submit" style="width: 120px; margin-top: 0;">
                    SEARCH 🔎
                </button>
                <a href="acc_list.php" class="btn" style="width: 80px; text-align:center; padding-top: 10px; margin-top: 0; background: #330000; border: 1px solid red;">
                    RESET
                </a>
            </form>

            <a href="acc_add.php" class="btn" style="border:1px solid #00ff00; padding:5px 10px; margin-bottom:15px; display:inline-block;">
                [+] INJECT NEW DATA
            </a>

            <table border="1" style="width: 100%; border-collapse: collapse; border-color: #004400;">
                <thead>
                    <tr style="background: #002200;">
                        <th width="5%">NO</th>
                        <th width="15%">PLATFORM</th>
                        <th width="20%">USERNAME</th>
                        <th width="20%">PASSWORD</th> 
                        <th>URL / NOTES</th>
                        <th width="15%">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (count($data_akun) > 0): 
                        foreach ($data_akun as $row): 
                    ?>
                    <tr>
                        <td style="text-align:center;"><?= $no++; ?></td>
                        <td style="color:#ccffcc; font-weight:bold;"><?= htmlspecialchars($row['platform_name']); ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        
                        <td style="color: #ffff00; position:relative;">
                            <span id="pass_<?= $row['id']; ?>" class="masked-pass">
                                <?= str_repeat('*', 10); ?>
                            </span>
                            
                            <a href="#" onclick="toggleVisibility(<?= $row['id']; ?>, '<?= htmlspecialchars($row['password']); ?>'); return false;" 
                            style="color: #008800; font-size: 14px; margin-left: 10px; text-decoration: none;">
                                👁️
                            </a>
                        </td>
                        
                        <td style="font-size: 12px;">
                            <?php if($row['url']): ?>
                                <a href="<?= htmlspecialchars($row['url']); ?>" target="_blank" style="text-decoration:underline;">[LINK]</a>
                            <?php endif; ?>
                            <span style="color: #008800;"><?= htmlspecialchars(substr($row['notes'], 0, 20)); ?>...</span>
                        </td>
                        
                        <td style="text-align:center;">
                            <a href="acc_edit.php?id=<?= $row['id']; ?>" style="color: cyan;">[EDIT]</a> 
                            &nbsp;|&nbsp; 
                            <a href="acc_list.php?del=<?= $row['id']; ?>" 
                               onclick="return confirm('WARNING: Are you sure you want to PURGE this data?');" 
                               style="color: red;">[DEL]</a>
                        </td>
                    </tr>
                    <?php 
                        endforeach; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="6" style="text-align:center; padding: 20px; color: red;">
                            > DATABASE EMPTY. 
                            <?php if($keyword): ?>
                                NO RESULTS FOUND FOR '<?= $keyword; ?>'.
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <br>
            <p style="font-size: 12px; color: #004400;">> END OF LIST.</p>
        </div>
    </div>

    <script>
        // Menyimpan status untuk setiap sandi yang ditampilkan
        let visiblePasswords = {}; 

        function toggleVisibility(id, originalPassword) {
            const passElement = document.getElementById('pass_' + id);
            
            // Cek status, apakah saat ini sandi sudah terlihat (true) atau masih tertutup (false)
            if (visiblePasswords[id]) {
                // Jika sudah terlihat (asli), ganti kembali ke bintang
                passElement.textContent = '**********';
                visiblePasswords[id] = false;
            } else {
                // Jika tertutup (bintang), ganti ke sandi asli
                passElement.textContent = originalPassword;
                visiblePasswords[id] = true;
            }
        }
    </script>

</body>
</html>