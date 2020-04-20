<?php

namespace MPMA\ProIDConsumer\Model\VO;

use ImagemFuncional;

class ImagensFuncionais extends VO
{
    protected $foto;
    protected $assinatura;   

    public function valid()
    {
        return (
            $foto->valid() &&
            $assinatura->valid()
        );
    }
}
