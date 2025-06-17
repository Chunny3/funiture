<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>登入</title>

  <!--Font Awesome 圖示與 Google 字體「Nunito」 -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">

  <style>
    .block {
      width: 300px;
      height: 250px;
    }
  </style>
</head>

<body>
  <div class="block bg-primary-subtle p-3 position-absolute start-0 end-0 m-auto rounded-2 mt-5">
    <h1>登入</h1>
    <form action="./doLogin.php" method="post">
      <input type="text" name="email" class="form-control mb-1" placeholder="使用者帳號">
      <input type="password" name="password1" class="form-control mb-1" placeholder="使用者密碼">
      <input type="password" name="password2" class="form-control mb-1" placeholder="再輸入一次使用者密碼">
      <div class="text-end">
        <button class="btn btn-info btn-send me-1">送出</button>
        <a class="btn btn-info btn-send" href="./add.php">註冊</a>
      </div>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq"
    crossorigin="anonymous"></script>
</body>

</html>