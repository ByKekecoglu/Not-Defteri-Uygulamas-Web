<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT dosya_yolu FROM notlar WHERE id = ?");
    $stmt->execute([$id]);
    $not = $stmt->fetch(PDO::FETCH_ASSOC);

   
    if ($not['dosya_yolu'] && file_exists($not['dosya_yolu'])) {
        unlink($not['dosya_yolu']);
    }

    $stmt = $conn->prepare("DELETE FROM notlar WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: index.php");
}
?>