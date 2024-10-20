<?php

namespace App\Core;

class View
{
    protected string $viewPath;

    public function __construct(string $viewPath = __DIR__ . '/../views/')
    {
        $this->viewPath = rtrim($viewPath, '/') . '/';
    }

    /**
     * Renderiza a view e passa os dados para a view com seus nomes originais.
     * 
     * @param string $view Nome do arquivo da view (sem extensão)
     * @param array $data Dados a serem passados para a view
     * @throws \Exception
     */
    public function render(string $view, array $data = [])
    {
        $file = $this->viewPath . $view . '.php';

        if (!file_exists($file)) {
            throw new \Exception("View file '{$view}.php' not found.");
        }

        // Função recursiva para converter arrays associativos em objetos
        $convertToObject = function ($value) use (&$convertToObject) {
            if (is_array($value)) {
                return (object) array_map($convertToObject, $value);
            }
            return $value;
        };

        // Extrai os dados para variáveis na view
        foreach ($data as $key => $value) {
            ${$key} = $convertToObject($value); // Converte e atribui o valor à variável de mesmo nome
        }

        // Inclui o arquivo da view
        require $file;
    }


    /**
     * Renderiza a view em formato de string (útil para retornar como resposta).
     * 
     * @param string $view Nome do arquivo da view (sem extensão)
     * @param array|object $data Dados que serão passados para a view
     * @return string Conteúdo renderizado da view
     * @throws \Exception
     */
    public function renderToString(string $view, $data = []): string
    {
        $file = $this->viewPath . $view . '.php';

        if (!file_exists($file)) {
            throw new \Exception("View file '{$view}.php' not found.");
        }

        // Converte array para objeto (caso seja array)
        if (is_array($data)) {
            $data = (object)$data;
        }

        // Extrai os dados para variáveis na view
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = (object) $value; // Converte array em objeto
            }
            ${$key} = $value;
        }

        // Inicia o buffer de saída
        ob_start();

        // Inclui o arquivo da view
        require $file;

        // Obtém o conteúdo do buffer e o encerra
        return ob_get_clean();
    }
}
