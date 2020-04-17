<?php

namespace Model\VO\MPMA;

use Model\VO\ImagemFuncional;

class ImagensFuncionais extends \Model\VO\ImagensFuncionais
{
    protected $outras_imagens;

    public function valid()
    {
        $valid = parent::valid();
        return ($valid && $outras_imagens->valid());
    }
}
