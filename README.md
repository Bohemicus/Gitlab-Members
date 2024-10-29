# Description
A simple command-line tool that returns the members of a group and its subgroups, providing as output list of members with projects and their associated groups and subgroups.

Developed with Symfony 7.1.

The tool also has a web UI based on EasyAdmin4.

# Requirements
- Have Docker installed
- Have Docker compose installed
- Have free port 9013 for the web tool

# Installation
- Do the clone of the project
- Create your own .env.local file by copying it from .env.local_sample
- Run the following commands:
```bash
docker-compose up -d --build
docker-compose exec -T app bash -c "cd .. && composer install"
docker-compose exec -T app bash -c "cd .. && php bin/console doctrine:database:create"
docker-compose exec -T app bash -c "cd .. && php bin/console doctrine:schema:create"
```


# Using the tool
Run the following command to see the results:

```bash
docker-compose exec app  bash
```
Once in the container bash environment place, move to the root of the project
through:
```bash
cd ..
```
Then finally launch the tool via command line:
```bash
php bin/console app:gitlab-members
```

# Other things
- The container has already configured a Mercure and Redis server for future developments.
- Code check via PHP Stan (level 5) and PHP CodeSniffer.
```bash
./vendor/bin/phpstan analyse -l 5 src 
./vendor/bin/phpcs  src
```
