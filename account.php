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
    else{
        header('Location: index.php');
        exit();
    }

    // Alert'leri GET ile aliyoruz
    if(isset($_GET['passResult'])){
        $passResult = $_GET['passResult'];
    }
    else if(isset($_GET['employeeResult'])){
        $employeeResult = $_GET['employeeResult'];
    }
    else if(isset($_GET['adminResult'])){
        $adminResult = $_GET['adminResult'];
    }

    // Employee POST START
    if(isset($_POST['employeeNewPasswordInput']) && isset($_POST['employeeOldPasswordInput'])){

        $employeeNewPasswordInput = $_POST['employeeNewPasswordInput'];
        $employeeOldPasswordInput = $_POST['employeeOldPasswordInput'];

        if(strlen($employeeNewPasswordInput) >= 4){
            $passResult = mysqlUpdateEmployeePass($tc_no, $employeeOldPasswordInput, $employeeNewPasswordInput);
        }
        else{
            $passResult = -3;
        }

        header("Location: account.php?passResult=".$passResult);
        exit();
    }
    else if(isset($_POST['employeeFirstNameInput']) && isset($_POST['employeeLastNameInput']) && 
    isset($_POST['employeeGSMNoInput']) && isset($_POST['employeeEmailInput']) && 
    isset($_POST['employeeAddressInput'])){

        $employeeFirstNameInput = $_POST['employeeFirstNameInput'];
        $employeeLastNameInput = $_POST['employeeLastNameInput'];
        $employeeGSMNoInput = $_POST['employeeGSMNoInput'];
        $employeeEmailInput = $_POST['employeeEmailInput'];
        $employeeAddressInput = $_POST['employeeAddressInput'];

        if(strlen($employeeFirstNameInput) >= 1 && strlen($employeeLastNameInput) >= 1 && 
        strlen($employeeGSMNoInput) >= 8 && strlen($employeeGSMNoInput) <= 20 && 
        strlen($employeeEmailInput) >= 4 && strlen($employeeAddressInput) >= 10){

            $employeeResult = mysqlUpdateEmployee($tc_no, $employeeFirstNameInput, $employeeLastNameInput, $employeeGSMNoInput, $employeeEmailInput, $employeeAddressInput);

        }
        else{
            $employeeResult = -4;
        }
        header("Location: account.php?employeeResult=".$employeeResult);
        exit();
    }
    // Employee POST END


    // Admin POST START
    if(isset($_POST['adminNewPasswordInput']) && isset($_POST['adminOldPasswordInput'])){

        $adminNewPasswordInput = $_POST['adminNewPasswordInput'];
        $adminOldPasswordInput = $_POST['adminOldPasswordInput'];

        if(strlen($adminNewPasswordInput) >= 4){
            $passResult = mysqlUpdateAdminPass($username, $adminOldPasswordInput, $adminNewPasswordInput);
        } 
        else{
            $passResult = -3;
        }
        header("Location: account.php?passResult=".$passResult);
        exit();
    }
    else if(isset($_POST['adminUsernameInput'])){

        $adminUsernameInput = $_POST['adminUsernameInput'];

        if(strlen($adminUsernameInput) >= 4){

            $adminResult = mysqlUpdateAdmin($username, $adminUsernameInput);

            if($adminResult == 1){
                $_SESSION['username'] = $adminUsernameInput;
            }
        }
        header("Location: account.php?adminResult=".$adminResult);
        exit();
    }
    // Admin POST END

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hesabım</title>

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


        <!-- Alerts Start -->
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-8 col-lg-6">
                <?php if(isset($passResult) && $passResult == 1 ):?>
                    <div class="alert alert-success" role="alert">
                        Şifre başarıyla değiştirildi.
                    </div>
                <?php elseif(isset($passResult) && $passResult == 0 ):?>
                    <div class="alert alert-danger" role="alert">
                        Sistem ile bağlanırken hata oluşmuştur. Lütfen bir süre sonra tekrar deneyin!!!
                    </div>
                <?php elseif(isset($passResult) && $passResult == -2 ):?>
                    <div class="alert alert-danger" role="alert">
                        Şifreniz hatalıdır!!!
                    </div>
                <?php elseif(isset($passResult) && $passResult == -3 ):?>
                    <div class="alert alert-danger" role="alert">
                        Şifreniz en az 4 karakter içermeli!!!
                    </div>
                <?php elseif(isset($adminResult) && $adminResult == 1 ):?>
                    <div class="alert alert-success" role="alert">
                        Kullanıcı adı başarıyla değiştirilmiştir.
                    </div>
                <?php elseif(isset($adminResult) && $adminResult == 0 ):?>
                    <div class="alert alert-danger" role="alert">
                        Sistem ile bağlanırken hata oluşmuştur. Lütfen bir süre sonra tekrar deneyin!!!
                    </div>
                <?php elseif(isset($adminResult) && $adminResult == -2 ):?>
                    <div class="alert alert-danger" role="alert">
                        Yeni kullanıcı adına sahip biri var!!!
                    </div>
                <?php elseif(isset($employeeResult) && $employeeResult == 1 ):?>
                    <div class="alert alert-success" role="alert">
                        Başarıyla değişikler uygulandı.
                    </div>
                <?php elseif(isset($employeeResult) && $employeeResult == 0 ):?>
                    <div class="alert alert-danger" role="alert">
                        Sistem ile bağlanırken hata oluşmuştur. Lütfen bir süre sonra tekrar deneyin!!!
                    </div>
                <?php elseif(isset($employeeResult) && $employeeResult == -2 ):?>
                    <div class="alert alert-danger" role="alert">
                        Girdiğiniz GSM no'ya sahip kişi bulunmaktadır!!!
                    </div>
                <?php elseif(isset($employeeResult) && $employeeResult == -3 ):?>
                    <div class="alert alert-danger" role="alert">
                        Girdiğiniz E-Mail'e sahip kişi bulunmaktadır!!!
                    </div>
                <?php elseif(isset($employeeResult) && $employeeResult == -4 ):?>
                    <div class="alert alert-danger" role="alert">
                        Girdilerinizde hatalıdır Lütfen kontrol ediniz!!!
                    </div>
                <?php endif;?>
            </div>
        </div>
        <!-- Alerts End -->

        <!-- Acc Start -->
        <?php if(isset($username) && isset($admin)):?>

        <!-- Admin Acc Start -->
            <div class="row justify-content-center mt-2 mb-3">
                <div class="col-12 col-md-8 col-lg-6">
                    <form action="" method="POST" class="was-validated">
                        <div class="form-group">
                            <label>Kullanıcı Adı</label>
                            <input type="text" class="form-control is-invalid" minlength="4" name="adminUsernameInput" value="<?php echo $admin['username'];?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Değişikliği Uygula</button>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center my-1">
                <div class="col-12 col-md-8 col-lg-6">
                    <button type="button" class="btn btn-danger my-5 float-left" data-toggle="modal" data-target="#changePassModal">Şifremi Değiştir</button>
                    <button type="button" class="btn btn-danger my-5 float-right" data-toggle="modal" data-target="#delAccModal">Hesabımı Sil</button>
                </div>
            </div>

            <!-- Change Pass Modal -->
            <div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="changePassLabel" aria-hidden="true">
                <form action="" method="POST" class="was-validated">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changePassLabel">Şifre Değiştirme</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Eski Şifre</label>
                                    <input type="password" class="form-control is-invalid" minlength="4" name="adminOldPasswordInput" required>
                                </div>
                                <div class="form-group">
                                    <label>Yeni Şifre</label>
                                    <input type="password" class="form-control is-invalid" minlength="4" name="adminNewPasswordInput" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-primary">Onayla</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Del Acc Modal -->
            <div class="modal fade" id="delAccModal" tabindex="-1" aria-labelledby="delAccLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="delAccLabel">Hesap Silme</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Hesabınızı silmek istediğinizden emin misiniz?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <form action="accountDelete.php" method="POST"><button type="submit" class="btn btn-danger float-right">Hesabımı Sil</button></form>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Admin Acc End -->  

        <?php elseif(isset($tc_no) && isset($employee)):?>

        <!-- Employee Acc Start -->
            <div class="row justify-content-center mt-2 mb-3">
                <div class="col-12 col-md-8 col-lg-6">
                    <form action="" method="POST" class="was-validated">
                        <div class="form-group">
                            <label>Adı</label>
                            <input type="text" class="form-control is-invalid" minlength="1" name="employeeFirstNameInput" value="<?php echo $employee['first_name'];?>" required>
                        </div>
                        <div class="form-group">
                            <label>Soyadı</label>
                            <input type="text" class="form-control is-invalid" minlength="1" name="employeeLastNameInput" value="<?php echo $employee['last_name'];?>" required>
                        </div>
                        <div class="form-group">
                            <label>GSM Numarası</label>
                            <input type="text" class="form-control is-invalid" minlength="8" maxlength="20" name="employeeGSMNoInput" value="<?php echo $employee['gsm_no'];?>" required>
                        </div>
                        <div class="form-group">
                            <label>E Posta</label>
                            <input type="email" class="form-control is-invalid" minlength="4" name="employeeEmailInput" value="<?php echo $employee['e_mail'];?>" required>
                        </div>
                        <div class="form-group">
                            <label>Adres</label>
                            <textarea class="form-control is-invalid" minlength="10" name="employeeAddressInput" rows="3" required><?php echo $employee['address'];?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary float-right">Değişikliği Uygula</button>
                    </form>
                </div>
            </div>
            <div class="row justify-content-center my-1">
                <div class="col-12 col-md-8 col-lg-6">
                    <button type="button" class="btn btn-danger my-5 float-left" data-toggle="modal" data-target="#changePassModal">Şifremi Değiştir</button>
                    <button type="button" class="btn btn-danger my-5 float-right" data-toggle="modal" data-target="#delAccModal">Hesabımı Sil</button>
                </div>
            </div>

            <!-- Change Pass Modal -->
            <div class="modal fade" id="changePassModal" tabindex="-1" aria-labelledby="changePassLabel" aria-hidden="true">
                <form action="" method="POST" class="was-validated">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changePassLabel">Şifre Değiştirme</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Eski Şifre</label>
                                    <input type="password" class="form-control is-invalid" minlength="4" name="employeeOldPasswordInput" required>
                                </div>
                                <div class="form-group">
                                    <label>Yeni Şifre</label>
                                    <input type="password" class="form-control is-invalid" minlength="4" name="employeeNewPasswordInput" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-primary">Onayla</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Del Acc Modal -->
            <div class="modal fade" id="delAccModal" tabindex="-1" aria-labelledby="delAccLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="delAccLabel">Hesap Silme</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Hesabınızı silmek istediğinizden emin misiniz?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                            <form action="accountDelete.php" method="POST"><button type="submit" class="btn btn-danger float-right">Hesabımı Sil</button></form>
                        </div>
                    </div>
                </div>
            </div>    
        <!-- Employee Acc End -->

        <?php endif;?>
        <!-- Acc End -->


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