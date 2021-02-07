build-all:
	@echo ":::up all container"
	docker-compose up  -d --force-recreate --build
	docker-compose exec app composer install
	docker-compose exec app npm install
	docker-compose exec app php artisan key:generate
	docker-compose exec app php artisan config:cache
	docker-compose exec app php artisan migrate
	docker-compose exec app chmod -R 777 storage bootstrap/cache

up-all:
	@echo ":::up all container"
	docker-compose up  -d --force-recreate --build
