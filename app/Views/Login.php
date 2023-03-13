<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?= $title; ?></title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/login.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

</head>

<body>
  <div class="container-fluid">
    <!-- Kiri -->
    <div class="row">
      <div class="col-md-6 col-sm-12 login z-2">

        <div class="row">
          <div class="col">
            <div class="judul ms-3 mt-3 mb-5 me-5">
              <h5><i class="bi bi-dot"></i>Rajasa Finishing</h5>
            </div>
          </div>
        </div>

        <div class="row mt-5 ms-5 me-5">
          <div class="col ">
            <h2>Login Page</h2>
            Please enter your details!
            <hr>
          </div>
        </div>

        <div class="row ms-5 mb-3 me-5">
          <div class="col">
            <form action="/login/auth" method="post" enctype="">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control shadow-sm" id="email" name="email">
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control shadow-sm" id="password" name="password">
              </div>
              <div class="mb-3 text-end">
                <a href="" class="forgot_password">Forgot Password</a>
              </div>
              <button type="submit" class="submit shadow-sm">Sign In</button>
              <button href="" type="button" class="google mt-2 shadow-sm"><i class="bi bi-google"></i>&nbsp;&nbsp;Login with Google</button>
            </form>
          </div>
        </div>

        <div class="row mt-5 ms-5 me-5">
          <div class="col text-center">
            Don't have an account? <a class="signup">Sign Up</a>
          </div>
        </div>

        <div class="row d-sm-none d-md-block">
          <div class="col footer ms-3">
            <h6>© Rajasa Finishing 2023</h6>
          </div>
        </div>

      </div>
      <!-- akhir kiri -->

      <!-- kanan -->
      <div class="col-6 logo position-relative d-sm-none d-md-block">
        <img src="Logo.png" alt="" srcset="" class="position-absolute top-50 start-50 translate-middle" style="width: 35%;">
        <div class="row">
          <div class="col z-1 blur">
          </div>
        </div>
      </div>
      <!-- akhir kanan -->
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>