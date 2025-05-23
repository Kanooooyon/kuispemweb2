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

// Ambil semua user
$all_users = mysqli_query($conn, "SELECT * FROM users");
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
            flex-direction: column;
            align-items: center;
        }

        .container {
            background: #fff;
            margin-top: 30px;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 800px;
        }

        h2, h3 {
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        table th {
            background-color: #3498db;
            color: #fff;
        }

        a.action {
            color: #2980b9;
            text-decoration: none;
            margin-right: 10px;
        }

        a.action:hover {
            text-decoration: underline;
        }

        .top-action {
            margin: 15px 0;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Selamat Datang, <?= htmlspecialchars($username) ?></h2>

        <p><strong>Foto Profil:</strong></p>
        <?php if (!empty($user['photo'])): ?>
            <img src="uploads/<?= htmlspecialchars($user['photo']) ?>" width="150" height="150" alt="Foto Profil">
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

        <hr>

        <h3>Daftar User</h3>
        <div class="top-action">
            <a href="tambah.php" class="action">+ Tambah User</a>
        </div>

        <table>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Aksi</th>
            </tr>
            <?php $no = 1; while($row = mysqli_fetch_assoc($all_users)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td>
                    <?php if ($row['username'] != $username): ?>
                        <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                        <a class="action" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus user ini?')">Hapus</a>
                    <?php else: ?>
                        (Sedang login)
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
