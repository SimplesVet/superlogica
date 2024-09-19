<?php
namespace SuperLogica;

use SuperLogica\SuperLogicaApi;

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
    public function getcobrancamescliente($id, $inicio, $fim)
    {
        $this->curl->get($this->url . '/cobranca?CLIENTES[0]=' . $id .'&dtInicio=' . $inicio . '&dtFim=' . $fim);
        if($this->curl->http_status_code != 200){
            return false;
        }
        
        return json_decode(json_encode($this->curl->response), true);
    }
}
