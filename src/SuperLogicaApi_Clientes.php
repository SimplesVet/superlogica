<?php
namespace SuperLogica;

use SuperLogica\SuperLogicaApi;

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
