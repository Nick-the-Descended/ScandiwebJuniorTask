FROM php:apache
LABEL authors="Nick"
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN a2enmod rewrite

COPY ./backend /backend
COPY ./backend/.htaccess /var/www/html/.htaccess
WORKDIR /backend

ENV PORT=8000

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/api"]