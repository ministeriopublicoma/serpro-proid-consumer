<?php
/**
 * Implementa gestão de documentos em conformidade com as definições disponíveis em
 * https://devserpro.github.io/apiserpro/apis/swagger-ui-master/dist/index.html?bearer=4e1a1858bdd584fdc077fb7d80f39283&url=https://devserpro.github.io/apiserpro/apis/swaggers/proid/swagger-proid-hom.json
*/
namespace MPMA\ProIDConsumer\Model;

define('ENDPOINT_MENSAGENS', '/v1/mensagens');
define('ENDPOINT_DOCUMENTOS', '/v1/documentos');
define('ENDPOINT_DOCUMENTOS_BLOQUEIO', '/v1/documentos/bloqueio');
define('ENDPOINT_DOCUMENTOS_RESTRICAO', '/v1/documentos/restricao');
define('ENDPOINT_DOCUMENTOS_ATIVACAO', '/v1/documentos/ativacao');
define('ENDPOINT_DOCUMENTOS_EXCLUSAO', '/v1/documentos/exclusao');

class CarteiraFuncional extends Model
{
    protected $tipo;
    protected $dados;
    protected $imagens;

    public function __construct($consumer, $id, $dados = null, $imagens = null)
    {
        parent::__construct($consumer);
        $this->tipo = (object) [
            'id' => $id
        ];
        $this->dados = $dados;
        $this->imagens = $imagens;
    }
    public function valid()
    {
        return (
            $dados->valid() &&
            $imagens->valid()
        );
    }
    public function getDataObject()
    {
        $properties = (object) [
            'tipo' => $this->tipo,
            'dados' => $this->dados,
            'imagens' => $this->imagens
        ];
        return $properties;
    }
    public function __toString()
    {
        return json_encode($this->getDataObject(), JSON_UNESCAPED_SLASHES);
    }
    public function add()
    {
        return $this->getConsumer()->post(ENDPOINT_DOCUMENTOS, $this->getDataObject());
    }
    public function block($motivo)
    {
        $block = (object) [
            'tipo' => $this->tipo,
            'documento' => (object) [
                'numero_registro' => $this->dados->getNumeroRegistro()
            ],
            'motivo' => $motivo
        ];
        return $this->getConsumer()->post(ENDPOINT_DOCUMENTOS_BLOQUEIO, $block);
    }
    public function restrict($motivo)
    {
        $restrict = (object) [
            'tipo' => $this->tipo,
            'documento' => (object) [
                'numero_registro' => $this->dados->getNumeroRegistro()
            ],
            'motivo' => $motivo
        ];
        return $this->getConsumer()->post(ENDPOINT_DOCUMENTOS_RESTRICAO, $restrict);
    }
    public function activate()
    {
        $activate = (object) [
            'tipo' => $this->tipo,
            'documento' => (object) [
                'numero_registro' => $this->dados->getNumeroRegistro()
            ]
        ];
        return $this->getConsumer()->post(ENDPOINT_DOCUMENTOS_ATIVACAO, $activate);
    }
    public function delete()
    {
        $delete = (object) [
            'tipo' => $this->tipo,
            'documento' => (object) [
                'numero_registro' => $this->dados->getNumeroRegistro()
            ]
        ];

        // A API pede método POST também para exclusão
        return $this->getConsumer()->post(ENDPOINT_DOCUMENTOS_EXCLUSAO, $delete);
    }
    private function doSendMessage($object)
    {
        return $this->getConsumer()->post(ENDPOINT_MENSAGENS, $object);
    }
    public function sendBroadcast($titulo, $conteudo, $links = [], $validadeInicio = '', $validadeFim = '')
    {
        if (empty($validadeInicio))
            $validadeInicio = date('Y-m-d\T00:00:00-03:00');
        if (empty($validadeFim))
            $validadeFim = date('Y-m-d\T23:59:59-03:00');

        $messageLinks = [];
        foreach ($links as $valor => $titulo) {
            $messageLinks[] = (object) [
                'titulo' => $titulo,
                'valor' => $valor
            ];
        }

        $message = (object) [
            'titulo' => $titulo,
            'conteudo' => $conteudo,
            'destino' => 'BROADCAST',
            'tipo' => $this->tipo,
            'validade' => (object) [
                'inicio' => $validadeInicio,
                'fim' => $validadeFim
            ],
            'links' => $messageLinks
        ];
        $this->doSendMessage($message);
    }
    public function sendMessage($titulo, $conteudo, $destinatarios, $links = [], $validadeInicio = '', $validadeFim = '')
    {
        if (empty($validadeInicio))
            $validadeInicio = date('Y-m-d\T00:00:00-03:00');
        if (empty($validadeFim))
            $validadeFim = date('Y-m-d\T23:59:59-03:00');

        $messageDestinatatios = [];
        foreach ($destinatarios as $numeroRegistro) {
            $messageDestinatatios[] = (object) [
                'numero_registro' => $numeroRegistro
            ];
        }

        if (count($links) > 1) {
            throw new \Exception("Apenas um link por mensagem", 4000);
        }
        
        $messageLinks = [];
        foreach ($links as $valor => $titulo) {
            $messageLinks[] = (object) [
                'titulo' => $titulo,
                'valor' => $valor
            ];
        }

        $message = (object) [
            'titulo' => $titulo,
            'conteudo' => $conteudo,
            'destino' => 'DIRECIONADA',
            'tipo' => $this->tipo,
            'destinatarios' => $messageDestinatatios,
            'validade' => (object) [
                'inicio' => $validadeInicio,
                'fim' => $validadeFim
            ],
            'links' => $messageLinks
        ];
        $this->doSendMessage($message);
    }
}