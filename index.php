<?php
$title="Welcome";
require_once "includes/header.php";
?>
<div class="grid">
  <div class="card">
    <h1 class="h1">Modern User Management System</h1>
    <p class="p">
      A complete <b>Task-3 Backend Development & Database Integration</b> project with:
      Secure Authentication, Role-Based Access, CRUD, Profile Picture Upload, Validation,
      Prepared Statements, and Admin Dashboard Analytics.
    </p>
    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a class="btn primary" href="register.php">Create Account</a>
      <a class="btn" href="login.php">Login</a>
      <a class="btn" href="README.html">View README</a>
    </div>

    <div class="kpis">
      <div class="kpi"><div class="t">Security</div><div class="v">SQL Safe</div></div>
      <div class="kpi"><div class="t">Passwords</div><div class="v">Hashed</div></div>
      <div class="kpi"><div class="t">Access</div><div class="v">Role Based</div></div>
    </div>
  </div>

  <div class="card">
    <h3 style="margin:0 0 10px 0">Quick Features</h3>
    <ul style="margin:0;color:rgba(255,255,255,.75);line-height:1.7">
      <li>Admin can Add/Edit/Delete Users</li>
      <li>Users can update profile & upload photo</li>
      <li>Delete confirmation popup modal</li>
      <li>Server-side validation</li>
      <li>Prepared Statements for all queries</li>
    </ul>
  </div>
</div>
<?php require_once "includes/footer.php"; ?>