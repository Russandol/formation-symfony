FROM webdevops/php-apache-dev:8.2

# Timezone setup
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime

# Set the final working directory
WORKDIR /app
