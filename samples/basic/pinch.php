<?php 
require __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

// Merchant Credentials - Found in API Keys menu (click your name in the portal)
$merchant_id = '';
$secret_key = '';
$publishable_key = '';

// Create Auth endpoint client using Guzzle
$auth_client = new Client([
    'base_uri'=>  'https://auth.getpinch.com.au/'
]);

// Create main API client using Guzzle
$mode = 'test'; // Set to 'live' when ready for production.
$client = new Client([
    'base_uri' => 'https://api.getpinch.com.au/'.$mode.'/'
]);

// Fetch an Access Token

function get_access_token($auth_client, $merchant_id, $secret_key)
{    
    $authResponse = $auth_client->request('POST', 'connect/token', [
        'auth' => [$merchant_id, $secret_key],
        'form_params' => [
            'grant_type' => 'client_credentials',
            'scope' => 'api1'
        ]
    ]);

    $jsonData = json_decode($authResponse->getBody());

    return $jsonData->access_token;
}

// Helper functions
function get_pinch_headers($access_token) 
{
    return [        
        'Authorization' => 'Bearer '.$access_token,
        'Pinch-Version' => '2017.2'
    ];
}

function pinch_http_get($endpoint, $client, $access_token) {
    $response = $client->request('GET', $endpoint, [
        'headers' => get_pinch_headers($access_token),
        'http_errors' => false
    ]);
    return json_decode($response->getBody());
}

function pinch_http_post($endpoint, $client, $access_token, $data) {
    $response = $client->request('POST', $endpoint, [
        'headers' => get_pinch_headers($access_token),
        'http_errors' => false,
        'json' => $data
    ]);
    return json_decode($response->getBody());
}

?>