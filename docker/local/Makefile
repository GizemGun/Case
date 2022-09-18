#TODO: DOCKERFILE BUILD FUNCTIONS
############################################################################################################
build:
	echo "local_build çalıştı."
	rm -rf vendor && rm -rf .env
	docker-compose up -d --build nginx


#TODO: COPY BUILD FUNCTIONS
############################################################################################################
copy_build:
	echo "local_copy_build çalıştı."
	docker cp case-api:/var/www/app/vendor .
	docker cp case-api:/var/www/app/.env .
	docker cp case-api:/var/www/app/composer.json .
	docker cp case-api:/var/www/app/composer.lock .


#TODO: ACTION BUILD FUNCTIONS
############################################################################################################
action_build:
	echo "local_action_build çalıştı."
	docker exec --user root case-api /bin/sh -c "chown -hR www-data:www-data ."
	docker exec --user www-data case-api /bin/sh -c "php bin/console d:s:u --force"
	docker exec --user www-data case-api /bin/sh -c "php bin/console d:m:m"


#TODO: FIRST BUILD FUNCTIONS
############################################################################################################
build_first:
	echo "local_build_first çalıştı."
	make -f docker/local/Makefile build
	sleep 10
	make -f docker/local/Makefile after_build

after_build:
	echo "local_after_build çalıştı."
	make -f docker/local/Makefile copy_build
	make -f docker/local/Makefile action_build


#TODO: REBUILD BUILD FUNCTIONS
############################################################################################################
before_rebuild:
	echo "local_before_rebuild çalıştı."
	docker-compose rm --stop -v --force symfony

rebuild:
	echo "local_rebuild çalıştı."
	make -f docker/local/Makefile before_rebuild
	make -f docker/local/Makefile build
	sleep 10
	make -f docker/local/Makefile after_rebuild

after_rebuild:
	echo "local_after_rebuild çalıştı."
	make -f docker/local/Makefile copy_build
	make -f docker/local/Makefile action_build


#TODO: REMOVE FUNCTIONS
############################################################################################################
remove_container:
	echo "local_remove_container çalıştı."
	rm -rf vendor && rm -rf .env
	docker-compose down --rmi all -v
	docker system prune -a -f --all
	docker system prune -a -f --volumes