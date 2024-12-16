<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PleskService
{
    public function createSubdomain($subdomain)
    {
        $xmlRequest = "
        <packet version=\"1.6.9.1\">
            <system>
                <authentication>
                    <username>wegostores</username>
                    <password>Wegostores@3030</password>
                </authentication>
            </system>
            <subdomain>
                <add>
                    <parent>wegostores.com</parent>
                    <name>{$subdomain}</name>
                </add>
            </subdomain>
        </packet>
        ";

        // Send the request with Basic Authentication for the HTTP request itself
        $response = Http::withBasicAuth('wegostores', 'Wegostores@3030')
            ->withHeaders(['Content-Type' => 'application/xml'])
            ->post("https://wegostores.com:8443/enterprise/control/agent.php", [$xmlRequest]);

        return $response->json(); // Return the response as JSON
    }
    }


        // if (!$response->successful()) {
        //     dd($response->body());
        // }





        // if ($response->successful()) {
        //     return [
        //         'success' => true,
        //         'message' => 'Subdomain created successfully.',
        //         'data' => $response->body(),
        //     ];
        // }

        // return [
        //     'success' => false,
        //     'message' => 'Failed to create subdomain.',
        //     'error' => $response->body(),
        // ];



