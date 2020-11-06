<?php
  session_start();
  require 'config/config.php';
  if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header('Location: login.php');
  }

  $pdostmt=$pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
  $pdostmt->execute();
  $result=$pdostmt->fetchAll();

  $blId=$_GET['id'];
  $cmstmt=$pdo->prepare("SELECT * FROM comments WHERE post_id=$blId");
  $cmstmt->execute();
  $cmResult=$cmstmt->fetchAll();

  if ($cmResult) {
    $auId=$cmResult[0]['author_id'];
    $austmt=$pdo->prepare("SELECT * FROM users WHERE id=$auId");
    $austmt->execute();
    $auResult=$austmt->fetchAll();
  }

  if ($_POST) {
    $comment = $_POST['comment'];
      $pdostmt = $pdo-> prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
      $result = $pdostmt->execute(
        array(':content'=>$comment,':author_id'=>$_SESSION['user_id'],':post_id'=>$blId=$_GET['id'])
      );
      if ($result) {
        header('Location:blogdetail.php?id='.$blId);
      }
  }



?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 3 | Widgets</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <div class="content-wrapper" style="margin-left:0px">

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <!-- Box Comment -->
          <div class="card card-widget">
            <div class="card-header">
              <div style="float:none;text-align:center !important" class="card-title">
                <h4><?php echo $result[0]['title'] ?></h4>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image'] ?>">
              <p><?php echo $result[0]['content'] ?></p>
              <h3>Comments</h3><hr>
              <a href="/blog" type="button" class="btn btn-default">Back</a>
            </div>
            <!-- /.card-body -->
            <div class="card-footer card-comments">
              <div class="card-comment">
                <?php
                  if ($cmResult) {
                ?>
                  <div class="comment-text" style="margin-left:0px !important">
                    <span class="username">
                      <?php echo $auResult[0]['name'] ?>
                      <span class="text-muted float-right"><?php echo $cmResult[0]['created_at'] ?></span>
                    </span><!-- /.username -->
                    <?php echo $cmResult[0]['content'] ?>
                  </div>
                <?php
                }
                ?>

                <!-- /.comment-text -->
              </div>
              <!-- /.card-comment -->
            </div>
            <!-- /.card-footer -->
            <div class="card-footer">
              <form action="" method="post">
                <div class="img-push">
                  <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                </div>
              </form>
            </div>
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->
  <!-- Main Footer -->
  <footer class="main-footer" style="margin-left:0px !important">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-outline-default">Logout</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="#">A Programmer</a>.</strong> All rights reserved.
  </footer>
  </div>
  <!-- ./wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
