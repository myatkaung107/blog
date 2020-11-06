<?php

require '../config/config.php';
session_start();

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_POST) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    if (empty($_POST['role'])) {
      $role=0;
    }else {
      $role=1;
    }
    $pdostmt = $pdo -> prepare("SELECT * FROM users WHERE email = :email");
    $pdostmt -> bindValue(':email',$email);
    $pdostmt -> execute();
    $user = $pdostmt -> fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email already used')</script>";
    }else {
      $pdostmt= $pdo-> prepare("INSERT INTO users(name,email,role) VALUES (:name,:email,:role)");
      $result=$pdostmt->execute(
        array(
          ':name'=>$name,':email'=>$email,':role'=>$role)
      );
      if ($result) {
        echo "<script>alert('New user is added');window.location.href='user_list.php';</script>";
      }
    }
?>

<?php include('header.html'); ?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-body">
            <form class="" action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="">Name</label>
                <input type="text" class="form-control" name="name" value="" required>
              </div>
              <div class="form-group">
                <label for="">Email</label>
                <input type="email" class="form-control" name="email" value="" required>
              </div>
              <div class="form-group">
                <label for="">Admin</label><br>
                <input type="checkbox" name="role" value="1">
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-outline-success" name="" value="Create">
                <a type="button" class="btn btn-outline-warning" href="user_list.php">Back</a>
              </div>
            </form>
          </div>
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col-md -->
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<?php
include('footer.html');
?>
