## Lamia Assignment

### By: Sandesh Melkote Shivashankar

### Initialize project:

    docker-compose up -d

### Run composer install

    docker-compose -f docker-compose.yml run composer install

### Connect to mysql service using

    docker exec -it mysql-container bash
    mysql -u root -p root
    use lamia_assignment;
And run following DB queries to setup dummy data:

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
      `countryCode` varchar(2) NOT NULL DEFAULT '',
      `tax` double NOT NULL,
      `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`productId`,`countryCode`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    
    CREATE TABLE `orders` (    
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `orderId` varchar(255) NOT NULL DEFAULT '',
      `productId` varchar(255) NOT NULL DEFAULT '',
      `quantity` int(11) NOT NULL,
      `basePrice` double NOT NULL,
      `taxPrice` double NOT NULL,
      `totalPrice` double NOT NULL,
      `email` varchar(255) DEFAULT NULL,
      `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `productId` (`productId`,`orderId`),
      KEY `orderId` (`orderId`)
    ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

### Dummy data:

    INSERT INTO `products` (`id`, `title`, `price`)
    VALUES
    ('product-bread', 'Bread', 2.88),
    ('product-milk', 'Milk', 1.53);

    INSERT INTO `product_taxes` (`productId`, `countryCode`, `tax`)
    VALUES
    	('product-bread', 'PL', 5),
    	('product-bread', 'FI', 12),
    	('product-milk', 'PL', 10),
    	('product-milk', 'FI', 19);

### Run tests

    docker-compose -f docker-compose.yml run php vendor/bin/phpunit Test/
   

### Run API

###send as JSON response
    curl -i --location --request POST 'http://127.0.0.1/api/order/create' \
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
    curl -i --location --request POST 'http://127.0.0.1/api/order/create' \
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
