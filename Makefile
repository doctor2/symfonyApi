SHELL := /bin/bash

init:
	echo "Prepare settings..."
	symfony composer install
	echo "DONE"

	echo "docker up"
	docker-compose up -d
	echo "DONE"

	echo "Wait mysql-server loading..."
	sleep 15s
	echo "DONE"

	symfony console doctrine:migrations:migrate
	symfony serve -d

stop:
	docker-compose down

	symfony server:stop
