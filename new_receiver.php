<?php
/**
 * @Notes:
 * @Author: assasin<assasin0308@sina.com>
 * @Date: 2020/4/10 16:53
 */
    require_once './rabbitmq_lib.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;



    // config your connection params
    $connection_arr = [
        'rabbbit_host' => '192.168.1.118',
        'rabbbit_port' => '5672',
        'rabbbit_user' => 'assasin',
        'rabbbit_userpasswd' => '123456',
        'rabbbit_queue' => 'worker',
    ];
    // instance of rabbitmq
    $rabbitmq = new RabbitMq($connection_arr);
    // connection rabbitmq
    $rabbitmq_connection = $rabbitmq->ConnectionRabbitMQ();
    // decalre channel of rabbitmq
    $channel = $rabbitmq->declare_channel();

    // start your code

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
    

    // close all
    $rabbitmq->close();



