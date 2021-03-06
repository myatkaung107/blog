<?php
require '../config/config.php';
require '../config/common.php';
session_start();

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_POST) {
  if (empty($_POST['name']) || empty($_POST['email'])) {
    if (empty($_POST['name'])) {
      $name_error = 'Fill in name';
    }
    if (empty($_POST['email'])) {
      $email_error = 'Fill in email';
    }elseif (!empty($_POST['password']) && strlen($_POST['password']) < 4) {
      $password_error = 'Password must be at least 4 characters';
    }

  }else {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    if (empty($_POST['role'])) {
      $role=0;
    }else {
      $role=1;
    }

    $pdostmt = $pdo -> prepare("SELECT * FROM users WHERE email = :email AND id!=:id");
    $pdostmt -> bindValue(':email',$email);
    $pdostmt -> bindValue(':id',$id);
    $pdostmt -> execute();
    $user = $pdostmt -> fetch(PDO::FETCH_ASSOC);

    if ($user) {
      echo "<script>alert('Email already used')</script>";
    }else {
      if ($password !=null) {
        $pdostmt= $pdo-> prepare("UPDATE users SET name='$name',email='$email',password='$password',role='$role' WHERE id='$id'");
      }else {
        $pdostmt= $pdo-> prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
      }
      $result=$pdostmt->execute();
      if ($result) {
        echo "<script>alert('User is updated');window.location.href='user_list.php';</script>";
      }
    }
  }
}
  $pdostmt=$pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
  $pdostmt->execute();
  $result=$pdostmt->fetchAll();

?>
<?php include('header.php'); ?>
  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <form class="" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token'] ?>">
                <div class="form-group">
                  <label for="">Name</label><p style="color:red"><?php echo empty($name_error) ? '':'*'.$name_error ?></p>
                  <input type="text" class="form-control" name="name" value="<?php echo escape($result[0]['name']) ?>" >
                </div>
                <div class="form-group">
                  <label for="">Email</label><p style="color:red"><?php echo empty($email_error) ? '':'*'.$email_error ?></p>
                  <input type="email" class="form-control" name="email" value="<?php echo escape($result[0]['email']) ?>" >
                </div>
                <div class="form-group">
                  <label for="">Password</label><p style="color:red"><?php echo empty($password_error) ? '':'*'.$password_error ?></p>
                  <input type="password" class="form-control" name="password" value="<?php echo escape($result[0]['password']) ?>" >
                </div>
                <div class="form-group">
                  <label for="vehicle3">Role</label><br>
                  <input type="checkbox" name="role" value="1">
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-outline-success" name="" value="Update">
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

<?php include('footer.html') ?>
