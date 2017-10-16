# OC BLOG - Project 5

1 . To create a new application, please start by cloning this repository.

```bash
$ git clone https://github.com/Smart4U/app-oc.git
```

2 . Add a new database on your system and run the migration
```bash
$ mysql -u root -p 
```
```sql
CREATE DATABSE my_database; quit;
```
```bash
$ vendor/bin/phinx migrate
```

3 . Edit the environment configuration file "example.env" correctly.
Then rename it to ".env".

4 . At the root of the project run in command line for download dependencies of the project. (This step requires composer on your server)
```bash
$ composer install; composer dump-autoload
```

5 . 3 . Then start building public files. (This step requires npm on your server)
```bash
$ npm install; npm run prod
```

6 . To take advantage of the cache and file upload it is necessary to give access to the folder storage
```bash
$ chmod 775 -R storage
```
#Configuration

### Apache 2

With .htaccess file
 
    <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
    </IfModule>

### Nginx 
Virtual host

    server {
        listen     80;
    
        root       /srv/http/netand/public/;
        index      index.html index.htm;
    
        server_name    netadn.dev;
        charset        utf-8;
    
        access_log /srv/http/app-oc/storage/logs/website.log;
        error_log /srv/http/app-oc/storage/logs/website.log;
    
        location / {
            index index.php;
            try_files $uri $uri/ /index.php?$args;
        }
    
        location ~ \.php$ {
            try_files $uri =404
    
            fastcgi_intercept_errors on;
   
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
            fastcgi_pass   unix:/var/run/php-fpm/php-fpm.sock;
            fastcgi_hide_header on;
            include        fastcgi.conf;
        }
    
    }
