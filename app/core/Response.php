<?php

namespace App\Core;

class Response
{
    protected int $statusCode = 200;
    protected array $headers = [];
    protected string $content = '';

    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;
        http_response_code($code);
    }

    public function setHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;
        header("$key: $value");
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function send()
    {
        // Enviar os headers
        foreach ($this->headers as $key => $value) {
            header("$key: $value");
        }

        // Enviar o conteúdo
        echo $this->content;
    }

    // Método genérico para enviar erros com código e mensagem customizados
    public function sendError(int $code, string $message)
    {
        $this->setStatusCode($code);
        $this->setContent(json_encode(['error' => $message]));
        $this->send();
    }

    // Método específico para erros 404
    public function sendNotFound(string $message = 'Page Not Found')
    {
        $this->sendError(404, $message);
    }

    // Método específico para erros 405
    public function sendMethodNotAllowed(string $message = 'Method Not Allowed')
    {
        $this->sendError(405, $message);
    }

    // Método para retornar sucesso com conteúdo opcional
    public function sendSuccess(string $message = 'Success', $data = null)
    {
        $this->setStatusCode(200);
        $this->setHeader('Content-Type', 'application/json');
        $response = ['message' => $message];
        
        if ($data) {
            $response['data'] = $data;
        }
        
        $this->setContent(json_encode($response));
        $this->send();
    }
}
