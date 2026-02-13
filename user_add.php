<?php
require_once "includes/config.php";
require_once "includes/functions.php";
requireLogin();
requireRole("Admin");
$title="Add User";

$msg=null;
$roles = $conn->query("SELECT id,role_name FROM roles")->fetch_all(MYSQLI_ASSOC);

if($_SERVER["REQUEST_METHOD"]==="POST"){
  $name=trim($_POST["name"]??"");
  $email=trim($_POST["email"]??"");
  $role_id=intval($_POST["role_id"]??2);
  $password=$_POST["password"]??"";

  if($name==""||$email==""||$password==""){
    $msg=["type"=>"bad","text"=>"All fields are required."];
  }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $msg=["type"=>"bad","text"=>"Invalid email format."];
  }elseif(strlen($password)<6){
    $msg=["type"=>"bad","text"=>"Password must be at least 6 characters."];
  }else{
    $stmt=$conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows>0){
      $msg=["type"=>"bad","text"=>"Email already exists."];
    }else{
      $hash=password_hash($password,PASSWORD_DEFAULT);
      $stmt2=$conn->prepare("INSERT INTO users(name,email,password_hash,role_id) VALUES(?,?,?,?)");
      $stmt2->bind_param("sssi",$name,$email,$hash,$role_id);
      if($stmt2->execute()){
        header("Location: users.php");
        exit;
      }else{
        $msg=["type"=>"bad","text"=>"Failed to add user."];
      }
    }
  }
}

require_once "includes/header.php";
?>
<div class="card" style="max-width:700px;margin:22px auto">
  <h2 style="margin:0 0 8px 0">Add New User</h2>
  <p class="p">Admin can create user accounts.</p>

  <?php if($msg): ?><div class="alert <?= esc($msg["type"]) ?>"><?= esc($msg["text"]) ?></div><?php endif; ?>

  <form method="post">
    <div class="form-row">
      <div>
        <div class="label">Full Name</div>
        <input class="input" name="name" required>
      </div>
      <div>
        <div class="label">Email</div>
        <input class="input" name="email" type="email" required>
      </div>
    </div>

    <div class="form-row">
      <div>
        <div class="label">Role</div>
        <select class="input" name="role_id">
          <?php foreach($roles as $r): ?>
            <option value="<?= esc($r["id"]) ?>"><?= esc($r["role_name"]) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <div class="label">Password</div>
        <input class="input" name="password" type="password" required>
      </div>
    </div>

    <div style="margin-top:14px;display:flex;gap:10px">
      <button class="btn primary" type="submit">Save User</button>
      <a class="btn" href="users.php">Back</a>
    </div>
  </form>
</div>
<?php require_once "includes/footer.php"; ?>