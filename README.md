## Lamia Assignment

###By: Sandesh Melkote Shivashankar


### Initialize project:
    docker-compose up -d

### Run composer install
    docker-compose -f docker-compose.yml run composer install

### Run tests
    docker-compose -f docker-compose.yml run php vendor/bin/phpunit Test/