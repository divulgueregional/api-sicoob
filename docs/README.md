# DOCUMENTAÇÃO API BANCO SICOOB

## Introdução

Siga a docmentação para poder utilizar a API do SICOOB

## Processo

- Cadastre-se mo portal do desenvolvedor do SICOOB: https://developers.sicoob.com.br/, o cliente deve fazer o cadastro.
- LOGIN: https://developers.sicoob.com.br/portal/login :
- CRIAR APP: criar o app junto ao cliente. O cliente vai pecisar fazer o login igual ao celular e ao logar vai ter que abrir o app no celular pra ativar com o código de liberação.
  OBS: ao criar o app vai pedir um certificado, pode gerar pelo navegador: Exportar - Avançar - Não exportar chave primaria - X509 codificado base64(.cer)
- APP Pentende: pelo celular. Na aba menu - transações pendentes.

Na aplicação precisa de 2 certificados, ver documentação.

- CERTIFICADOS: vai precisar .pfx em base64, .key (privada) .pem (publica)
- Cria uma aplicação da API que desejar com o certificado .pfx em base64
- O token é o primeiro passo para utilizar os métodos do boleto e precisa do certificado .pem e .key

## Links

- SANDBOX: https://developers.sicoob.com.br/portal/sandbox
- DEVELOPER: https://developers.sicoob.com.br/portal/login (cada cliente precisa cadastrar seu app)
- V3: https://documenter.getpostman.com/view/38568066/2sAYdbNsv1#7bc7cf95-2d82-4b30-af9e-356de86ae19e
