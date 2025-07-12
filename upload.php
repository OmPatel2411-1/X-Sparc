<?php
$conn = new mysqli("localhost", "root", "", "rewear");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $desc = $_POST['description'];
  $price = $_POST['price'];

  $image = $_FILES['image']['name'];
  $target = "uploads/" . basename($image);
  move_uploaded_file($_FILES['image']['tmp_name'], $target);

  $stmt = $conn->prepare("INSERT INTO products (image, description, price) VALUES (?, ?, ?)");
  $stmt->bind_param("ssd", $image, $desc, $price);
  $stmt->execute();

  echo "Product submitted successfully. Awaiting admin approval.";
}
?>
