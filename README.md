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

~~~sh
php -S localhost:8080 -t public
~~~