# Requirements
- Docker
- Docker compose

# Dependencies
- https://github.com/opengento/magento2-frankenphp-base
- https://github.com/opengento/magento2-application

# Install step
```
cp .env.sample .env
docker compose up -d
docker compose exec frankenphp bash
composer install
bin/magento setup:install \
    --base-url=https://localhost \
    --db-host=fdb \
    --db-name=magento \
    --db-user=magento \
    --db-password=magento \
    --admin-firstname=Magento \
    --admin-lastname=Admin \
    --admin-email=admin@example.com \
    --admin-user=admin \
    --admin-password=12345 \
    --language=en_US \
    --currency=USD \
    --timezone=America/Chicago \
    --use-rewrites=1 \
    --search-engine=opensearch \
    --opensearch-host=fopensearch \
    --opensearch-port=9200
cp app/etc/env.php.local app/etc/env.php
bin/magento se:up
```

You may need to restart the container after completing the Magento installation.

# See also
https://github.com/dunglas/frankenphp
https://frankenphp.dev/fr/
