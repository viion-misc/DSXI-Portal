#!/usr/bin/env bash
cd ~/web/setup/
source colors.sh

#------------------------------------------------------
# FFXI Dark Star Private Server
# Code to Automate Ubuntu setup
# ------------------------------------------------------
# THIS SCRIPT IS INTENDED FOR LOCAL VAGRANT ENVIRONMENTS
# AND SUCH HAS HARD CODED CONFIGS TO ALLOW QUICK SETUP
# ------------------------------------------------------
# USE "setup_xi_live.sh" to setup a Live server.
# (Live is not yet possible through vagrant)
# ------------------------------------------------------
Heading ">> Setting up DSXI Server <<"

# Where FFXI Will be stored, don't change this unless you have to
serverInstallPath="/home/vagrant/ffxi"

# Set User ID and Group ID
userID=$(id|awk '{print$1}'|cut -d "(" -f2|sed 's/)//')
groupID=$(id|awk '{print$2}'|cut -d "(" -f2|sed 's/)//')

# Set IP for server (this will be the VM IP.)
publicIP="11.11.11.11"

# Settings
mysqlPassword="dsxi"
mysqlDbUser="dsxi"
mysqlDbPass="dsxi"
mysqlDbName="dsxi"

Text "Setting Default MySQL Passwords"
echo "mysql-server mysql-server/root_password password $mysqlPassword" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password $mysqlPassword" | debconf-set-selections


# ----------------------------------------------------------------

# Installation
Title "INSTALLATION"
Info 'Creating folders.'
mkdir $serverInstallPath


# Install everything required
Info "Installing and configuring system requirements "
sudo apt-get install -y software-properties-common python-software-properties
sudo add-apt-repository -y ppa:ubuntu-toolchain-r/test
sudo apt-get update
sudo apt-get install -y python-dev build-essential git mysql-server libmysqlclient-dev libluajit-5.1-dev libzmq3-dev autoconf pkg-config python-pip g++-5 screen
sudo pip install --upgrade pip
sudo pip install --upgrade virtualenv
sudo pip install sqlalchemy pymysql beautifulsoup4 pyyaml
sudo rm /usr/bin/gcc /usr/bin/g++ /usr/bin/cpp
sudo ln -s /usr/bin/gcc-5 /usr/bin/gcc
sudo ln -s /usr/bin/g++-5 /usr/bin/g++
sudo ln -s /usr/bin/cpp-5 /usr/bin/cpp
clear
Text "Installation complete"

# ----------------------------------------------------------------

# Fetch source code from github
Title "BUILD SERVER"
Info "Getting Dark Star source code from GitHub ..."
git clone http://github.com/DarkstarProject/darkstar.git/ $serverInstallPath/

# Compile dark star
Info "Compiling Dark Star."
chmod +x $serverInstallPath/autogen.sh
cd $serverInstallPath; ./autogen.sh; ./configure --enable-debug=gdb; make
clear
Text "Server Compiled Successfully"

# ----------------------------------------------------------------

# Create database
Title "CREATE DATABASE"

## Create table
Info "Creating FFXI Database."
mysql -uroot -p$mysqlPassword <<MYSQL_SCRIPT
CREATE DATABASE $mysqlDbName;
CREATE USER '$mysqlDbUser'@'localhost' IDENTIFIED BY '$mysqlDbPass';
GRANT ALL PRIVILEGES ON $mysqlDbName .* TO '$mysqlDbUser'@'localhost';
FLUSH PRIVILEGES;
EXIT
MYSQL_SCRIPT

# Import SQL files
Info "Importing tables and data from sql files ..."
cd $serverInstallPath/sql/; for f in $serverInstallPath/sql/*.sql;do mysql $mysqlDbName -u $mysqlDbUser -p$mysqlDbPass < $f;done

# Set Zone IPs
Info "Setting Zone IPs"
mysql -u$mysqlDbUser -p$mysqlDbPass <<MYSQL_SCRIPT
USE $mysqlDbName;
UPDATE zone_settings SET zoneip = $publicIP;
EXIT
MYSQL_SCRIPT
clear
Text "Database Created Successfully"

# ----------------------------------------------------------------

# Create database
Title "UPDATE CONFIG"

# Updating config
Info "Updating DS Config Files ..."
perl -pi -e 's/mysql_login:     root/mysql_login:     '$mysqlDbUser'/g' $serverInstallPath/conf/login_darkstar.conf
perl -pi -e 's/mysql_password:  root/mysql_password:  '$mysqlDbPass'/g' $serverInstallPath/conf/login_darkstar.conf
perl -pi -e 's/mysql_database:  dspdb/mysql_database:  '$mysqlDbName'/g' $serverInstallPath/conf/login_darkstar.conf
perl -pi -e 's/mysql_login:     root/mysql_login:     '$mysqlDbUser'/g' $serverInstallPath/conf/map_darkstar.conf
perl -pi -e 's/mysql_password:  root/mysql_password:  '$mysqlDbPass'/g' $serverInstallPath/conf/map_darkstar.conf
perl -pi -e 's/mysql_database:  dspdb/mysql_database:  '$mysqlDbName'/g' $serverInstallPath/conf/map_darkstar.conf
perl -pi -e 's/mysql_login:     root/mysql_login:     '$mysqlDbUser'/g' $serverInstallPath/conf/search_server.conf
perl -pi -e 's/mysql_password:  root/mysql_password:  '$mysqlDbPass'/g' $serverInstallPath/conf/search_server.conf
perl -pi -e 's/mysql_database:  dspdb/mysql_database:  '$mysqlDbName'/g' $serverInstallPath/conf/search_server.conf
perl -pi -e 's/    map_config.mysql_login = "root";/    map_config.mysql_login = "'$mysqlDbUser'";/g' $serverInstallPath/src/map/map.cpp
perl -pi -e 's/    map_config.mysql_password = "root";/    map_config.mysql_password = "'$mysqlDbPass'";/g' $serverInstallPath/src/map/map.cpp
perl -pi -e 's/    map_config.mysql_database = "dspdb";/    map_config.mysql_database = "'$mysqlDbName'";/g' $serverInstallPath/src/map/map.cpp
perl -pi -e 's/    search_config.mysql_login = "root";/    search_config.mysql_login = "'$mysqlDbUser'";/g' $serverInstallPath/src/search/search.cpp
perl -pi -e 's/    search_config.mysql_password = "root";/    search_config.mysql_password = "'$mysqlDbPass'";/g' $serverInstallPath/src/search/search.cpp
perl -pi -e 's/    search_config.mysql_database = "dspdb";/    search_config.mysql_database = "'$mysqlDbName'";/g' $serverInstallPath/src/search/search.cpp
Text "Database Created Successfully"

# ----------------------------------------------------------------
Title "XI Server Installed"
sudo -H -u vagrant bash /dsxi/setup/server_start
Info ""
Info "You can access your server through the ip: 11.11.11.11"
Info "After a computer restart, just do vagrant up again, do not provision (wipes server)"
Info "This server will not be publically accessible, it is for dev/testing"
Info "To use the same script on a live server, run: setup_xi_live.sh"
Info "- - - - - - - - - - - - - - - - - -"
Complete
