<?php

    require("database.php");

    // Eger zaten giris yapiliysa account'a yonlendiriyor
    session_start();
    if(isset($_SESSION['username']) || isset($_SESSION['tc_no'])){
        header('Location: account.php');
        exit();
    }


    // Employee POST START
    if(isset($_POST['employeeTCInput']) && isset( $_POST['employeeFirstNameInput']) && 
    isset( $_POST['employeeLastNameInput']) && isset( $_POST['employeeGenderInput']) && 
    isset( $_POST['employeeBirthDateInput']) && isset( $_POST['employeeGSMNoInput']) && 
    isset( $_POST['employeeEmailInput']) && isset( $_POST['employeeAddressInput']) && isset( $_POST['employeePasswordInput'])){
        
        $employeeTCInput = $_POST['employeeTCInput'];
        $employeeFirstNameInput = $_POST['employeeFirstNameInput'];
        $employeeLastNameInput = $_POST['employeeLastNameInput'];
        $employeeGenderInput = $_POST['employeeGenderInput'];
        $employeeBirthDateInput = $_POST['employeeBirthDateInput'];
        $employeeGSMNoInput = $_POST['employeeGSMNoInput'];
        $employeeEmailInput = $_POST['employeeEmailInput'];
        $employeeAddressInput = $_POST['employeeAddressInput'];
        $employeePasswordInput = $_POST['employeePasswordInput'];

        if(strlen($employeeTCInput) == 11 && strlen($employeeFirstNameInput) >= 1 && 
        strlen($employeeLastNameInput) >= 1 && ($employeeGenderInput == 'Kadın' || $employeeGenderInput == 'Erkek') && 
        strlen($employeeBirthDateInput) >= 1 && strlen($employeeGSMNoInput) >= 8 && strlen($employeeGSMNoInput) <= 20 && 
        strlen($employeeEmailInput) >= 4 && strlen($employeeAddressInput) >= 10 && strlen($employeePasswordInput) >= 4) {

            $employeeReturn = mysqlInsertemployee($employeeTCInput, $employeeFirstNameInput, $employeeLastNameInput, $employeeGenderInput, $employeeBirthDateInput, $employeeGSMNoInput, $employeeEmailInput, $employeeAddressInput, $employeePasswordInput);

            if($employeeReturn == 1){
                $_SESSION['tc_no'] = $employeeTCInput;
                header('Location: index.php');
                exit();
            }

        }
        else{
            $employeeReturn = -4;
        }

    }
    // Employee POST END

    // Admin POST START
    if(isset($_POST['adminUsernameInput']) && isset( $_POST['adminPasswordInput'] )){
        
        $adminUsernameInput = $_POST['adminUsernameInput'];
        $adminPasswordInput = $_POST['adminPasswordInput'];

        if(strlen($adminUsernameInput) >= 4 && strlen($adminPasswordInput) >= 4){

            $adminReturn = mysqlInsertAdmin($adminUsernameInput, $adminPasswordInput);

            if($adminReturn == 1){
                $_SESSION['username'] = $adminUsernameInput;
                header('Location: index.php');
                exit();
            }

        }
        else{
            $adminReturn = -2;
        }

    }
    // Admin POST END

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt Ol</title>

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
                </ul>

                <hr class="dropdown-divider">
                
                <a class="btn btn-success my-2 my-sm-0 mx-1" href="login.php" role="button">Giriş Yap</a>
                <a class="btn btn-danger my-2 my-sm-0 mx-1" href="signup.php" role="button">Kayıt Ol</a>
            </div>
        </nav>
        <!-- Navbar End -->


        <!-- Tabs Start -->
        <div class="row justify-content-center my-3">
            <div class="col-12 col-md-8">
                <div class="btn-group w-100" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary" id="EmployeeButton" onclick="selectTab('AdminForm')">Personel</button>
                    <button type="button" class="btn btn-outline-primary" id="AdminButton" onclick="selectTab('EmployeeForm')">Admin</button>
                </div>
            </div>
        </div>
        <!-- Tabs End -->

        <!-- Alerts Start -->
        <div class="row justify-content-center mt-4">
            <div class="col-12 col-md-8 col-lg-6">
                <?php if(isset($employeeReturn) && $employeeReturn == 0 ):?>
                    <div class="alert alert-danger" role="alert">
                        Sistem ile bağlanırken hata oluşmuştur. Lütfen bir süre sonra tekrar deneyin!!!
                    </div>
                <?php elseif(isset($employeeReturn) && $employeeReturn == -1 ):?>
                    <div class="alert alert-danger" role="alert">
                        Bu TC no'ya sahip bir çalışan zaten bulunmaktadır!!!
                    </div>
                <?php elseif(isset($employeeReturn) && $employeeReturn == -2 ):?>
                    <div class="alert alert-danger" role="alert">
                        Bu GSM no'ya sahip bir çalışan zaten bulunmaktadır!!!
                    </div>
                <?php elseif(isset($employeeReturn) && $employeeReturn == -3 ):?>
                    <div class="alert alert-danger" role="alert">
                        Bu E-Mail'e sahip bir çalışan zaten bulunmaktadır!!!
                    </div>
                <?php elseif(isset($employeeReturn) && $employeeReturn == -4 ):?>
                    <div class="alert alert-danger" role="alert">
                        Girdileriniz hatalıdır Lütfen kontrol ediniz!!!
                    </div>
                <?php elseif(isset($adminReturn) && $adminReturn == 0 ):?>
                    <div class="alert alert-danger" role="alert">
                        Sistem ile bağlanırken hata oluşmuştur. Lütfen bir süre sonra tekrar deneyin!!!
                    </div>
                <?php elseif(isset($adminReturn) && $adminReturn == -1 ):?>
                    <div class="alert alert-danger" role="alert">
                        Bu kullanıcı adına sahip bir admin bulunmaktadır!!!
                    </div>
                <?php elseif(isset($adminReturn) && $adminReturn == -2 ):?>
                    <div class="alert alert-danger" role="alert">
                        Girdileriniz hatalıdır Lütfen kontrol ediniz!!!
                    </div>
                <?php endif;?>
            </div>
        </div>
        <!-- Alerts End -->

        <!-- Employee Signup Start -->
        <div class="row justify-content-center mb-5 mt-3" style="display: flex;" id="EmployeeForm">
            <div class="col-12 col-md-8 col-lg-6">
                <form action="" method="POST" class="was-validated">
                    <div class="form-group">
                        <label>TC Kimlik Numarası</label>
                        <input type="text" class="form-control is-invalid" minlength="11" maxlength="11" name="employeeTCInput" required>
                    </div>
                    <div class="form-group">
                        <label>Adı</label>
                        <input type="text" class="form-control is-invalid" minlength="1" name="employeeFirstNameInput" required>
                    </div>
                    <div class="form-group">
                        <label>Soyadı</label>
                        <input type="text" class="form-control is-invalid" minlength="1" name="employeeLastNameInput" required>
                    </div>
                    <div class="form-group">
                        <label>Cinsiyeti</label>
                        <select class="form-control is-invalid" name="employeeGenderInput" required>
                            <option>Erkek</option>
                            <option>Kadın</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Doğum Tarihi</label>
                        <input type="date"class="form-control is-invalid" name="employeeBirthDateInput" required>
                    </div>
                    <div class="form-group">
                        <label>GSM Numarası</label>
                        <input type="text" class="form-control is-invalid" minlength="8" maxlength="20" name="employeeGSMNoInput" required>
                    </div>
                    <div class="form-group">
                        <label>E Posta</label>
                        <input type="email" class="form-control is-invalid" minlength="4" name="employeeEmailInput" required>
                    </div>
                    <div class="form-group">
                        <label>Adres</label>
                        <textarea class="form-control is-invalid" minlength="10" name="employeeAddressInput" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Şifre</label>
                        <input type="password" class="form-control is-invalid" minlength="4" name="employeePasswordInput" required>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Kayıt Ol</button>
                </form>
            </div>
        </div>
        <!-- Employee Signup End -->

        <!-- Admin Signup Start -->
        <div class="row justify-content-center mb-5 mt-3" style="display: none;" id="AdminForm">
            <div class="col-12 col-md-8 col-lg-6">
                <form action="" method="POST" class="was-validated">
                    <div class="form-group">
                        <label>Kullanıcı Adı</label>
                        <input type="text" class="form-control is-invalid" minlength="4"  name="adminUsernameInput" required>
                    </div>
                    <div class="form-group">
                        <label>Şifre</label>
                        <input type="password" class="form-control is-invalid" minlength="4" name="adminPasswordInput" required>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Kayıt Ol</button>
                </form>
            </div>
        </div>
        <!-- Admin Signup End -->


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
    <script>
        function selectTab(tabName){
            if(tabName == "EmployeeForm"){
                document.getElementById("AdminForm").style.display = "flex";
                document.getElementById("EmployeeForm").style.display = "none";
                
                document.getElementById("EmployeeButton").classList.replace("btn-primary", "btn-outline-primary");
                document.getElementById("AdminButton").classList.replace("btn-outline-primary", "btn-primary");
            }
            else{
                document.getElementById("AdminForm").style.display = "none";
                document.getElementById("EmployeeForm").style.display = "flex";

                document.getElementById("AdminButton").classList.replace("btn-primary", "btn-outline-primary");
                document.getElementById("EmployeeButton").classList.replace("btn-outline-primary", "btn-primary");
            }
        }
    </script>
</body>
</html>