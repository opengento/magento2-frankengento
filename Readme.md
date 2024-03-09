# Requirements
- Docker
- Docker compose

# Install step
```
docker compose up
docker compose exec --user franken -it frankenphp bash
cd app/ && cp composer.json.sample composer.json && composer install
```