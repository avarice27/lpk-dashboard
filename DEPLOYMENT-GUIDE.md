# 🚀 Panduan Lengkap Deployment LPK Dashboard ke Production

## 📋 **Daftar Isi**
1. [Persiapan Server](#persiapan-server)
2. [Instalasi Software](#instalasi-software)
3. [Konfigurasi Database](#konfigurasi-database)
4. [Deploy Laravel Application](#deploy-laravel-application)
5. [Konfigurasi Web Server](#konfigurasi-web-server)
6. [SSL Certificate](#ssl-certificate)
7. [Monitoring & Backup](#monitoring--backup)
8. [Troubleshooting](#troubleshooting)

---

## 🖥️ **Persiapan Server**

### **1.1 VPS/Cloud Server Requirements**
- **OS**: Ubuntu 22.04 LTS atau CentOS 8+
- **RAM**: Minimal 2GB (Recommended: 4GB+)
- **Storage**: Minimal 20GB SSD
- **CPU**: 2 vCPU cores
- **Network**: Public IP dengan akses internet

### **1.2 Domain & DNS**
- Beli domain dari provider (Namecheap, GoDaddy, dll)
- Point A record ke IP server Anda
- Setup subdomain jika diperlukan

---

## 🛠️ **Instalasi Software**

### **2.1 Update System**
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip software-properties-common
```

### **2.2 Install PHP 8.2**
```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common php8.2-mysql php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-bcmath
```

### **2.3 Install PostgreSQL 15**
```bash
sudo apt install -y postgresql postgresql-contrib
sudo systemctl start postgresql
sudo systemctl enable postgresql
```

### **2.4 Install Nginx**
```bash
sudo apt install -y nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

### **2.5 Install Composer**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
```

### **2.6 Install Node.js & NPM**
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

---

## 🗄️ **Konfigurasi Database**

### **3.1 Setup PostgreSQL User & Database**
```bash
sudo -u postgres psql

# Buat user dan database
CREATE USER lpk_user WITH PASSWORD 'your_secure_password';
CREATE DATABASE lpk_dashboard;
GRANT ALL PRIVILEGES ON DATABASE lpk_dashboard TO lpk_user;
\q
```

### **3.2 Konfigurasi PostgreSQL**
```bash
# Copy konfigurasi production
sudo cp postgresql-production.conf /etc/postgresql/15/main/postgresql.conf

# Restart PostgreSQL
sudo systemctl restart postgresql
```

---

## 🚀 **Deploy Laravel Application**

### **4.1 Clone/Upload Project**
```bash
cd /var/www
sudo git clone https://github.com/yourusername/lpk-dashboard.git
sudo chown -R www-data:www-data lpk-dashboard
cd lpk-dashboard
```

### **4.2 Setup Environment**
```bash
# Copy environment file
sudo cp env.production.example .env

# Edit .env file
sudo nano .env

# Generate application key
sudo -u www-data php artisan key:generate

# Set proper permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### **4.3 Install Dependencies & Build**
```bash
# Install Composer dependencies
sudo -u www-data composer install --no-dev --optimize-autoloader

# Install NPM dependencies
sudo -u www-data npm install
sudo -u www-data npm run build
```

### **4.4 Run Migrations & Seeders**
```bash
# Run migrations
sudo -u www-data php artisan migrate --force

# Run seeders (optional)
sudo -u www-data php artisan db:seed --force
```

### **4.5 Optimize Laravel**
```bash
# Clear caches
sudo -u www-data php artisan config:clear
sudo -u www-data php artisan cache:clear
sudo -u www-data php artisan view:clear
sudo -u www-data php artisan route:clear

# Cache for production
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

---

## 🌐 **Konfigurasi Web Server**

### **5.1 Setup Nginx**
```bash
# Copy konfigurasi Nginx
sudo cp nginx.conf /etc/nginx/sites-available/lpk-dashboard

# Enable site
sudo ln -s /etc/nginx/sites-available/lpk-dashboard /etc/nginx/sites-enabled/

# Test konfigurasi
sudo nginx -t

# Restart Nginx
sudo systemctl restart nginx
```

### **5.2 Setup PHP-FPM**
```bash
# Copy konfigurasi PHP-FPM
sudo cp php-fpm.conf /etc/php/8.2/fpm/pool.d/www.conf

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

---

## 🔒 **SSL Certificate**

### **6.1 Install Certbot**
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### **6.2 Generate SSL Certificate**
```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### **6.3 Auto-renewal**
```bash
# Test auto-renewal
sudo certbot renew --dry-run

# Add to crontab
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

---

## 📊 **Monitoring & Backup**

### **7.1 Setup Monitoring**
```bash
# Make scripts executable
sudo chmod +x monitor-system.sh
sudo chmod +x backup-database.sh

# Setup cron jobs
sudo crontab -e

# Add these lines:
*/5 * * * * /var/www/lpk-dashboard/monitor-system.sh
0 2 * * * /var/www/lpk-dashboard/backup-database.sh
```

### **7.2 Setup Firewall**
```bash
sudo chmod +x firewall-rules.sh
sudo ./firewall-rules.sh
```

---

## 🔧 **Troubleshooting**

### **8.1 Common Issues**

#### **Laravel Error 500**
```bash
# Check Laravel logs
sudo tail -f /var/www/lpk-dashboard/storage/logs/laravel.log

# Check permissions
sudo chown -R www-data:www-data /var/www/lpk-dashboard
sudo chmod -R 755 /var/www/lpk-dashboard
```

#### **Database Connection Error**
```bash
# Check PostgreSQL status
sudo systemctl status postgresql

# Check connection
sudo -u postgres psql -d lpk_dashboard -U lpk_user
```

#### **Nginx Error**
```bash
# Check Nginx status
sudo systemctl status nginx

# Check error logs
sudo tail -f /var/log/nginx/error.log
```

### **8.2 Performance Optimization**
```bash
# Enable OPcache
sudo apt install -y php8.2-opcache

# Enable Redis for caching
sudo apt install -y redis-server
sudo systemctl enable redis-server
```

---

## 📱 **Testing Production**

### **9.1 Test Checklist**
- [ ] Website bisa diakses via domain
- [ ] HTTPS redirect berfungsi
- [ ] Database connection berfungsi
- [ ] Login/Register berfungsi
- [ ] File upload berfungsi
- [ ] Email sending berfungsi (jika ada)

### **9.2 Performance Testing**
```bash
# Test response time
curl -w "@curl-format.txt" -o /dev/null -s "https://yourdomain.com"

# Test database performance
sudo -u www-data php artisan tinker
# Test queries di sini
```

---

## 🎯 **Maintenance**

### **10.1 Regular Tasks**
- Monitor system resources setiap hari
- Check backup logs setiap minggu
- Update system packages setiap bulan
- Review security logs setiap minggu

### **10.2 Update Application**
```bash
cd /var/www/lpk-dashboard
sudo git pull origin main
sudo -u www-data composer install --no-dev --optimize-autoloader
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

---

## 📞 **Support & Contact**

Jika mengalami masalah:
1. Check logs terlebih dahulu
2. Restart services yang bermasalah
3. Check system resources
4. Hubungi admin system

---

**🎉 Selamat! LPK Dashboard Anda sudah online di production!**

**URL**: https://yourdomain.com  
**Admin Panel**: https://yourdomain.com/login  
**Database**: PostgreSQL (localhost:5432)  
**Web Server**: Nginx + PHP-FPM 8.2


















