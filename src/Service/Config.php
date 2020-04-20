<?php

namespace MPMA\ProIDConsumer\Service;

class Config
{
    private $data;

    public function __construct($configFile)
    {
        if (! file_exists($configFile)) {
            throw new \Exception("Arquivo de configuração não encontrado. [{$configFile}]", 1);
        }
        $this->data = json_decode(file_get_contents($configFile));
        if (! is_object($this->data)) {
            throw new \Exception("Arquivo de configuração possui erros. [{$configFile}]", 2);
        }
    }
    public function __get($attribute) {
        return $this->data->$attribute;
    }
    public function __set($attribute, $value) {
        $this->data->$attribute = $value;
        return $this;
    }
}