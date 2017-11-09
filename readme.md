## CartTest

#### Requirements

 - php >= 7.1
 - php-xdebug *
 - postgresql/mysql


#### Installation:

1. `cd /var/www/html` (or your web root folder)
2. `git clone https://github.com/opiy-org/cart-test.git .`
3. `composer install --no-dev`  or `composer install` * 
4. `cp .env.example .env`
5. `vi .env `  (or any other editor you prefer)  **
6. `./site_deploy.sh`
7. `./site_deploy_dev.sh`  *



*optional, if APP_ENV=local or staging (for generating ide heplers, api docs, running tests)

**  configuration:
```
APP_ENV=local  - for local | staging - dev installation | production
APP_DEBUG=true | false
APP_LOG_LEVEL=debug | error

APP_URL=http://your.site.ru
SESSION_DOMAIN=your.site.ru

SESSION_LIFETIME=5 (minutes cart live)
SESSION_SECURE_COOKIE=true (if https)

DB_CONNECTION=pgsql | mysql

BROADCAST_DRIVER=redis | log | null
CACHE_DRIVER=redis | file
and so on...

``` 

  

