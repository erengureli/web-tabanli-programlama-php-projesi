<?php

    require("database.php");

    // Eger giris yaptiysa degiskene atiyor
    session_start();
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $admin = mysqlGetArrayAdmin($username);
    }
    else if(isset($_SESSION['tc_no'])){
        $tc_no = $_SESSION['tc_no'];
        $employee = mysqlGetArrayEmployee($tc_no);
    }

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

</head>
<body>
    <div class="container-md">

        <!-- Navbar Start -->
        <nav class="navbar navbar-expand-md sticky-top-md navbar-light bg-light">
            <a class="navbar-brand" href="index.php">
                <img src="img/bim_logo.png" alt="BIM Logo" height="40"> 
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Ana Sayfa</a>
                    </li>

                    <?php if(isset($username)):?>
                        <li class="nav-item">
                            <a class="nav-link" href="account.php">Hesap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="employees.php">Personeller</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Ürünler</a>
                        </li>
                    <?php elseif(isset($tc_no)):?>
                        <li class="nav-item">
                            <a class="nav-link" href="account.php">Hesap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="products.php">Ürünler</a>
                        </li>
                    <?php endif;?>
                </ul>

                <hr class="dropdown-divider">
                
                <?php if(isset($username)):?>
                    <p class="my-auto mr-2">Merhaba, 
                        <?php 
                            if(isset($admin['username'])){
                                echo $admin['username'];
                            }
                            else {
                                header('Location: logout.php');
                                exit();
                            }
                        ?>
                    </p>
                    <a class="btn btn-outline-danger my-2 my-sm-0 mx-1" type="submit" href="logout.php" role="button">Çıkış Yap</a>
                <?php elseif(isset($tc_no)):?>
                    <p class="my-auto mr-2">Merhaba, 
                        <?php
                            if(isset($employee['first_name']) && isset($employee['last_name'])){
                                echo $employee['first_name'] . " " . $employee['last_name'];
                            }
                            else {
                                header('Location: logout.php');
                                exit();
                            }
                        ?>
                    </p>
                    <a class="btn btn-outline-danger my-2 my-sm-0 mx-1" href="logout.php" role="button">Çıkış Yap</a>
                <?php else:?>
                    <a class="btn btn-success my-2 my-sm-0 mx-1" href="login.php" role="button">Giriş Yap</a>
                    <a class="btn btn-danger my-2 my-sm-0 mx-1" href="signup.php" role="button">Kayıt Ol</a>
                <?php endif;?>
            </div>
        </nav>
        <!-- Navbar End -->


        <!-- Jumbotron Start -->
        <div class="jumbotron">
            <h1 class="display-4">Bizimle çalışmak ister misiniz?</h1>
            <p class="lead">Bizle çalışmak için tek yapmanız gereken küçük bir kayıt formu doldurmak.</p>
            <hr class="my-4">
            <p>Şimdi kayıt olarak işe başlayabilirsiniz.</p>
            <a class="btn btn-primary btn-lg" href="signup.php" role="button">Kayıt Ol</a>
        </div>
        <!-- Jumbotron End -->

        <!-- SSS Start -->
        <div class="row">
            <div class="col my-3">
                <h1 class="text-center">S.S.S.</h1>
            </div>
        </div>
        <div class="row">
            <div class="col my-3">
                <div class="card">
                    <div class="card-header">İşe alındığımı nasıl anlıcam?</div>
                    <div class="card-body">
                        <h5 class="card-title">Cevap:</h5>
                        <p class="card-text">İşe kayıt olunca otomatik alınmış olacaksınız.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col my-3">
                <div class="card">
                    <div class="card-header">Ne kadar maaş alıcam?</div>
                    <div class="card-body">
                        <h5 class="card-title">Cevap:</h5>
                        <p class="card-text">Aylık 100.000 dolar.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- SSS End -->


        <!-- Footer Start -->
        <footer class="my-2 pt-5 pb-4 text-muted text-center text-small bg-light">
            <p class="mb-1">© 2024-2024 BİM</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="https://github.com/erengureli/web-tabanli-programlama-php-projesi">Github</a></li>
                <li class="list-inline-item"><a href="https://github.com/erengureli/web-tabanli-programlama-php-projesi">Github</a></li>
                <li class="list-inline-item"><a href="https://github.com/erengureli/web-tabanli-programlama-php-projesi">Github</a></li>
            </ul>
        </footer>
        <!-- Footer End -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
</body>
</html>