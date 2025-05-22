<?php
include 'koneksi/db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM users WHERE id = $id";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
?>

<form action="update.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    Nama: <input type="text" name="name" value="<?= $data['name'] ?>"><br><br>
    
    Foto lama: <br>
    <img src="uploads/<?= $data['foto'] ?>" width="100"><br><br>
    
    Upload foto baru: <input type="file" name="foto"><br><br>
    
    <button type="submit" name="update">Update</button>
</form>
