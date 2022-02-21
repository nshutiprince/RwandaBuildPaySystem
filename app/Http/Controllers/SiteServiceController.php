<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SiteServiceController extends Controller
{
    public $accessToken;

    /**
     * Helper us get a user token from the site service that we need to authenticate
     */
    public function __construct()
    {
        $response = Http::asForm()->post('https://test.siteservices.murugocloud.com/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => env('SITE_SERVICE_CLIENT_ID'),
            'client_secret' => env('SITE_SERVICE_CLIENT_SECRET'),
            'scope' => '',
        ]);

        $this->accessToken = $response->json()['access_token'];
    }

    /**
     * Helper us search for a location
     * Needs an access token
     * The api needs a key and an entry
     */
    public function searchLocation()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->asForm()->post('https://test.siteservices.murugocloud.com/api/v2/search-location', [
            'key' => 'name',
            'entry' => 'Kebab House'
        ]);

        return $response->json();
    }

    /**
     * Helper us search for an organization
     * Needs an access token
     * The api receive an entry as parameter
     */
    public function searchOraganization()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->asForm()->post('https://test.siteservices.murugocloud.com/api/v2/search-organizations', [
            'entry' => 'bar'
        ]);

        return $response->json();
    }

    /**
     * Return a list of all approved location
     * Needs an access token
     */
    public function getApprovedLocation()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->get('https://test.siteservices.murugocloud.com/api/v2/paginated-locations');

        return $response->json();
    }

    /**
     * Return a list of all approved organization
     * Needs an access token
     */
    public function getApprovedOraganization()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])->get('https://test.siteservices.murugocloud.com/api/v2/paginated-organizations');

        return $response->json();
    }

    /**
     * Adds a location to the site service
     * Needs an access token
     * The api receive mainly the murugo location id,name
     */
    public function addLocation()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->accessToken,
        ])
        ->asForm()
        ->post('https://test.siteservices.murugocloud.com/api/v2/submit-organization', [
            'murugo_location_id' => '7',
            'name' => 'Lux',
            'phone' => '74455112222',
        ]);

        return $response->json();
    }
}
