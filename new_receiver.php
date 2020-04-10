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
        'rabbbit_queue' => 'test',
    ];
    // instance of rabbitmq
    $rabbitmq = new RabbitMq($connection_arr);
    // connection rabbitmq
    $rabbitmq_connection = $rabbitmq->ConnectionRabbitMQ();
    // decalre channel of rabbitmq
    $channel = $rabbitmq->declare_channel();

    // start your code

    $rabbitmq->receive_msg('test-2');


    // close all
    $rabbitmq->close();



