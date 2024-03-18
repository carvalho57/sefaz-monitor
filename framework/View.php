<?php

declare(strict_types=1);

namespace Framework;

class View
{
    public function __construct(
        private string $viewPath,
        private array $params = []
    ) {
    }

    public function render(): string
    {
        $viewPath = VIEWS_PATH . '/' . $this->viewPath . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException('View not found');
        }

        extract($this->params, EXTR_SKIP);
        ob_start();

        include $viewPath;

        return ob_get_clean();
    }

    public static function make(string $viewPath, array $params): static
    {
        return new static($viewPath, $params);
    }

    public function __toString(): string
    {
        return $this->render();
    }
}
