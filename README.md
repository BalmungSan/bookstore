# BookStore
A simple Web App for sharing books

The project consists of an online platform that allows the sale of new or used books among users.

# Authors:
 * Luis Miguel Mejía Suárez
 * Sergio Alejandro Lasso
 * Pedro Calle Jaramillo

Universidad EAFIT - 2017 (Tópicos Especiales en Telemática)

# Usage
This project can be deployed either in docker or in a centos 7 server

## Docker
Docker is the simplest way to deploy this application.
Simply go to the docker folder and type the following commands

	$ docker build -t lmejias3/mysql ./mysql
	$ docker build -t lmejias3/apache-php ./apache-php/
	$ docker network create --subnet=[subnet ip] --gateway=[gateway ip] [net name]
	$ docker run --net [net name] --ip [mysql ip] -e MYSQL_ROOT_PASSWORD=[mysql root password] -p [mysql port]:3306 -v [local mysql folder]:/var/lib/mysql --name [mysql container name] -d lmejias3/mysql
	$ docker run --net [net name] --ip [apache ip] -e MYSQL_PORT=[mysql port] -e MYSQL_HOST=[mysql ip] -p [http port]:80 -p [https port]:443 -v [local storage folder]:/var/www/bookstore/public/books --name [apache container name] -d lmejias3/apache-php
	$ docker exec -e APACHE_HOSTS=[apache containers ips] [mysql container name] /home/bookstore/initdb.sh

### where
* __subnet ip:__ is the ip address of a subnet for all containers running this application. _e.g. 203.0.113.0/24_
* __gateway ip:__ is the ip address for the default gateway of the subnet. _e.g. 203.0.113.254_
* __net name:__ is the name given to the subnet. e.g _bookstore-net_
* __mysql ip:__ is the ip address assigned to the mysql container. must be a valid ip address of the subnet. _e.g. 203.0.113.100_
* __mysql root password:__ is the password associated to the root user in the mysql container. _e.g. 1234_
* __mysql port:__ is the port to access to the mysql daemon. _e.g. 3366_
* __local mysql foler:__ is the local path to store the mysql data. _e.g. 1 (linux). /home/user1/docker/mysql e.g. 2 (windows) /c/Users/user1/docker/mysql_
* __mysql container name:__ is the name associated with the running mysql container _e.g. bookstore-mysql_
* __apache ip:__ is the ip address assigned to the apache-php container. must be a valid ip address of the subnet. _e.g. 203.0.113.150_
* __http port:__ is the port to access to the apache server. _e.g. 80_
* __https port:__ is the port to securely access to the apache server. _e.g. 443_
* __local storage foler:__ is the local path to store the books previews (PDFs). _e.g. 1 (linux). /home/user1/docker/books e.g. 2 (windows) /c/Users/user1/docker/books_
* __apache container name:__ is the name associated with the running apache-php container _e.g. bookstore-apache-php_
* __apache containers ips:__ are all the ip addresses of the apache containers that can be connected to the mysql database. Wildcards can be used. _e.g. 1 (one host) 203.0.113.150 e.g. 2 (multiple hosts) 203.0.113.%_

### note
> Creating a subnet for docker only works when running natively. If you're running docker toolbox over virtualbox omit the subnet creation, all net and ip assignaments to containers (with the --net and --ip otions) and use the following values for these env variables

> __MYSQL_HOST__=192.168.99.100 (Or whatever the default ip of your docker virtual machine)

> __APACHE_HOSTS__=172.17.0.1 (Or whatever the default gateway ip of your docker virtual machine)

Now you can access the application typing in a web browser the following url http://[apache ip]:[http port]/ e.g. http://203.0.113.150/

## Centos 7 Server
If you prefer to deploy the application in a centos 7 server o virtual machine then follow these steps

### LAMP
First let's set up a traditional LAMP configuration

	$ yum install -y httpd mariadb-server mariadb php php-mysql
	$ systemctl start httpd
	$ systemctl enable httpd
	$ systemctl start mariadb
	$ systemctl enable mariadb
	$ mysql_secure_installation //set the root password and answer yes to all questions
	
### Firewall and selinux
The simplest way to avoid connection problems is to disable the firewall and selinux, if you don't care about this the following commands will do the trick. If security is a major concern for you then it's up to you to configure those services.

	$ systemctl stop firewalld
	$ systemctl disable firewalld
	$ vim /etc/sysconfig/selinux //and change the value of SELINUX to disabled
	$ reboot //for changes take effect

### MySQL
To configure the MySQL database follow the next commands

    $ cd docker/mysql  //go to the docker/mysql folder of this repo
	$ mysql -u root -p //type the mysql root password
	> CREATE DATABASE bookstore;
	> GRANT ALL PRIVILEGES ON bookstore.* TO 'bookstore'@'localhost' IDENTIFIED BY 'bookstore';
	> FLUSH PRIVILEGES; //press ctrl + D to exit after this command
	$ mysql bookstore -u bookstore -pbookstore < ./bookstore.sql
	$ mysql bookstore -u bookstore -pbookstore < ./data.sql
	
### Apache
Now we're going to configure the apache service

	$ vim /etc/httpd/conf/httpd.conf //open the httpd configuration file
	
> _edit the **DocumentRoot** line_

> DocumentRoot /var/www/bookstore/public

> _add the following lines_

> \<Directory /var/www/bookstore/public\>

> 	Options Indexes FollowSymLinks

> 	AllowOverride All

> 	Require all granted

> \</Directory\>
	
	$ systemctl restart httpd
	
### FuelPHP
Then install the FuelPHP framework

	$ wget http://fuelphp.com/files/download/36
	$ unzip 36 -d /var/www

### BookStore
Finally install the bookstore app

	$ cp -rf bookstore/* /var/www/fuelphp-1.8/ //this is the bookstore folder inside this repo
	$ mv /var/www/fuelphp-1.8 /var/www/bookstore
	$ chown -R apache:pache /var/www/bookstore
	$ chmod -R g=u /var/www/bookstore

Now you can access the application typing in a web browser the ip of the centos machine

# Notes
* This is a LAMP project
* This is a SOA project
* This project uses the FuelPHP Framework (http://fuelphp.com/)
* This project runs on docker (https://www.docker.com/)
* This project is a modification of SharedBooks (https://github.com/jocamp18/SharedBooks)