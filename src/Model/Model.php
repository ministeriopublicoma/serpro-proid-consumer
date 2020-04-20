<?php

namespace MPMA\ProIDConsumer\Model;

class Model
{
    private $config;
    private $consumer;

    public function __construct($config = null, $consumer = null)
    {
        $this->config = $config;
        $this->consumer = $consumer;
    }
    public function setConfig($config)
    {
        $this->config = $config;
        return $this;
    }
    public function getConfig()
    {
        return $this->config;
    }
    public function setConsumer($consumer)
    {
        $this->consumer = $consumer;
        return $this;
    }
    public function getConsumer()
    {
        return $this->consumer;
    }
}