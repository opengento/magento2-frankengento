# Requirements
- Docker
- Docker compose

# Install step
```
cp .env.sample .env
docker compose up -d
docker compose exec -it frankenphp bash
cp composer.json.sample composer.json && composer install
```

### Worker mode
You can use `pub/index.php.sample` if you want to try Worker Mode
You well also need to make changes in env/franken.env
```
FRANKENPHP_WORKER_ENABLE=1
FRANKENPHP_CONFIG="worker ./pub/index.php"
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
