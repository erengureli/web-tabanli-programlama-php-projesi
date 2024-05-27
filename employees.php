<?php

    require("database.php");

    // Eger adminse degiskene atiyor degilse index'e gonderiyor
    session_start();
    if(isset($_SESSION['username'])){
        $username = $_SESSION['username'];
        $admin = mysqlGetArrayAdmin($username);
    }
    else{
        header('Location: index.php');
        exit();
    }

    // Basarili olunca sayfayi yenileyince alert verebilmesi icin
    if(isset($_GET['delReturn']) && strlen($_GET['delReturn']) >= 1 ){

        $delReturn = $_GET['delReturn'];

    }

    // Delete POST
    if(isset($_GET['delTC']) && strlen($_GET['delTC']) == 11 ){
        $delReturn = mysqlDeleteEmployee($_GET['delTC']);

        if($delReturn == 1){
            header('Location: employees.php?delReturn=1');
            exit();
        }
    }

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personeller</title>

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

                    <li class="nav-item">
                        <a class="nav-link" href="account.php">Hesap</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="employees.php">Personeller</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Ürünler</a>
                    </li>
                </ul>

                <hr class="dropdown-divider">
                
                <p class="my-auto mr-2">Merhaba, 
                    <?php 
                        echo $admin['username'];
                    ?>
                </p>
                <a class="btn btn-outline-danger my-2 my-sm-0 mx-1" type="submit" href="logout.php" role="button">Çıkış Yap</a>
            </div>
        </nav>
        <!-- Navbar End -->


        <!-- Alerts Start -->
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-xl-10">
                <?php if(isset($delReturn) && $delReturn == 1):?>
                    <div class="alert alert-success" role="alert">
                        Kullanici başarıyla silinmiştir!!!
                    </div>
                <?php elseif(isset($delReturn) && $delReturn == 0):?>
                    <div class="alert alert-danger" role="alert">
                        Sistem ile bağlanırken hata oluşmuştur. Lütfen bir süre sonra tekrar deneyin!!!
                    </div>
                <?php endif;?>
            </div>
        </div>
        <!-- Alerts End -->

        <!-- Employee List Start -->
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-xl-10">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">TC</th>
                            <th scope="col">Adı</th>
                            <th scope="col">Soyadı</th>
                            <th scope="col">Cinsiyeti</th>
                            <th scope="col">Doğum Tarihi</th>
                            <th scope="col">GSM No</th>
                            <th scope="col">E-Mail</th>
                            <th scope="col">Adres</th>
                            <th scope="col">Sil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $allEmployees = mysqlGetAllArraysEmployee();

                                if($allEmployees){
                                    foreach($allEmployees as $employee){

                                        if(isset($employee['tc_no']) && strlen($employee['tc_no']) == 11){
    
                                            $gender = ($employee['gender'] == 'Male') ? ('Erkek') : ('Kadın');
    
                                            echo    '<tr>
                                                        <th scope="row">' . $employee['employee_id'] . '</th>
                                                        <td>' . $employee['tc_no'] . '</td>
                                                        <td>' . $employee['first_name'] . '</td>
                                                        <td>' . $employee['last_name'] . '</td>
                                                        <td>' . $gender . '</td>
                                                        <td>' . $employee['birth_date'] . '</td>
                                                        <td>' . $employee['gsm_no'] . '</td>
                                                        <td>' . $employee['e_mail'] . '</td>
                                                        <td>' . $employee['address'] . '</td>
                                                        <td> <a class="btn btn-danger" href="employees.php?delTC=' . $employee['tc_no']  . '" role="button">Sil</a> </td>
                                                    </tr>';
                                        }
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Employee List End -->


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