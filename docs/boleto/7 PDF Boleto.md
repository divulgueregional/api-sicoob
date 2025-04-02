# PDF BOLETO-SICOOB

## PDF boleto

Gerar PDF do boleto.

```php
  //compactar pdf
  $boleto_compactado_Json = bin2hex(gzcompress($boletoGerado['response']->resultado->pdfBoleto, 9));

  //descompcta o xml
  $reponse = gzuncompress(hex2bin($receber->boleto_pdf));

  // decodificar base64
  $pdf_data = base64_decode($reponse);

  header('Content-Type: application/pdf');
  header('Content-Disposition: inline; filename="boleto.pdf"');

  // Exibir no navegador
  echo $pdf_data;
```
