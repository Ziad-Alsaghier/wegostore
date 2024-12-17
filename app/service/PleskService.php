<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PleskService
{
    private $username = 'wegostores';
    private $password = 'Wegostores@3030';

    public function createSubdomain($subdomain)
    {
        // Correct XML packet structure
        $xmlRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<packet>
    <subdomain>
        <add>
            <parent>wegostores.com</parent>
            <name>{$subdomain}</name>
            <property>
                <name>www_root</name>
                <value>/httpdocs/{$subdomain}</value>
            </property>
        </add>
    </subdomain>
</packet>
XML;

        // Send the request with proper headers and Basic Authentication
        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeaders(['Content-Type' => 'application/xml'])
            ->withoutVerifying() // Skip SSL verification for now (if necessary)
            ->send('POST', 'https://wegostores.com:8443/enterprise/control/agent.php', [
                'body' => $xmlRequest,
            ]);

        // Check and return the response
        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Subdomain created successfully.',
                'data' => $response->getStatusCode(),
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create subdomain.',
            'error' => $response->body(),
        ];
    }
}
