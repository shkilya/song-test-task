### ELDORADO TEST TASK

## Installation

### Declare Docker network
```bash
docker network create -d bridge --subnet 192.168.82.0/24 --gateway 192.168.82.1 eldorado_song_backend
```
```bash
docker network create -d bridge song_message_bus
```

### Docker Up
```bash
docker-compose up -d
```

### Composer install
```bash
./composer.sh install
```

### Install RabbitMQ queues + exchanges:
```bash
./rabbit.sh vhost:mapping:create /usr/src/app/rabbitmq/song_vhost.yml -Hrabbitmq -uguest -pguest
```

## Consumers

### Song created 
```
./console.sh swarrot:consume:song_created
```


## Development

### Web address
[http://localhost:8820/](http://localhost:8820/)

### Console commands
```bash
./console.sh --help
```

### Doctrine migrations
```bash
./console.sh do:mi:mi --no-interaction
```

### Doctrine schema diff
```bash
./console.sh do:mi:di --no-interaction
```

### PostgreSQL
```yaml
Host: 127.0.0.1
Port: 5820
User: developer
Pass: password
```


### X-Debug configuration
1. Configure server with name docker and setup a path-mapping to /usr/src/app
2. Listen to port 9820

## Tests

### Generate JWT keys
```bash
openssl req -newkey rsa:2048 -new -nodes -x509 -days 3650 -keyout "var/test-jwt-private.key" -out "var/test-jwt-certificate.pem"
```
```bash
sudo chown www-data:www-data var/jwt-private.key
```
