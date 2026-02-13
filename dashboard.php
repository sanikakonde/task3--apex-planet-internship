<?php
require_once "includes/config.php";
require_once "includes/functions.php";
requireLogin();
$title="Dashboard";

$totalUsers = 0;
$totalAdmins = 0;
$recentUsers = [];

$q1 = $conn->query("SELECT COUNT(*) as c FROM users");
$totalUsers = $q1->fetch_assoc()["c"] ?? 0;

$q2 = $conn->query("SELECT COUNT(*) as c FROM users u JOIN roles r ON u.role_id=r.id WHERE r.role_name='Admin'");
$totalAdmins = $q2->fetch_assoc()["c"] ?? 0;

$stmt = $conn->prepare("SELECT u.id,u.name,u.email,r.role_name,u.created_at FROM users u JOIN roles r ON u.role_id=r.id ORDER BY u.id DESC LIMIT 5");
$stmt->execute();
$recentUsers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

require_once "includes/header.php";
?>
<div class="grid">
  <div class="card">
    <h2 style="margin:0 0 8px 0">Dashboard Analytics</h2>
    <p class="p">Role-based dashboard with live database statistics.</p>

    <div class="kpis">
      <div class="kpi"><div class="t">Total Users</div><div class="v"><?= esc($totalUsers) ?></div></div>
      <div class="kpi"><div class="t">Admins</div><div class="v"><?= esc($totalAdmins) ?></div></div>
      <div class="kpi"><div class="t">Your Role</div><div class="v"><?= esc($_SESSION["role"]) ?></div></div>
    </div>

    <div style="margin-top:16px;display:flex;gap:10px;flex-wrap:wrap">
      <?php if($_SESSION["role"]==="Admin"): ?>
        <a class="btn primary" href="users.php">Manage Users</a>
      <?php endif; ?>
      <a class="btn" href="profile.php">My Profile</a>
    </div>
  </div>

  <div class="card">
    <h3 style="margin:0 0 10px 0">Recent Users</h3>
    <table class="table">
      <tr>
        <th>Name</th><th>Role</th><th>Created</th>
      </tr>
      <?php foreach($recentUsers as $u): ?>
        <tr>
          <td><?= esc($u["name"]) ?><div style="color:rgba(255,255,255,.6);font-size:12px"><?= esc($u["email"]) ?></div></td>
          <td><?= esc($u["role_name"]) ?></td>
          <td style="font-size:12px;color:rgba(255,255,255,.7)"><?= esc($u["created_at"]) ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>
</div>
<?php require_once "includes/footer.php"; ?>