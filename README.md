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

### Worker mode
In our demo we use `pub/worker.php` for the FrankePHP Worker Mode.
We made these changes in env/franken.env
```
FRANKENPHP_WORKER_ENABLE=1
FRANKENPHP_CONFIG="worker ./pub/worker.php"
```

# Known bugs
While we are developping a Magento Extension, you need to commented out these lines in :
`app/vendor/laminas/laminas-http/src/PhpEnvironment/Request.php`
```

    /**
     * Provide an alternate Parameter Container implementation for server parameters in this object,
     * (this is NOT the primary API for value setting, for that see getServer())
     *
     * @return $this
     */
    public function setServer(ParametersInterface $server)
    {
        $this->serverParams = $server;

        // This seems to be the only way to get the Authorization header on Apache
/*
        if (function_exists('apache_request_headers')) {
            $apacheRequestHeaders = apache_request_headers();
            if (! isset($this->serverParams['HTTP_AUTHORIZATION'])) {
                if (isset($apacheRequestHeaders['Authorization'])) {
                    $this->serverParams->set('HTTP_AUTHORIZATION', $apacheRequestHeaders['Authorization']);
                } elseif (isset($apacheRequestHeaders['authorization'])) {
                    $this->serverParams->set('HTTP_AUTHORIZATION', $apacheRequestHeaders['authorization']);
                }
            }
        }
*/
```

# See also
https://github.com/dunglas/frankenphp
https://frankenphp.dev/fr/
