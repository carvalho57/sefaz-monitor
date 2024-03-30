# Sefaz Monitor

Disponibiliza um mapa do Brasil e informa o status da NF-e para cada estado, utilizando o webservice NfeStatusServico

## Objetivos de aprendizado

- Curl
- Protocolo SOAP

## Requisitos

- [ ] Como usuário gostaria de visualizar um mapa interativo do Brasil. Quando passar o mouse sobre cada estado, um
      card deve ser atualizado com as informações do webservice do estado (Tempo médio de resposta, se esta em contigência)
- [ ] Como um usuário gostaria de um botão **Atualizar** que, quando clicado atualize as informações dos estados.
- [ ] Como usuário gostária que houvesse legendas dos status (por exemplo: Normal, Parado, Contigência).

## Como executar
Siga esses passos para executar a aplicação

Acesse o diretório docker:
~~~sh
cd docker
~~~

Build e start o container:
~~~sh
docker-compose up -d --build
~~~

Gere os arquivos client.pem e key.pem atráves do seu certificado digital no formato .p12 ou .pfx
~~~sh
# Extrair chave.pem
openssl pkcs12 -in [caminho para o certificado] -out key.pem -nocerts -nodes

# Extrair cliente.pem
openssl pkcs12 -in [caminho para o certificado] -out client.pem -clcerts -nokeys -nodes
~~~


## LInks adicionas e resources

* https://www.php.net/manual/en/book.curl.php
* https://symfony.com/doc/current/create_framework/index.html
* https://en.wikipedia.org/wiki/SOAP
* https://www.youtube.com/watch?v=it8ybkQuAh8
* https://www.youtube.com/watch?v=-Pa0t8pRmDM
* https://www.w3.org/TR/2000/NOTE-SOAP-20000508/
* https://www.w3.org/TR/soap12-part1/