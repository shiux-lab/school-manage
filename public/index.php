<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <!-- Meta Start -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>成员管理</title>
    <!-- <link rel="stylesheet" href="static/css/bootstrap3.min.css"> -->
    <!-- Meta End -->

    <!-- Link Start -->
    <link rel="stylesheet" href="static/css/bootstrap.min.css">
    <link rel="stylesheet" href="static/css/all.min.css">
    <link rel="stylesheet" href="static/css/bootstrap-4.min.css">
    <link rel="stylesheet" href="static/css/main.css">
    <!-- Link End -->
</head>

<body>
<!-- App Start -->
<div id="app">
  <?php
  session_start();

  if (empty($_SESSION['admin'][1])) {
    echo '<a class="btn btn-dark" id="login" href="javascript:" @click="loginBtn()"><i class="fas fa-sign-out-alt"></i> 登录</a>';
  } else {
    require_once __DIR__ . '/../view/index.view.php';
  }
  ?>
</div>
<!-- App End -->
<!-- Script Start -->
<script src="static/js/jquery.min.js"></script>
<script src="static/js/sweetalert2.min.js"></script>
<script src="static/js/vue.min.js"></script>
<script src="static/js/popper.min.js"></script>
<script src="static/js/main.js"></script>
<script src="static/js/bootstrap.min.js"></script>
<!--  <script src="static/js/tab.min.js"></script>-->
<!-- Script End -->
</body>

</html>