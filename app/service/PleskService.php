<?php

namespace App\service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;

trait PleskService
{
    protected $client;
    protected $pleskUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
    }

    public function createSubdomain($subdomain)
    {
        $this->client = new Client();
        $this->pleskUrl = 'https://wegostores.com:8443'; // Set Plesk URL in .env file
        $this->username = 'wegostores'; // Plesk admin username
        $this->password = 'Wegostores@3030'; // Plesk admin password
        // Prepare the XML request to create a subdomain
        $xmlRequest = <<<XML
        <?xml version="1.0" encoding="UTF-8"?>
        <packet version="1.6.3.0">
            <subdomain>
                <add>
                    <parent>https://wegostores.com</parent>
                    <name>{$subdomain}</name>
                </add>
            </subdomain>
        </packet>
        XML;
        // Send the request to the Plesk API
        $response = Http::withBasicAuth($this->username, $this->password)
        ->withHeaders(['Content-Type' => 'application/xml'])
        ->post($this->pleskUrl . '/enterprise/control/agent.php', $xmlRequest);

        // Handle the response from Plesk
        if ($response->successful()) {
            return $response->body();
        } else {
            return 'Error: ' . $response->body();
        }
    }
}
