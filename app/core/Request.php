<?php

namespace App\Core;

class Request 
{
    protected array $body = [];

    public function __construct()
    {
        // Parse o corpo da requisição apenas uma vez ao inicializar
        $this->parseBody();
    }

    // Retorna o caminho da URL sem query strings
    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) {
            return $this->sanitizePath($path);
        }
        
        return $this->sanitizePath(substr($path, 0, $position));
    }

    // Retorna o método HTTP da requisição (GET, POST, etc.)
    public function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    // Retorna todos os parâmetros de query na URL, sanitizados
    public function getQueryParams(): array
    {
        return filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
    }

    // Retorna o corpo da requisição, com sanitização adequada
    public function getBody(): array
    {
        return $this->body;
    }

    // Suporte a obter cabeçalhos HTTP específicos
    public function getHeader(string $key): ?string
    {
        $header = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$header] ?? null;
    }

    // Validação de CSRF tokens em requisições POST
    public function validateCsrfToken(): bool
    {
        $csrfToken = $this->getBody()['_csrf'] ?? null;
        return $csrfToken && hash_equals($_SESSION['_csrf_token'] ?? '', $csrfToken);
    }

    // Verifica se a requisição é AJAX (normalmente usada em APIs)
    public function isAjax(): bool
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    // Retorna o tipo de conteúdo da requisição (ex: JSON, form-data)
    public function getContentType(): string
    {
        return $_SERVER['CONTENT_TYPE'] ?? 'application/x-www-form-urlencoded';
    }

    // Suporte a JSON no corpo da requisição
    protected function parseBody()
    {
        if ($this->getMethod() === 'POST' || $this->getMethod() === 'PUT' || $this->getMethod() === 'PATCH') {
            $contentType = $this->getContentType();

            if (strpos($contentType, 'application/json') !== false) {
                // Lê o conteúdo JSON do corpo da requisição
                $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
            } else {
                // Sanitiza os inputs do POST (outras requisições de formulário)
                $this->body = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? [];
            }
        }
    }

    // Sanitiza o caminho da URL para evitar ataques
    protected function sanitizePath(string $path): string
    {
        return filter_var($path, FILTER_SANITIZE_URL);
    }

    // Função auxiliar para pegar um campo específico da requisição
    public function input(string $key, $default = null)
    {
        return $this->body[$key] ?? $default;
    }

    // Suporte a obter o IP do cliente de forma segura
    public function getClientIp(): ?string
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    // Verifica se a requisição é segura (SSL)
    public function isSecure(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    }

    // Sanitiza e valida inputs (GET ou POST)
    public function validateInput(array $rules): array
    {
        $filtered = [];

        foreach ($rules as $key => $filter) {
            $filtered[$key] = filter_var($this->input($key), $filter);
        }

        return $filtered;
    }
}
