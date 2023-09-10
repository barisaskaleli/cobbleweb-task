# Installation

```
cp .env.dist .env
```

```
docker-compose up -d --build
```

And then get in the fpm container and run these commands
```
docker exec -it cobble-fpm sh
```
```
php bin/console lexik:jwt:generate-keypair
```
```
sh bin/clean_db.sh
```

After installation done you can reach from http://127.0.0.1:8001 here or you can use postman collection

[POSTMAN COLLECTION](https://github.com/barisaskaleli/cobbleweb-task/blob/main/API.postman_collection.json)