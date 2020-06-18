<?php

namespace MPMA\ProIDConsumer\Service;

define('E401', 'Erro de autenticação');
define('E403', 'Sem permissão');
define('E422', 'Erro de validação ou de negócio');
define('E502', 'Erro durante alguma integração do servidor, a solicitação deve ser reenviada');

class Consumer
{
    private $baseUrl;
    private $debug;
    private $token;
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client();
    }
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }
    private function throwException($code)
    {
        throw new \Exception(constant(
            'E' . $code
        ), (1000 + $code));
    }
    public function getUri($endpoint)
    {
        return $this->baseUrl . $endpoint;
    }
    public function __call($method, $args)
    {
        call_user_func_array(
            [$this->client, $method],
            $args
        );
    }
    public function getClient()
    {
        return $this->client;
    }
    public function post($endpoint, $obj)
    {
        if ($obj instanceof \stdClass)
            $obj = json_encode($obj);

        $res = $this->client->request(
            'POST',
            $this->getUri($endpoint),
            [
                'version' => 2,
                'force_ip_resolve' => 'v4',
                'debug' => $this->debug,
                'headers' => [
                    'authorization' => "Bearer {$this->token}",
                    'content-type' => 'application/json'
                ],
                'body' => $obj
            ]
        );
    }
}
