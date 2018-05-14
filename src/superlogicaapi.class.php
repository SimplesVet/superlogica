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

/**
 *
 */
class SuperLogicaApi_Clientes extends SuperLogicaApi
{
    public function get($id = '', $comp = '?apenasColunasPrincipais=1&status=0')
    {
        $id = (!empty($id)) ? '/' . $id : '';
        $this->curl->get($this->url . '/clientes' . $id . $comp);

        return json_decode(json_encode($this->curl->response), true);
    }

    public function put($param)
    {
        $urlParam = http_build_query($this->decode($param));
        $this->curl->put($this->url . '/clientes?' . $urlParam);

        return json_decode(json_encode($this->curl->response), true);
    }

    public function post($param)
    {
        $this->curl->post($this->url . '/clientes', $this->decode($param));

        return json_decode(json_encode($this->curl->response), true);
    }

    public function token($param)
    {
        $urlParam = http_build_query($param);
        $this->curl->get($this->url . '/clientes/token?' . $urlParam);

        return json_decode(json_encode($this->curl->response), true);
    }

    public function checkout($param)
    {
        $this->curl->post($this->url . '/checkout', $this->decode($param));

        return json_decode(json_encode($this->curl->response), true);
    }

    public function assinatura($param)
    {
        $this->curl->post($this->url . '/assinaturas', $this->decode($param));

        return json_decode(json_encode($this->curl->response), true);
    }

    public function faturar($param)
    {
        $urlParam = http_build_query($this->decode($param));
        $this->curl->post($this->url . '/faturar?' . $urlParam);

        return json_decode(json_encode($this->curl->response), true);
    }
}
/**
 *
 */
class SuperLogicaApi_Cobrancas extends SuperLogicaApi
{
    public function get($id = '', $comp = '?apenasColunasPrincipais=1&status=0')
    {
        $id = (!empty($id)) ? '/' . $id : '';
        $this->curl->get($this->url . '/cobranca' . $id . $comp);

        return json_decode(json_encode($this->curl->response), true);
    }

    public function put($param)
    {
        $urlParam = http_build_query($this->decode($param));
        $this->curl->put($this->url . '/cobranca?' . $urlParam);

        return json_decode(json_encode($this->curl->response), true);
    }

    public function post($param)
    {
        $urlParam = http_build_query($this->decode($param));
        $this->curl->post($this->url . '/cobranca?' . $urlParam);

        return json_decode(json_encode($this->curl->response), true);
    }

    public function itens($param)
    {
        $urlParam = http_build_query($this->decode($param));
        $this->curl->post($this->url . '/cobrancaitens?' . $urlParam);

        return json_decode(json_encode($this->curl->response), true);
    }

    public function getallrecorrencias($param)
    {
        //echo $this->decode($urlParam);
        $this->curl->post($this->url . '/recorrencias/recorrenciasdocliente', $param);

        return json_decode(json_encode($this->curl->response), true);
    }

    public function getrecorrencias($param)
    {
        $urlParam = http_build_query($this->decode($param));
        $this->curl->get($this->url . '/recorrencias/recorrenciasdocliente?' . $urlParam);
        return json_decode(json_encode($this->curl->response), true);
    }

    public function putrecorrencias($param)
    {
        $urlParam = http_build_query($this->decode($param));
        $this->curl->put(urldecode($this->url . '/recorrencias?' . $urlParam));

        return json_decode(json_encode($this->curl->response), true);
    }
}
