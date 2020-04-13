# rabbitmq
### install dependency

```json
yum -y install gcc glibc-devel make ncurses-devel openssl-devel xmlto perl wget gtk2-devel binutils-devel
```

### install Erlang

```json
wget http://erlang.org/download/otp_src_22.0.tar.gz
tar -zxvf otp_src_22.0.tar.gz
mv otp_src_22.0 /usr/local/
cd /usr/local/otp_src_22.0/
mkdir ../erlang
./configure --prefix=/usr/local/erlang
make install

# verift installed
ll /usr/local/erlang/bin

echo 'export PATH=$PATH:/usr/local/erlang/bin' >> /etc/profile
source /etc/profile

# test
erl

halt().  
```

### install Rabbitmq

```json
wget https://github.com/rabbitmq/rabbitmq-server/releases/download/v3.7.15/rabbitmq-server-generic-unix-3.7.15.tar.xz
yum install -y xz
/bin/xz -d rabbitmq-server-generic-unix-3.7.15.tar.xz
tar -xvf rabbitmq-server-generic-unix-3.7.15.tar
mv rabbitmq_server-3.7.15/ /usr/local/
mv /usr/local/rabbitmq_server-3.7.15  rabbitmq
echo 'export PATH=$PATH:/usr/local/rabbitmq/sbin' >> /etc/profile
source /etc/profile
mkdir /etc/rabbitmq
# start
rabbitmq-server -detached
# stop
rabbitmqctl stop
# status
rabbitmqctl status

# enable web management plugin
rabbitmq-plugins enable rabbitmq_management

# scan all users
rabbitmqctl list_users
# create a user 
rabbitmqctl add_user zhaobl 123456
# config privilege
rabbitmqctl set_permissions -p "/" zhaobl ".*" ".*" ".*"
# scan user's permission
rabbitmqctl list_user_permissions zhaobl
# config tag
rabbitmqctl set_user_tags zhaobl administrator
# delete default user
rabbitmqctl delete_user guest
```

### php-amqp

```json
{
    "require": {
        "php-amqplib/php-amqplib": ">=2.9.0"
    }
}

composer install 

```

```php
# send.php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
# create a connection to the server:
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
#  declare a queue for us to send to; then publish a message to the queue:
$channel->queue_declare('hello', false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!'\n";
# Lastly, close the channel and the connection;
$channel->close();
$connection->close();
```

```php
# receive.php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";
$callback = function ($msg) {
  echo ' [x] Received ', $msg->body, "\n";
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}

```

```shell
# Putting it all together
# run the consumer (receiver):
php receive.php
# then, run the publisher (sender):
php send.php

# Listing queues
sudo rabbitmqctl list_queues
```

