#!/bin/bash

echo "🔥 Mengatur firewall untuk production server..."

# Reset firewall rules
ufw --force reset

# Set default policies
ufw default deny incoming
ufw default allow outgoing

# Allow SSH (port 22)
ufw allow 22/tcp

# Allow HTTP (port 80)
ufw allow 80/tcp

# Allow HTTPS (port 443)
ufw allow 443/tcp

# Allow PostgreSQL (port 5432) - hanya dari internal network
ufw allow from 10.0.0.0/8 to any port 5432
ufw allow from 172.16.0.0/12 to any port 5432
ufw allow from 192.168.0.0/16 to any port 5432

# Enable firewall
ufw --force enable

echo "✅ Firewall berhasil dikonfigurasi!"
echo "📋 Status firewall:"
ufw status numbered


