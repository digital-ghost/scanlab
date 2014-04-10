# Server installation guide.

## Easy way using Apache:

1) On debian/ubuntu/kali run this commands as root:

	apt-get install apache2 mongodb libapache2-mod-php5 php5-cli php5-mongodb php5-gd php5-geoip

	a2enmod rewrite && php5enmod mongo && service apache2 restart

See IMPORTANT NOTES if you have any troubles.

2) You need to configure your apache server. Make sure you set "AllowOverride" option to "All".

3) Upload or "git clone" Scanlab sources to your prefered folder.

4) Edit file 'inc/example.settings.php' and move it to 'inc/settings.php'.

5) Go to http://your.scanlab.domain.com/install.php and set password for your ROOT user.


## Using nginx for better performance (for advanced users):

Install these packages:

	nginx-full mongodb php5-fpm php5-cli
	php5-mongodb php5-gd php5-geoip

Example nginx configuration file can be found in 'other' folder.

Then see instructions for apache and do your best.

## IMPORTANT NOTES:

**Debian Wheezy/Kali Linux** users can find php5-mongodb package in backports repository.

	deb http://http.debian.net/debian wheezy-backports main

If you are using newest version of **Ubuntu**, you will also **need to install**:

php5-json