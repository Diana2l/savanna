<?php

// Function to get the current IP address
function getIPAddress()
{
    return gethostbyname(gethostname());
}

// Return IP address as JSON
header('Content-Type: application/json');
echo json_encode(['ip' => getIPAddress()]);