<?php

namespace App\services;

use Illuminate\Support\Facades\Http;

class PleskService
{
    private $username = 'wegostores';
    private $password = 'Wegostores@3030';

    public function createSubdomain($subdomain)
    {
        // Construct the XML request body
        $xmlRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.9.1">
    <system>
        <authentication>
            <username>{$this->username}</username>
            <password>{$this->password}</password>
        </authentication>
    </system>
    <subdomain>
        <add>
            <parent>wegostores.com</parent>
            <name>{$subdomain}</name>
        </add>
    </subdomain>
</packet>
XML;

// Send the request with Basic Authentication for the HTTP request itself
$response = Http::withBasicAuth($this->username, $this->password)
->withHeaders(['Content-Type' => 'application/xml'])
->withoutVerifying() // Disable SSL verification for testing
->post("https://wegostores.com:8443/enterprise/control/agent.php", $xmlRequest);

// Check if the request was successful and return the response
if ($response->successful()) {
// Return the raw XML data as part of the response
return [
'success' => true,
'message' => 'Subdomain created successfully.',
'data' => $response->body(),
];
}

// Handle failure response
return [
'success' => false,
'message' => 'Failed to create subdomain.',
'error' => $response->body(),
];
}
}
