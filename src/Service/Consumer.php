<?php

namespace Service;

define('ENDPOINT_TOKEN', '/v1/token');

define('E401', 'Erro de autenticação');
define('E403', 'Sem permissão');
define('E422', 'Erro de validação ou de negócio');
define('E502', 'Erro durante alguma integração do servidor, a solicitação deve ser reenviada');

class Consumer
{
    private $client;
    private $config;
    private $token;

    public function __construct($config = null)
    {
        $this->client = new \GuzzleHttp\Client();
        $this->token = '';
        if (! empty($config))
            $this->config = $config;
    }
    public function setConfig($config)
    {
        $this->config = $config;
    }
    private function throwException($code)
    {
        throw new \Exception(constant(
            'E' . $code
        ), (1000 + $code));
    }
    private function getUri($endpoint)
    {
        $env = $this->config->environment;
        return $this->config->serpro->baseUrl->$env . $endpoint;
    }
    private function getAuthUri($endpoint)
    {
        $env = $this->config->environment;
        return $this->config->serpro->authUrl->$env . $endpoint;
    }
    private function generateToken()
    {
        $uri = $this->getAuthUri(ENDPOINT_TOKEN);

        $res = $this->client->request('POST', $uri, [
            'auth' => [
                $this->config->serpro->appId,
                $this->config->serpro->appKey
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
                'scope' => 'escopo_proid'
            ]
        ]);
        if (200 != $res->getStatusCode()) {
            throw new \Exception(
                "Erro ao gerar token de autenticação:\n" . $res->getBody()
            , 10);
        }
        if ('application/json' != $res->getHeader('content-type')[0]) {
            throw new \Exception(
                "Resposta deveria ser 'application/json' ao gerar token de autenticação:\n" . $res->getBody()
            , 11);
        }
        $response = json_decode($res->getBody());
        if ('Bearer' != $response->token_type) {
            throw new \Exception(
                "Token_type deveria ser 'Bearer'. Retornado {$response->token_type}"
            , 12);
        }
        return $response->access_token;
    }
    public function getToken()
    {
        if (empty($this->token)) {
            $this->token = $this->generateToken();
        }
        return $this->token;
    }
    public function __call($method, $args)
    {
        $token = $this->getToken();
        call_user_func_array(
            [$this->client, $method],
            $args
        );
    }
    public function post($endpoint, $obj)
    {
        if ($obj instanceof \stdClass)
            $obj = json_encode($obj);

        $token = $this->getToken();
        $res = $this->client->request(
            'POST',
            $this->getUri($endpoint),
            [
                'version' => 2,
                'force_ip_resolve' => 'v4',
                'debug' => $this->config->debug,
                'headers' => [
                    'authorization' => "Bearer {$token}",
                    'content-type' => 'application/json'
                ],
                'body' => $obj
            ]
        );
    }
}
