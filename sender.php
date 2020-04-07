<?php
/**
 * @Notes: 消费生产者--分发任务
 * @Author: assasin<assasin0308@sina.com>
 * @Date: 2020/4/7 15:13
 */

require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

    $queue = 'worker';
    $connection = new AMQPStreamConnection(
        '192.168.1.118',
        '15672',
        'assasin',
        '123456'
    );
    $channel = $connection->channel();
    //消息到达队列中后，如果没有一个消费者来处理消息的话，我们希望队列中的消息不要丢弃，也就是消息持久化。
    //在生产者和消费者中都要将queue_declare第3个参数设置为true，表示让消息队列持久化。
    $channel->queue_declare($queue,false,true,false,false);

    for($i = 0;$i < 100;$i++){
        $arr = [
            'id' => 'message_'.$i,
            'order_id' => str_replace('.', '' , microtime(true)) . mt_rand(10, 99) . $i,
            'content' => 'hello-assasin-'.time(),
        ];
        $data = json_encode($arr);
        $msg = new AMQPMessage($data,[
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            // 设置rabbitmq重启后也不会丢失队列，或者设置为'delivery_mode' => 2
        ]);
        $channel->basic_publish($msg,'',$queue);
        echo 'send message'.$data.PHP_EOL;
    }

    $channel->close();
    $connection->close();