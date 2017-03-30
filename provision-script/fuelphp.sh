echo "日付設定"
cp -p /usr/share/zoneinfo/Japan /etc/localtime
yum -y install ntp
cp -f /virtual/vagrant/public_html/www.zzzzzzzz.jp/provision-script/etc/ntp.conf /etc/ntp.conf
ntpdate ntp.nict.jp
service ntpd start
chkconfig ntpd on

echo "mroongaインストール"
yum -y remove mysql
yum install -y http://packages.groonga.org/centos/groonga-release-1.1.0-1.noarch.rpm
yum install -y http://repo.mysql.com/mysql-community-release-el6-7.noarch.rpm
yum makecache
yum install -y mysql-community-mroonga
yum install -y groonga-tokenizer-mecab
service mysqld start
chkconfig mysqld on

echo "FuelPHP設定開始"
ln -sf /virtual/vagrant/public_html/www.zzzzzzzz.jp/provision-script/phpmyadmin /virtual/vagrant/public_html/www.zzzzzzzz.jp/public/phpmyadmin

# development DB作成
mysql -uroot -e "create database fuel_dev"

# develoment DB設定
cd /virtual/vagrant/public_html/www.zzzzzzzz.jp; cp fuel/app/config/development/db.php.sample fuel/app/config/development/db.php

cd /virtual/vagrant/public_html/www.zzzzzzzz.jp; php composer.phar self-update
cd /virtual/vagrant/public_html/www.zzzzzzzz.jp; php composer.phar install
cd /virtual/vagrant/public_html/www.zzzzzzzz.jp; php oil r migrate:current
echo "FuelPHP設定完了"

