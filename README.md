# LightRoute - Mini Framework de Roteamento em PHP

Bem-vindo ao **LightRoute**, um mini framework de roteamento em PHP, criado para fins de estudo. O objetivo deste projeto é fornecer uma estrutura simples para definir e gerenciar rotas em suas aplicações PHP, facilitando o controle de navegação e permitindo a construção de aplicações de forma organizada e clara.

## Objetivo

Este framework foi criado para servir como base de estudo para quem deseja aprender mais sobre roteamento em PHP. Ele oferece uma maneira fácil e eficiente de definir rotas e trabalhar com parâmetros de URL e requisições HTTP, como GET e POST.

## Funcionalidades

- Definição de rotas GET e POST.
- Suporte para parâmetros dinâmicos nas rotas (`/user/{id}`).
- Injeção de dependências para objetos de requisição e resposta.
- Tratamento básico de erros 404.
- Facilita a separação de lógica de rotas e controladores.

## Instalação

Para utilizar o **LightRoute**, siga os passos abaixo para configurar o ambiente local:

### Pré-requisitos

- PHP 7.4 ou superior
- Composer

### Passos para Instalação

1. Clone o repositório do projeto:

```bash
git clone https://github.com/railan-bernardo-oficial/framework-light-route.git
```

1. Navegue até o diretório do projeto:

```bash
  cd framework-light-route
```

3 Instale as dependências usando o Composer:

```bash
  composer install
```

4 Configure o servidor local para apontar para o diretório raiz do projeto.

## EXEMPLO DE USO

Aqui está um exemplo simples de como utilizar o LightRoute:

1 Abra o arquivo web.php no diretório ``` app/routes ```

```php
  <?php

        use App\Core\Router;
        use App\Core\Request;
        use App\Core\Response;
        use App\Controllers\HomeController;

        $request = new Request();
        $response = new Response();
        $router = new Router($request, $response);

        $router->get('/', [HomeController::class, "home"]);

        $router->exec();
```

## Licença

Este projeto está sob a licença MIT. Sinta-se à vontade para usar, modificar e distribuir conforme necessário.


### Pontos a Considerar

- O nome do projeto foi sugerido como "LightRoute" no exemplo acima, mas você pode alterá-lo conforme a sua escolha.
- Certifique-se de substituir o URL do repositório GitHub com o seu próprio, se necessário.
- Este README inclui instruções básicas de instalação e uso.
