## CartTest

#### Requirements

 - php >= 7.1
 - php-xdebug *
 - postgresql/mysql


#### Installation:

1. `cd /var/www/html` (or your web root folder)
2. `git clone https://github.com/opiy-org/cart-test.git .`
3. `composer install --no-dev`  or `composer install` (if APP_ENV=local or staging) 
4. `cp .env.example .env`
5. `vi .env `  (or any other editor you prefer)
6. `./site_deploy.sh`
7. `./site_deploy_dev.sh`  *



*optional, if APP_ENV=local or staging (for generating ide heplers, api docs, running tests)  

