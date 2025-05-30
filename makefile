# Variables para Docker
export UID := $(shell id -u)
export GID := $(shell id -g)

up:
	@echo "🔹 Levantando backend..."
	cd backend && UID=$(UID) GID=$(GID) docker compose up -d --build

down:
	@echo "🔻 Parando backend..."
	cd backend && UID=$(UID) GID=$(GID) docker compose down

clean:
	@echo "🧼 Limpiando backend..."
	cd backend && docker compose down --volumes --remove-orphans
test:
	@echo "🧪 Ejecutando pruebas backend..."
	cd backend && docker compose exec symfony_app php bin/phpunit --colors=always --testdox
logs:
	@echo "🧾 Logs backend..."
	cd backend && docker compose logs -f

migrate:
	@echo "🔄 Ejecutando migraciones..."
	cd backend && docker compose exec symfony_app php bin/console doctrine:migrations:migrate --no-interaction

build:
	@echo "🛠 Construyendo backend..."
	cd backend && docker compose build


clean-cache:
	@echo "🧹 Limpiando cachés de PHP..."
	rm -rf backend/.phpcs-cache backend/phpunit.result.cache backend/tests/phpunit.result.cache
	docker exec -it symfony_app rm -rf /var/www/html/.phpcs-cache /var/www/html/tests/phpunit.result.cache


console-backend:
	@echo "🔧 Entrando al contenedor backend (symfony_app)..."
	docker exec -it symfony_app bash

