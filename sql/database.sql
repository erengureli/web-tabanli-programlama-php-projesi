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