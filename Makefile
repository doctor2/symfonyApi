#!/bin/sh
GREEN='\033[0;32m'

echo "\n${GREEN}Prepare settings...${NC}"

composer install

echo "\n${GREEN}DONE"

echo "Now run"
docker-compose run  -d
echo "${NC}"

php bin/console doctrine:migrations:migrate
