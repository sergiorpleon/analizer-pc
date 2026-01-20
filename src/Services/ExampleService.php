<?php
/**
 * Ejemplo de nueva clase siguiendo PSR-4
 * 
 * Este archivo demuestra cómo crear una nueva clase
 * que siga el estándar PSR-4 del proyecto.
 * 
 * @package App\Services
 * @author Tu Nombre
 */

namespace App\Services;

use App\Models\Database;
use App\Models\OllamaService;

/**
 * Servicio de ejemplo para demostrar PSR-4
 * 
 * Este servicio podría manejar lógica de negocio adicional
 * que no encaja directamente en Models o Controllers.
 */
class ExampleService
{
    /**
     * @var Database Instancia de la base de datos
     */
    private $db;

    /**
     * @var OllamaService Servicio de Ollama
     */
    private $ollama;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->ollama = new OllamaService();
    }

    /**
     * Método de ejemplo
     * 
     * @param string $input Texto de entrada
     * @return array Resultado procesado
     */
    public function processData($input)
    {
        // Lógica de ejemplo
        $embedding = $this->ollama->generateEmbedding($input);

        return [
            'input' => $input,
            'embedding_size' => count($embedding),
            'processed_at' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Otro método de ejemplo
     * 
     * @return string
     */
    public function getStatus()
    {
        return 'Service is running';
    }
}

/*
 * CÓMO USAR ESTA CLASE:
 * 
 * 1. Guarda este archivo en: src/Services/ExampleService.php
 * 
 * 2. En cualquier controlador o archivo, importa la clase:
 *    use App\Services\ExampleService;
 * 
 * 3. Úsala directamente:
 *    $service = new ExampleService();
 *    $result = $service->processData('test');
 * 
 * 4. NO necesitas hacer require/include manual
 * 5. NO necesitas regenerar el autoloader (Composer lo hace automáticamente)
 * 
 * REGLAS PSR-4:
 * 
 * ✅ Namespace debe coincidir con la ruta:
 *    Archivo: src/Services/ExampleService.php
 *    Namespace: App\Services
 * 
 * ✅ Nombre de clase debe coincidir con nombre de archivo:
 *    Archivo: ExampleService.php
 *    Clase: class ExampleService
 * 
 * ✅ Un archivo, una clase principal
 * 
 * ✅ Capitalización importa (ExampleService.php, no exampleservice.php)
 */
