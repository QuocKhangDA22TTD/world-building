# World Building Application

Ứng dụng quản lý thế giới ảo (World Building) được xây dựng bằng Laravel 12 và Tailwind CSS 4.

## Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- Node.js >= 18.x và npm
- MySQL >= 5.7 hoặc MariaDB
- Git

## Hướng dẫn cài đặt

### 1. Clone dự án

```bash
git clone <repository-url>
cd world-building
```

### 2. Cài đặt dependencies

```bash
# Di chuyển vào thư mục src
cd src

# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install
```

### 3. Cấu hình môi trường

```bash
# Copy file .env.example thành .env
copy .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Cấu hình database

Mở file `.env` và cập nhật thông tin database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=world_building
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Tạo database

Tạo database mới trong MySQL:

```sql
CREATE DATABASE world_building CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Chạy migrations và seeders

```bash
# Chạy migrations để tạo các bảng
php artisan migrate

# Chạy seeders để tạo dữ liệu mẫu (roles và users)
php artisan db:seed
```

### 7. Build assets

```bash
# Build CSS và JS
npm run build

# Hoặc chạy dev mode (tự động rebuild khi có thay đổi)
npm run dev
```

### 8. Chạy ứng dụng

```bash
php artisan serve
```

Truy cập: `http://127.0.0.1:8000`

## Tài khoản mặc định

Sau khi chạy seeder, bạn có thể đăng nhập với các tài khoản sau:

### Super Admin
- Email: `admin@example.com`
- Password: `password`

### User thường
- Email: `user@example.com`
- Password: `password`

## Cấu trúc dự án

```
world-building/
├── src/                    # Thư mục chính của Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/    # Controllers
│   │   │   └── Middleware/     # Middleware
│   │   ├── Models/             # Models
│   │   └── Policies/           # Authorization Policies
│   ├── database/
│   │   ├── migrations/         # Database migrations
│   │   └── seeders/            # Database seeders
│   ├── resources/
│   │   ├── views/              # Blade templates
│   │   ├── css/                # CSS files
│   │   └── js/                 # JavaScript files
│   └── routes/
│       └── web.php             # Web routes
└── README.md
```

## Chức năng chính

### Backend
- ✅ Authentication & Authorization (3 roles: super_admin, admin, user)
- ✅ CRUD Worlds (Thế giới)
- ✅ CRUD Entity Types (Loại thực thể)
- ✅ CRUD Entities (Thực thể)
- ✅ CRUD Relationships (Quan hệ giữa các thực thể)
- ✅ CRUD Tags (Nhãn)
- ✅ Liên kết Entity-Tag (Many-to-Many)
- ✅ Tìm kiếm và lọc theo type, tag, tên
- ✅ Xem quan hệ của entity

### Frontend
- ✅ Dashboard với thống kê
- ✅ Quản lý Users (cho admin/super_admin)
- ✅ Màn hình World chi tiết
- ✅ Màn hình Entity Types
- ✅ Màn hình Entities với tìm kiếm & filter
- ✅ Màn hình Relationships
- ✅ Màn hình Tags
- ✅ Responsive design với Tailwind CSS

## Database Schema

### Bảng chính

1. **roles** - Vai trò người dùng (super_admin, admin, user)
2. **users** - Người dùng
3. **worlds** - Thế giới (mỗi user có thể tạo nhiều worlds)
4. **entity_types** - Loại thực thể (Character, Location, Item, etc.)
5. **entities** - Thực thể trong world
6. **relationships** - Quan hệ giữa các entities
7. **tags** - Nhãn để phân loại entities
8. **entity_tags** - Bảng pivot cho many-to-many relationship

## Lệnh hữu ích

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Reset database (xóa tất cả và chạy lại migrations + seeders)
php artisan migrate:fresh --seed

# Chạy tests (nếu có)
php artisan test

# Build production assets
npm run build
```

## Troubleshooting

### Lỗi "Class not found"
```bash
composer dump-autoload
```

### Lỗi permissions (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### Lỗi npm
```bash
rm -rf node_modules package-lock.json
npm install
```

### Lỗi database connection
- Kiểm tra MySQL service đang chạy
- Kiểm tra thông tin trong file `.env`
- Đảm bảo database đã được tạo

## Công nghệ sử dụng

- **Backend**: Laravel 12
- **Frontend**: Blade Templates, Tailwind CSS 4
- **Database**: MySQL
- **Build Tool**: Vite
- **Authentication**: Laravel built-in Auth

## License

MIT License

## Liên hệ

Nếu có vấn đề hoặc câu hỏi, vui lòng tạo issue trên GitHub.
