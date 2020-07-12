# vending-machine

### Requirements
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Examples in this repo
- [x] ***No Framework***
- [x] **DDD** (Domain Driver Design)
- [x] ***Dependency injection*** and container with [PHP-DI](http://php-di.org/)
- [x] ***CommandBus*** and ***CommandQuery*** with [Tactician](https://tactician.thephpleague.com)
- [x] Unit testing with [PHPUnit](https://phpunit.de/)
- [x] Implement **DomainEvents**
- [x] Implement **Subscriber** to update vending machine status
- [x] Simple **EventStore** (var/log/domain_events.log)
- [x] Simple **Repository** with json file and serialize entity with [json-serializer](https://github.com/zumba/json-serializer)
- [x] **CLI** with [Symfony console](https://symfony.com/doc/current/components/console.html)

### Installation

Clone this repository
```sh
$ git clone git@github.com:albertcolom/vendor-machine.git
```
Start docker compose
```sh
$ docker-compose up -d
```
### CLI
```sh
$ docker-compose exec php bin/console
```
Available commands
```sh
vending-machine
    vending-machine:coin:add       Add coin into the machine
    vending-machine:coin:add:user  User add coin into the machine
    vending-machine:coin:refund    Refund user coins
    vending-machine:create         Create vending machine with catalog and wallet
    vending-machine:create:empty   Create empty vending machine
    vending-machine:product:add    Add product into the machine
    vending-machine:product:buy    Buy product
    vending-machine:summary        Vending machine summary
```
Example commands with parameters
```sh
$ docker-compose exec php bin/console vending-machine:coin:add 0.1
$ docker-compose exec php bin/console vending-machine:coin:add:user 0.1
$ docker-compose exec php bin/console vending-machine:coin:refund
$ docker-compose exec php bin/console vending-machine:create
$ docker-compose exec php bin/console vending-machine:create:empty
$ docker-compose exec php bin/console vending-machine:product:add water 1
$ docker-compose exec php bin/console vending-machine:product:buy water
$ docker-compose exec php bin/console vending-machine:summary
```
### Read live file event log
```sh
$ docker-compose exec php tail -f var/log/domain_events.log
```
Sample Output
```sh
[2020-07-12 19:03:09] "CoinAmountWasCreated" {"coin_type":0.05,"quantity":1}
[2020-07-12 19:03:09] "CoinAmountWasCreated" {"coin_type":0.1,"quantity":1}
[2020-07-12 19:03:09] "CoinAmountWasCreated" {"coin_type":1,"quantity":1}
[2020-07-12 21:20:25] "ProductLineWasCreated" {"product_type":"water","price":1,"quantity":1}
[2020-07-12 21:40:01] "CoinAmountWasRemoved" {"coin_type":1,"quantity":3}
[2020-07-12 21:40:01] "ProductLineWasRemoved" {"product_type":"juice","price":1,"quantity":1}
```

### Test
```sh
$ docker-compose exec php bin/phpunit
```
### Screenshots
- Success message:

![Success](https://i.imgur.com/kb4iZZa.png)

- Error message:

![Error](https://i.imgur.com/1VXo8dk.png)

- Vending machine summary:

![Summary](https://i.imgur.com/XDcZxyF.png)
