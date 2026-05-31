# Requirements
- Docker

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
    --db-host=db \
    --db-name=magento \
    --db-user=magento \
    --db-password=magento \
    --backend-frontname=admin \
    --admin-firstname=Magento \
    --admin-lastname=Admin \
    --admin-email=admin@example.com \
    --admin-user=admin \
    --admin-password=Magent0! \
    --language=en_US \
    --currency=USD \
    --timezone=America/Chicago \
    --use-rewrites=1 \
    --search-engine=opensearch \
    --opensearch-host=opensearch \
    --opensearch-port=9200 \
    --cache-backend redis \
    --cache-backend-redis-server redis \
    --cache-backend-redis-port 6379 \
    --cache-backend-redis-db 0 \
    --page-cache=redis \
    --page-cache-redis-server redis \
    --page-cache-redis-port 6379 \
    --page-cache-redis-db=1 \
    --session-save=redis
    --session-save-redis-host redis \
    --session-save-redis-port 6379 \
    --session-save-redis-db 2
bin/magento se:up
```

You may need to restart the container after completing the Magento installation.

# See also
https://github.com/dunglas/frankenphp
https://frankenphp.dev/fr/
