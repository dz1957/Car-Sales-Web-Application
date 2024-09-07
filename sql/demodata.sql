-- read customer info;
DROP TEMPORARY TABLE IF EXISTS table_new_01;
CREATE TEMPORARY TABLE table_new_01(
  customer_id INT NOT NULL AUTO_INCREMENT,
  customer_type VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(255) NOT NULL,
  street VARCHAR(255) NOT NULL,
  city VARCHAR(255) NOT NULL,
  state VARCHAR(255) NOT NULL,
  postal VARCHAR(255) NOT NULL,
  biz_tax_id VARCHAR(255) NOT NULL,
  biz_name VARCHAR(255) NOT NULL,
  biz_contact_first VARCHAR(255) NOT NULL,
  biz_contact_last VARCHAR(255) NOT NULL,
  biz_contact_title VARCHAR(255) NOT NULL,
  driver_lic VARCHAR(255) NOT NULL,
  person_first VARCHAR(255) NOT NULL,
  person_last VARCHAR(255) NOT NULL,
  PRIMARY KEY(customer_id)
);

-- SHOW VARIABLES LIKE "secure_file_priv";
LOAD DATA INFILE 'C:/ProgramData/MySQL/MySQL Server 8.1/Uploads/customers.tsv'
INTO TABLE `table_new_01`
FIELDS TERMINATED BY '\t'
ENCLOSED BY ''
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(customer_type,  email,  phone,  street,  city,  state,  postal,
  biz_tax_id,  biz_name,  biz_contact_first,  biz_contact_last,  biz_contact_title,
  driver_lic,  person_first,  person_last);
-- SELECT * FROM table_new_01;

INSERT INTO customer(Customer_id, Customer_type, Email, Phone_number, Street ,City, State, Postal_code) 
SELECT Customer_id, Customer_type, email,phone,street,city,state,postal FROM table_new_01;

INSERT INTO individual(customer_id, license_id, firstname, lastname)
SELECT customer_id, driver_lic, person_first, person_last FROM table_new_01
WHERE customer_type = 'Person';

INSERT INTO business(customer_id, tin, business_name, primary_title, primary_name)
SELECT customer_id, biz_tax_id, biz_name, biz_contact_title, CONCAT(biz_contact_first,' ',biz_contact_last) FROM table_new_01
WHERE customer_type = 'Business';

-- read users info;
DROP TEMPORARY TABLE IF EXISTS table_new_02;
CREATE TEMPORARY TABLE table_new_02 (
    Username VARCHAR(50) NOT NULL,
    Userpassword VARCHAR(30) NOT NULL,
    Firstname VARCHAR(30) NOT NULL,
    Lastname VARCHAR(30) NOT NULL,
    User_type VARCHAR(50) NOT NULL,
    PRIMARY KEY (Username)
);

LOAD DATA INFILE 'C:/ProgramData/MySQL/MySQL Server 8.1/Uploads/users.tsv'
INTO TABLE `table_new_02`
FIELDS TERMINATED BY '\t'
ENCLOSED BY ''
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(username, userpassword, firstname, lastname, user_type);

UPDATE table_new_02 SET user_type = 'Owner' WHERE user_type = 'inventory clerk,salesperson,manager';
UPDATE table_new_02 SET user_type = 'Inventory Clerk' WHERE user_type = 'inventory clerk';
UPDATE table_new_02 SET user_type = 'Salesperson' WHERE user_type = 'salesperson';
UPDATE table_new_02 SET user_type = 'Manager' WHERE user_type = 'manager';

INSERT INTO user (username, userpassword, firstname, lastname, user_type) 
SELECT username, userpassword, firstname, lastname, user_type FROM table_new_02;
INSERT INTO manager SELECT username FROM table_new_02 WHERE user_type LIKE '%manager%' or user_type LIKE '%Owner%';
INSERT INTO salespeople SELECT username FROM table_new_02 WHERE user_type LIKE '%salesperson%' or user_type LIKE 'Owner';
INSERT INTO inventoryclerker SELECT username FROM table_new_02 WHERE user_type LIKE '%inventory clerk%' or user_type LIKE 'Owner';

-- read vehicle info;
DROP TEMPORARY TABLE IF EXISTS table_new_03;
CREATE TEMPORARY TABLE table_new_03 (
  VIN VARCHAR(17) NOT NULL PRIMARY KEY,
  model_name VARCHAR(255) NOT NULL,
  year INT NOT NULL,
  description TEXT NOT NULL,
  manufacturer_name VARCHAR(255) NOT NULL,
  condition_ VARCHAR(255) NOT NULL,
  vehicle_type VARCHAR(255) NOT NULL,
  odometer INT NOT NULL,
  fuel_type VARCHAR(255) NOT NULL,
  colors VARCHAR(255) NOT NULL,
  purchase_date DATE NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  purchased_from_customer VARCHAR(255) NOT NULL,
  purchase_clerk VARCHAR(255) NOT NULL,
  sale_date DATE DEFAULT NULL,
  sold_to_customer VARCHAR(255),
  salesperson VARCHAR(255) 
);

LOAD DATA INFILE 'C:/ProgramData/MySQL/MySQL Server 8.1/Uploads/vehicles.tsv'
INTO TABLE `table_new_03`
FIELDS TERMINATED BY '\t'
ENCLOSED BY ''
LINES TERMINATED BY '\n'
IGNORE 1 LINES
(VIN, model_name, year, description, manufacturer_name, condition_,	vehicle_type, odometer,
	fuel_type, colors, purchase_date,	price,	purchased_from_customer, purchase_clerk,
	@sale_date,	@sold_to_customer,	@salesperson)
    SET sale_date = NULLIF(@sale_date,''),
    sold_to_customer = NULLIF(@sold_to_customer,''),
    salesperson = NULLIF(@salesperson,'');

-- static tables
INSERT INTO MANUFACTURER (Manufacturer) VALUES
('Acura'), ('FIAT'), ('Lamborghini'), ('Nio'), 
('Alfa Romeo'), ('Ford'), ('Land Rover'), 
('Porsche'), ('Aston Martin'), ('Geeley'), ('Lexus'), 
('Ram'), ('Audi'), ('Genesis'), ('Lincoln'), ('Rivian'),
 ('Bentley'), ('GMC'), ('Lotus'), ('Rolls-Royce'), 
('BMW'), ('Honda'), ('Maserati'), ('smart'), ('Buick'), 
('Hyundai'), ('MAZDA'), ('Subaru'), ('Cadillac'), ('INFINITI'), 
('McLaren'), ('Tesla'), ('Chevrolet'), ('Jaguar'), ('Mercedes-Benz'), 
('Toyota'), ('Chrysler'), ('Jeep'), ('MINI'), ('Volkswagen'), 
('Dodge'), ('Karma'), ('Mitsubishi'), ('Volvo'),
 ('Ferrari'), ('Kia'), ('Nissan'), ('XPeng');
-- Insert sample data into VEHICLETYPE table
INSERT INTO VEHICLETYPE (Vehicle_type) VALUES
('Sedan'),
('Coupe'),
('Convertible'),
('Truck'),
('Van'),
('Minivan'),
('SUV'),
('Other');
INSERT INTO Vehicle (VIN, Vehicle_type, Manufacturer, Model_year, Model, Fuel_type, Mileage, Vehicle_description, 
Seller_ID, Clerker_username, Vehicle_condition, Purchase_date, Purchase_price)
SELECT VIN, vehicle_type, manufacturer_name, year, model_name, fuel_type, odometer, description,
customer_id, purchase_clerk, condition_, purchase_date, price FROM table_new_03 as new03 LEFT OUTER JOIN table_new_01 as new01
ON new03.purchased_from_customer = new01.biz_tax_id OR new03.purchased_from_customer = new01.driver_lic;

INSERT INTO BUYS (VIN, Buyer_ID, Salespeople_username, Sale_date )
SELECT VIN, customer_id, salesperson, sale_date FROM table_new_03 as new03 LEFT OUTER JOIN table_new_01 as new01
ON new03.sold_to_customer = new01.biz_tax_id OR new03.sold_to_customer = new01.driver_lic
WHERE customer_id IS NOT NULL;

INSERT INTO vehicle_color
SELECT VIN, SUBSTRING_INDEX(SUBSTRING_INDEX(colors, ',', n.counts+1), ',', -1) as color
FROM table_new_03 AS new03 INNER JOIN (SELECT 0 counts UNION ALL SELECT 1 UNION ALL SELECT 2) AS n 
ON LENGTH(colors) - LENGTH(REPLACE(colors, ',' , '')) >= n.counts;

-- vendor info;
LOAD DATA INFILE 'C:/ProgramData/MySQL/MySQL Server 8.1/Uploads/vendors.tsv'
INTO TABLE `Vendor`
FIELDS TERMINATED BY '\t'
ENCLOSED BY ''
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

-- parts info;
DROP TEMPORARY TABLE IF EXISTS table_new_04;
CREATE TEMPORARY TABLE table_new_04 (
	VIN VARCHAR(17) NOT NULL,
	order_num VARCHAR(20) NOT NULL,
    Vendor_name VARCHAR(30) NOT NULL,
    part_number VARCHAR(30) NOT NULL,
    description VARCHAR(50) NOT NULL,
    price FLOAT NOT NULL,
    status VARCHAR(20) NOT NULL,
    qty INT,
    PRIMARY KEY (VIN , order_num, part_number)
);

LOAD DATA INFILE 'C:/ProgramData/MySQL/MySQL Server 8.1/Uploads/parts.tsv'
INTO TABLE `table_new_04`
FIELDS TERMINATED BY '\t'
ENCLOSED BY ''
LINES TERMINATED BY '\n'
IGNORE 1 LINES;

INSERT INTO Parts_order (VIN, order_ordinal, vendor_name) SELECT DISTINCT VIN,order_num, vendor_name FROM table_new_04;
INSERT INTO Parts (VIN,  Order_ordinal,  Parts_number,  Parts_quantity,  Unit_price,  Parts_status,  Parts_description)
SELECT VIN, order_num, part_number, qty, price, 
CASE WHEN status='installed' THEN 'Installed' WHEN status='received' THEN 'Received' WHEN status='ordered' THEN 'Ordered' END AS status_, description
FROM table_new_04;

DROP TEMPORARY TABLE IF EXISTS table_new_01;
DROP TEMPORARY TABLE IF EXISTS table_new_02;
DROP TEMPORARY TABLE IF EXISTS table_new_03;
DROP TEMPORARY TABLE IF EXISTS table_new_04;