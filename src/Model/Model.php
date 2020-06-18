<?php

namespace MPMA\ProIDConsumer\Model;

class Model
{
    private $consumer;

    public function __construct($consumer = null)
    {
        $this->consumer = $consumer;
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