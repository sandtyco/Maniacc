<?php
// maniacc/function.php

// --- PENTING: KUNCI ENKRIPSI SISTEM (HARUS 32 KARAKTER) ---
define('SECRET_KEY', '0123456789abcdefABCDEFghiJKLmnoP'); // GANTI DENGAN KUNCI ANDA!
define('CIPHER_ALGO', 'aes-256-cbc'); 

// Koneksi Database
include_once __DIR__ . '/include/conn.php';

// --- MODUL ENKRIPSI/DEKRIPSI ---

// FUNGSI ENKRIPSI DATA
function encrypt_data($data) {
    $ivlen = openssl_cipher_iv_length(CIPHER_ALGO);
    $iv = openssl_random_pseudo_bytes($ivlen);
    $encrypted = openssl_encrypt($data, CIPHER_ALGO, SECRET_KEY, 0, $iv);
    $output = base64_encode($encrypted . '::' . $iv);
    return $output;
}

// FUNGSI DEKRIPSI DATA
function decrypt_data($data) {
    if (empty($data)) return ''; // Tambahkan cek untuk data kosong/null
    
    // Pastikan format data terenkripsi benar sebelum explode
    if (strpos(base64_decode($data, true), '::') === false) {
        // Jika data lama belum terenkripsi, kembalikan data aslinya (opsional: ini untuk migrasi)
        return $data; 
    }
    
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, CIPHER_ALGO, SECRET_KEY, 0, $iv);
}

// --- FUNGSI UTAMA (CRUD YANG SUDAH DIAMANKAN) ---

// FUNGSI HITUNG (Untuk Statistik Dashboard)
function count_accounts() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM accounts");
    return $stmt->fetchColumn();
}

// FUNGSI CREATE (Menambah Akun Baru)
function add_account($data) {
    global $pdo;
    
    // ENKRIPSI SEBELUM SIMPAN
    $encrypted_password = encrypt_data($data['password']);
    $encrypted_notes    = encrypt_data($data['notes']);
    
    $sql = "INSERT INTO accounts (platform_name, username, password, url, notes) 
            VALUES (:platform, :username, :password, :url, :notes)";
    
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([
        ':platform' => $data['platform'],
        ':username' => $data['username'],
        ':password' => $encrypted_password,
        ':url'      => $data['url'],
        ':notes'    => $encrypted_notes
    ]);
}

// FUNGSI UPDATE (Simpan Perubahan Data)
function update_account($id, $data) {
    global $pdo;
    
    // ENKRIPSI SEBELUM UPDATE
    $encrypted_password = encrypt_data($data['password']);
    $encrypted_notes    = encrypt_data($data['notes']);

    $sql = "UPDATE accounts SET 
            platform_name = :platform,
            username      = :username,
            password      = :password,
            url           = :url,
            notes         = :notes
            WHERE id      = :id";
            
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute([
        ':platform' => $data['platform'],
        ':username' => $data['username'],
        ':password' => $encrypted_password,
        ':url'      => $data['url'],
        ':notes'    => $encrypted_notes,
        ':id'       => $id
    ]);
}

// FUNGSI DELETE (Hapus Akun berdasarkan ID)
function delete_account($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM accounts WHERE id = ?");
    return $stmt->execute([$id]);
}

// FUNGSI READ SEMUA (Dekripsi saat diambil)
function get_all_accounts($keyword = '') {
    global $pdo; 
    
    // Siapkan SQL dasar
    $sql = "SELECT * FROM accounts";
    
    // Siapkan array parameter untuk prepared statement
    $params = [];
    
    // 1. Tambahkan kondisi pencarian (WHERE) jika ada kata kunci
    if (!empty($keyword)) {
        // Kolom yang dicari: platform_name, username, url
        $sql .= " WHERE platform_name LIKE ? OR username LIKE ? OR url LIKE ?";
        $like_keyword = '%' . $keyword . '%';
        // Tambahkan parameter ke array
        $params = [$like_keyword, $like_keyword, $like_keyword];
    }
    
    // 2. Tambahkan pengurutan
    $sql .= " ORDER BY id DESC";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params); // Eksekusi dengan parameter pencarian
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // DEKRIPSI (tetap berjalan di sini)
        foreach ($results as $key => $row) {
            $results[$key]['password'] = decrypt_data($row['password']);
            $results[$key]['notes'] = decrypt_data($row['notes']);
        }

        return $results;
    } catch (PDOException $e) {
        return []; 
    }
}

// FUNGSI READ SINGLE (Dekripsi saat diambil)
function get_account_by_id($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // DEKRIPSI
    if ($row) {
        $row['password'] = decrypt_data($row['password']);
        $row['notes']    = decrypt_data($row['notes']);
    }
    
    return $row;
}

// --- function.php (Lanjutan: Modul Keamanan User) ---

function update_user_password($username, $old_password, $new_password) {
    global $pdo;

    // 1. Ambil hash password lama dari database
    $sql = "SELECT password FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return "User tidak ditemukan."; // Seharusnya tidak terjadi
    }

    $stored_hash = $user['password'];

    // 2. Verifikasi Password Lama
    // Kita gunakan password_verify() karena login sudah pakai hashing aman
    if (!password_verify($old_password, $stored_hash)) {
        return "Kata sandi lama salah!";
    }

    // 3. Hash Password Baru
    // Hash password baru sebelum disimpan
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // 4. Update Password Baru di Database
    $update_sql = "UPDATE users SET password = ? WHERE username = ?";
    $update_stmt = $pdo->prepare($update_sql);

    if ($update_stmt->execute([$new_hash, $username])) {
        return true; // Berhasil
    } else {
        return "Gagal memperbarui kata sandi.";
    }
}

?>