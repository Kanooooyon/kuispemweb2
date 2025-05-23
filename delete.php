<?php
session_start();
include 'koneksi/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $userLogin = $_SESSION['user'];
    $check = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    $target = mysqli_fetch_assoc($check);

    if ($target && $target['username'] !== $userLogin) {
        $delete = "DELETE FROM users WHERE id=$id";
        mysqli_query($conn, $delete);
    }

    header("Location: dashboard.php");
    exit();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
