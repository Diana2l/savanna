<?php

namespace App\Providers;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    protected $client;

    public function __construct()
    {
        $this->client = new MqttClient('broker.emqx.io', 1883, 'mqtt-publisher');
    }

    public function connect()
    {
        $connectionSettings = (new ConnectionSettings)
            ->setKeepAliveInterval(60)
            ->setLastWillTopic('test/paho/testament')
            ->setLastWillMessage('Client has disconnected')
            ->setLastWillQualityOfService(1);

        try {
            $this->client->connect($connectionSettings, true);
        } catch (\Exception $e) {
            echo 'Could not connect: ' . $e->getMessage();
            sleep(5);
            $this->connect();
        }
    }

    public function publish($topic, $message)
    {
        $this->client->publish($topic, $message, MqttClient::QOS_AT_LEAST_ONCE);
    }

    public function disconnect()
    {
        $this->client->disconnect();
    }

    public function getIpAddress()
    {
        $ip_address = gethostbyname(gethostname());
        return $_ip_address;
    }
}