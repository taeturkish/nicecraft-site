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
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Paneli - Nicecraft</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Nicecraft - Yönetim Paneli</h1>
    </header>

    <section class="admin-actions">
        <h2>Yönetim İşlemleri</h2>

        <!-- Kullanıcıyı banlama -->
        <h3>Kullanıcı Banlama</h3>
        <form action="ban_user.php" method="POST">
            <label for="ban_user_id">Kullanıcı ID:</label>
            <input type="number" id="ban_user_id" name="ban_user_id" required><br><br>

            <button type="submit">Kullanıcıyı Yasakla</button>
        </form>

        <!-- Para verme -->
        <h3>Para Verme</h3>
        <form action="give_coins.php" method="POST">
            <label for="user_id">Kullanıcı ID:</label>
            <input type="number" id="user_id" name="user_id" required><br><br>
            
            <label for="amount">Para Miktarı:</label>
            <input type="number" id="amount" name="amount" required><br><br>

            <button type="submit">Para Ver</button>
        </form>

        <!-- Rütbe verme -->
        <h3>Rütbe Verme</h3>
        <form action="give_role.php" method="POST">
            <label for="user_id">Kullanıcı ID:</label>
            <input type="number" id="user_id" name="user_id" required><br><br>

            <label for="role_id">Rütbe ID:</label>
            <input type="number" id="role_id" name="role_id" required><br><br>

            <button type="submit">Rütbe Ver</button>
        </form>

        <!-- Grup Kapatma -->
        <h3>Grup Kapatma</h3>
        <form action="close_group.php" method="POST">
            <label for="group_id">Grup ID:</label>
            <input type="number" id="group_id" name="group_id" required><br><br>

            <button type="submit">Grubu Kapat</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2025 Nicecraft Minecraft Sunucu</p>
    </footer>
</body>
</html>
