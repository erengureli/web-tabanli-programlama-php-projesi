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
    if(isset($_GET['return'])){
        $return = $_GET['return'];
    }

    // Product GET
    if(isset($_GET['product_name_del']) && strlen($_GET['product_name_del']) >= 1 ){ // Product Silme

        $product_name_del = $_GET['product_name_del'];

        $delReturn = mysqlDeleteProduct($product_name_del);

    }
    else if(isset($_GET['product_name_upd']) && strlen($_GET['product_name_upd']) >= 1 ){ // Product Guncelleme

        $product_name_upd = $_GET['product_name_upd'];
        $product_upd = mysqlGetArrayProduct($product_name_upd);

        if(isset($_POST['productNameInput']) && isset($_POST['productAmountInput']) && isset($_POST['productPriceInput'])){

            $productNameInput = $_POST['productNameInput'];
            $productAmountInput = $_POST['productAmountInput'];
            $productPriceInput = $_POST['productPriceInput'];
    
            $return = mysqlUpdateProduct($product_name_upd, $productNameInput, $productAmountInput, $productPriceInput);
    
            if($return == 1){
                header("Location: products.php?return=".$return);
                exit();
            }
    
        }

    }
    else if(isset($_POST['productNameInput']) && isset($_POST['productAmountInput']) && isset($_POST['productPriceInput'])){ // Product Ekleme

        $productNameInput = $_POST['productNameInput'];
        $productAmountInput = $_POST['productAmountInput'];
        $productPriceInput = $_POST['productPriceInput'];

        $return = mysqlInsertProduct($productNameInput, $productAmountInput, $productPriceInput);

        if($return == 1){
            header("Location: products.php?return=".$return);
            exit();
        }

    }

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler</title>

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


        <!-- Form Start -->
        <div class="row justify-content-center my-2">
            <div class="col-12 col-xl-10">
                <form action="" method="POST" class="was-validated">
                    <div class="form-group">
                        <label>Ürün Adı</label>
                        <input type="text" class="form-control is-invalid" minlength="1" name="productNameInput" value="<?php echo (isset($product_upd)) ? ($product_upd['product_name']):('');  ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group">
                                <label>Ürün Miktarı</label>
                                <input type="number" class="form-control is-invalid" minlength="1" step="1" value="<?php echo (isset($product_upd)) ? ($product_upd['product_amount']):('');  ?>" name="productAmountInput" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label>Ürün Fiyatı</label>
                                <input type="number" class="form-control is-invalid" minlength="1" step="0.01" value="<?php echo (isset($product_upd)) ? ($product_upd['product_price']):('');  ?>" name="productPriceInput" required>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($product_name_upd)):?>
                        <a class="btn btn-secondary" href="products.php" role="button">İptal</a>
                        <button type="submit" class="btn btn-primary float-right">Ürünü Kaydet</button>
                    <?php else:?>
                        <button type="submit" class="btn btn-primary float-right">Ürünü Ekle</button>
                    <?php endif;?>
                </form>
            </div>
        </div>
        <!-- Form End -->

        <!-- Alerts Start -->
        <div class="row justify-content-center my-2">
            <div class="col-12 col-xl-10">
                <?php if(isset($delReturn) && $delReturn == 1):?>
                    <div class="alert alert-success" role="alert">
                        Ürün başarıyla silinmiştir!!!
                    </div>
                <?php elseif(isset($return) && $return == 1):?>
                    <div class="alert alert-success" role="alert">
                        Ürün başarıyla eklenmiştir!!!
                    </div>
                <?php elseif(isset($return) && $return == 0):?>
                    <div class="alert alert-success" role="alert">
                        Eklerken hata oluşmuştur!!!
                    </div>
                <?php elseif(isset($return) && $return == -1):?>
                    <div class="alert alert-success" role="alert">
                        Bu isimde bir ürün bulunmaktadır!!!
                    </div>
                <?php endif;?>
            </div>
        </div>
        <!-- Alerts End -->


        <!-- Products List Start -->
        <div class="row justify-content-center my-2">
            <div class="col-12 col-xl-10">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">İsim</th>
                            <th scope="col">Miktar</th>
                            <th scope="col">Fiyat</th>
                            <th scope="col">Düzenle</th>
                            <th scope="col">Sil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                $allProducts = mysqlGetAllArraysProduct();

                                if($allProducts){
                                    foreach($allProducts as $product){

                                        if(isset($product['product_name']) && strlen($product['product_name']) >= 1){
    
                                            echo    '<tr>
                                                        <th scope="row">' . $product['product_id'] . '</th>
                                                        <td>' . $product['product_name'] . '</td>
                                                        <td>' . $product['product_amount'] . '</td>
                                                        <td>' . $product['product_price'] . ' TL</td>
                                                        <td> <a class="btn btn-success" href="products.php?product_name_upd=' . $product['product_name']  . '" role="button">Düzenle</a> </td>
                                                        <td> <a class="btn btn-danger" href="products.php?product_name_del=' . $product['product_name']  . '" role="button">Sil</a> </td>
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
        <!-- Products List End -->


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