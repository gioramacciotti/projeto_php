-- Active: 1696892627153@@127.0.0.1@3306@projeto_php
-- Create user table
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL
);

-- Create table for job titles
CREATE TABLE job_title (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL
);

-- Create table for states
CREATE TABLE state (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create table for cities
CREATE TABLE city (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    state_id INT NOT NULL,
    FOREIGN KEY (state_id) REFERENCES state(id)
);

-- Create table for employees
CREATE TABLE employee (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    job_title_id INT NOT NULL,
    city_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    FOREIGN KEY (job_title_id) REFERENCES job_title(id),
    FOREIGN KEY (city_id) REFERENCES city(id)
);

-- Create table for clients
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    city_id INT NOT NULL,
    address VARCHAR(255) NOT NULL,
    FOREIGN KEY (city_id) REFERENCES city(id)
);

-- Create table for orders
CREATE TABLE order (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    employee_id INT NOT NULL,
    order_date DATE NOT NULL,
    FOREIGN KEY (client_id) REFERENCES client(id),
    FOREIGN KEY (employee_id) REFERENCES employee(id)
);


