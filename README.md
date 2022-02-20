## Lamia Assignment

### By: Sandesh Melkote Shivashankar

### Initialize project:

    docker-compose up -d

### Run composer install

    docker-compose -f docker-compose.yml run composer install

### DB Migration

    CREATE TABLE `products` (
    `id` varchar(255) NOT NULL DEFAULT '',
    `title` varchar(255) DEFAULT NULL,
    `price` double NOT NULL,
    `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

    CREATE TABLE `product_taxes` (    
      `productId` varchar(255) NOT NULL DEFAULT '',
      `country` varchar(2) NOT NULL DEFAULT '',
      `tax` double NOT NULL,
      `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`productId`,`country`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

### Dummy data:

    INSERT INTO `products` (`id`, `title`, `price`)
    VALUES
    ('product-bread', 'Bread', 2),
    ('product-milk', 'Milk', 1.53);

    INSERT INTO `product_taxes` (`productId`, `countryCode`, `tax`)
    VALUES
    	('product-milk', 'PL', 5),
    	('product-bread', 'FI', 12),
    	('product-milk', 'FI', 12);

### Run tests

    docker-compose -f docker-compose.yml run php vendor/bin/phpunit Test/
   

### Run API

###send as JSON response
    curl -i --location --request POST 'http://127.0.0.1/api/invoice/create' \
    --header 'Content-Type: application/json' \
    --data-raw '{
    "products": [
        {
            "productId": "product-milk",
            "quantity": 10
        },
        {
            "productId": "product-bread",
            "quantity": 5
        }
    ],
    "country": "FI",
    "invoiceFormat": "json",
    "isSendEmail": false,
    "email": ""
    }'

###send as an email
    curl -i --location --request POST 'http://127.0.0.1/api/invoice/create' \
    --header 'Content-Type: application/json' \
    --data-raw '{
    "products": [
        {
            "productId": "product-milk",
            "quantity": 10
        },
        {
            "productId": "product-bread",
            "quantity": 5
        }
    ],
    "country": "FI",
    "invoiceFormat": "json",
    "isSendEmail": true,
    "email": "sandeshm521@gmail.com"
    }'
