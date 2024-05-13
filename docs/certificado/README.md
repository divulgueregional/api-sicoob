# CERTIFICADO

## Introdução
Você precisará gerar 3 certificados.

- .pfx em base64 (usado para gerar a aplicação dentro do Sicoob). Gerei pelo navegador exportando o certificado instalado.
- .pem chave publica (usado na biblioteca para executar os métodos)
- .pem chave privada (key) (usado na biblioteca para executar os métodos)


## Gerar o certicado publico e privado

Esses dois certificado serão apontados dentro do $config<br>
```php
$config = [
  'certificate' => './api-sicoob/path/cert.pem',//local do certificado crt
  'certificateKey' => './api-sicoob/path/key.pem',//local do certificado key
];
```

Segue abaixo um exemplo para gerar os dois certificados

```php
$caminhoCertificado = __DIR__."/api-sicoob/path/meuCertifiado.pfx";
$senhaCertificado = "1234";

$res = [];
$openSSL = openssl_pkcs12_read(file_get_contents($caminhoCertificado), $res, $senhaCertificado);

if (! $openSSL) {
    throw new Exception("Error: " . openssl_error_string());
}

$cert = $res['cert'] . implode('', $res['extracerts']);
file_put_contents('cert.pem', $cert);

$cert = $res['pkey'];
file_put_contents('key.pem', $cert);
 
```