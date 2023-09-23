# syntax=docker.io/docker/dockerfile:1.4
FROM italo2sanfer/sysclesia-php:0.1
WORKDIR /usr/share/nginx/html/eventos_shalon
COPY . .
#COPY ./entrypoint.sh /
#RUN chmod +x /entrypoint.sh
#ENTRYPOINT ["/entrypoint.sh"]