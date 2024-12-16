<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PleskService
{
    protected $pleskUrl;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->pleskUrl = config('plesk.url');
        $this->username = config('plesk.username');
        $this->password = config('plesk.password');
    }

    public function createSubdomain($subdomain)
    {
        $parentDomain = parse_url($this->pleskUrl, PHP_URL_HOST);

        $xmlRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.9.1">
    <system>
        <authentication>
            <username>wegostores</username>
            <password>Wegostores@3030</password>
        </authentication>
    </system>
    <subdomain>
        <add>
            <parent>{$parentDomain}</parent>
            <name>{$subdomain}</name>
        </add>
    </subdomain>
</packet>
XML;

        $response = Http::withOptions(['verify' => false, 'timeout' => 30]) // Increased timeout
            ->withBasicAuth($this->username, $this->password)
            ->withHeaders(['Content-Type' => 'application/xml'])
            ->post("{$this->pleskUrl}/enterprise/control/agent.php", $xmlRequest);

        if (!$response->successful()) {
            \Log::error('Plesk Subdomain Creation Failed', ['error' => $response->body()]);
        }





        if ($response->successful()) {
            return [
                'success' => true,
                'message' => 'Subdomain created successfully.',
                'data' => $response->body(),
            ];
        }

        return [
            'success' => false,
            'message' => 'Failed to create subdomain.',
            'error' => $response->body(),
        ];
    }
}
