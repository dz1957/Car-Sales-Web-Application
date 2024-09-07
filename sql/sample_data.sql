-- static tables
INSERT INTO MANUFACTURER (Manufacturer) VALUES
('Acura'), ('FIAT'), ('Lamborghini'), ('Nio'), 
('Alfa'), ('Romeo'), ('Ford'), ('Land'), ('Rover'), 
('Porsche'), ('Aston'), ('Martin'), ('Greeley'), ('Lexus'), 
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

-- Users
INSERT INTO USER (Username, Userpassword, Firstname, LastName, User_type) VALUES
('admin@example.com', 'adminpass', 'Admin', 'User', 'Admin'),
('manager1@example.com', 'managerpass', 'Manager', 'One', 'Manager'),
('salesperson1@example.com', 'salespass', 'Sales', 'Person', 'Salesperson'),
('clerk1@example.com', 'clerkpass', 'Inventory', 'Clerk', 'Inventory Clerk');

INSERT INTO  MANAGER (Username) VALUES ('manager1@example.com');
INSERT INTO  SALESPEOPLE (Username) VALUES ('salesperson1@example.com');
INSERT INTO  INVENTORYCLERKER (Username) VALUES ('clerk1@example.com');

-- customers related
INSERT INTO CUSTOMER (Email, Phone_number, Street, City, State, Postal_code) VALUES
('customer_ind1@example.com', '1234567890', '123 Main St', 'Cityville', 'CA', '12345'),
('customer_ind2@example.com', '9876543210', '456 Oak St', 'Townsville', 'NY', '54321'),
('customer_bss1@example.com', '1234567890', '123 Main St', 'Cityville', 'CA', '12345'),
('customer_bss2@example.com', '9876543210', '456 Oak St', 'Townsville', 'NY', '54321');

INSERT INTO INDIVIDUAL (Customer_ID, License_ID, Firstname, LastName) VALUES
(1, 'ABC123', 'John', 'Doe'),
(2, 'XYZ789', 'Jane', 'Smith');

INSERT INTO BUSINESS (Customer_ID, TIN, Business_name, Primary_title, Primary_name) VALUES
(3, '123456789', 'Doe Enterprises', 'CEO', 'John Doe'),
(4, '987654321', 'Smith Corp', 'President', 'Jane Smith');


-- Vehicle related

INSERT INTO VEHICLE (VIN, Vehicle_type, Manufacturer, Model_year, Model, Fuel_type, Mileage, Vehicle_description, Seller_ID, Clerker_username, Vehicle_condition, Purchase_date, Purchase_price) VALUES
('12345678901234567', 'Sedan', 'Toyota', 2022, 'Camry', 'Gas', 15000, 'A reliable sedan', 2, 'clerk1@example.com', 'Excellent', '2023-01-15', 25000.00),
('98765432109876543', 'SUV', 'Ford', 2021, 'Explorer', 'Hybrid', 20000, 'A spacious SUV', 1, 'clerk1@example.com', 'Very Good', '2023-02-20', 35000.00),
('11223344556677889', 'Other', 'Jeep', 2011, 'Wrangler', 'Gas', 23000, 'A strange Jeep', 3, 'clerk1@example.com', 'Good', '2020-02-20', 15000.00);

-- Insert sample data into VEHICLE_COLOR table
INSERT INTO VEHICLE_COLOR (VIN, Color) VALUES
('12345678901234567', 'Blue'),
('98765432109876543', 'Silver'),
('11223344556677889', 'Red');



-- Insert sample data into VENDOR table
INSERT INTO VENDOR (Vendor_name, Phone_number, Street, City, State, Postal_code) VALUES
('AutoParts Inc.', '1112223333', '456 Parts St', 'Parts City', 'TX', '54321'),
('CarAccessories Ltd.', '4445556666', '789 Acc St', 'Access Town', 'CA', '12345');

-- Insert sample data into PARTS_ORDER table
set @ord = (SELECT count(Order_ordinal ) FROM PARTS_ORDER where VIN = "12345678901234567");
INSERT INTO PARTS_ORDER (Order_ordinal, VIN, Clerker_username, Vendor_name) VALUES
(Concat("12345678901234567", '-', CONVERT(@ord+1, CHAR)), '12345678901234567', 'clerk1@example.com', 'AutoParts Inc.');
INSERT INTO PARTS (VIN, Order_ordinal, Parts_number, Parts_quantity, Unit_price, Parts_status, Parts_description) VALUES
('12345678901234567', Concat("12345678901234567", '-', CONVERT(@ord+1, CHAR)), '123ABC', 2, 50.00, 'Received', 'Replacement tires'),
('12345678901234567', Concat("12345678901234567", '-', CONVERT(@ord+1, CHAR)), '456ABC', 1, 300.00, 'Received', 'Replacement mats');

set @ord = (SELECT count(Order_ordinal) FROM PARTS_ORDER where VIN = "98765432109876543");
INSERT INTO PARTS_ORDER (Order_ordinal, VIN, Clerker_username, Vendor_name) VALUES
(Concat("98765432109876543", '-', CONVERT(@ord+1, CHAR)), '98765432109876543', 'clerk1@example.com', 'CarAccessories Ltd.');
INSERT INTO PARTS (VIN, Order_ordinal, Parts_number, Parts_quantity, Unit_price, Parts_status, Parts_description) VALUES
('98765432109876543', Concat("98765432109876543", '-', CONVERT(@ord+1, CHAR)), '456XYZ', 1, 100.00, 'Installed', 'Roof rack');

