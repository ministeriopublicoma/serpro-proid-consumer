# MPMA Consumer ProID SERPRO

Esta biblioteca tem o objetivo de interfacear a API ProID do SERPRO
para emissão, bloqueio, restrição, liberação e exclusão de documentos,
bem como envio de mensagens para o App ProID.

Foi desenvolvida pela Coordenadoria de Modernização e Tecnologia da Informação
do Ministério Público do Estado do Maranhão.

## Instalação

Para instalar a biblioteca no seu projeto, utilize o `composer`:

```
composer require mpma/serpro-proid-consumer:2.0.0
```

## Dados Fornecidos pelo SERPRO

Os dados abaixo serão fornecidos pelo SERPRO e são diferentes em homologação e produção. Entre parênteses está o nome do parâmetro que usamos nos exemplos a seguir.

* URL Base (```$config->baseUrl```)
* URL de Autenticação (```$config->authUrl```)
* Id da Aplicação (```$config->appId```)
* Chave da Aplicação (```$config->appKey```)
* Escopo de Autenticação (```$config->scope```)
* ID do Documento de Membro (```$config->docIdMembro```)
* ID do Documento de Servidor (```$config->docIdServidor```)

## Exemplos de Uso

### Estrutura básica do script

```php
use \MPMA\ProIDConsumer\Service\Consumer,
    \MPMA\ProIDConsumer\Model\VO\ImagemFuncional,
    \MPMA\ProIDConsumer\Model\VO\ImagensFuncionais,
    \MPMA\ProIDConsumer\Model\VO\DadosFuncionais,
    \MPMA\ProIDConsumer\Model\CarteiraFuncional,
    \MPMA\ProIDConsumer\Model\Autenticacao;

require 'vendor/autoload.php';

$authConsumer = new Consumer();
$dataConsumer = new Consumer();

$authConsumer
    ->setBaseUrl($config->authUrl)
    ->setDebug(true);

$autenticacao = new Autenticacao($authConsumer);
$token = $autenticacao
    ->setAppId($config->appId)
    ->setAppKey($config->appKey)
    ->setScope($config->scope)
    ->getToken();

$dataConsumer
    ->setBaseUrl($config->baseUrl)
    ->setDebug(true)
    ->setToken($token);
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
$assinatura_pgj->load('assets/assinatura_pgj.png');
$assinatura_pgj->chave = 'assinatura_presidente';  // chave SERPRO desta imagem

/* Este QRCode é gerado internamente e só é apresentado na cópia digital
 * da carteira impressa. Não confundir com o QRCode Vio, gerado pelo
 * ProID autormaticamente.
 */
$qrcode = new ImagemFuncional();
$qrcode->load('assets/qrcode.png');
$qrcode->chave = 'qrcode';    // chave SERPRO desta imagem

$imagensFuncionais = new ImagensFuncionais();
$imagensFuncionais
    ->setFoto($foto)
    ->setAssinatura($assinatura)
    ->setOutrasImagens([
        $assinatura_pgj,
        $qrcode
    ]);

$carteira = new CarteiraFuncional(
    $dataConsumer,
    $config->docIdServidor,  // ou $config->docIdMembro
    $dadosFuncionais,
    $imagensFuncionais
);

$carteira->add();
```

### Gerenciar um documento

```php
$dadosFuncionais = new DadosFuncionais();
$dadosFuncionais->setNumeroRegistro('0000001'); // matrícula

$carteira = new CarteiraFuncional(
    $dataConsumer,
    $config->docIdServidor,  // ou $config->docIdMembro
    $dadosFuncionais
);

/* Bloqueia o documento */
$carteira->block('Motivo do bloqueio');

/* Adiciona restrição a um documento */
$carteira->restrict('Descrição da restrição');

/* Ativa (remove bloqueios e/ou restrições) um documento */
$carteira->activate();

/* Exclui o documento */
$carteira->delete();
```

### Enviar uma mensagem direcionada

```php
$carteira = new CarteiraFuncional(
    $dataConsumer,
    $config->docIdServidor  // ou $config->docIdMembro
);

$carteira->sendMessage(
    'Teste de mensagem direcionada',  // título
    'Este é um teste de mensagem ProID',   // conteúdo
    [ '0000001' ], // matrículas dos destinatários
    [ 'https://www.mpma.mp.br' => 'Site do Ministério Público do Estado do Maranhão' ] // link (opcional)
);
```

O método ```sendMessage``` aceita dois outros parâmetros opcionais após o link para informar o início e o fim da validade da mensagem. Estes campos são do tipo _timestamp_ com _timezone_, que pode ser gerado com ```date('Y-m-d\TH:i:s-03:00')```.

### Enviar uma mensagem para todos

```php
$carteira = new CarteiraFuncional(
    $dataConsumer,
    $config->docIdServidor  // ou $config->docIdMembro
);

$carteira->sendBroadcast(
    'Teste de mensagem geral', // título
    'Este é um teste de broadcast ProID. Não é necessário informar destinatários.',  // conteúdo
    [ 'https://www.mpma.mp.br' => 'Site do Ministério Público do Estado do Maranhão' ] // link (opcional)
);
```

O método ```sendBroadcast``` aceita dois outros parâmetros opcionais após o link para informar o início e o fim da validade da mensagem. Estes campos são do tipo _timestamp_ com _timezone_, que pode ser gerado com ```date('Y-m-d\TH:i:s-03:00')```.
