-- Drop the database if it exists
DROP DATABASE IF EXISTS php;
-- Create the database
CREATE DATABASE php;
-- Use the database
USE php;
-- Create the User table
CREATE TABLE User (
    UserId INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    UNIQUE (Username)
);
-- Create the Person table
CREATE TABLE Person (
    PId INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Phone VARCHAR(20),
    Address VARCHAR(255),
    UserId INT,
    FOREIGN KEY (UserId) REFERENCES User(UserId)
);

-- Create the Customer table
CREATE TABLE Customer (
    PId INT AUTO_INCREMENT PRIMARY KEY,
    Service VARCHAR(255),
    FOREIGN KEY (PId) REFERENCES Person(PId)
);

-- Create the Employee table
CREATE TABLE Employee (
    PId INT AUTO_INCREMENT PRIMARY KEY,
    Salary DECIMAL(10, 2),
    FOREIGN KEY (PId) REFERENCES Person(PId)
);

-- Create the Inventory table
CREATE TABLE Inventory (
    InvId INT AUTO_INCREMENT PRIMARY KEY,
    InvName VARCHAR(255),
    InvQuantity INT,
    UserId INT,
    FOREIGN KEY (UserId) REFERENCES User(UserId)
);

-- Create the Supplier table
CREATE TABLE Supplier (
    SupId INT AUTO_INCREMENT PRIMARY KEY,
    SPhone VARCHAR(20),
    SName VARCHAR(255),
    SAddress VARCHAR(255),
    InvId INT,
    FOREIGN KEY (InvId) REFERENCES Inventory(InvId)
);

-- Create the Delivery table
CREATE TABLE Delivery (
    InvId INT,
    SupId INT,
    SQuantity INT,
    SPrice DECIMAL(10, 2),
    PRIMARY KEY (InvId, SupId),
    FOREIGN KEY (InvId) REFERENCES Inventory(InvId),
    FOREIGN KEY (SupId) REFERENCES Supplier(SupId)
);
