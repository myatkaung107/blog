<?php

session_start();
require '../config/config.php';

if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
  header('Location: login.php');
}

if ($_POST) {
  $file = 'images/'.($_FILES['image']['name']);
  $imageType = pathinfo($file,PATHINFO_EXTENSION);
  if ($imageType!='png' && $imageType!='jpg' && $imageType!='jpeg') {
    echo "<script>alert('Image must be image type')</script>";
  }else {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'],$file);
    $pdostmt = $pdo-> prepare("INSERT INTO posts(title,content,author_id,image) VALUES (:title,:content,:author_id,:image)");
    $result = $pdostmt->execute(
      array(
        ':title'=>$title,':content'=>$content,':author_id'=>$_SESSION['user_id'],':image'=>$image
      )
    );
    if ($result) {
      echo "<script>alert('New Record is added');window.location.href='index.php';</script>";
    }
  }
}

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
                  <div class="form-group">
                    <label for="">Title</label>
                    <input type="text" class="form-control" name="title" value="" required>
                  </div>
                  <div class="form-group">
                    <label for="">Content</label>
                    <textarea class="form-control" name="content" rows="8" cols="80"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="">Image</label><br>
                    <input type="file" name="image" value="" required>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="btn btn-outline-success" name="" value="Create">
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
  <?php
  include('footer.html');
  ?>
