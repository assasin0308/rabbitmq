<?php
/**
 * @Notes:消息消费者--接收端
 * @Author: assasin<assasin0308@sina.com>
 * @Date: 2020/4/7 15:28
 */

require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

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

    echo ' [*] Waiting for messages. To exit press CTRL+C '.PHP_EOL;

    $callback = function($msg){
        echo "received message:",$msg->body,PHP_EOL;
        sleep(1);
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    };
    $channel->basic_qos(null,1,null);  //处理和确认完消息后再消费新的消息
    $channel->basic_consume($queue, '', false, false, false, false, $callback); //第4个参数值为false表示启用消息确认

    while (count($channel->callbacks)){
        $channel->wait();
    }

    $channel->close();
    $connection->close();