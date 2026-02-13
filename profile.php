<?php
require_once "includes/config.php";
require_once "includes/functions.php";
requireLogin();
$title="My Profile";

$user_id = intval($_SESSION["user_id"]);
$msg=null;

$stmt = $conn->prepare("SELECT id,name,email,profile_image FROM users WHERE id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if($_SERVER["REQUEST_METHOD"]==="POST"){
  $name = trim($_POST["name"] ?? "");
  if($name==""){
    $msg=["type"=>"bad","text"=>"Name cannot be empty."];
  }else{
    $imgName = $user["profile_image"];
    if(isset($_FILES["profile"]) && $_FILES["profile"]["error"]===0){
      $allowed=["image/jpeg","image/png","image/webp"];
      $maxSize=2*1024*1024;

      if(!in_array($_FILES["profile"]["type"], $allowed)){
        $msg=["type"=>"bad","text"=>"Only JPG, PNG, WEBP allowed."];
      }elseif($_FILES["profile"]["size"] > $maxSize){
        $msg=["type"=>"bad","text"=>"File size must be less than 2MB."];
      }else{
        $ext = pathinfo($_FILES["profile"]["name"], PATHINFO_EXTENSION);
        $newName = "user_" . $user_id . "_" . time() . "." . $ext;
        $dest = "uploads/" . $newName;
        if(move_uploaded_file($_FILES["profile"]["tmp_name"], $dest)){
          $imgName = $newName;
        }else{
          $msg=["type"=>"bad","text"=>"Upload failed."];
        }
      }
    }

    if(!$msg){
      $stmt2=$conn->prepare("UPDATE users SET name=?, profile_image=? WHERE id=?");
      $stmt2->bind_param("ssi",$name,$imgName,$user_id);
      if($stmt2->execute()){
        $_SESSION["name"]=$name;
        $msg=["type"=>"ok","text"=>"Profile updated successfully."];
        $user["name"]=$name;
        $user["profile_image"]=$imgName;
      }else{
        $msg=["type"=>"bad","text"=>"Update failed."];
      }
    }
  }
}

require_once "includes/header.php";
$img = $user["profile_image"] ? "uploads/".$user["profile_image"] : "assets/img/default.png";
?>
<div class="card" style="max-width:800px;margin:22px auto">
  <div style="display:flex;gap:14px;align-items:center;flex-wrap:wrap">
    <img class="avatar" src="<?= esc($img) ?>">
    <div>
      <h2 style="margin:0">Edit Profile</h2>
      <div style="color:rgba(255,255,255,.7);font-size:13px"><?= esc($user["email"]) ?></div>
    </div>
  </div>

  <?php if($msg): ?><div class="alert <?= esc($msg["type"]) ?>"><?= esc($msg["text"]) ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" style="margin-top:10px">
    <div class="label">Full Name</div>
    <input class="input" name="name" value="<?= esc($user["name"]) ?>" required>

    <div class="label">Profile Picture (JPG/PNG/WEBP max 2MB)</div>
    <input class="input" name="profile" type="file" accept="image/*">

    <div style="margin-top:14px;display:flex;gap:10px">
      <button class="btn primary" type="submit">Save Changes</button>
      <a class="btn" href="dashboard.php">Back</a>
    </div>
  </form>
</div>
<?php require_once "includes/footer.php"; ?>