<?php
require_once "includes/config.php";
require_once "includes/functions.php";
requireLogin();
requireRole("Admin");

$id = intval($_GET["id"] ?? 0);

if($id>0){
  $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
}

header("Location: users.php");
exit;
?>