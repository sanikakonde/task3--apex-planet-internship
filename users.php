<?php
require_once "includes/config.php";
require_once "includes/functions.php";
requireLogin();
requireRole("Admin");
$title="User Management";

$res = $conn->query("SELECT u.id,u.name,u.email,r.role_name,u.created_at FROM users u JOIN roles r ON u.role_id=r.id ORDER BY u.id DESC");
$users = $res->fetch_all(MYSQLI_ASSOC);

require_once "includes/header.php";
?>
<div class="card" style="margin-top:22px">
  <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
    <div>
      <h2 style="margin:0">Users CRUD Panel</h2>
      <p class="p" style="margin:6px 0 0 0">Add, Update, Delete users securely using prepared statements.</p>
    </div>
    <a class="btn primary" href="user_add.php">+ Add User</a>
  </div>

  <table class="table" style="margin-top:12px">
    <tr>
      <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Action</th>
    </tr>
    <?php foreach($users as $u): ?>
      <tr>
        <td><?= esc($u["id"]) ?></td>
        <td><?= esc($u["name"]) ?></td>
        <td><?= esc($u["email"]) ?></td>
        <td><?= esc($u["role_name"]) ?></td>
        <td style="font-size:12px;color:rgba(255,255,255,.7)"><?= esc($u["created_at"]) ?></td>
        <td>
          <a class="btn small" href="user_edit.php?id=<?= esc($u["id"]) ?>">Edit</a>
          <button class="btn small danger" onclick="openDeleteModal('user_delete.php?id=<?= esc($u["id"]) ?>')">Delete</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php require_once "includes/footer.php"; ?>