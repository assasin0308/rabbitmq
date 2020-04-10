<?php
/**
 * @Notes: classify rabbbitmq
 * @Author: assasin<assasin0308@sina.com>
 * @Date: 2020/4/10 16:26
 */
    require_once './rabbitmq_lib.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;
    use PhpAmqpLib\Message\AMQPMessage;

    // config your connection params
    $connection_arr = [
        'rabbbit_host' => '192.168.1.118',
        'rabbbit_port' => '5672',
        'rabbbit_user' => 'assasin',
        'rabbbit_userpasswd' => '123456',
        'rabbbit_queue' => 'test',
    ];
    // instance of rabbitmq
    $rabbitmq = new RabbitMq($connection_arr);
    // connection rabbitmq
    $rabbitmq_connection = $rabbitmq->ConnectionRabbitMQ();
    // decalre channel of rabbitmq
    $channel = $rabbitmq->declare_channel();



    // start your code
    for($i = 0;$i < 100;$i++){
        $arr = [
            'id' => 'message_'.$i,
            'order_id' => str_replace('.', '' , microtime(true)) . mt_rand(10, 99) . $i,
            'content' => 'hello-assasin-'.time(),
        ];
        // send your msg
        $rabbitmq->send_msg('xzkj_admin',$arr);
    }



    // the end  close all
    $rabbitmq->close();