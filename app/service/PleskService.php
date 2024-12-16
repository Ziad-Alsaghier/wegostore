<?php

namespace App\Services;

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
<packet version="1.6.3.0">
    <system>
        <authentication>
            <username>{$this->username}</username>
            <password>{$this->password}</password>
        </authentication>
    </system>
    <subdomain>
        <add>
            <parent>wegostores.com</parent>  <!-- Change this to your actual domain -->
            <name>{$subdomain}</name>
        </add>
    </subdomain>
</packet>
XML;

        // Send the request with Basic Authentication for the HTTP request itself
        $response = Http::withBasicAuth($this->username, $this->password) // Basic Auth
            ->withHeaders(['Content-Type' => 'application/xml'])
            ->withoutVerifying() // Disable SSL verification for testing (not for production)
            ->post("https://wegostores.com:8443/enterprise/control/agent.php", $xmlRequest);

        // Check if the request was successful and return the response
        if ($response->successful()) {
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
