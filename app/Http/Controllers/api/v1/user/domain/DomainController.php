<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
use App\Services\PleskService;

class DomainController extends Controller
{
    use PleskService;

    private $domains;

    protected $domainRequest = [
        'name',
        'store_id',
    ];

    public function __construct(Domain $domains)
    {
        $this->domains = $domains;
    }

    /**
     * Add a new domain and create a subdomain automatically.
     */
    public function add_domain(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'store_id' => 'required|exists:stores,id',
        ]);

        $domainRequest = $request->only($this->domainRequest);
        $domainRequest['user_id'] = $request->user()->id;

        $existingDomain = $this->domains
            ->where('name', $request->name)
            ->whereNull('status')
            ->first();

        if ($existingDomain) {
            return response()->json([
                'fail' => 'Domain is pending approval.',
            ], 400);
        }

        $domain = $this->domains->create($domainRequest);

        $response = $this->createSubdomain($domain->name);

        if ($response['success']) {
            return response()->json([
                'success' => 'Domain added and subdomain created successfully.',
                'plesk_response' => $response['data'],
            ], 201);
        }

        $domain->delete();

        return response()->json([
            'fail' => 'Failed to create subdomain.',
            'plesk_error' => $response['error'],
        ], 500);
    }
}
