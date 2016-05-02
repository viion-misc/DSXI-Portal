#!/bin/bash
#   User ID
userID=$(id|awk '{print$1}'|cut -d "(" -f2|sed 's/)//')
groupID=$(id|awk '{print$2}'|cut -d "(" -f2|sed 's/)//')
#    File Paths
echo 'Where will FFXI be kept? '
read serverCode
#   IP Information
publicIP=$(dig TXT +short o-o.myaddr.l.google.com @ns1.google.com)
#   Verify Backup paths exist and create them if they do not.
echo 'Creating folders.'
mkdir $serverCode
echo 'Folder created.'
echo 'Installing and configuring system requirements. '
#   installing System requirements
sudo apt-get install -y software-properties-common python-software-properties
sudo add-apt-repository ppa:ubuntu-toolchain-r/test
sudo apt-get update
sudo apt-get install -y python-dev build-essential git mysql-server libmysqlclient-dev libluajit-5.1-dev libzmq3-dev autoconf pkg-config python-pip g++-5 screen
sudo pip install --upgrade pip
sudo pip install --upgrade virtualenv
sudo pip install sqlalchemy pymysql beautifulsoup4 pyyaml
sudo rm /usr/bin/gcc /usr/bin/g++ /usr/bin/cpp
sudo ln -s /usr/bin/gcc-5 /usr/bin/gcc
sudo ln -s /usr/bin/g++-5 /usr/bin/g++
sudo ln -s /usr/bin/cpp-5 /usr/bin/cpp

#   Fetch new code from GIT
echo 'Fetching Server source code.'
git clone http://github.com/DarkstarProject/darkstar.git/ $serverCode/
echo 'Source code downloaded. Now compiling the code.'
#   Recompile code
chmod +x $serverCode/autogen.sh
cd $serverCode; ./autogen.sh; ./configure --enable-debug=gdb; make
clear
echo 'Code compile complete.'
echo 'Setting SQL Database.'
#   Data Base Variables
echo 'Please enter the mySQL root password (The Password you sued when installing mySQL): '
read -s rootSqlPassword
echo 'Please specify a SQL user name: '
read dbUser
echo 'Create a password for '$dbUser' (WARNING: if you use special characters you will have to manually correct the configuration files): '
read -s dbPassword
echo 'Name your Database: '
read dbName
#   Create Server Database and User
mysql -uroot -p$rootSqlPassword <<MYSQL_SCRIPT
CREATE DATABASE $dbName;
CREATE USER '$dbUser'@'localhost' IDENTIFIED BY '$dbPassword';
GRANT ALL PRIVILEGES ON $dbName .* TO '$dbUser'@'localhost';
FLUSH PRIVILEGES;
EXIT
MYSQL_SCRIPT
#   Importing Databases into SQL
cd $serverCode/sql/; for f in $serverCode/sql/*.sql;do mysql $dbName -u $dbUser -p$dbPassword < $f;done
#   Setting external IP in Database zone_settings
mysql -u$dbUser -p$dbPassword <<MYSQL_SCRIPT
USE $dbName;
UPDATE zone_settings SET zoneip = $publicIP;
EXIT
MYSQL_SCRIPT

#       Updating configuration file
perl -pi -e 's/mysql_login:     root/mysql_login:     '$dbUser'/g' $serverCode/conf/login_darkstar.conf
perl -pi -e 's/mysql_password:  root/mysql_password:  '$dbPassword'/g' $serverCode/conf/login_darkstar.conf
perl -pi -e 's/mysql_database:  dspdb/mysql_database:  '$dbName'/g' $serverCode/conf/login_darkstar.conf
perl -pi -e 's/mysql_login:     root/mysql_login:     '$dbUser'/g' $serverCode/conf/map_darkstar.conf
perl -pi -e 's/mysql_password:  root/mysql_password:  '$dbPassword'/g' $serverCode/conf/map_darkstar.conf
perl -pi -e 's/mysql_database:  dspdb/mysql_database:  '$dbName'/g' $serverCode/conf/map_darkstar.conf
perl -pi -e 's/mysql_login:     root/mysql_login:     '$dbUser'/g' $serverCode/conf/search_server.conf
perl -pi -e 's/mysql_password:  root/mysql_password:  '$dbPassword'/g' $serverCode/conf/search_server.conf
perl -pi -e 's/mysql_database:  dspdb/mysql_database:  '$dbName'/g' $serverCode/conf/search_server.conf
perl -pi -e 's/    map_config.mysql_login = "root";/    map_config.mysql_login = "'$dbUser'";/g' $serverCode/src/map/map.cpp
perl -pi -e 's/    map_config.mysql_password = "root";/    map_config.mysql_password = "'$dbPassword'";/g' $serverCode/src/map/map.cpp
perl -pi -e 's/    map_config.mysql_database = "dspdb";/    map_config.mysql_database = "'$dbName'";/g' $serverCode/src/map/map.cpp
perl -pi -e 's/    search_config.mysql_login = "root";/    search_config.mysql_login = "'$dbUser'";/g' $serverCode/src/search/search.cpp
perl -pi -e 's/    search_config.mysql_password = "root";/    search_config.mysql_password = "'$dbPassword'";/g' $serverCode/src/search/search.cpp
perl -pi -e 's/    search_config.mysql_database = "dspdb";/    search_config.mysql_database = "'$dbName'";/g' $serverCode/src/search/search.cpp

#   Create Start/Stop/Restart script
sudo touch /etc/init.d/ffxi;sudo chown $userID:$groupID /etc/init.d/ffxi;sudo chmod 755 /etc/init.d/ffxi
echo -e "#!/bin/bash\nFF_DIR='"$serverCode"'\ncase \"\$1\" in\n   start)\n   cd \$FF_DIR/\n      /usr/bin/screen -dmS lobby \$FF_DIR/dsconnect\n      echo 'Lobby has Started.'\n      /usr/bin/screen -dmS game \$FF_DIR/dsgame\n      echo 'Game has Started.'\n      /usr/bin/screen -dmS search \$FF_DIR/dssearch\n      echo 'Search has Started.'\n      cd \$FF_DIR/auctionhouse/bin/\n      /usr/bin/screen -dmS auction \$FF_DIR/auctionhouse/bin/broker.py\n      echo 'Auction House has Started.'\n   ;;\n   stop)\n      /usr/bin/screen -XS lobby quit\n      echo 'Lobby has been Shutdown.'\n      /usr/bin/screen -XS game quit\n      echo 'Game has been Shutdown.'\n      /usr/bin/screen -XS search quit\n      echo 'Search has been Shutdown.'\n      /usr/bin/screen -XS auction quit\n      echo 'Auction House has been Shutdown.'\n   ;;\n   restart)\n      /usr/bin/screen -XS lobby quit\n      echo 'Lobby has been Shutdown.'\n      /usr/bin/screen -XS game quit\n      echo 'Game has been Shutdown.'\n      /usr/bin/screen -XS search quit\n      echo 'Search has been Shutdown.'\n      /usr/bin/screen -XS auction quit\n      echo 'Auction House has been Shutdown.'\n      sleep 30\n      cd \$FF_DIR/\n      /usr/bin/screen -dmS lobby \$FF_DIR/dsconnect\n      echo 'Lobby has Started.'\n      /usr/bin/screen -dmS game \$FF_DIR/dsgame\n      echo 'Game has Started.'\n      /usr/bin/screen -dmS search \$FF_DIR/dssearch\n      echo 'Search has Started.'\n      cd \$FF_DIR/auctionhouse/bin/\n      /usr/bin/screen -dmS auction \$FF_DIR/auctionhouse/bin/broker.py\n      echo 'Auction House has Started.'\n   ;;\n   *)\n      echo \"Usage: FFXI-Server {start|stop|restart}\" >&2\n      exit 3\n   ;;\nesac" > /etc/init.d/ffxi


echo 'Complete!';