<?php

$db = new PDO('sqlite:'.__DIR__.'/database.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS transaction_details;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS customers;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  password TEXT NOT NULL,
  role TEXT DEFAULT 'kasir',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  category_id INTEGER,
  name TEXT NOT NULL,
  price REAL NOT NULL,
  stock INTEGER DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE customers (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  phone TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE transactions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER,
  customer_id INTEGER,
  invoice_number TEXT NOT NULL UNIQUE,
  total_amount REAL NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

CREATE TABLE transaction_details (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  transaction_id INTEGER NOT NULL,
  product_id INTEGER NOT NULL,
  quantity INTEGER NOT NULL,
  price REAL NOT NULL,
  subtotal REAL NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (transaction_id) REFERENCES transactions(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE payments (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  transaction_id INTEGER NOT NULL,
  payment_method TEXT NOT NULL,
  amount REAL NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (transaction_id) REFERENCES transactions(id)
);

INSERT INTO users (name, email, password, role) VALUES
('Budi Santoso', 'budi@toko.com', 'secret', 'admin'),
('Siti Rahma', 'siti@toko.com', 'secret', 'kasir'),
('Pak Hendra', 'hendra@toko.com', 'secret', 'owner');

INSERT INTO categories (name) VALUES
('Minuman'), ('Makanan Ringan'), ('Sembako');

INSERT INTO products (category_id, name, price, stock) VALUES
(1, 'Kopi Susu Kekinian', 18000.00, 50),
(1, 'Teh Manis Dingin', 5000.00, 100),
(2, 'Keripik Singkong Pedas', 12000.00, 30),
(3, 'Beras Premium 5kg', 75000.00, 15);

INSERT INTO customers (name, phone) VALUES
('Pelanggan Umum', NULL),
('Rina Wijaya', '081234567890'),
('Doni Pratama', '085711223344');

INSERT INTO transactions (user_id, customer_id, invoice_number, total_amount) VALUES
(2, 2, 'INV-20260923-001', 41000.00);

INSERT INTO transaction_details (transaction_id, product_id, quantity, price, subtotal) VALUES
(1, 1, 2, 18000.00, 36000.00),
(1, 2, 1, 5000.00, 5000.00);

INSERT INTO payments (transaction_id, payment_method, amount) VALUES
(1, 'qris', 41000.00);
");

echo "SUCCESS: Database created and populated successfully.\n";
