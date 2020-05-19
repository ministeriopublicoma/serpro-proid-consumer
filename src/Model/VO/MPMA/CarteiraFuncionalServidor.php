<?php

namespace MPMA\ProIDConsumer\Model\VO\MPMA;

define('CARTEIRA_FUNCIONAL_MPMA_SERVIDOR', 'dc188e69-5e45-4b7e-abdd-c88bc719ac8d');

class CarteiraFuncionalServidor extends CarteiraFuncional
{
    public function __construct()
    {
        parent::__construct();
        $this->tipo = (object) [
            'id' => CARTEIRA_FUNCIONAL_MPMA_SERVIDOR
        ];
    }
}
