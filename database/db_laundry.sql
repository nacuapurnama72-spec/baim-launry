CREATE DATABASE IF NOT EXISTS db_laundry CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_laundry;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(255) NOT NULL UNIQUE,
    role ENUM('admin','kasir') NOT NULL DEFAULT 'kasir',
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(255) NOT NULL,
    harga_per_kg INT UNSIGNED NOT NULL,
    estimasi_waktu VARCHAR(255) NOT NULL,
    unit ENUM('kg','pcs') NOT NULL DEFAULT 'kg',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE customers (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    no_whatsapp VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB;

CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi VARCHAR(255) NOT NULL UNIQUE,
    invoice_number VARCHAR(50) NULL UNIQUE,
    customer_id BIGINT UNSIGNED NULL,
    nama_pelanggan VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    id_layanan BIGINT UNSIGNED NOT NULL,
    berat_kg DECIMAL(8,2) NOT NULL,
    total_harga INT UNSIGNED NOT NULL,
    payment_status ENUM('Lunas','Belum Bayar') NOT NULL DEFAULT 'Belum Bayar',
    tanggal_masuk DATE NOT NULL,
    estimasi_selesai DATETIME NOT NULL,
    tanggal_selesai DATETIME NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Diterima',
    catatan TEXT NULL,
    user_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_transaksi_layanan FOREIGN KEY (id_layanan) REFERENCES services(id),
    CONSTRAINT fk_transaksi_customer FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE SET NULL,
    CONSTRAINT fk_transaksi_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO users (name,email,username,role,password) VALUES
('Admin Laundry','admin@gmail.com','admin','admin','$2y$12$5ODVOU3Jx.h9wWnIVGL89uf3J7DJPabcU2qVx0ZM6jy2zbxp8Tbbi'),
('Kasir Laundry','kasir@gmail.com','kasir','kasir','$2y$12$5ODVOU3Jx.h9wWnIVGL89uf3J7DJPabcU2qVx0ZM6jy2zbxp8Tbbi');

INSERT INTO services (nama_layanan,harga_per_kg,estimasi_waktu,unit) VALUES
('Cuci Kering Kiloan',7000,'3-4 hari','kg'),
('Cuci Setrika',12000,'1-2 hari','kg'),
('Express',25000,'5 jam','kg'),
('Satuan / Bedcover',35000,'2 hari','pcs');
