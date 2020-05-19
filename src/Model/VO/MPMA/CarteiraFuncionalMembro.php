<?php

namespace MPMA\ProIDConsumer\Model\VO\MPMA;

define('CARTEIRA_FUNCIONAL_MPMA_MEMBRO', 'f964d251-59e0-4f33-beab-68a88feb10be');

class CarteiraFuncionalMembro extends CarteiraFuncional
{
    public function __construct()
    {
        parent::__construct();
        $this->tipo = (object) [
            'id' => CARTEIRA_FUNCIONAL_MPMA_MEMBRO
        ];
    }
}
