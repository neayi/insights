<?php


namespace App\Src\UseCases\Infra\Gateway;


class InMemoryFileStorage implements FileStorage
{
    private $fs = [];

    public function setContent(string $path, array $content)
    {
        $this->fs[$path] = $content;
    }

    public function content(string $path): array
    {
        return $this->fs[$path];
    }

}
