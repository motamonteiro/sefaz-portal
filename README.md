# Sefaz/Portal
Pacote que configura automaticamente o frontend das aplicações.

Instale uma nova versão do Laravel
``` bash
laravel new novoSistema
```

Baixe as dependências do projeto
``` bash
composer install
```

Adicione a dependência do Sefaz/Portal no novoSistema
``` bash
composer require motamonteiro/sefaz-portal
```

Publique os arquivos necessários na pasta public
``` bash
php artisan vendor:publish
```

Inicie o servidor do php
``` bash
php -S 0.0.0.0:8000 -t public
```

Inicie o browser
``` bash
http://localhost:8000
```
