#!/bin/bash
echo 'Start init'

if [ -z ${ROOT_PASSWORD} ]; then
	ROOT_PASSWORD=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 20)
	echo "Use generate password : ${ROOT_PASSWORD}"
fi

echo "root:${ROOT_PASSWORD}" | chpasswd

if [ -f /var/www/html/core/config/common.config.php ]; then
	echo 'Jeedom is already install'
else
	echo 'Start jeedom installation'
	rm -rf /root/install.sh
	wget https://raw.githubusercontent.com/jeedom/core/stable/install/install.sh -O /root/install.sh
	chmod +x /root/install.sh
	/root/install.sh -s 6
fi

echo 'All init complete'
chmod 777 /dev/tty*
chmod 777 -R /tmp
chmod 755 -R /var/www/html
chown -R www-data:www-data /var/www/html
mkdir -p /var/log/supervisor/
mkdir -p /var/log/apache2/

# For Openzwave
wget https://raw.githubusercontent.com/jeedom/plugin-openzwave/master/resources/install.sh -O /root/openzwave_install.sh
chmod +x /root/openzwave_install.sh
/root/openzwave_install.sh

/usr/bin/supervisord
