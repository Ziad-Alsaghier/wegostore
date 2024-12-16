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

        $xmlRequest = "
<packet version=\"1.6.9.1\">
    <subdomain>
        <add>
            <parent>wegostores.com</parent>
            <name>testsubdomain</name>
        </add>
    </subdomain>
    <authentication>
        <username>wegostores</username>
        <password>Wegostores@3030</password>
    </authentication>
</packet>
";

$response = Http::withOptions(['timeout' => 30])
    ->withHeaders(['Content-Type' => 'application/xml'])
    ->post("https://wegostores.com:8443/enterprise/control/agent.php", $xmlRequest);


        if (!$response->successful()) {
            dd($response->body());
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
