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

3. **Configure a conexão com o banco de dados**
- Edite o arquivo `src/Config/config.php` e ajuste as informações de acesso:
  - `host`, `port`, `dbname`, `user`, `password`

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
    {
        "id": 1,
        "status": "CANCELADO"
    }
    ou 
    {
        "id": 1,
        "status": "PAGO"
    }
- O layout utiliza Bootstrap 5 via CDN.
- O autoload segue o padrão PSR-4.

