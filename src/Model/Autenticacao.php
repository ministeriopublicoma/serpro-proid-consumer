<?php
/**
 * Implementa autenticação
 * https://devserpro.github.io/apiserpro/apis/swagger-ui-master/dist/index.html?bearer=4e1a1858bdd584fdc077fb7d80f39283&url=https://devserpro.github.io/apiserpro/apis/swaggers/proid/swagger-proid-hom.json
*/
namespace MPMA\ProIDConsumer\Model;

define('ENDPOINT_TOKEN', '/v1/token');

class Autenticacao extends Model
{
    private $appId;
    private $appKey;
    private $scope;
    private $token;

    public function setAppID($appId)
    {
        $this->appId = $appId;
        return $this;
    }
    public function setAppKey($appKey)
    {
        $this->appKey = $appKey;
        return $this;
    }
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }
    private function generateToken()
    {
        $uri = $this->getConsumer()->getUri(ENDPOINT_TOKEN);
        $res = $this->getConsumer()
            ->getClient()
            ->request('POST', $uri, [
                'auth' => [
                    $this->appId,
                    $this->appKey
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'scope' => $this->scope
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
}