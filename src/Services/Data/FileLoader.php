<?php

declare(strict_types=1);

namespace App\Services\Data;

class FileLoader
{
    /**
     * Carga el contenido de un archivo desde una ruta local o URL.
     */
    public function load(string $path): string
    {
        if (str_starts_with($path, 'http')) {
            return $this->loadRemote($path);
        }
        return $this->loadLocal($path);
    }

    private function loadLocal(string $path): string
    {
        if (!file_exists($path)) {
            throw new \Exception("Archivo local no encontrado: $path");
        }
        $content = file_get_contents($path);
        if ($content === false) {
            throw new \Exception("No se pudo leer el archivo local: $path");
        }
        return $content;
    }

    private function loadRemote(string $path): string
    {
        $content = @file_get_contents($path);
        if ($content === false) {
            throw new \Exception("No se pudo leer el archivo remoto: $path");
        }
        return $content;
    }
}
