# Notes API

Backend for Notes API

## Install dependencies


```
composer install
npm install
```

## Create Keys

In order to use JWT you need to create the certificates; to do this, you need to do as following:

- Make a new directory to hold the keys: `mkdir var/jwt`
- Create a private key (set a password that you'll use really soon): `openssl genrsa -out var/jwt/private.pem -aes256 4096`
- Create the public key: `openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem`
- Edit .env (copy from .env.dist if it does not exist) and set the selected password for the variable JWT_PASSPHRASE

## Edit Environment variables

- Open .env and make changes as necessary

## Create Environment

To create this environment you need recent docker compose and docker versions and [ahoy](https://github.com/ahoy-cli/ahoy)
After installing that, you should run:

```
ahoy up
ahoy site install
npm run encore dev
chmod 777 -R var/cache/dev/easy_admin/
```

And voilá! You can access your app at the url provided by `ahoy docker url`

## Installed Stuff

### Nginx

Nginx is running in port 80. Use `ahoy docker url` to get the url.

### PHP-FPM

PHP is running in a separate container using fpm in port 9000 (not accessible from host).

### Postgres

You can find credentials in environment variables in docker-compose.yml.

### Adminer

This is a simple database admin UI. To access it, you should go to the url provided by `ahoy docker adminer-url`

### Cli

This container have some cli utilities to manage your app. See https://hub.docker.com/r/kporras07/docker-drupal-cli/ for more info.

In order to access the cli, you should run `ahoy bash`. Now, you can run commands like ahoy, composer, node, grunt, python, the Symfony console inside the container.

### Selenium

Set wd_host to 'http://browser:4444/wd/hub' in behat config. If you need vnc, you can connect to url given by `ahoy docker vnc-url` using 'secret' as password.

### Ngrok

This is used to share your local environment over the internet. In order to do this, you should run `ahoy docker share-url`. This command will give you an url that you can access in your browser and get the share urls for http and https.

### Varnish

It's a reverse proxy usually used in production. It's bundled here for situations where you need to test with the varnish cache. In order to access the site through varnish, run `ahoy docker varnish-url` and open that url in your browser.

### Mailhog

To see the the mailhog UI, run `ahoy docker mailhog-url` and access that url from the browser. Your new messages will appear there.

### Traefik

This is a proxy used to route request for given domain to nginx backend. To use it, you just need to have a .env file in the root of this folder with `VIRTUAL_HOST` variable set to any wanted domain. When you run `ahoy up` the magic will happen and you'll be able to open that domain in your browser and get your site. If you need more than one domain, set them in the same variable separated by comma.
