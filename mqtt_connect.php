<?php

require 'vendor/autoload.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

// MQTT Broker settings
$server = 'broker.emqx.io';
$port = 1883;
$clientId = 'php_mqtt_client';

// Function to get IP address
function getIPAddress()
{
    return gethostbyname(gethostname());
}

// MQTT Connection and Retry Logic
function connectAndPublish($retries = 5, $interval = 5)
{
    global $server, $port, $clientId;

    $mqtt = new MqttClient($server, $port, $clientId);
    $connectionSettings = (new ConnectionSettings())
        ->setKeepAliveInterval(60)
        ->setUsername(null)
        ->setPassword(null)
        ->setUseTls(false);

    $attempt = 0;
    while ($attempt < $retries) {
        try {
            $mqtt->connect($connectionSettings, true);
            echo "Connected to MQTT broker.\n";

            $ipAddress = getIPAddress();
            $mqtt->publish('device/ip', $ipAddress, 0);
            echo "Published IP Address: $ipAddress\n";

            $mqtt->disconnect();
            return true;
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage() . "\n";
            $attempt++;
            if ($attempt < $retries) {
                echo "Retrying in $interval seconds... (Attempt $attempt)\n";
                sleep($interval);
            }
        }
    }
    echo "Failed to connect after $retries attempts.\n";
    return false;
}

// Run the MQTT connection and publishing process
connectAndPublish();

?>