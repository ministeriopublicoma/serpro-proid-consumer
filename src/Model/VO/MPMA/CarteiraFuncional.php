<?php

namespace MPMA\ProIDConsumer\Model\VO\MPMA;

use DadosFuncionais,
    ImagensFuncionais;

class CarteiraFuncional extends \MPMA\ProIDConsumer\Model\VO\CarteiraFuncional
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
    public function getTipo()
    {
        return $this->tipo;
    }
}
