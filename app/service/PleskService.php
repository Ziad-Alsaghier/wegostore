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
<packet version="1.6.9.1">
    <system>
        <authentication>
            <username>{$this->username}</username>
            <password>{$this->password}</password>
        </authentication>
    </system>
    <subdomain>
        <add>
            <parent>https://wegostores.com:8443</parent>
            <name>{$subdomain}</name>
        </add>
    </subdomain>
</packet>
XML;

        // Send the request without Basic Authentication in the header, using only XML credentials
        $response = Http::withHeaders(['Content-Type' => 'application/xml'])
            ->withoutVerifying() // Disable SSL verification for testing
            ->post("https://wegostores.com:8443/enterprise/control/agent.php", $xmlRequest); // Note: No Basic Auth in the header

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
