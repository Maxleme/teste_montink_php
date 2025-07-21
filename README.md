# MVC Loja - E-commerce em PHP Puro

## Pré-requisitos
- PHP 7.4+
- Composer
- MySQL

## Passos para rodar o projeto

1. **Clone o repositório e acesse a pasta do projeto**

2. **Instale as dependências do Composer**
```bash
composer install
composer dump-autoload
```

3. **Configure a conexão com o banco de dados e phpmailer**
- Faça uma copia do arquivo `.env.example` com o nome de `.env`
```bash
    cp .env.example .env
```
- Edite o arquivo `.env` e ajuste as informações de acesso:
  - `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`
  e as informações de Email
  - `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_PORT`, `MAIL_FROM`, `MAIL_FROM_NAME`, `MAIL_SECURE`

4. **Crie as tabelas do banco de dados**
```bash
php criar_tabelas.php
```

5. **Inicie o servidor embutido do PHP**
```bash
php -S localhost:8000 -t public
```

6. **Acesse o sistema**
- Abra o navegador e acesse: [http://localhost:8000](http://localhost:8000)

## Observações
- Para testar o webhook, envie um POST para `/webhook` com JSON contendo `id` e `status` do pedido.
    exemplo:
    ```bash 
    {
        "id": 1,
        "status": "CANCELADO"
    }
    ```
    ou 
    ```bash
    {
        "id": 1,
        "status": "PAGO"
    }
    ```
- O layout utiliza Bootstrap 5 via CDN.
- O autoload segue o padrão PSR-4.

