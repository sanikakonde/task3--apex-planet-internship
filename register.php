<?php
require_once "includes/config.php";
require_once "includes/functions.php";
$title="Register";

$msg = null;

if($_SERVER["REQUEST_METHOD"]==="POST"){
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if($name=="" || $email=="" || $password==""){
        $msg = ["type"=>"bad","text"=>"All fields are required."];
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $msg = ["type"=>"bad","text"=>"Invalid email format."];
    }elseif(strlen($password) < 6){
        $msg = ["type"=>"bad","text"=>"Password must be at least 6 characters."];
    }else{
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
            $msg = ["type"=>"bad","text"=>"Email already registered."];
        }else{
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role_id = 2; // default user role
            $stmt2 = $conn->prepare("INSERT INTO users(name,email,password_hash,role_id) VALUES(?,?,?,?)");
            $stmt2->bind_param("sssi",$name,$email,$hash,$role_id);
            if($stmt2->execute()){
                $_SESSION["ok"]="Registration successful. Please login.";
                header("Location: login.php");
                exit;
            }else{
                $msg = ["type"=>"bad","text"=>"Something went wrong. Try again."];
            }
        }
    }
}

require_once "includes/header.php";
?>
<div class="card" style="max-width:520px;margin:22px auto">
  <h2 style="margin:0 0 8px 0">Create Account</h2>
  <p class="p">Secure registration with hashed passwords.</p>

  <?php if($msg): ?>
    <div class="alert <?= esc($msg["type"]) ?>"><?= esc($msg["text"]) ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="label">Full Name</div>
    <input class="input" name="name" required>

    <div class="label">Email</div>
    <input class="input" name="email" type="email" required>

    <div class="label">Password</div>
    <input class="input" name="password" type="password" required>

    <div style="margin-top:14px;display:flex;gap:10px">
      <button class="btn primary" type="submit">Register</button>
      <a class="btn" href="login.php">Already have account?</a>
    </div>
  </form>
</div>
<?php require_once "includes/footer.php"; ?>