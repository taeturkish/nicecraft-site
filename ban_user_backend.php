<?php
session_start();
include('db_connection.php');

// Kullanıcı ID ve grup bilgileri
$group_id = $_POST['group_id'];
$ban_user_id = $_POST['user_id'];
$admin_id = $_SESSION['user_id'];

// Kullanıcının grup içindeki rütbesini kontrol et
$query = "SELECT role_id FROM group_members WHERE group_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $group_id, $admin_id);
$stmt->execute();
$stmt->bind_result($role_id);
$stmt->fetch();

// Admin rütbesi var mı?
$query = "SELECT can_ban FROM group_roles WHERE group_id = ? AND id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $group_id, $role_id);
$stmt->execute();
$stmt->bind_result($can_ban);
$stmt->fetch();

if ($can_ban) {
    // Kullanıcıyı banla
    $ban_query = "INSERT INTO group_bans (group_id, user_id, banned_by) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($ban_query);
    $stmt->bind_param("iii", $group_id, $ban_user_id, $admin_id);
    $stmt->execute();

    echo "Kullanıcı başarıyla yasaklandı.";
} else {
    echo "Bu rütbenin yasaklama yetkisi yok.";
}
?>
