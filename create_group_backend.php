<?php
session_start();
include('db_connection.php');

// Kullanıcı ID ve grup adı
$user_id = $_SESSION['user_id'];
$group_name = $_POST['group_name'];

// Kullanıcının bakiyesi kontrol edilir
$query = "SELECT coins FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($coins);
$stmt->fetch();

// 100 Coins var mı?
if ($coins >= 100) {
    // Grup oluşturulacak
    $create_group_query = "INSERT INTO groups (group_name, owner_id) VALUES (?, ?)";
    $stmt = $conn->prepare($create_group_query);
    $stmt->bind_param("si", $group_name, $user_id);
    $stmt->execute();

    // Kullanıcının bakiyesi 100 Coins azaltılacak
    $new_balance = $coins - 100;
    $update_balance_query = "UPDATE users SET coins = ? WHERE id = ?";
    $stmt = $conn->prepare($update_balance_query);
    $stmt->bind_param("ii", $new_balance, $user_id);
    $stmt->execute();

    echo "Grup başarıyla oluşturuldu!";
} else {
    echo "Grup oluşturmak için yeterli Coins yok.";
}
?>
