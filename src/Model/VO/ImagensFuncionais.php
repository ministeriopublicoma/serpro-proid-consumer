<?php

namespace MPMA\ProIDConsumer\Model\VO;

use ImagemFuncional;

class ImagensFuncionais extends VO
{
    protected $foto;
    protected $assinatura;   
    protected $outras_imagens;

    public function valid()
    {
        return (
            $foto->valid() &&
            $assinatura->valid() &&
            $outras_imagens->valid()
        );
    }
}
