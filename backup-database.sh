#!/bin/bash

# Database backup script untuk LPK Dashboard
# Jalankan dengan cron job: 0 2 * * * /path/to/backup-database.sh

# Konfigurasi
DB_NAME="your_database_name"
DB_USER="your_username"
BACKUP_DIR="/var/backups/postgresql"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="$BACKUP_DIR/lpk_dashboard_$DATE.sql"
RETENTION_DAYS=30

# Buat direktori backup jika belum ada
mkdir -p $BACKUP_DIR

echo "🗄️ Memulai backup database $DB_NAME..."

# Backup database
pg_dump -U $DB_USER -h localhost $DB_NAME > $BACKUP_FILE

if [ $? -eq 0 ]; then
    echo "✅ Backup berhasil: $BACKUP_FILE"

    # Compress backup file
    gzip $BACKUP_FILE
    echo "📦 File berhasil di-compress: $BACKUP_FILE.gz"

    # Hapus backup lama (lebih dari 30 hari)
    find $BACKUP_DIR -name "*.sql.gz" -mtime +$RETENTION_DAYS -delete
    echo "🧹 Backup lama berhasil dihapus"

    # Log backup
    echo "$(date): Backup database berhasil - $BACKUP_FILE.gz" >> /var/log/database-backup.log

else
    echo "❌ Backup gagal!"
    echo "$(date): Backup database gagal" >> /var/log/database-backup.log
    exit 1
fi

echo "🎯 Backup selesai pada $(date)"


