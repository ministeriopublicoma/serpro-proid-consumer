<?php

namespace Model\VO\MPMA;

define('CARTEIRA_FUNCIONAL_MPMA', 'dc188e69-5e45-4b7e-abdd-c88bc719ac8d');

use DadosFuncionais,
    ImagensFuncionais;

class CarteiraFuncional extends \Model\VO\CarteiraFuncional
{
    protected $tipo;
    protected $dados;
    protected $imagens;

    public function __construct()
    {
        parent::__construct();
        $this->tipo = (object) [
            'id' => CARTEIRA_FUNCIONAL_MPMA
        ];
    }
    public function valid()
    {
        return (
            $dados->valid() &&
            $imagens->valid()
        );
    }
    public function getTipo()
    {
        return $this->tipo;
    }
}
