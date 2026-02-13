<?php
require_once "includes/config.php";
require_once "includes/functions.php";
$title="Login";

$msg = null;
$ok = flash("ok");

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if($email=="" || $password==""){
        $msg=["type"=>"bad","text"=>"Email and password are required."];
    }else{
        $stmt = $conn->prepare("SELECT u.id,u.name,u.email,u.password_hash,r.role_name 
                                FROM users u 
                                JOIN roles r ON u.role_id=r.id
                                WHERE u.email=? LIMIT 1");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res->num_rows===1){
            $row = $res->fetch_assoc();
            if(password_verify($password, $row["password_hash"])){
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["name"] = $row["name"];
                $_SESSION["email"] = $row["email"];
                $_SESSION["role"] = $row["role_name"];
                header("Location: dashboard.php");
                exit;
            }else{
                $msg=["type"=>"bad","text"=>"Invalid password."];
            }
        }else{
            $msg=["type"=>"bad","text"=>"Account not found."];
        }
    }
}

require_once "includes/header.php";
?>
<div class="card" style="max-width:520px;margin:22px auto">
  <h2 style="margin:0 0 8px 0">Welcome Back</h2>
  <p class="p">Login with session-based authentication.</p>

  <?php if($ok): ?><div class="alert ok"><?= esc($ok) ?></div><?php endif; ?>
  <?php if($msg): ?><div class="alert <?= esc($msg["type"]) ?>"><?= esc($msg["text"]) ?></div><?php endif; ?>

  <form method="post">
    <div class="label">Email</div>
    <input class="input" name="email" type="email" required>

    <div class="label">Password</div>
    <input class="input" name="password" type="password" required>

    <div style="margin-top:14px;display:flex;gap:10px">
      <button class="btn primary" type="submit">Login</button>
      <a class="btn" href="register.php">Create account</a>
    </div>

    <div class="alert" style="margin-top:14px">
      <b>Admin Demo:</b> admin@demo.com / Admin@123
    </div>
  </form>
</div>
<?php require_once "includes/footer.php"; ?>