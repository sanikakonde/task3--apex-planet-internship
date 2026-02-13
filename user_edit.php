<?php
require_once "includes/config.php";
require_once "includes/functions.php";
requireLogin();
requireRole("Admin");
$title="Edit User";

$id = intval($_GET["id"] ?? 0);
$msg=null;

$roles = $conn->query("SELECT id,role_name FROM roles")->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT id,name,email,role_id FROM users WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if(!$user){
  header("Location: users.php");
  exit;
}

if($_SERVER["REQUEST_METHOD"]==="POST"){
  $name=trim($_POST["name"]??"");
  $email=trim($_POST["email"]??"");
  $role_id=intval($_POST["role_id"]??2);

  if($name==""||$email==""){
    $msg=["type"=>"bad","text"=>"Name and Email are required."];
  }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $msg=["type"=>"bad","text"=>"Invalid email format."];
  }else{
    $stmt2=$conn->prepare("UPDATE users SET name=?, email=?, role_id=? WHERE id=?");
    $stmt2->bind_param("ssii",$name,$email,$role_id,$id);
    if($stmt2->execute()){
      header("Location: users.php");
      exit;
    }else{
      $msg=["type"=>"bad","text"=>"Update failed."];
    }
  }
}

require_once "includes/header.php";
?>
<div class="card" style="max-width:700px;margin:22px auto">
  <h2 style="margin:0 0 8px 0">Edit User</h2>
  <p class="p">Update existing user record.</p>

  <?php if($msg): ?><div class="alert <?= esc($msg["type"]) ?>"><?= esc($msg["text"]) ?></div><?php endif; ?>

  <form method="post">
    <div class="form-row">
      <div>
        <div class="label">Full Name</div>
        <input class="input" name="name" value="<?= esc($user["name"]) ?>" required>
      </div>
      <div>
        <div class="label">Email</div>
        <input class="input" name="email" type="email" value="<?= esc($user["email"]) ?>" required>
      </div>
    </div>

    <div class="label">Role</div>
    <select class="input" name="role_id">
      <?php foreach($roles as $r): ?>
        <option value="<?= esc($r["id"]) ?>" <?= $r["id"]==$user["role_id"] ? "selected":"" ?>>
          <?= esc($r["role_name"]) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <div style="margin-top:14px;display:flex;gap:10px">
      <button class="btn primary" type="submit">Update</button>
      <a class="btn" href="users.php">Back</a>
    </div>
  </form>
</div>
<?php require_once "includes/footer.php"; ?>