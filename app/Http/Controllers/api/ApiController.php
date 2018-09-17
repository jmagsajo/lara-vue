<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use App\Models\api\OauthClients as oauthModel;

class ApiController extends Controller
{
    public function getToken(Request $request){

    	$client_secret = oauthModel::select('secret')->where('id', 2)->first();
    	$http = new Client;
		
	    $response = $http->post(url('oauth/token'), [
	        'form_params' => [
		        'grant_type' => 'client_credentials',
		        'client_id' => 2,
		        'client_secret' => $client_secret->secret,
		        'scope' => '',
		    ],
	    ]);

	    return $response;
	}
}
