<?php
namespace SuperLogica;

use \Curl\Curl;

class SuperLogicaApi
{
    protected $url 			= SUPERLOGICA_API_URL;
    protected $urlOld 		= SUPERLOGICA_API_URL_OLD;
    protected $app_token 	= SUPERLOGICA_API_APPTOKEN;
    protected $api_secret 	= SUPERLOGICA_API_SECRET;
    protected $access_token = SUPERLOGICA_API_ACCESSTOKEN;
    protected $curl;

    public function __construct()
    {
        $this->curl = new Curl();
        $this->curl->setHeader('app_token', $this->app_token);
        $this->curl->setHeader('access_token', $this->access_token);
    }

    public function decode($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $array[$key] = utf8_decode($value);
            }
        }
        return $array;
    }
}
