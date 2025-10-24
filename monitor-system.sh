#!/bin/bash

# System monitoring script untuk LPK Dashboard
# Jalankan setiap 5 menit dengan cron: */5 * * * * /path/to/monitor-system.sh

LOG_FILE="/var/log/system-monitor.log"
ALERT_EMAIL="admin@yourdomain.com"

# Fungsi untuk log
log_message() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $1" >> $LOG_FILE
}

# Check disk usage
DISK_USAGE=$(df / | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    log_message "WARNING: Disk usage is ${DISK_USAGE}%"
    echo "Disk usage warning: ${DISK_USAGE}%" | mail -s "Disk Usage Alert" $ALERT_EMAIL
fi

# Check memory usage
MEMORY_USAGE=$(free | grep Mem | awk '{printf("%.2f", $3/$2 * 100.0)}')
if (( $(echo "$MEMORY_USAGE > 80" | bc -l) )); then
    log_message "WARNING: Memory usage is ${MEMORY_USAGE}%"
    echo "Memory usage warning: ${MEMORY_USAGE}%" | mail -s "Memory Usage Alert" $ALERT_EMAIL
fi

# Check CPU load
CPU_LOAD=$(uptime | awk -F'load average:' '{print $2}' | awk '{print $1}' | sed 's/,//')
CPU_LOAD_NUM=$(echo $CPU_LOAD | awk '{print $1}')
if (( $(echo "$CPU_LOAD_NUM > 2.0" | bc -l) )); then
    log_message "WARNING: CPU load is ${CPU_LOAD}"
    echo "CPU load warning: ${CPU_LOAD}" | mail -s "CPU Load Alert" $ALERT_EMAIL
fi

# Check PostgreSQL status
if ! systemctl is-active --quiet postgresql; then
    log_message "ERROR: PostgreSQL service is down!"
    echo "PostgreSQL service is down!" | mail -s "PostgreSQL Service Alert" $ALERT_EMAIL
fi

# Check Nginx status
if ! systemctl is-active --quiet nginx; then
    log_message "ERROR: Nginx service is down!"
    echo "Nginx service is down!" | mail -s "Nginx Service Alert" $ALERT_EMAIL
fi

# Check PHP-FPM status
if ! systemctl is-active --quiet php8.2-fpm; then
    log_message "ERROR: PHP-FPM service is down!"
    echo "PHP-FPM service is down!" | mail -s "PHP-FPM Service Alert" $ALERT_EMAIL
fi

# Check Laravel application
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost)
if [ $HTTP_STATUS -ne 200 ]; then
    log_message "WARNING: Laravel application returned HTTP $HTTP_STATUS"
    echo "Laravel application warning: HTTP $HTTP_STATUS" | mail -s "Application Alert" $ALERT_EMAIL
fi

log_message "System monitoring completed successfully"


