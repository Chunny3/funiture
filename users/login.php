<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>會員登入</title>
  <!--Font Awesome 圖示與 Google 字體「Nunito」 -->
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
  <link href="../css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>

  <div style="min-height: 100vh;">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
      <!-- 左側圖片 -->
      <div class="col-lg-6 text-center mb-4 mb-lg-0 d-flex align-items-center justify-content-center"
        style="min-height: 100vh;">
        <img src="../index/img/login.jpg" alt="登入圖片" class="img-fluid"
          style="height: 100vh; width: 100%; object-fit: cover;">
      </div>
    
      <!-- 右側登入表單 -->
      <div class="col-lg-6">
        <div class="p-5 d-flex justify-content-center align-items-center" style="height: 100vh;">
          <div class="w-100" style="max-width: 350px; margin: auto;">
            <div class="text-center">
              <h1 class="h4 text-gray-900 mb-4">Welcome  Back  Oak!y</h1>
            </div>
            <form class="user" action="./doLogin.php" method="post">
              <div class="form-group mb-3">
                <input type="text" name="email" class="form-control form-control-user" placeholder="Email"
                  required>
              </div>
              <div class="form-group mb-3">
                <input type="password" name="password" class="form-control form-control-user" placeholder="Password"
                  required>
              </div>
              <button type="submit" class="btn btn-secondary btn-user btn-block">
                登入
              </button>
              <a href="./register.php" class="btn btn-secondary btn-user btn-block">註冊新帳號</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../js/sb-admin-2.min.js"></script>
</body>

</html>