<?php
/**
 * @Notes: classify rabbbitmq
 * @Author: assasin<assasin0308@sina.com>
 * @Date: 2020/4/10 16:26
 */
require_once('./rabbitmq_lib.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
$connection_arr = [
    'rabbbit_host' => '192.168.1.118',
    'rabbbit_port' => '5672',
    'rabbbit_user' => 'assasin',
    'rabbbit_userpasswd' => '123456',
    'rabbbit_queue' => 'worker',
];
$rabbitmq = new RabbitMq($connection_arr);
$rabbitmq_connection = $rabbitmq->ConnectionRabbitMQ();
$channel = $rabbitmq->declare_channel();
for($i = 0;$i < 100;$i++){
    $arr = [
        'id' => 'message_'.$i,
        'order_id' => str_replace('.', '' , microtime(true)) . mt_rand(10, 99) . $i,
        'content' => 'hello-assasin-'.time(),
    ];
    $data = json_encode($arr);
//    $msg = new AMQPMessage($data,[
//        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
//        // 设置rabbitmq重启后也不会丢失队列，或者设置为'delivery_mode' => 2
//    ]);
    $msg = new AMQPMessage($data,[
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
    ]);
    $channel->basic_publish($msg,'',$this->queue);
    echo 'send message'.$data.PHP_EOL;
}

$rabbitmq->close();