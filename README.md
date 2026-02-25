## Getting start
```shell
cp .env.example .env

docker compose up --build
docker compose exec user-domains-app php artisan key:generate
docker compose exec user-domains-app php artisan migrate

```

## Host
http://localhost:8004