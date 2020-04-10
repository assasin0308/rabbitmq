<?php
/**
 * @Notes:
 * @Author: assasin<assasin0308@sina.com>
 * @Date: 2020/4/10 16:10
 */
require_once __DIR__.'/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
class  RabbitMq{
    private $queue;
    private $rabbitmq_host;
    private $rabbitmq_port;
    private $rabbitmq_user;
    private $rabbitmq_userpasswd;
    private $connection;
    private $channel;

    /**
     * RabbitMq constructor.
     * @param $rabbit_host
     * @param $rabbit_port
     * @param $rabbit_user
     * @param $rabbit_userpasswd
     * @param $queue
     */
    public function __construct($connection_arr){
            $this->rabbitmq_host = $connection_arr['rabbbit_host'];
            $this->rabbitmq_port = $connection_arr['rabbbit_port'];;
            $this->rabbitmq_user = $connection_arr['rabbbit_user'];;
            $this->rabbitmq_userpasswd = $connection_arr['rabbbit_userpasswd'];;
            $this->queue = $connection_arr['rabbbit_queue'];;
    }


    /**
     * @Notes: connection rabbitmq
     * @Author: assasin <assasin0308@sina.com>
     * @Date: 2020/4/10 16:15
     * @param:
     */
    public  function ConnectionRabbitMQ(){
        $this->connection = new AMQPStreamConnection(
            $this->rabbitmq_host,
            $this->rabbitmq_port,
            $this->rabbitmq_user,
            $this->rabbitmq_userpasswd
        );
        return $this->connection;
    }

    /**
     * @Notes: generate MQ channel
     * @Author: assasin <assasin0308@sina.com>
     * @Date: 2020/4/10 16:23
     * @param:
     */
    public  function declare_channel(){
        $this->channel = $this->connection->channel();
        //消息到达队列中后，如果没有一个消费者来处理消息的话，我们希望队列中的消息不要丢弃，也就是消息持久化。
        //在生产者和消费者中都要将queue_declare第3个参数设置为true，表示让消息队列持久化。
        $this->channel->queue_declare($this->queue,false,true,false,false);
        return $this->channel;
    }

    /**
     * @Notes: close channel
     * @Author: assasin <assasin0308@sina.com>
     * @Date: 2020/4/10 16:23
     * @param:
     */
    private function close_channel(){
        $this->channel->close();
    }

    /**
     * @Notes: close connnection
     * @Author: assasin <assasin0308@sina.com>
     * @Date: 2020/4/10 16:24
     * @param:
     */
    private function close_connection(){
        $this->connection->close();
    }

    /**
     * @Notes: close channel and close connection
     * @Author: assasin <assasin0308@sina.com>
     * @Date: 2020/4/10 16:25
     * @param:
     */
    public function close(){
        $this->close_channel();
        $this->close_connection();
    }







}