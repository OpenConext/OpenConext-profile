FROM ghcr.io/openconext/openconext-containers/openconext-phpfpm-dev:latest AS node-build
COPY . /var/www/html/
WORKDIR /var/www/html/
ENV SYMFONY_ENV=prod
RUN composer install -n --prefer-dist -o --ignore-platform-reqs --no-dev && \
    composer run-script symfony-scripts && \
    bin/console assets:install && \
    npm i --production && \
    npm run build

FROM ghcr.io/openconext/openconext-containers/openconext-httpd:latest AS httpd-build
MAINTAINER Bart Geesink (bart.geesink@surf.nl)
RUN mkdir -p /var/www/html/
COPY --from=node-build /var/www/html/public/. /var/www/html/public/
COPY --from=node-build /var/www/html/assets/. /var/www/html/assets/
COPY ./docker/conf/000-default.conf /usr/local/apache2/conf/
COPY ./docker/conf/httpd.conf /usr/local/apache2/conf/

EXPOSE 80

CMD ["httpd", "-D", "FOREGROUND"]

FROM ghcr.io/openconext/openconext-containers/openconext-phpfpm:latest AS phpfpm-build
COPY --from=node-build /var/www/html/public/. /var/www/html/public/
COPY --from=node-build /var/www/html/assets/. /var/www/html/assets/
COPY --from=node-build /var/www/html/templates/. /var/www/html/templates/
COPY --from=node-build /var/www/html/translations/. /var/www/html/translations/
COPY --from=node-build /var/www/html/config/. /var/www/html/config/
COPY --from=node-build /var/www/html/vendor/. /var/www/html/vendor/
COPY --from=node-build /var/www/html/var/. /var/www/html/var/
COPY --from=node-build /var/www/html/src/. /var/www/html/src/
COPY --from=node-build /var/www/html/bin/. /var/www/html/bin/
COPY --from=node-build /var/www/html/composer.json /var/www/html/

LABEL maintainer "Bart Geesink" <bart.geesink@surf.nl>

RUN mkdir -p /var/www/html/var/prod && \
    chown -R www-data /var/www/html/var/prod

COPY ./docker/conf/zz-docker.conf /usr/local/etc/php-fpm.d
COPY ./docker/conf/10-docker-opcache-openconext.conf /usr/local/etc/php/conf.d/

CMD ["/usr/local/sbin/php-fpm" , "-F"]
