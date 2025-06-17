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

  <!-- 外層-->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <!--登入表單,卡片元件-->
        <div class="ccard o-hidden border-0 shadow-lg my-5.">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">

                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                </div>
                <form class="user">
                  <div class="form-group">
                    <input type="email" class="form-control form-control-user" id="exampleInputEmail"
                      aria-describedby="emailHelp" placeholder="Email">
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-user" id="exampleInputPassword"
                      placeholder="Password">
                  </div>
                  <div class="form-group">
                    <!-- <div class="custom-control custom-checkbox small">
                      <input type="checkbox" class="custom-control-input" id="customCheck">
                      <label class="custom-control-label" for="customCheck">Remember
                        Me</label>
                    </div> -->
                  </div>
                  <a href="index.html" class="btn btn-primary btn-user btn-block">
                    Login
                  </a>
                  <hr>
                  <!-- <a href="index.html" class="btn btn-google btn-user btn-block">
                    <i class="fab fa-google fa-fw"></i> Login with Google
                  </a>
                  <a href="index.html" class="btn btn-facebook btn-user btn-block">
                    <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                  </a> -->
                </form>
                <hr>
                <!-- <div class="text-center">
                  <a class="small" href="forgot-password.html">Forgot Password?</a>
                </div> -->
                <div class="text-center">
                  <a class="small" href="register.html">Create an Account!</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>







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