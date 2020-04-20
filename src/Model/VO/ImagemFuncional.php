<?php

namespace MPMA\ProIDConsumer\Model\VO;

class ImagemFuncional extends VO
{
    protected $base64;
    protected $tipo;

    public function load($filename)
    {
        $binary = file_get_contents($filename);
        $this->base64 = base64_encode($binary);
        $this->tipo = preg_replace('_([^/]+)/([^/]+)_', '\2', mime_content_type($filename));
    }
    public function save($basename, $addExtension = true)
    {
        if ($addExtension)
            $filename = "{$basename}.{$this->tipo}";
        $binary = base64_decode($this->base64);
        file_put_contents($filename, $binary);
    }
    public function valid()
    {
        return true;
    }
}
