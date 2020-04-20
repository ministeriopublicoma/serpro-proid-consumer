<?php

namespace MPMA\ProIDConsumer\Model\VO\MPMA;

use Model\VO\ImagemFuncional;

class ImagensFuncionais extends \MPMA\ProIDConsumer\Model\VO\ImagensFuncionais
{
    protected $outras_imagens;

    public function valid()
    {
        $valid = parent::valid();
        return ($valid && $outras_imagens->valid());
    }
}
