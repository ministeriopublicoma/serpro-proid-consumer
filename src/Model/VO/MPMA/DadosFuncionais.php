<?php

namespace MPMA\ProIDConsumer\Model\VO\MPMA;

class DadosFuncionais extends \MPMA\ProIDConsumer\Model\VO\DadosFuncionais
{
    protected $nome;
    protected $cargo;
    protected $especialidade;
    protected $rg;
    protected $emissor_rg;
    protected $uf_rg;
    protected $dt_emissao_rg;
    protected $cpf;
    protected $dt_nascimento;
    protected $naturalidade;
    protected $nome_pai;
    protected $nome_mae;
    protected $uf_nascimento;
    protected $nacionalidade;
    protected $grupo_sanguineo;
    protected $fator_rh;
    protected $local_expedicao;
    protected $data_expedicao;
    protected $codigo;

    public function valid()
    {
        return true;
    }
}
