FROM amazonlinux

RUN yum update -y
RUN amazon-linux-extras install httpd_modules php7.4 vim docker -y
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN yum install php-mbstring php-gd php-dom php-devel* gcc libzip php-libzip libzip-devel zlip zip unzip php-pear make openssl-devel php-pgsql -y