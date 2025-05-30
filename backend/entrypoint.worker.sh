#!/bin/sh
set -e

#!/bin/sh

# Espera hasta que RabbitMQ esté disponible
echo "Esperando a que RabbitMQ esté listo..."
until nc -z rabbitmq 5672; do
  sleep 1
done

echo "RabbitMQ listo. Ejecutando el worker..."
php bin/console messenger:consume command_async event_async --time-limit=3600 --memory-limit=128M --no-interaction

