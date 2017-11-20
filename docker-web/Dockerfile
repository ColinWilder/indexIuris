FROM ubuntu:16.04
RUN apt-get update && apt-get install -y \
    apache2 \
    curl \
    php7.0 \
    php7.0-mysql \
    php7.0-xml \
    php7.0-curl \
    libapache2-mod-php7.0 && \
    a2enmod rewrite && \
    a2enmod headers
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName indexiuris.dev-cdh.org" >> /etc/apache2/apache2.conf
RUN ln -s /etc/apache2/mods-available/expires.load /etc/apache2/mods-enabled/
RUN service apache2 start && service apache2 reload
RUN apachectl configtest
EXPOSE 80
ENTRYPOINT service apache2 start  && bash
