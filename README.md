# paragin-assignment

## requirements:

- php >=7.4
- node & yarn
- [symfony binary](https://symfony.com/download)
- [docker desktop](https://www.docker.com/products/docker-desktop)

## setup

**1) download composer dependencies**

make sure you have [composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

you may alternatively need to run `php composer.phar install`, depending
on how you installed composer.

**2) database setup**

make sure [docker](https://www.docker.com/products/docker-desktop) is downloaded and running on your machine. 
then, from inside the project, run:

```
docker-compose up -d
```

**3) start webserver**

if you're on a mac and are running the symfony server for the first time, in your terminal run
```
symfony server:ca:install
```

then, to start the webserver, from within the project directory run:

```
symfony serve -d
```

**4) load database schema**

to load the database schema, make sure the [symfony binary](https://symfony.com/download) is installed and from within the project directory run:

```
symfony console doctrine:migrations:migrate -n
```

**5) install assets**

to install the node packages and compile the assets, from within the project directory run:

```
yarn install --force
yarn run encore dev
```

**6) visit application**

follow the link displayed in your terminal at the end of step 3 (usually https://127.0.0.1:8000)
