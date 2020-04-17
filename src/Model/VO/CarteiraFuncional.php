<?php

namespace Model\VO;

use DadosFuncionais,
    ImagensFuncionais;

class CarteiraFuncional extends VO
{
    protected $tipo;
    protected $dados;
    protected $imagens;

    public function __construct()
    {
        parent::__construct();
    }
    public function valid()
    {
        return (
            $dados->valid() &&
            $imagens->valid()
        );
    }
    public function setId($id)
    {
        $this->tipo = (object) [
            'id' => $id
        ];        
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function getNumeroRegistro()
    {
        return $this->dados->getNumeroRegistro();
    }
}
