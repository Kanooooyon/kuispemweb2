<?php
session_start();
include 'koneksi/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['user'];

$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['foto'])) {
    $foto = $_FILES['foto'];
    $namaFile = $foto['name'];
    $tmp = $foto['tmp_name'];

    $folder = 'uploads/';
    $path = $folder . $namaFile;

    if (move_uploaded_file($tmp, $path)) {
        $update = "UPDATE users SET photo='$namaFile' WHERE username='$username'";
        mysqli_query($conn, $update);
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Gagal upload foto.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        .container {
            background: #fff;
            margin-top: 60px;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }

        img {
            display: block;
            margin: 10px auto;
            border-radius: 50%;
            border: 2px solid #ccc;
        }

        .form-section {
            margin-top: 25px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input[type="file"] {
            width: 100%;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #3498db;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .logout {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #e74c3c;
            text-decoration: none;
        }

        .logout:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat Datang, <?= htmlspecialchars($username) ?></h2>

        <p><strong>Foto Profil:</strong></p>
        <?php if (!empty($user['photo'])): ?>
    <img src="uploads/<?= htmlspecialchars((string) $user['photo']) ?>" width="150" height="150" alt="Foto Profil">
    <?php else: ?>
    <p style="text-align: center;">Belum ada foto.</p>
    <?php endif; ?>


        <div class="form-section">
            <form method="POST" enctype="multipart/form-data">
                <label for="foto">Update Foto:</label>
                <input type="file" name="foto" id="foto" required>
                <button type="submit">Upload</button>
            </form>
        </div>

        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
