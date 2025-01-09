<?php

namespace App\Http\Controllers;

use App\Providers\MqttService;

class MqttController extends Controller
{
    protected $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    public function publishIp()
    {
        $this->mqttService->connect();

        $ip_address = $this->mqttService->getIpAddress();
        $this->mqttService->publish('your/topic', $ip_address);

        $this->mqttService->disconnect();

        return response()->json(['ip_address' => $ip_address]);
}
}