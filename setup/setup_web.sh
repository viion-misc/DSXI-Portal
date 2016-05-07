#!/usr/bin/env bash
cd /dsxi/setup
source /dsxi/setup/colors.sh

# Start
Heading "++ Setting up DSXI ++"

# initialize
USER=vagrant
cd /dsxi
sudo locale-gen en_GB.UTF-8

# Settings
mysqlPassword="dsxi"
mysqlDbUser="dsxi"
mysqlDbPass="dsxi"
mysqlDbName="dsxi"

# - - - - - - - - - - - - - - - - - -
# INSTALLATION
# - - - - - - - - - - - - - - - - - -

Title "INSTALLATION"
# latest PHP 7.0 packages
Text "Updating"
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update

# Default Password
Text "Setting Default MySQL Passwords"
echo "mysql-server mysql-server/root_password password $mysqlPassword" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password $mysqlPassword" | debconf-set-selections

# - - - - - - - - - - - - - - - - - -
# Install everything
# - - - - - - - - - - - - - - - - - -
Text "Installing Stuff"
sudo apt-get install -y htop unzip curl nginx
sudo apt-get install -y php-apcu php7.0-dev php7.0-cli php7.0-json php7.0-fpm php7.0-intl php7.0-mysql php7.0-curl php7.0-mcrypt php7.0-gd php7.0-mbstring

# - - - - - - - - - - - - - - - - - -
# Setup MySQL
# - - - - - - - - - - - - - - - - - -
#
Title "SETUP DATABASE"
sudo mysql --defaults-file=/etc/mysql/debian.cnf -e "GRANT ALL PRIVILEGES ON *.* TO '$mysqlDbUser'@'localhost';"
sudo mysql --defaults-file=/etc/mysql/debian.cnf -e "FLUSH PRIVILEGES;"

Text "Creating Database"
sudo mysql --defaults-file=/etc/mysql/debian.cnf -e "CREATE DATABASE phpmyadmin;"
sudo mysql --defaults-file=/etc/mysql/debian.cnf -e "GRANT ALL ON phpmyadmin.* TO '$mysqlDbUser'@'localhost' IDENTIFIED BY '$mysqlDbPass';"
sudo mysql --defaults-file=/etc/mysql/debian.cnf -e "FLUSH PRIVILEGES;"

# - - - - - - - - - - - - - - - - - -
# Composer
# - - - - - - - - - - - - - - - - - -
Text "Installing composer (global)"
sudo curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# not installed PHP dependencies? do so now
if [ ! -d "/dsxi/vendor" ]; then
    sudo -u $USER composer install
fi

# - - - - - - - - - - - - - - - - - -
# Nginx Host
# - - - - - - - - - - - - - - - - - -
Text "Copying NGINX host"
sudo rm /etc/nginx/sites-available/default
sudo cp /dsxi/setup/nginx/default /etc/nginx/sites-available/default
sudo cp /dsxi/setup/nginx/nginx.conf /etc/nginx/nginx.conf
sudo service nginx restart

# - - - - - - - - - - - - - - - - - -
# PHP SETTINGS
# - - - - - - - - - - - - - - - - - -
Text "Updating PHP Settings"
sudo sed -i 's|;date.timezone =|date.timezone = Europe/London|' /etc/php/7.0/fpm/php.ini
sudo sed -i 's|display_errors = Off|display_errors = On|' /etc/php/7.0/fpm/php.ini
sudo sed -i 's|upload_max_filesize = 2M|upload_max_filesize = 64M|' /etc/php/7.0/fpm/php.ini
sudo sed -i 's|post_max_size = 8M|post_max_size = 64M|' /etc/php/7.0/fpm/php.ini
sudo sed -i 's|max_execution_time = 30|max_execution_time = 300|' /etc/php/7.0/fpm/php.ini
sudo sed -i 's|;request_terminate_timeout = 0|request_terminate_timeout = 300|' /etc/php/7.0/fpm/pool.d/www.conf
sudo sed -i 's|;pm.process_idle_timeout = 10s|pm.process_idle_timeout = 300s|' /etc/php/7.0/fpm/pool.d/www.conf
sudo sed -i "s|user www-data|user $USER|" /etc/nginx/nginx.conf
sudo sed -i "s|www-data|$USER|" /etc/php/7.0/fpm/pool.d/www.conf

# - - - - - - - - - - - - - - - - - -
# Restart
# - - - - - - - - - - - - - - - - - -
Text "Restart Nginx"
sudo service nginx reload
Text "Restart PHP 7.0"
sudo service php7.0-fpm restart

# - - - - - - - - - - - - - - - - - -
# Finished
# - - - - - - - - - - - - - - - - - -
Info "- - - - - - - - - - - - - - - - - -"
Info "Finished, your live URL is:"
Info "- dsxi.server"
Info "- - - - - - - - - - - - - - - - - -"
Info "Don't do provision, ever. When restarting your PC"
Info "only do: vagrantup up and check you can access the site."
Info "Provision scraps everything and starts again installing everything."
Info "- - - - - - - - - - - - - - - - - -"
Complete
