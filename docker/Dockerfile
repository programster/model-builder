# Due to layout of this project, the dockerfile will be moved up two directories and run during
# the build. Thus when performing any ADD commands, remember that this is "where you are"

FROM ubuntu:20.04

# Don't prompt for timezone etc.
env DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get dist-upgrade -y


# Install php 8 through ondrej repo
RUN apt-get install -y software-properties-common apt-transport-https \
  && add-apt-repository ppa:ondrej/php -y \
  && apt-get update \
  && apt-get install php8.0-cli -y

# Install the relevant packages
RUN apt-get install -y vim apache2 libapache2-mod-php8.0 supervisor php8.0-cli php8.0-pgsql curl php8.0-curl

# Install swoole
RUN apt-get update \
  && apt-get install php-swoole -y \
  && phpenmod swoole

# Enable the php mod we just installed, along with url rewriting and ssl
RUN a2enmod php8.0 ssl rewrite


# expose port 80 and 443 (ssl) for the web requests
EXPOSE 80
EXPOSE 443

# Manually set the apache environment variables in order to get apache to work immediately.
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2

# It appears that the new apache requires these env vars as well
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2/apache2.pid


###### Update the php INI settings #########

# Set display errors to true. We will disable them based on environment in PHP.
RUN sed -i 's;display_errors = .*;display_errors = On;' /etc/php/8.0/apache2/php.ini
RUN sed -i 's;display_errors = .*;display_errors = On;' /etc/php/8.0/cli/php.ini

# Uncomment any of these if you need them.

# Increase php's max allowed memory size
#RUN sed -i 's;memory_limit = .*;memory_limit = -1;' /etc/php5/apache2/php.ini
#RUN sed -i 's;memory_limit = .*;memory_limit = -1;' /etc/php5/cli/php.ini

# Set the max execution time
#RUN sed -i 's;max_execution_time = .*;max_execution_time = -1;' /etc/php5/apache2/php.ini
#RUN sed -i 's;max_execution_time = .*;max_execution_time = -1;' /etc/php5/cli/php.ini

# This is also needed for execution time
#RUN sed -i 's;max_input_time = .*;max_input_time = -1;' /etc/php5/apache2/php.ini


# Install the cron service to tie up the container's foreground process
RUN apt-get install cron -y

# Add the startup script to the container. This will run when container starts.
ADD docker/startup.sh /root/startup.sh

# Update our apache sites available with the config we created
ADD docker/apache-config.conf /etc/apache2/sites-enabled/000-default.conf

COPY --chown=root:root docker/apache-ssl-config.conf /etc/apache2/sites-enabled/default-ssl.conf


# Use the crontab file.
# The crontab file was already added when we added "project"
COPY --chown=root:root docker/crons.conf /root/crons.conf
COPY --chown=root:root docker/create-env-file.php /root/create-env-file.php
RUN crontab /root/crons.conf && rm /root/crons.conf

# Add our websites files to the default apache directory (/var/www)
# This should be as late as possible in the dockerfile to speed up rebuilds
COPY --chown=root:www-data app /var/www/my-site
RUN chmod 750 -R /var/www/my-site

# Execute the containers startup script which will start many processes/services
# The startup file was already added when we added "project"
CMD ["/bin/bash", "/root/startup.sh"]
