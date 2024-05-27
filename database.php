<?php

    define("MYSQL_SERVER", "localhost");
    define("MYSQL_USERNAME", "root");
    define("MYSQL_PASSWORD", "");
    define("MYSQL_DB", "bim_database");
    

    // Kolayca MYSQL'e baglanmak icin fonksiyon
    function connectMysql(){
        $mysqli = new mysqli(MYSQL_SERVER, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DB);

        return $mysqli;
    }



    /********** Admins **********/ 


    // Array -> Basarili
    // false -> Hata olustu
    // Verilen username'e sahip admin'in ozelliklerini getiriyor
    function mysqlGetArrayAdmin($username) {

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM admin WHERE username = ?;");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $mysqli->close();

        if($result->num_rows == 1) return $result->fetch_array(MYSQLI_ASSOC);
        
        return false;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir admin var
    // Verilen bilgileri admin olarak kaydediyor
    function mysqlInsertAdmin($username, $password){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM admin WHERE username = ?;");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $hashedPass =  password_hash($password, PASSWORD_BCRYPT);

        $stmt = $mysqli->prepare("INSERT INTO admin(username, password) VALUES ( ?, ?)");
        $stmt->bind_param("ss", $username, $hashedPass);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;
        
        return 0;
    }

    // 1 -> Basarili
    // 0 -> Hatali sifre
    // -1 -> Boyle bir kullanici yok
    // Verilen username ve password ile admin var mi diye kontrol ediyor
    function mysqlLoginAdmin($username, $password){

        $admin = mysqlGetArrayAdmin($username);

        if(!$admin) return -1;

        if (password_verify($password, $admin['password'])) return 1;
        
        return 0;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir admin yok
    // -2 -> Yeni username'e sahip biri var
    // Verilen username'i degistiriyor
    function mysqlUpdateAdmin($username, $newUsername){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM admin WHERE username = ?;");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 0){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $stmt = $mysqli->prepare("SELECT * FROM admin WHERE username = ?;");
        $stmt->bind_param("s", $newUsername);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -2;
        }

        $stmt = $mysqli->prepare("UPDATE admin SET username = ? WHERE username = ?;");
        $stmt->bind_param("ss", $newUsername, $username);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;
        
        return 0;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir admin yok
    // -2 -> Sifre hatali
    // Verilen username'deki password'u kontrol ederek degistiriyor
    function mysqlUpdateAdminPass($username, $password, $newpass){

        $admin = mysqlGetArrayAdmin($username);

        if(!$admin) return -1;

        if (password_verify($password, $admin['password'])){

            $mysqli = connectMysql();

            $hashedPass =  password_hash($newpass, PASSWORD_BCRYPT);

            $stmt = $mysqli->prepare("UPDATE admin SET password = ? WHERE username = ?;");
            $stmt->bind_param("ss", $hashedPass, $username);
            $result = $stmt->execute();

            $stmt->close();
            $mysqli->close();

            if($result) return 1;

            return 0;
        }

        return -2;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir admin yok
    // Verilen username'deki admini siliyor
    function mysqlDeleteAdmin($username){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM admin WHERE username = ?;");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 0){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $stmt = $mysqli->prepare("DELETE FROM admin WHERE username = ?;");
        $stmt->bind_param("s", $username);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;
        
        return 0;
    }



    /********** Employees **********/ 

    // Array -> Basarili
    // false -> Hata olustu
    // Verilen tc_no'ya sahip employee'nin ozelliklerini getiriyor
    function mysqlGetArrayEmployee($tc_no) {

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM employee WHERE tc_no = ?;");
        $stmt->bind_param("s", $tc_no);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $mysqli->close();

        if($result->num_rows == 1) return $result->fetch_array(MYSQLI_ASSOC);
        
        return false;
    }

    // Array -> Basarili
    // false -> Hata olustu
    // Butun employee'leri cekiyor
    function mysqlGetAllArraysEmployee() {

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM employee;");
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $mysqli->close();

        if($result->num_rows >= 1) return $result->fetch_all(MYSQLI_ASSOC);
        
        return false;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir employee var
    // -2 -> Bu gsm_no'ya sahip employee var
    // -3 -> Bu e_mail'e sahip employee var
    // Verilen bilgileri employee olarak kaydediyor
    function mysqlInsertEmployee($tc_no, $first_name, $last_name, $gender, $birth_date, $gsm_no, $e_mail, $address, $password) {

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM employee WHERE tc_no = ?;");
        $stmt->bind_param("s", $tc_no);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $stmt = $mysqli->prepare("SELECT * FROM employee WHERE gsm_no = ? AND NOT tc_no = ?;");
        $stmt->bind_param("ss", $gsm_no, $tc_no);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -2;
        }

        $stmt = $mysqli->prepare("SELECT * FROM employee WHERE e_mail = ? AND NOT tc_no = ?;");
        $stmt->bind_param("ss", $e_mail, $tc_no);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -3;
        }

        $hashedPass =  password_hash($password, PASSWORD_BCRYPT);
        $genderCode = ($gender == 'Erkek') ? ('Male'):('Female');

        $stmt = $mysqli->prepare("INSERT INTO employee(tc_no, first_name, last_name, gender, birth_date, gsm_no, e_mail, address, password) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $tc_no, $first_name, $last_name, $genderCode, $birth_date, $gsm_no, $e_mail, $address, $hashedPass);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;

        return 0;
    }

    // 1 -> Basarili
    // 0 -> Hatali sifre
    // -1 -> Boyle bir employee yok
    // Verilen tc_no ve password ile kullanici var mi diye kontrol ediyor
    function mysqlLoginEmployee($tc_no, $password){

        $employee = mysqlGetArrayEmployee($tc_no);

        if(!$employee) return -1;

        if (password_verify($password, $employee['password'])) return 1;
        
        return 0;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir employee yok
    // -2 -> gsm_no ayni olan employee var
    // -3 -> e_mail ayni olan employee var
    // Verilen tc_no'daki employee'nin bilgilerini guncelliyor
    function mysqlUpdateEmployee($tc_no, $first_name, $last_name, $gsm_no, $e_mail, $address){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM employee WHERE tc_no = ?;");
        $stmt->bind_param("s", $tc_no);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 0){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $stmt = $mysqli->prepare("SELECT * FROM employee WHERE gsm_no = ? AND NOT tc_no = ?;");
        $stmt->bind_param("ss", $gsm_no, $tc_no);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -2;
        }

        $stmt = $mysqli->prepare("SELECT * FROM employee WHERE e_mail = ? AND NOT tc_no = ?;");
        $stmt->bind_param("ss", $e_mail, $tc_no);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -3;
        }

        $stmt = $mysqli->prepare("UPDATE employee SET first_name = ?, last_name = ?, gsm_no = ?, e_mail = ?, address = ? WHERE tc_no = ?;");
        $stmt->bind_param("ssssss", $first_name, $last_name, $gsm_no, $e_mail, $address, $tc_no);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;
        
        return 0;
    }

    // 1 -> Basarili
    // 0 -> Hatali sifre
    // -1 -> Boyle bir employee yok
    // Verilen tc_no'daki employee'nin sifresini degistiriyor
    function mysqlUpdateEmployeePass($tc_no, $oldpass, $newpass){

        $employee = mysqlGetArrayEmployee($tc_no);

        if(!$employee) return -1;

        if (password_verify($oldpass, $employee['password'])){

            $mysqli = connectMysql();

            $hashedPass =  password_hash($newpass, PASSWORD_BCRYPT);

            $stmt = $mysqli->prepare("UPDATE employee SET password = ? WHERE tc_no = ?;");
            $stmt->bind_param("ss", $hashedPass, $tc_no);
            $result = $stmt->execute();

            $stmt->close();
            $mysqli->close();

            if($result) return 1;
            
            return 0;
        }

        return -2;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir employee yok
    // Verilen tc_no'daki employee'yi siliyor
    function mysqlDeleteEmployee($tc_no){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM employee WHERE tc_no = ?;");
        $stmt->bind_param("s", $tc_no);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 0){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $stmt = $mysqli->prepare("DELETE FROM employee WHERE tc_no = ?;");
        $stmt->bind_param("s", $tc_no);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;
        
        return 0;
    }



    /********** Products **********/ 


    // Array -> Basarili
    // false -> Hata olustu
    // Verilen product_name'e sahip product ozelliklerini getiriyor
    function mysqlGetArrayProduct($product_name){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM product WHERE product_name = ?;");
        $stmt->bind_param('s', $product_name);
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $mysqli->close();

        if($result->num_rows == 1) return $result->fetch_array(MYSQLI_ASSOC);
        
        return false;
    }

    // Array -> Basarili
    // false -> Hata olustu
    // Butun product'lari getiriyior
    function mysqlGetAllArraysProduct(){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM product;");
        $stmt->execute();
        $result = $stmt->get_result();

        $stmt->close();
        $mysqli->close();

        if($result->num_rows >= 1) return $result->fetch_all(MYSQLI_ASSOC);
        
        return false;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir product bulunmaktadir
    // Verilen bilgileri product olarak ekliyor
    function mysqlInsertProduct($product_name, $product_amount, $product_price){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM product WHERE product_name = ?;");
        $stmt->bind_param("s", $product_name);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $stmt = $mysqli->prepare("INSERT INTO product(product_name, product_amount, product_price) VALUES ( ?, ?, ?)");
        $stmt->bind_param("sid", $product_name, $product_amount, $product_price);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;
        
        return 0;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir product bulunmamaktdir
    // -2 -> Yeni product_name'e sahip biri vardir
    // Verilen product_name'deki product'in ozelliklerini guncelliyor
    function mysqlUpdateProduct($product_name, $new_product_name, $product_amount, $product_price){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM product WHERE product_name = ?;");
        $stmt->bind_param("s", $product_name);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 0){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $stmt = $mysqli->prepare("SELECT * FROM product WHERE product_name = ? AND NOT product_name = ? ;");
        $stmt->bind_param("ss", $new_product_name, $product_name);
        $stmt->execute();
        if($stmt->get_result()->num_rows == 1){
            $stmt->close();
            $mysqli->close();
            return -2;
        }

        $stmt = $mysqli->prepare("UPDATE product SET product_name = ?, product_amount = ?, product_price = ? WHERE product_name = ?;");
        $stmt->bind_param("sids", $new_product_name, $product_amount, $product_price, $product_name);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;
        
        return 0;
    }

    // 1 -> Basarili
    // 0 -> Hata olustu
    // -1 -> Boyle bir product yok
    // Verilen product_name'deki product'i siliyor
    function mysqlDeleteProduct($product_name){

        $mysqli = connectMysql();

        $stmt = $mysqli->prepare("SELECT * FROM product WHERE product_name = ?;");
        $stmt->bind_param("s", $product_name);
        $stmt->execute();
        if($stmt->get_result()->num_rows != 1){
            $stmt->close();
            $mysqli->close();
            return -1;
        }

        $stmt = $mysqli->prepare("DELETE FROM product WHERE product_name = ?;");
        $stmt->bind_param("s", $product_name);
        $result = $stmt->execute();

        $stmt->close();
        $mysqli->close();

        if($result) return 1;
        
        return 0;

    }
