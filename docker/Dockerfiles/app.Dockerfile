FROM php:8.2.5-cli-alpine3.17
RUN apk add --no-cache linux-headers
RUN apk add --no-cache composer
RUN apk add busybox-extras
RUN docker-php-ext-install sockets
ARG UID
RUN addgroup -S appgroup -g $UID && adduser -u $UID -S appuser -G appgroup
RUN apk --update add sudo
RUN echo "appuser ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers
USER appuser
