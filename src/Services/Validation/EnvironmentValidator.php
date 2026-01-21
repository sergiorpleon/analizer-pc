<?php

declare(strict_types=1);

namespace App\Services\Validation;

use App\Exceptions\EnvironmentException;

/**
 * Validador de robustez del entorno.
 */
final readonly class EnvironmentValidator
{
    public static function validate(): void
    {
        $provider = getenv('EMBEDDING_PROVIDER') ?: 'ollama';

        if ($provider === 'gemini') {
            $apiKey = getenv('GEMINI_API_KEY');
            if (empty($apiKey)) {
                throw new EnvironmentException("El proveedor es Gemini pero GEMINI_API_KEY no está definida en el entorno.");
            }
        }

        if ($provider === 'ollama') {
            $config = require __DIR__ . '/../../../config/config.php';
            $url = $config['ollama']['url'] ?? 'http://ollama:11434';
            
            if (!self::checkOllamaHealth($url)) {
                // No lanzamos excepción para permitir que la app cargue, pero podríamos loguear o avisar.
                // En este caso, según el requisito, notificamos.
                error_log("AVISO: El servicio Ollama en $url no responde. Asegúrate de iniciar Docker con el perfil 'local-ai'.");
            }
        }
    }

    private static function checkOllamaHealth(string $url): bool
    {
        $ch = curl_init($url . '/api/tags');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200;
    }
}
