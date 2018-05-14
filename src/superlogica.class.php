<?php
namespace SuperLogica;

require_once(ROOT_GENESIS . "inc/superlogica/Api.php");
require_once(ROOT_GENESIS . "inc/superlogica/Clientes.php");
require_once(ROOT_GENESIS . "inc/superlogica/Configuracoes.php");
require_once(ROOT_GENESIS . "inc/superlogica/Contatos.php");
require_once(ROOT_GENESIS . "inc/superlogica/Planos.php");
require_once(ROOT_GENESIS . "inc/superlogica/Receitas.php");
require_once(ROOT_GENESIS . "inc/superlogica/Revenda.php");

/**
* Classe para encapsular as operações do Superlogica
*/
class Superlogica
{
    private $api;

    public function __construct()
    {
        $this->api = new Superlogica_Api(SUPERLOGICA_URLAPI);
        $this->api->login(SUPERLOGICA_USUARIO, SUPERLOGICA_SENHA, SUPERLOGICA_CONTA);
    }

    public function clienteToken($amb_int_codigo)
    {
        $arrRetorno = array();
        try {
            $mysql = new GDbMysql();
            $sql = "SELECT amb_var_email
		              FROM vw_adm_ambiente
		             WHERE amb_int_codigo = ?";
            $param = array("i", $amb_int_codigo);
            $mysql->execute($sql, $param);
            if ($mysql->fetch()) {
                $contatos = new Superlogica_Api_Contatos($this->api);
                $token = $contatos->getToken($mysql->res['amb_var_email']);
                $arrRetorno['token'] = $token;
                $arrRetorno['status'] = true;
                $arrRetorno['msg'] = 'Consulta realizada com sucesso';
            }
        } catch (GDbException $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getError();
        } catch (Exception $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getMessage();
        }
        $mysql->close();
        return $arrRetorno;
    }

    public function clienteArea($amb_int_codigo)
    {
        $arrRetorno = array();
        try {
            $mysql = new GDbMysql();
            $sql = "SELECT amb_var_email
		              FROM vw_adm_ambiente
		             WHERE amb_int_codigo = ?";
            $param = array("i", $amb_int_codigo);
            $mysql->execute($sql, $param);
            if ($mysql->fetch()) {
                $contatos = new Superlogica_Api_Contatos($this->api);
                // $url = $contatos->loginViaToken($mysql->res['amb_var_email']);
                $token = $contatos->getToken($mysql->res['amb_var_email']);

                $arrRetorno['token'] = $token;
                $arrRetorno['status'] = true;
                $arrRetorno['msg'] = 'Consulta realizada com sucesso';
            }
        } catch (GDbException $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getError();
        } catch (Exception $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getMessage();
        }
        $mysql->close();
        return $arrRetorno;
    }

    public function clienteInadimplente($amb_int_codigo)
    {
        $arrRetorno = array();
        try {
            $clientes = new Superlogica_Api_Clientes($this->api);
            $inadimplente = $clientes->inadimplente('SV-'.$amb_int_codigo, 0);
            $arrRetorno['inadimplente'] = $inadimplente;
            $arrRetorno['status'] = true;
            $arrRetorno['msg'] = 'Consulta realizada com sucesso';
        } catch (Exception $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getMessage();
        }
        return $arrRetorno;
    }

    public function clienteAlterar($amb_int_codigo, $amb_int_idsuperlogica)
    {
        $arrRetorno = array();
        try {
            $mysql = new GDbMysql();

            $sql = "SELECT amb_var_razaosocial, amb_var_nome, amb_var_cnpj,
		      			   amb_var_email, amb_var_telefone, NULL AS amb_var_celular,
		      			   amb_cha_tipopessoa, end_var_endereco, end_var_numero,
		      			   end_var_complemento, end_var_bairro, end_var_municipio,
		      			   end_var_uf, end_var_cep, amb_int_diafatura,
		      			   amb_dtf_inclusao
		              FROM vw_adm_ambiente
		             WHERE amb_int_codigo = ?";
            $param = array("i", $amb_int_codigo);
            $mysql->execute($sql, $param);
            if ($mysql->fetch()) {
                $arrCliente = array(
                    'ST_NOME_SAC' => empty($mysql->res['amb_var_razaosocial']) ? $mysql->res['amb_var_nome'] : $mysql->res['amb_var_razaosocial'],
                    'ST_NOMEREF_SAC' => $mysql->res['amb_var_nome'],
                    'ST_CGC_SAC' => $mysql->res['amb_var_cnpj'],
                    'ST_EMAIL_SAC' => $mysql->res['amb_var_email'],
                    'ST_TELEFONE_SAC' => $mysql->res['amb_var_telefone'],
                    'ST_DIAVENCIMENTO_SAC' => $mysql->res['amb_int_diafatura'],
                    'ST_ENDERECO_SAC' => $mysql->res['end_var_endereco'],
                    'ST_NUMERO_SAC' => $mysql->res['end_var_numero'],
                    'ST_COMPLEMENTO_SAC' => $mysql->res['end_var_complemento'],
                    'ST_BAIRRO_SAC' => $mysql->res['end_var_bairro'],
                    'ST_CIDADE_SAC' => $mysql->res['end_var_municipio'],
                    'ST_ESTADO_SAC' => $mysql->res['end_var_uf'],
                    'ST_CEP_SAC' => $mysql->res['end_var_cep'],
                    'FL_MESMOEND_SAC' => '1'
                    // 'ST_CELULAR_SAC' => $mysql->res['amb_var_celular'],
                    // 'FL_PESSOAJURIDICA_SAC' => (($mysql->res['amb_cha_tipopessoa'] == 'J') ? '1' : '0'),
                    // 'DT_CADASTRO_SAC' => $mysql->res['amb_dtf_inclusao']
                );

                $clientes = new Superlogica_Api_Clientes($this->api);
                $clientes->alterar($amb_int_idsuperlogica, $arrCliente);
                $arrRetorno['status'] = true;
                $arrRetorno['msg'] = 'Ambiente alterado com sucesso';
            } else {
                $arrRetorno['status'] = false;
                $arrRetorno['msg'] = 'Não encontrado';
            }
        } catch (GDbException $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getError();
        } catch (Exception $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getMessage();
        }
        $mysql->close();
        return $arrRetorno;
    }

    public function clienteAssinar($amb_int_codigo, $amb_int_idsuperlogica)
    {
        $arrRetorno = array();
        try {
            $mysql = new GDbMysql();

            $sql = "SELECT pla_var_chave, pla_dec_valor, pla_var_chave, amb_daf_adesao,
	                       date_format(greatest(amb_dat_experimentacaofim, amb_dat_adesao), '%m/%d/%Y') AS amb_dat_descontoinicio,
	                       date_format(adddate(greatest(amb_dat_experimentacaofim, amb_dat_adesao), INTERVAL 2 MONTH), '%m/%d/%Y') AS amb_dat_descontofim,
	                       date_format(adddate(greatest(amb_dat_experimentacaofim, amb_dat_adesao), INTERVAL 2 MONTH), '%m/%Y') AS amb_daf_descontofim,
	                       date_format(adddate(greatest(amb_dat_experimentacaofim, amb_dat_adesao), INTERVAL 3 MONTH), '%m/%d/%Y') AS amb_dat_cheioinicio
		              FROM vw_adm_ambiente
		             WHERE amb_int_codigo = ?";
            $param = array("i", $amb_int_codigo);
            $mysql->execute($sql, $param);
            if ($mysql->fetch()) {
                $clientes = new Superlogica_Api_Clientes($this->api);
                $clientes->contratar($amb_int_idsuperlogica, $mysql->res['pla_var_chave']);

                // $receitas = new Superlogica_Api_Receitas($this->api);
                // $receitas->novaRecorrente(
                // 	$amb_int_idsuperlogica,
                // 	'DESC',
                // 	($mysql->res['pla_dec_valor'] / 2) * -1,
                // 	1, // ID conta
                // 	$mysql->res['amb_dat_descontoinicio'],
                // 	30, // Periodicidade
                // 	1, // Quantidade
                // 	'Desconto de 50% até ' . $mysql->res['amb_daf_descontofim'],
                // 	$mysql->res['amb_dat_descontofim']
                // );

                $mysql->freeResult();

                $query = "CALL sp_slo_contrata_retorno(?,?,@p_status,@p_msg)";
                $param = array('is', $amb_int_codigo, $amb_int_idsuperlogica);

                $mysql->execute($query, $param, false);
                $mysql->execute('SELECT @p_status, @p_msg');
                $mysql->fetch();

                $return['status'] = ($mysql->res[0]) ? true : false;
                $return['msg'] = $mysql->res[1];

                if ($return['status']) {
                    $arrRetorno['status'] = true;
                    $arrRetorno['msg'] = 'Assinatura criada com sucesso';
                } else {
                    $arrRetorno = $return;
                }
            } else {
                $arrRetorno['status'] = false;
                $arrRetorno['msg'] = 'Ambiente não encontrado';
            }
        } catch (GDbException $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getError();
        } catch (Exception $exc) {
            $arrRetorno['status'] = false;
            $arrRetorno['msg'] = $exc->getMessage();
        }
        $mysql->close();
        return $arrRetorno;
    }

    // public function clienteContratar($amb_int_codigo) {
    // 	$arrRetorno = array();
    // 	try {
    // 	    $mysql = new GDbMysql();

    // 	    $sql = "SELECT pla_var_chave, pla_dec_valor,
    //                        date_format(greatest(amb_dat_experimentacaofim, amb_dat_adesao), '%m/%d/%Y') AS amb_dat_descontoinicio,
    //                        date_format(adddate(greatest(amb_dat_experimentacaofim, amb_dat_adesao), INTERVAL 2 MONTH), '%m/%d/%Y') AS amb_dat_descontofim,
    //                        date_format(adddate(greatest(amb_dat_experimentacaofim, amb_dat_adesao), INTERVAL 2 MONTH), '%m/%Y') AS amb_daf_descontofim,
    //                        date_format(adddate(greatest(amb_dat_experimentacaofim, amb_dat_adesao), INTERVAL 3 MONTH), '%m/%d/%Y') AS amb_dat_cheioinicio
    // 	              FROM vw_adm_ambiente
    // 	             WHERE amb_int_codigo = ?";
    // 	    $param = array("i", $amb_int_codigo);
    // 	    $mysql->execute($sql, $param);
    // 	    if($mysql->fetch()){
    // 		    var_dump($mysql->res);
    // 			$receitas = new Superlogica_Api_Receitas($this->api);
    // 			$receitas->novaRecorrente(
    // 				'SV-'.$amb_int_codigo,
    // 				$mysql->res['pla_var_chave'],
    // 				$mysql->res['pla_dec_valor'] / 2,
    // 				1, // ID conta
    // 				$mysql->res['amb_dat_descontoinicio'],
    // 				30, // Periodicidade
    // 				1, // Quantidade
    // 				'Desconto de 50% até ' . $mysql->res['amb_daf_descontofim'],
    // 				$mysql->res['amb_dat_descontofim']
    // 			);
    // 			$receitas->novaRecorrente(
    // 				'SV-'.$amb_int_codigo,
    // 				$mysql->res['pla_var_chave'],
    // 				$mysql->res['pla_dec_valor'],
    // 				1, // ID conta
    // 				$mysql->res['amb_dat_cheioinicio'],
    // 				30, // Periodicidade
    // 				1, // Quantidade
    // 				null,
    // 				null
    // 			);
    // 			$arrRetorno['status'] = true;
    // 			$arrRetorno['msg'] = 'Adicionados com sucesso';
    // 	    } else {
    // 			$arrRetorno['status'] = false;
    // 			$arrRetorno['msg'] = 'Não encontrado';
    // 	    }

    // 	} catch (GDbException $exc) {
    // 		$arrRetorno['status'] = false;
    // 		$arrRetorno['msg'] = $exc->getError();
    // 	} catch (Exception $exc) {
    // 		$arrRetorno['status'] = false;
    // 		$arrRetorno['msg'] = $exc->getMessage();
    // 	}
    // 	$mysql->close();
    // 	return $arrRetorno;
    // }
}
