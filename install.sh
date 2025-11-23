#!/bin/bash

# ==============================
# Demon Panel Auto Installer
# For: VPS / GitHub Codespaces / CodeSandbox
# Repo: https://github.com/cyberxdevsofficial/demon
# Owner: Anuga Senithu De Silva
# ==============================

GREEN="\033[1;32m"
NC="\033[0m"

echo -e "${GREEN}==== Demon Panel Auto Installer ====${NC}"
echo "Detecting environment..."

# Detect Codespaces / Codesandbox
if [[ "$CODESPACES" == "true" ]]; then
    ENV="codespaces"
elif grep -qi "codesandbox" /etc/hostname 2>/dev/null; then
    ENV="codesandbox"
else
    ENV="vps"
fi

echo "Environment detected: $ENV"
echo "Updating system packages..."

sudo apt update -y
sudo apt upgrade -y

echo -e "${GREEN}Installing dependencies...${NC}"

sudo apt install -y \
    nginx \
    php php-cli php-fpm php-mysql php-zip php-gd php-curl php-mbstring php-xml \
    unzip git curl composer

# ==================================================================
# CodeSandbox limitation handler
# ==================================================================
if [[ "$ENV" == "codesandbox" ]]; then
    echo -e "${GREEN}[Codesandbox Detected] Skipping systemctl/nginx start${NC}"
    SKIPSYSTEMD=true
else
    SKIPSYSTEMD=false
fi

# ==================================================================
# Download panel
# ==================================================================
echo -e "${GREEN}Downloading Demon Panel...${NC}"

sudo rm -rf /var/www/demon
sudo git clone https://github.com/cyberxdevsofficial/demon /var/www/demon

cd /var/www/demon

echo -e "${GREEN}Installing Composer dependencies...${NC}"
composer install --no-dev

cp .env.example .env 2>/dev/null

echo -e "${GREEN}Generating app key...${NC}"
php artisan key:generate

sudo chown -R www-data:www-data /var/www/demon
sudo chmod -R 775 /var/www/demon/storage /var/www/demon/bootstrap/cache

# ==================================================================
# Nginx Config
# ==================================================================
echo -e "${GREEN}Configuring nginx...${NC}"

NGINX_CONF="/etc/nginx/sites-available/demon.conf"

sudo bash -c "cat > $NGINX_CONF" <<EOL
server {
    listen 80 default_server;
    server_name _;

    root /var/www/demon/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOL

if [[ "$SKIPSYSTEMD" == false ]]; then
    sudo ln -sf /etc/nginx/sites-available/demon.conf /etc/nginx/sites-enabled/demon.conf
    sudo systemctl restart nginx
    sudo systemctl restart php*-fpm 2>/dev/null
fi

# ==================================================================
# URL Detection
# ==================================================================
if [[ "$ENV" == "codespaces" ]]; then
    URL="https://${CODESPACE_NAME}-80.${GITHUB_CODESPACES_PORT_FORWARDING_DOMAIN}"
elif [[ "$ENV" == "codesandbox" ]]; then
    URL=$(hostname -I | awk '{print $1}')
    URL="http://$URL:8080"
else
    URL=$(curl -s ifconfig.me)
    URL="http://$URL"
fi

# ==================================================================
# Done
# ==================================================================
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN} Demon Panel Installed Successfully! ${NC}"
echo -e "URL: ${GREEN}$URL${NC}"
echo -e "Document Root: /var/www/demon"
echo -e "Admin Login: /login"
echo -e "${GREEN}=========================================${NC}"
