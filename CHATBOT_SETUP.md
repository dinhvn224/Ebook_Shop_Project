# 🤖 Chatbot AI Gemini với n8n - Hướng dẫn cài đặt

## 📋 Tổng quan

Hệ thống chatbot AI tích hợp Google Gemini API thông qua n8n workflow automation cho website bán sách Laravel.

### 🏗️ Kiến trúc hệ thống

```
Frontend (HTML/CSS/JS) → Laravel Controller → Database Search
                                    ↓ (nếu không tìm thấy)
                                    n8n Workflow → Gemini API
```

## 🚀 Cài đặt

### 1. Yêu cầu hệ thống

- PHP 8.1+
- Laravel 10+
- MySQL 8.0+
- Node.js 16+
- n8n (cài đặt riêng)

### 2. Cài đặt Laravel Backend

#### Bước 1: Cài đặt dependencies
```bash
composer install
npm install
```

#### Bước 2: Cấu hình môi trường
Tạo file `.env` từ `.env.example` và cấu hình:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:ZzoiaPCaZ8Gr2n2mLHirrqba4mgAIf02y64W9zq1VGY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=book_shop_pj
DB_USERNAME=root
DB_PASSWORD=

# Gemini API Key (cho n8n)
GEMINI_API_KEY=your_gemini_api_key_here
```

#### Bước 3: Chạy migrations
```bash
php artisan migrate
```

#### Bước 4: Seed dữ liệu (tùy chọn)
```bash
php artisan db:seed
```

#### Bước 5: Khởi chạy Laravel
```bash
php artisan serve
```

### 3. Cài đặt n8n

#### Bước 1: Cài đặt n8n
```bash
npm install n8n -g
```

#### Bước 2: Khởi chạy n8n
```bash
n8n start
```

n8n sẽ chạy tại: `http://localhost:5678`

#### Bước 3: Import workflow
1. Truy cập n8n tại `http://localhost:5678`
2. Tạo workflow mới
3. Import file `n8n-workflow-chatbot.json`
4. Cấu hình environment variables:
   - `GEMINI_API_KEY`: API key của Google Gemini

### 4. Cấu hình Google Gemini API

#### Bước 1: Tạo API Key
1. Truy cập [Google AI Studio](https://makersuite.google.com/app/apikey)
2. Tạo API key mới
3. Copy API key

#### Bước 2: Cấu hình trong n8n
1. Vào n8n Settings → Environment Variables
2. Thêm biến `GEMINI_API_KEY` với giá trị API key

## 📁 Cấu trúc file

```
Ebook_Shop_Project/
├── app/
│   └── Http/Controllers/
│       └── ChatBotController.php          # Controller xử lý chatbot
├── public/
│   ├── css/
│   │   └── chatbot.css                    # CSS cho chatbot
│   └── js/
│       └── chatbot.js                     # JavaScript cho chatbot
├── resources/views/client/layouts/
│   └── app.blade.php                      # Layout tích hợp chatbot
├── routes/
│   └── web.php                            # Routes cho chatbot
├── n8n-workflow-chatbot.json              # Cấu hình n8n workflow
└── CHATBOT_SETUP.md                       # File hướng dẫn này
```

## 🔧 Cấu hình

### 1. Routes
```php
// routes/web.php
Route::post('/chatbot/webhook', [ChatBotController::class, 'webhook'])->name('chatbot.webhook');
```

### 2. Controller Methods
- `webhook()`: Nhận request từ frontend
- `searchBooks()`: Tìm kiếm sách trong database
- `formatBooks()`: Format kết quả sách

### 3. Frontend Integration
Chatbot được tích hợp tự động vào tất cả trang sử dụng layout `client/layouts/app.blade.php`

## 🎯 Tính năng

### 1. Tìm kiếm thông minh
- Tìm kiếm theo tên sách
- Tìm kiếm theo tác giả
- Tìm kiếm theo thể loại
- Tìm kiếm theo nhà xuất bản
- Tìm kiếm theo giá

### 2. Giao diện hiện đại
- Responsive design
- Typing indicator
- Auto-scroll
- Session management
- Error handling

### 3. AI Integration
- Google Gemini API
- Context-aware responses
- Fallback to database search

## 🧪 Testing

### 1. Test Database Search
```javascript
// Mở browser console và test
fetch('/chatbot/webhook', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        message: 'Tìm sách Harry Potter',
        sessionId: 'test_session',
        timestamp: new Date().toISOString()
    })
})
.then(response => response.json())
.then(data => console.log(data));
```

### 2. Test n8n Workflow
```bash
curl -X POST http://localhost:5678/webhook/chatbot \
  -H "Content-Type: application/json" \
  -d '{
    "message": "Giới thiệu về sách",
    "sessionId": "test_session",
    "timestamp": "2024-01-01T00:00:00.000Z"
  }'
```

## 🐛 Troubleshooting

### 1. Chatbot không hiển thị
- Kiểm tra console browser có lỗi JavaScript
- Đảm bảo file CSS/JS được load đúng
- Kiểm tra CSRF token

### 2. Không kết nối được Laravel
- Kiểm tra Laravel server đang chạy
- Kiểm tra route `/chatbot/webhook`
- Kiểm tra logs Laravel

### 3. n8n không hoạt động
- Kiểm tra n8n server đang chạy
- Kiểm tra environment variables
- Kiểm tra workflow đã được import

### 4. Gemini API lỗi
- Kiểm tra API key hợp lệ
- Kiểm tra quota API
- Kiểm tra network connection

## 📊 Monitoring

### 1. Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### 2. n8n Logs
```bash
n8n start --log-level debug
```

### 3. Browser Console
Mở Developer Tools để xem logs JavaScript

## 🔒 Security

### 1. CSRF Protection
- Tất cả requests đều có CSRF token
- Token được tự động thêm vào headers

### 2. Input Validation
- Sanitize user input
- Validate message length
- Rate limiting (có thể thêm)

### 3. API Security
- Gemini API key được bảo vệ trong environment variables
- Không expose API key trong frontend

## 🚀 Deployment

### 1. Production Environment
```bash
# Build assets
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set production environment
APP_ENV=production
APP_DEBUG=false
```

### 2. n8n Production
```bash
# Sử dụng PM2
pm2 start n8n --name "n8n-chatbot"

# Hoặc Docker
docker run -it --rm \
  --name n8n \
  -p 5678:5678 \
  -e N8N_BASIC_AUTH_ACTIVE=true \
  -e N8N_BASIC_AUTH_USER=admin \
  -e N8N_BASIC_AUTH_PASSWORD=password \
  n8nio/n8n
```

## 📝 Changelog

### Version 1.0.0
- ✅ Tích hợp chatbot cơ bản
- ✅ Database search functionality
- ✅ n8n workflow integration
- ✅ Gemini API integration
- ✅ Responsive UI design

## 🤝 Contributing

1. Fork repository
2. Tạo feature branch
3. Commit changes
4. Push to branch
5. Tạo Pull Request

## 📄 License

MIT License - xem file LICENSE để biết thêm chi tiết.

## 📞 Support

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra troubleshooting section
2. Tạo issue trên GitHub
3. Liên hệ team development

---

**Lưu ý**: Đảm bảo tất cả services (Laravel, n8n, MySQL) đều đang chạy trước khi test chatbot. 
