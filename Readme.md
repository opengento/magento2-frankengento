# Requirements
- Docker
- Docker compose

# Install step
```
docker compose up -d
docker compose exec -it frankenphp bash
cp composer.json.sample composer.json && composer install
```
