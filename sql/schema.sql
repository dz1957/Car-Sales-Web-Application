-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech019';

DROP DATABASE IF EXISTS `cs6400_fa23_team019`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

-- CREATE DATABASE
CREATE DATABASE IF NOT EXISTS cs6400_fa23_team019 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_fa23_team019;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_fa23_team019`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

-- TABLES
-- user and customer tables
CREATE TABLE USER (
    Username VARCHAR(50) NOT NULL,
    Userpassword VARCHAR(30) NOT NULL,
    Firstname VARCHAR(30) NOT NULL,
    Lastname VARCHAR(30) NOT NULL,
    User_type ENUM('Manager', 'Salesperson', 'Inventory Clerk', 'Owner'),
    PRIMARY KEY (Username)
);

CREATE TABLE MANAGER (
    Username VARCHAR(50) NOT NULL,
    PRIMARY KEY (Username),
    FOREIGN KEY (Username)
        REFERENCES USER (Username)
);

CREATE TABLE SALESPEOPLE (
    Username VARCHAR(50) NOT NULL,
    PRIMARY KEY (Username),
    FOREIGN KEY (Username)
        REFERENCES USER (Username)
);

CREATE TABLE INVENTORYCLERKER (
    Username VARCHAR(50) NOT NULL,
    PRIMARY KEY (Username),
    FOREIGN KEY (Username)
        REFERENCES USER (Username)
);

CREATE TABLE CUSTOMER (
    Customer_ID INT NOT NULL AUTO_INCREMENT,
    Email VARCHAR(40) NOT NULL,
    Phone_number VARCHAR(10) NOT NULL,
    Street VARCHAR(100) NOT NULL,
    City VARCHAR(30) NOT NULL,
    State VARCHAR(2) NOT NULL,
    Postal_code VARCHAR(5) NOT NULL,
    Customer_type VARCHAR(10) NOT NULL,
    PRIMARY KEY (Customer_ID)
);

CREATE TABLE INDIVIDUAL (
    Customer_ID INT NOT NULL,
    License_ID VARCHAR(30) NOT NULL,
    Firstname VARCHAR(30) NOT NULL,
    Lastname VARCHAR(30) NOT NULL,
    PRIMARY KEY (License_ID),
    UNIQUE(Customer_ID),
    FOREIGN KEY (Customer_ID)
        REFERENCES CUSTOMER (Customer_ID)
);

CREATE TABLE BUSINESS (
    Customer_ID INT NOT NULL,
    TIN VARCHAR(10) NOT NULL,
    Business_name VARCHAR(50) NOT NULL,
    Primary_title VARCHAR(30) NOT NULL,
    Primary_name VARCHAR(30) NOT NULL,
    PRIMARY KEY (TIN),
    UNIQUE(Customer_ID),
    FOREIGN KEY (Customer_ID)
        REFERENCES CUSTOMER (Customer_ID)
);

-- Vehicles related tables

CREATE TABLE MANUFACTURER (
    Manufacturer VARCHAR(30) NOT NULL,
    PRIMARY KEY (Manufacturer)
);

CREATE TABLE VEHICLETYPE (
    Vehicle_type VARCHAR(30) NOT NULL,
    PRIMARY KEY (Vehicle_type)
);

CREATE TABLE VEHICLE (
    VIN VARCHAR(17) NOT NULL,
    Vehicle_type VARCHAR(30) NOT NULL,
    Manufacturer VARCHAR(30) NOT NULL,
    Model_year YEAR NOT NULL,
    Model VARCHAR(50) NOT NULL,
    Fuel_type ENUM('Gas', 'Diesel', 'Natural Gas', 'Hybrid', 'Plugin Hybrid', 'Battery', 'Fuel Cell'),
    Mileage FLOAT NOT NULL,
    Vehicle_description VARCHAR(200) NULL,
    Seller_ID INT NOT NULL,
    Clerker_username VARCHAR(50) NOT NULL,
    Vehicle_condition ENUM('Excellent', 'Very Good', 'Good', 'Fair'),
    Purchase_date DATE NOT NULL,
    Purchase_price  DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (VIN),
    FOREIGN KEY (Manufacturer)
        REFERENCES MANUFACTURER (Manufacturer),
    FOREIGN KEY (Vehicle_type)
        REFERENCES VEHICLETYPE (Vehicle_type),
    FOREIGN KEY (Seller_ID)
        REFERENCES CUSTOMER (Customer_ID),
    FOREIGN KEY (Clerker_username)
        REFERENCES INVENTORYCLERKER (Username)
);

CREATE TABLE VEHICLE_COLOR (
    VIN VARCHAR(17) NOT NULL,
    Color ENUM('Aluminum', 'Beige', 'Black', 'Blue', 'Brown', 'Bronze', 'Claret', 'Copper', 'Cream', 'Gold', 'Gray', 'Green', 'Maroon', 'Metallic', 'Navy', 'Orange', 'Pink', 'Purple', 'Red', 'Rose', 'Rust', 'Silver', 'Tan', 'Turquoise', 'White', 'Yellow'),
    UNIQUE (VIN , Color),
    FOREIGN KEY (VIN)
        REFERENCES VEHICLE (VIN)
);

-- Vendor related tables

CREATE TABLE VENDOR (
    Vendor_name VARCHAR(30) NOT NULL,
    Phone_number VARCHAR(10) NOT NULL,
    Street VARCHAR(100) NOT NULL,
    City VARCHAR(30) NOT NULL,
    State VARCHAR(2) NOT NULL,
    Postal_code VARCHAR(5) NOT NULL,
    PRIMARY KEY (Vendor_name)
);

CREATE TABLE PARTS_ORDER (
    Order_ordinal VARCHAR(20) NOT NULL,
    VIN VARCHAR(17) NOT NULL,
    Clerker_username VARCHAR(40),
    Vendor_name VARCHAR(30) NOT NULL,
    PRIMARY KEY (VIN , Order_ordinal),
    FOREIGN KEY (Clerker_username)
        REFERENCES INVENTORYCLERKER (Username),
    FOREIGN KEY (Vendor_name)
        REFERENCES VENDOR (Vendor_name),
    FOREIGN KEY (VIN)
        REFERENCES VEHICLE (VIN)
);

CREATE TABLE PARTS (
    VIN VARCHAR(17) NOT NULL,
    Order_ordinal VARCHAR(20) NOT NULL,
    Parts_number VARCHAR(30) NOT NULL,
    Parts_quantity INT NOT NULL,
    Unit_price  DECIMAL(10,2) NOT NULL,
    Parts_status ENUM('Ordered', 'Received', 'Installed'),
    Parts_description VARCHAR(200) NULL,
    PRIMARY KEY (VIN , Order_ordinal , Parts_number),
    FOREIGN KEY (VIN , Order_ordinal)
        REFERENCES PARTS_ORDER (VIN , Order_ordinal)
);

-- relationship related tables :

CREATE TABLE BUYS (
    VIN VARCHAR(17) NOT NULL,
    Buyer_ID INT NOT NULL,
    Salespeople_username VARCHAR(50) NOT NULL,
    Sale_date DATE NOT NULL,
    PRIMARY KEY (VIN , Buyer_ID , Salespeople_username),
    FOREIGN KEY (VIN)
        REFERENCES VEHICLE (VIN),
    FOREIGN KEY (Salespeople_username)
        REFERENCES SALESPEOPLE (username),
    FOREIGN KEY (Buyer_ID)
        REFERENCES CUSTOMER (Customer_ID)
);
       
SHOW tables;
