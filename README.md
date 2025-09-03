# 🌴 Sisawitv2

**Sisawitv2** adalah Sistem Monitoring Produktivitas Kelapa Sawit yang dibangun dengan CodeIgniter 3 menggunakan skema data warehouse (fact constellation) dan lingkungan yang terintegrasi dengan Docker untuk kemudahan pengembangan dan deployment. Sisawit digunakan untuk monitoring hasil panen bulanan & mingguan, kontribusi panen tiap kebun, & aset perkebunan kelapa sawit. 

## Requirement
- PHP 8.0
- MySQL 5.7
- CodeIgniter 3

## ⚙️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/kellychan3/sisawitv2.git
cd sisawitv2
```

### 2. Setup Environment File
Salin file .env.example menjadi .env dan sesuaikan variabelnya:
```bash
cp .env.example .env
```
Hubungi Kelly untuk mendapatkan file .env

### 3. Siapkan SQL Inisialisasi Database
Buat folder inisialisasi dan masukkan file SQL:
```bash
mkdir -p docker/mysql-init
```
Hubungi Kelly untuk mendapatkan file *.sql.

### 4. Install Dependensi PHP
```bash
composer install
```

### 5. Download PDI
Unduh file pdi.zip dari link drive berikut:https://drive.google.com/file/d/1L6jSetbVLe0GjPMxcAAZSqimWy_K5nVh/view?usp=sharing, lalu ekstrak ke dalam folder pentaho/:

### 6. Install Docker Desktop & Jalankan Docker
```bash
docker compose down -v
docker compose up --build
```

### 7. Akses aplikasi:
Buka browser ke: http://localhost:8081
