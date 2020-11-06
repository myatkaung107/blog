<?php

session_start();
require '../config/config.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

if ($_POST) {
  $id = $_POST['id'];
  $title = $_POST['title'];
  $content = $_POST['content'];
  if ($_FILES['image']['name'] !=null) {
    print"<pre>";
    print_r($_FILES['image']['name']);exit();
    $file = 'images/'.($_FILES['image']['name']);
    $imageType = pathinfo($file,PATHINFO_EXTENSION);
    if ($imageType !='png' && $imageType !='jpg' && $imageType !='jpeg') {
      echo "<script>alert('Image must be image type')</script>";
    }else {
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],$file);
      $pdostmt = $pdo-> prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id='$id'");
      $result = $pdostmt->execute();
      if ($result) {
        echo "<script>alert('New Record is updated');window.location.href='index.php';</script>";
      }
    }
  }else {
    $pdostmt = $pdo-> prepare("UPDATE posts SET title='$title',content='$content' WHERE id='$id'");
    $result = $pdostmt->execute();
    if ($result) {
      echo "<script>alert('New Record is updated');window.location.href='index.php';</script>";
    }
  }
}

$pdostmt=$pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$pdostmt->execute();
$result=$pdostmt->fetchAll();



?>

<?php
include('header.html');
?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <form class="" action="" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                  <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" value="<?php echo $result[0]['title'] ?>" required>
                  </div>
                  <div class="form-group">
                    <label for="">Content</label>
                    <textarea class="form-control" name="content" rows="8" cols="80"><?php echo $result[0]['content'] ?></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><br>
                    <img src="images/<?php echo $result[0]['image'] ?>" width="100" height="100" alt=""><br><br>
                    <input type="file" name="image" value="">
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-outline-success" name="" value="Update">
                    <a type="button" class="btn btn-outline-warning" href="index.php">Back</a>
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