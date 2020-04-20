# MPMA Consumer ProID SERPRO

Esta biblioteca tem o objetivo de interfacear a API ProID do SERPRO
para emissão, bloqueio, restrição, liberação e exclusão de documentos,
bem como envio de mensagens para o App ProID.

Foi desenvolvida pela Coordenadoria de Modernização e Tecnologia da Informação
do Ministério Público do Estado do Maranhão.

## Instalação

Para instalar a biblioteca no seu projeto, utilize o `composer`:

        composer require mpma/serpro-proid-consumer

## Arquivo de configuração padrão

```json
{
    "environment": "homol",
    "debug": false,
    "serpro": {
        "baseUrl": {
            "homol": "https://sandbox.proid.serpro.gov.br/api",
            "prod": "https://proid.serpro.gov.br/api"
        },
        "authUrl": {
            "homol": "https://valautentikus.estaleiro.serpro.gov.br/autentikus-authn/api",
            "prod": "https://autentikus.estaleiro.serpro.gov.br/autentikus-authn/api"
        },
        "appId": "ID da Aplicação",
        "appKey": "Chave da Aplicação"
    }
}
```

## Exemplos de Uso

### Estrutura básica do script

```php
define('CONFIG_FILE', 'config.json');

use \MPMA\ProIDConsumer\Service\Config,
    \MPMA\ProIDConsumer\Service\Consumer,
    \MPMA\ProIDConsumer\Model\VO\ImagemFuncional,
    \MPMA\ProIDConsumer\Model\VO\MPMA\DadosFuncionais,
    \MPMA\ProIDConsumer\Model\VO\MPMA\ImagensFuncionais,
    \MPMA\ProIDConsumer\Model\VO\MPMA\CarteiraFuncional,
    \MPMA\ProIDConsumer\Model\Document,
    \MPMA\ProIDConsumer\Model\Carteira;

require 'vendor/autoload.php';

$config = new Config(CONFIG_FILE);
$consumer = new Consumer();
$consumer->setConfig($config);
```

### Adicionar um novo documento

```php
$dadosFuncionais = new DadosFuncionais();
$dadosFuncionais
    ->setNumeroRegistro('0000001') // matrícula
    ->setNome('RICARDO AUGUSTO MARTINS COELHO')
    ->setCargo('ANALISTA MINISTERIAL')
    ->setEspecialidade('INFORMATICA')
    ->setRG('123456789-0')
    ->setEmissorRG('SSP')
    ->setUFRG('MA')
    ->setDtEmissaoRg('01/01/2001')
    ->setCPF('123.456.789-00')
    ->setDtNascimento('01/01/2000')
    ->setNaturalidade('SAO LUIS')
    ->setNomePai('NOME DO PAI')
    ->setNomeMae('NOME DA MAE')
    ->setUFNascimento('MA')
    ->setNacionalidade('BRASIL')
    ->setGrupoSanguineo('X')
    ->setFatorRH('+/-')
    ->setLocalExpedicao('SAO LUIS')
    ->setDataExpedicao('31/12/2020')
    ->setCodigo('123456');   // senha

$foto = new ImagemFuncional();
$foto->load('assets/foto.jpg');

$assinatura = new ImagemFuncional();
$assinatura->load('assets/assinatura.png');

$assinatura_pgj = new ImagemFuncional();
$assinatura_pgj->load('assets/assinatura_presidente.png');
$assinatura_pgj->chave = 'assinatura_presidente';

$imagensFuncionais = new ImagensFuncionais();
$imagensFuncionais
    ->setFoto($foto)
    ->setAssinatura($assinatura)
    ->setOutrasImagens([
        $assinatura_pgj
    ]);

$carteira = new CarteiraFuncional();
$carteira
    ->setDados($dadosFuncionais)
    ->setImagens($imagensFuncionais);

$document = new Document();
$document
    ->setConfig($config)
    ->setConsumer($consumer)
    ->add($carteira);
```

### Gerenciar um documento

```php
$dadosFuncionais = new DadosFuncionais();
$dadosFuncionais
    ->setNumeroRegistro('0000001'); // matrícula do portador do documento

$carteira = new CarteiraFuncional();
$carteira->setDados($dadosFuncionais);

$document = new Document();
$document
    ->setConfig($config)
    ->setConsumer($consumer);

/* Bloqueia o documento */
$document->block($carteira, 'Motivo do bloqueio');

/* Adiciona restrição a um documento */
$document->restrict($carteira, 'Descrição da restrição');

/* Ativa (remove bloqueios e/ou restrições) um documento */
$document->activate($carteira);

/* Exclui o documento */
$document->delete($carteira);
```

### Enviar uma mensagem direcionada

```php
$carteira = new CarteiraFuncional();
$document = new Document();
$document
    ->setConfig($config)
    ->setConsumer($consumer)
    ->sendMessage(
        $carteira,
        'Teste de mensagem direcionada',  // título
        'Este é um teste de mensagem ProID',   // conteúdo
        [ '0000001' ], // matrículas dos destinatários
        [ 'https://www.mpma.mp.br' => 'Site do Ministério Público do Estado do Maranhão' ] // link
    );
```

### Enviar uma mensagem para todos

```php
$carteira = new CarteiraFuncional();
$document = new Document();
$document
    ->setConfig($config)
    ->setConsumer($consumer)
    ->sendBroadcast(
        $carteira,
        'Teste de mensagem geral', // título
        'Este é um teste de broadcast ProID. Não é necessário informar destinatários.',  // conteúdo
        [ 'https://www.mpma.mp.br' => 'Site do Ministério Público do Estado do Maranhão' ] // link
    );
```
