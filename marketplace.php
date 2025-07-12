<?php
$conn = new mysqli("localhost", "root", "", "rewear");
$result = $conn->query("SELECT * FROM products WHERE status = 'approved'");
?>

<h2>Available Clothing Items</h2>
<?php while($row = $result->fetch_assoc()): ?>
  <div style="border:1px solid #ccc; padding:10px; margin:10px;">
    <img src="uploads/<?= $row['image'] ?>" width="150"><br>
    <p><?= $row['description'] ?></p>
    <p>₹<?= $row['price'] ?></p>
  </div>
<?php endwhile; ?>

