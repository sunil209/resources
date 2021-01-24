FROM composer as vendors
WORKDIR /composer
COPY composer.* ./
COPY auth.json ./
RUN composer install

FROM node:10-alpine as node-modules
WORKDIR /node
RUN npm config set unsafe-perm true && npm install gulp -g
COPY package.json .
COPY package-lock.json .
RUN npm i

FROM node-modules as frontend-build
COPY app/wp-content/themes/postclick/src app/wp-content/themes/postclick/src
COPY app/wp-content/mu-plugins/insta-wp/src/Modules app/wp-content/mu-plugins/insta-wp/src/Modules
COPY tsconfig.json tsconfig.json
RUN npx webpack --config app/wp-content/themes/postclick/src/Assets/Build/webpack.config.js

FROM php:7.4-apache as wordpress_build

ENV APACHE_DOCUMENT_ROOT /var/www/app

RUN apt-get update \
	&& apt-get install -y --no-install-recommends ghostscript \
	&& rm -rf /var/lib/apt/lists/*

RUN set -ex \
	&& savedAptMark="$(apt-mark showmanual)" \
    && apt-get update \
	&& apt-get install -y --no-install-recommends libfreetype6-dev libjpeg-dev libmagickwand-dev libpng-dev libzip-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install -j "$(nproc)" bcmath exif gd mysqli opcache zip \
	&& pecl install imagick-3.4.4 \
	&& docker-php-ext-enable imagick \
	&& apt-mark auto '.*' > /dev/null \
	&& apt-mark manual $savedAptMark \
	&& ldd "$(php -r 'echo ini_get("extension_dir");')"/*.so \
	 	| awk '/=>/ { print $3 }' | sort -u | xargs -r dpkg-query -S | cut -d: -f1 | sort -u | xargs -rt apt-mark manual \
	&& apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false \
	&& rm -rf /var/lib/apt/lists/*

RUN { \
		echo 'opcache.memory_consumption=256'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN { \
		echo 'upload_max_filesize = 8M'; \
		echo 'post_max_size = 8M'; \
	} > /usr/local/etc/php/conf.d/uploads.ini

RUN { \
		echo 'error_reporting = E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_RECOVERABLE_ERROR'; \
		echo 'display_errors = Off'; \
		echo 'display_startup_errors = Off'; \
		echo 'log_errors = On'; \
		echo 'error_log = /dev/stderr'; \
		echo 'log_errors_max_len = 1024'; \
		echo 'ignore_repeated_errors = On'; \
		echo 'ignore_repeated_source = Off'; \
		echo 'html_errors = Off'; \
	} > /usr/local/etc/php/conf.d/error-logging.ini

RUN set -eux \
	&& a2enmod rewrite expires \
	&& a2enmod remoteip \
	&& { \
		echo 'RemoteIPHeader X-Forwarded-For'; \
		echo 'RemoteIPTrustedProxy 10.0.0.0/8'; \
		echo 'RemoteIPTrustedProxy 172.16.0.0/12'; \
		echo 'RemoteIPTrustedProxy 192.168.0.0/16'; \
		echo 'RemoteIPTrustedProxy 169.254.0.0/16'; \
		echo 'RemoteIPTrustedProxy 127.0.0.0/8'; \
	} > /etc/apache2/conf-available/remoteip.conf \
	&& a2enconf remoteip \
	&& find /etc/apache2 -type f -name '*.conf' -exec sed -ri 's/([[:space:]]*LogFormat[[:space:]]+"[^"]*)%h([^"]*")/\1%a\2/g' '{}' +

COPY docker/wordpress/docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]

FROM wordpress_build as production_build

# copying regular composer dependencies
COPY --from=vendors /composer/vendor /var/www/vendor
# copying plugins installed with composer
COPY --from=vendors /composer/app/wp-content/plugins /var/www/app/wp-content/plugins
COPY app /var/www/app
COPY --from=frontend-build /node/app/wp-content/themes/postclick/src/Assets/Dist /var/www/app/wp-content/themes/postclick/src/Assets/Dist/

# Changing document root using instruction from official image: https://hub.docker.com/_/php
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf


RUN apt-get update \
    && apt-get install ssl-cert \
    && a2enmod ssl

COPY docker/wordpress/local/cert /etc/ssl/certs/postclick-local
COPY docker/wordpress/local/apache_ssl_local.conf /etc/apache2/sites-enabled/
