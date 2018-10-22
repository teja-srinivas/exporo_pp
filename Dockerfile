FROM registry.gitlab.com/exporo/alpine-nginx-php-fpm:0.0.9

RUN apk -v --update --no-cache add openssh mysql-client zip curl tar && \
    sed -i s/#PermitRootLogin.*/PermitRootLogin\ without-password/ /etc/ssh/sshd_config

RUN curl -L https://download.newrelic.com/php_agent/archive/8.2.0.221/newrelic-php5-8.2.0.221-linux-musl.tar.gz | tar -C /tmp -zx && \
    NR_INSTALL_USE_CP_NOT_LN=1 NR_INSTALL_SILENT=1 /tmp/newrelic-php5-*/newrelic-install install && \
    rm -rf /tmp/newrelic-php5-* /tmp/nrinstall*

COPY ./infrastructure/docker/manifest/ /
RUN chmod 755 /entrypoint.sh
ADD ./application/ /var/www/html
RUN chmod -R 777 /var/www/html/

WORKDIR /var/www/html

EXPOSE 8080
EXPOSE 22

CMD ["/bin/bash", "/entrypoint.sh"]
