# Production Setup Guide for ISP Jabbar 🚀

Panduan ini untuk setup VPS production yang siap handle **10k user** dengan performa maksimal menggunakan Redis Cache & Queue.

## 1. Rekomendasi VPS (Pilih Salah Satu)

### Option A: IDCloudHost (Lokal Indonesia) ✅ **RECOMMENDED**
*   **Paket:** Cloud M (atau Cloud S minimal)
*   **Specs:** 4 vCPU, 8GB RAM
*   **OS:** Ubuntu 22.04 / 24.04 LTS
*   **Kenapa:** Latency kecil, support lokal, bayar Rupiah.

### Option B: Contabo (Murah tapi Luar)
*   **Paket:** Cloud VPS 20
*   **Specs:** 6 vCPU, 12GB RAM
*   **OS:** Ubuntu 22.04 LTS
*   **Kenapa:** Value for money, tapi latency lebih tinggi (~100ms).

---

## 2. Install Server Stack (Ubuntu)

Jalankan perintah ini satu per satu di terminal VPS (as root):

```bash
# Update system
apt update && apt upgrade -y

# Install Nginx, Git, Zip, Unzip, Curl, Redis
apt install -y nginx git zip unzip curl redis-server supervisor

# Install PHP 8.2 & Extensions
add-apt-repository ppa:ondrej/php -y
apt update
apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-curl php8.2-zip php8.2-intl php8.2-gd php8.2-redis

# Install MySQL / MariaDB
apt install -y mariadb-server
mysql_secure_installation
```

---

## 3. Configure Redis for Performance

Edit file config Redis:
`nano /etc/redis/redis.conf`

Cari dan ubah baris ini:
```conf
supervised systemd
maxmemory 256mb
maxmemory-policy allkeys-lru
```

Restart Redis:
```bash
systemctl restart redis.service
```

---

## 4. Setup Project (Laravel)

```bash
# Clone Repo (Ganti URL dengan repo Mas)
cd /var/www
git clone https://github.com/velora-1d/isp-jabbar23-web.git isp-jabbar

# Setup Permissions
chown -R www-data:www-data /var/www/isp-jabbar
chmod -R 775 /var/www/isp-jabbar/storage

# Install Dependencies
cd /var/www/isp-jabbar
cp .env.example .env
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan storage:link
```

---

## 5. Konfigurasi .env (PENTING!)

Edit `.env` dan pastikan setting ini:

```env
APP_ENV=production
APP_DEBUG=false

# Cache & Session pakai Redis biar ngebut 🚀
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Config Redis Predis
REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## 6. Setup Supervisor (Queue Worker)

Worker ini yang akan kerjain tugas berat di background (kirim WA, generate invoice, dll) biar user gak nunggu loading lama.

Buat file config:
`nano /etc/supervisor/conf.d/isp-jabbar-worker.conf`

Isi dengan:
```ini
[program:isp-jabbar-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/isp-jabbar/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/isp-jabbar/storage/logs/worker.log
stopwaitsecs=3600
```

Start Worker:
```bash
supervisorctl reread
supervisorctl update
supervisorctl start isp-jabbar-worker:*
```

---

## 7. Optimasi Database & Nginx

Jangan lupa:
1.  **Cache Config:** `php artisan config:cache`
2.  **Cache Route:** `php artisan route:cache`
3.  **Cache View:** `php artisan view:cache`

Selamat! Sistem ISP Jabbar sekarang siap handle traffic tinggi! 🚀
