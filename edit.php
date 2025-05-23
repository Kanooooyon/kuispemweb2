<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi/db.php';

if (!isset($_GET['id'])) {
    echo "ID user tidak ditemukan.";
    exit();
}

$id = intval($_GET['id']); // pastikan ID-nya aman
$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $password = password_hash($password, PASSWORD_BCRYPT);
        $update = "UPDATE users SET username='$username', password='$password' WHERE id=$id";
    } else {
        $update = "UPDATE users SET username='$username' WHERE id=$id";
    }

    mysqli_query($conn, $update);
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Edit User</title></head>
<body>
    <h2>Edit User</h2>
    <form method="POST">
        Username: <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>
        Password Baru (kosongkan jika tidak diubah): <input type="password" name="password"><br><br>
        <button type="submit">Update</button>
    </form>
    <br><a href="dashboard.php">Kembali</a>
</body>
</html>
