<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $insert = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if (mysqli_query($conn, $insert)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User</title>
</head>
<body>
    <h2>Tambah User</h2>
    <form method="POST">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Simpan</button>
    </form>
    <br><a href="dashboard.php">Kembali</a>
</body>
</html>
