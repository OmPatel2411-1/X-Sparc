<?php
$conn = new mysqli("localhost", "root", "", "rewear");
$id = $_GET['id'];
$conn->query("UPDATE products SET status = 'approved' WHERE id = $id");
header("Location: admin.php");
?>
