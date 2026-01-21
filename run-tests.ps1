# Script PowerShell para ejecutar tests del proyecto Analizer PC

Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║  TESTS - ANALIZER PC                                       ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Función para mostrar uso
function Show-Usage {
    Write-Host "Uso: .\run-tests.ps1 [opción]" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "Opciones:"
    Write-Host "  unit          - Ejecutar solo tests unitarios"
    Write-Host "  feature       - Ejecutar solo tests de integración"
    Write-Host "  all           - Ejecutar todos los tests"
    Write-Host "  coverage      - Ejecutar con reporte de cobertura"
    Write-Host "  config        - Ejecutar tests de configuración"
    Write-Host "  factories     - Ejecutar tests de factorías"
    Write-Host "  importer      - Ejecutar tests del importador"
    Write-Host "  component     - Ejecutar tests del componente"
    Write-Host ""
}

# Si no hay argumentos, mostrar uso
if ($args.Count -eq 0) {
    Show-Usage
    exit 0
}

switch ($args[0]) {
    "unit" {
        Write-Host "🧪 Ejecutando tests unitarios..." -ForegroundColor Green
        docker exec php-app vendor/bin/phpunit tests/Unit --testdox
    }
    "feature" {
        Write-Host "🔬 Ejecutando tests de integración..." -ForegroundColor Green
        docker exec php-app vendor/bin/phpunit tests/Feature --testdox
    }
    "all" {
        Write-Host "🧪 Ejecutando TODOS los tests..." -ForegroundColor Green
        docker exec php-app vendor/bin/phpunit --testdox
    }
    "coverage" {
        Write-Host "📊 Ejecutando tests con cobertura..." -ForegroundColor Green
        docker exec php-app vendor/bin/phpunit --coverage-text
    }
    "config" {
        Write-Host "⚙️  Ejecutando tests de configuración..." -ForegroundColor Green
        docker exec php-app vendor/bin/phpunit tests/Unit/ConfigTest.php --testdox
    }
    "factories" {
        Write-Host "🏭 Ejecutando tests de factorías..." -ForegroundColor Green
        docker exec php-app vendor/bin/phpunit tests/Unit/EmbeddingFactoryTest.php --testdox
        docker exec php-app vendor/bin/phpunit tests/Unit/DataSourceFactoryTest.php --testdox
    }
    "importer" {
        Write-Host "📥 Ejecutando tests del importador..." -ForegroundColor Green
        docker exec php-app vendor/bin/phpunit tests/Unit/DataImporterTest.php --testdox
    }
    "component" {
        Write-Host "🧩 Ejecutando tests del componente..." -ForegroundColor Green
        docker exec php-app vendor/bin/phpunit tests/Feature/ComponentTest.php --testdox
    }
    default {
        Write-Host "❌ Opción no reconocida: $($args[0])" -ForegroundColor Red
        Write-Host ""
        Show-Usage
        exit 1
    }
}
