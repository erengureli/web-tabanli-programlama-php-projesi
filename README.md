# Web Tabanlı Programlama PHP Projesi
Bu projemde BİM marketleri için temsili CRUD web sitesi tasarladım.

[Sitemin Tanıtım Videosunun Linki]()

### Basitçe içindekiler;
 - **Admin Üye Olma**
 - **Admin Üye Girişi**
 - **Personel Üye Olma**
 - **Personel Üye Girişi**
 - **Personelleri Listeleme**
 - **Ürün Ekleme**
 - **Ürün Düzenleme**
 - **Ürün Silme**

 ## XAMPP Servera Nasıl Kurulur

 - Öncelikle MYSQl ve Apache serverlarini açtığımızdan emin olmamız gerekiyor. Bunu yapabilmek için XAMPP'i açıp MYSQL ve Apache yazan kısmın sağındaki **Start** butonlarına basmamız gerekiyor.

 - Serverları açtıktan sonra **http://localhost/phpmyadmin/** linkinden (Eğer dışardaki bir server için ayarlıyorsak localhost yerine serverin ipsini girmemiz gerekiyor) phpmyadmin'e giriyoruz.

 - Burası ilk açılışda şifresiz geliyor ama sizde kullanıcı adı ve şifre soruyorsa onları giriyoruz.

 - Burda projemizin çalışabilmesi için bir database ve içinde table'lar oluşturmamız gerekiyor.

 ```sql
DROP DATABASE IF EXISTS bim_database;

CREATE DATABASE bim_database;
USE bim_database;


CREATE TABLE admin(
    admin_id INT NOT NULL UNIQUE AUTO_INCREMENT,
    username CHAR(255) NOT NULL UNIQUE,
    password CHAR(255) NOT NULL,
    PRIMARY KEY (admin_id)
);

CREATE TABLE employee(
    employee_id INT NOT NULL UNIQUE AUTO_INCREMENT,
    tc_no CHAR(11) NOT NULL UNIQUE,
    first_name CHAR(255) NOT NULL,
    last_name CHAR(255) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    birth_date DATE NOT NULL,
    gsm_no CHAR(20) NOT NULL UNIQUE,
    e_mail CHAR(255) NOT NULL UNIQUE,
    address CHAR(255) NOT NULL,
    password CHAR(255) NOT NULL,
    PRIMARY KEY (employee_id)
);

CREATE TABLE product(
    product_id INT NOT NULL UNIQUE AUTO_INCREMENT,
    product_name CHAR(255) NOT NULL UNIQUE,
    product_amount INT NOT NULL,
    product_price DECIMAL(8, 2) NOT NULL,
    PRIMARY KEY (product_id)
);
 ```

 - Yukarıda verdiğim kodu kopyalayarak **phpmyadmin**'de üst taraftaki SQL sekmesine yapıştırıyoruz.

 - Ve database'imizin adı ne olmasını istiyorsak **bim_database**'e onu yazmamız gerekiyor. Ben bim_database olarak bırakıcam. (**Uyarı:** Eğer aynı isimde başka bir database varsa onu silecektir)

 - Eğer hata almadıysak phpmyadmin'ile işimiz bitmiştir. Şimdi github'daki dosyaları indiriyoruz. (İçindeki README.md dosyası ve sql klasörü gereksiz isterseniz silebilirsiniz.)

 - İndirdiğimiz dosyanın içindeki dosyaların hepsini kopyalayarak **C:\xampp\htdocs** klasörüne atıyoruz. (**Uyarı:** Eğer xampp'ın taşınabilir versiyonunu kullanıyorsanız xampp dosyalarının içindeki **htdocs** klasörüne atacaksınız.)

 - htdocs'a attıktan sonra **database.php** dosyasına giriyoruz. Ve en üstteki define'ların içindekileri değiştiriyoruz. Sırayla yukardan aşağıya doğru;
   - MYSQL serverının IP'si (genelde localhost olarak kalır)
   - MYSQL'de belirlediğimiz kullanıcı ismi
   - MYSQL'de belirlediğimiz şifre
   - MYSQL içinde oluşturduğumuz database ismi (benim yukarıda **bim_database** diye bıraktığım isim)

 - Bunları da ayarladıktan sonra herhangi bir internet sağlayıcısından **http://localhost/** yazarak bağlanabilirsiniz. (Eğer dışardaki bir server için ayarlıyorsak localhost yerine serverin ipsini girmemiz gerekiyor)

 ## Database'in Yapısı

 ![Database](/sql/database.png)

 ## Credits
 - [Bootstrap 4.6](https://getbootstrap.com/docs/4.6/getting-started/introduction/): UI tasarımları için.