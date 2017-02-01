#Build
##mysql
	docker build -t lmejias3/mysql ./mysql
##apache-php
	docker build -t lmejias3/apache-php ./apache-php/

#Run
##mysql
	docker run -e MYSQL_ROOT_PASSWORD=[mysql root password] -p [mysql port]:3306 -v [local mysql folder]:/var/lib/mysql --name [mysql container name] -d lmejias3/mysql
	docker exec [mysql container name] /home/bookstore/initdb.sh
##apache-php
	docker run -e MYSQL_PORT=[mysql port] -e MYSQL_HOST=[mysql ip] -p [http port]:80 -p [https port]:443 -v [local storage folder]:/var/www/bookstore/public/books --name [http container name] -d lmejias3/apache-php