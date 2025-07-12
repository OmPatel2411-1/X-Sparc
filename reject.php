<?php
$conn = new mysqli("localhost", "root", "", "rewear");
$id = $_GET['id'];
$conn->query("DELETE FROM products WHERE id = $id");
header("Location: admin.php");
?>
