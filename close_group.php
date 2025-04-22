<?php
session_start();
include('db_connection.php');

// Kullanıcı ID ve yetkili kontrolü
$user_id = $_SESSION['user_id'];

// Kullanıcı verisini al
$query = "SELECT role_id FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($role_id);
$stmt->fetch();

// Admin ya da üstü yetkisi kontrol edelim
$query = "SELECT name FROM roles WHERE id = ? AND (name = 'Admin' OR name = 'Üst Admin' OR name = 'Yönetici')";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $role_id);
$stmt->execute();
$stmt->bind_result($role_name);
$stmt->fetch();

if (!$role_name) {
    echo "Bu sayfaya erişim izniniz yok!";
    exit;
}

// Grup ID'si alalım
$group_id = $_POST['group_id'];

// Grubu kapat
$close_group_query = "UPDATE groups SET is_closed = TRUE WHERE id = ?";
$stmt = $conn->prepare($close_group_query);
$stmt->bind_param("i", $group_id);
$stmt->execute();

echo "Grup başarıyla kapatıldı.";
?>