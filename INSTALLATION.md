# Hướng dẫn cài đặt chi tiết

## Bước 1: Chuẩn bị môi trường

### Windows

1. **Cài đặt XAMPP** (hoặc WAMP/Laragon)
   - Download từ: https://www.apachefriends.org/
   - Cài đặt và khởi động Apache + MySQL

2. **Cài đặt Composer**
   - Download từ: https://getcomposer.org/download/
   - Chạy file cài đặt và làm theo hướng dẫn

3. **Cài đặt Node.js**
   - Download từ: https://nodejs.org/ (chọn LTS version)
   - Cài đặt và kiểm tra: `node -v` và `npm -v`

4. **Cài đặt Git**
   - Download từ: https://git-scm.com/download/win
   - Cài đặt với các tùy chọn mặc định

### Linux (Ubuntu/Debian)

```bash
# Update package list
sudo apt update

# Cài đặt PHP và extensions
sudo apt install php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip

# Cài đặt Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Cài đặt Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Cài đặt MySQL
sudo apt install mysql-server

# Cài đặt Git
sudo apt install git
```

### macOS

```bash
# Cài đặt Homebrew (nếu chưa có)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# Cài đặt PHP
brew install php@8.2

# Cài đặt Composer
brew install composer

# Cài đặt Node.js
brew install node

# Cài đặt MySQL
brew install mysql
brew services start mysql

# Git thường đã có sẵn, nếu không:
brew install git
```

## Bước 2: Clone và cài đặt dự án

```bash
# Clone repository
git clone <repository-url>
cd world-building

# Di chuyển vào thư mục src
cd src

# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install
```

## Bước 3: Cấu hình

### 3.1. Tạo file .env

```bash
# Windows
copy .env.example .env

# Linux/Mac
cp .env.example .env
```

### 3.2. Generate Application Key

```bash
php artisan key:generate
```

### 3.3. Cấu hình Database

Mở file `.env` và chỉnh sửa:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=world_building
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

**Lưu ý:**
- Với XAMPP trên Windows: password thường để trống
- Với MySQL trên Linux/Mac: nhập password bạn đã đặt khi cài đặt

## Bước 4: Tạo Database

### Sử dụng phpMyAdmin (XAMPP)

1. Mở trình duyệt: `http://localhost/phpmyadmin`
2. Click tab "Databases"
3. Nhập tên: `world_building`
4. Chọn Collation: `utf8mb4_unicode_ci`
5. Click "Create"

### Sử dụng MySQL Command Line

```bash
# Đăng nhập MySQL
mysql -u root -p

# Tạo database
CREATE DATABASE world_building CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Thoát
exit;
```

## Bước 5: Chạy Migrations và Seeders

```bash
# Chạy migrations (tạo các bảng)
php artisan migrate

# Chạy seeders (tạo dữ liệu mẫu)
php artisan db:seed
```

**Nếu gặp lỗi và muốn reset lại:**

```bash
php artisan migrate:fresh --seed
```

## Bước 6: Build Assets

```bash
# Build một lần (production)
npm run build

# Hoặc chạy dev mode (tự động rebuild)
npm run dev
```

## Bước 7: Chạy ứng dụng

```bash
php artisan serve
```

Mở trình duyệt và truy cập: `http://127.0.0.1:8000`

## Bước 8: Đăng nhập

Sử dụng một trong các tài khoản sau:

**Super Admin:**
- Email: `admin@example.com`
- Password: `password`

**User thường:**
- Email: `user@example.com`
- Password: `password`

## Xử lý lỗi thường gặp

### Lỗi: "Class not found"

```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Lỗi: "SQLSTATE[HY000] [1045] Access denied"

- Kiểm tra lại username/password trong file `.env`
- Đảm bảo MySQL service đang chạy

### Lỗi: "SQLSTATE[HY000] [2002] Connection refused"

- Kiểm tra MySQL service: 
  - Windows (XAMPP): Mở XAMPP Control Panel, start MySQL
  - Linux: `sudo service mysql start`
  - Mac: `brew services start mysql`

### Lỗi: "npm ERR! code ENOENT"

```bash
# Xóa và cài lại
rm -rf node_modules package-lock.json
npm install
```

### Lỗi: "Vite manifest not found"

```bash
npm run build
```

### Lỗi permissions (Linux/Mac)

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Cấu hình nâng cao

### Chạy với port khác

```bash
php artisan serve --port=8080
```

### Chạy trên network (cho phép truy cập từ máy khác)

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Cấu hình email (nếu cần)

Trong file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Development Tips

### Chạy cả server và vite cùng lúc

**Terminal 1:**
```bash
php artisan serve
```

**Terminal 2:**
```bash
npm run dev
```

### Clear tất cả cache

```bash
php artisan optimize:clear
```

### Xem routes

```bash
php artisan route:list
```

### Tạo dữ liệu test

Sau khi đăng nhập, bạn có thể:
1. Tạo World mới
2. Tạo Entity Types (Character, Location, Item, etc.)
3. Tạo Entities
4. Tạo Relationships giữa các entities
5. Tạo Tags và gán cho entities

## Cập nhật dự án

Khi có phiên bản mới:

```bash
# Pull code mới
git pull origin main

# Cập nhật dependencies
composer install
npm install

# Chạy migrations mới (nếu có)
php artisan migrate

# Build lại assets
npm run build

# Clear cache
php artisan optimize:clear
```

## Backup Database

```bash
# Export database
mysqldump -u root -p world_building > backup.sql

# Import database
mysql -u root -p world_building < backup.sql
```

## Hỗ trợ

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra lại các bước cài đặt
2. Xem phần "Xử lý lỗi thường gặp"
3. Tạo issue trên GitHub với thông tin chi tiết về lỗi
