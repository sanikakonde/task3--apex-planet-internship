<?php require_once __DIR__ . "/functions.php"; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= esc($title ?? "User Management Pro") ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/app.js" defer></script>
</head>
<body>
<div class="container">
  <div class="nav">
    <div class="brand">
      <span style="font-size:18px">⚡ UserMgmt Pro</span>
      <span class="badge">Task-3 Ready</span>
    </div>
    <div style="display:flex;gap:10px;align-items:center">
      <?php if(isLoggedIn()): ?>
        <span style="color:rgba(255,255,255,.7);font-size:13px">
          <?= esc($_SESSION['name']) ?> (<?= esc($_SESSION['role']) ?>)
        </span>
        <a class="btn small" href="profile.php">Profile</a>
        <a class="btn small" href="dashboard.php">Dashboard</a>
        <a class="btn small danger" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn small" href="login.php">Login</a>
        <a class="btn small primary" href="register.php">Register</a>
      <?php endif; ?>
    </div>
  </div>
