# Symfony World: Adventurs through Security Workshop

Well hi there! This repository holds the code that you'll need for the
"Symfony Security in Depth" workshop at SymfonyWorld 2021 Winter.

## Requirements

- PHP Version 7.4 or newer with all extensions required for Symfony, plus the GD extension
- The Symfony CLI (https://symfony.com/download)
- Composer 2.0
- Docker (for the database only - alternatively, you can use a local MySQL installation)

## Setup

Ok, let's get this code running!

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

**Database Setup**

If you want to setup a local database instance (e.g. MySQL), great!

But this code comes with a `docker-compose.yaml` file that can handle
creating a MySQL instance for you.

First, make sure you have [Docker installed](https://docs.docker.com/get-docker/)
and running. To start the container, run:

```
docker-compose up -d
```

Next, build the database and execute the migrations with:

```
# "symfony console" is equivalent to "bin/console"
# but it's aware of your database container
symfony console doctrine:database:create
symfony console doctrine:schema:update --force
symfony console doctrine:fixtures:load
```

(If you get an error about "MySQL server has gone away", just wait
a few seconds and try again - the container is probably still booting).

If you do *not* want to use Docker, just make sure to start your own
database server and update the `DATABASE_URL` environment variable in
`.env` or `.env.local` before running the commands above.

**Start the Symfony web server**

You can use Nginx or Apache, but Symfony's local web server
works even better.

To install the Symfony local web server, follow
"Downloading the Symfony client" instructions found
here: https://symfony.com/download - you only need to do this
once on your system.

Then, to start the web server, open a terminal, move into the
project, and run:

```
symfony serve
```

(If this is your first time using this command, you may see an
error that you need to run `symfony server:ca:install` first).

Now check out the site at `https://localhost:8000`

Have fun!

## Any Problems?

If you have any doubts or issues, no worries! Just send me a message:
ryan@symfonycasts.com.

<3 Your friends at SymfonyCasts
