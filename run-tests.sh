#!/bin/bash
# Script para ejecutar tests del proyecto Analizer PC

echo "╔════════════════════════════════════════════════════════════╗"
echo "║  TESTS - ANALIZER PC                                       ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Función para mostrar uso
show_usage() {
    echo "Uso: ./run-tests.sh [opción]"
    echo ""
    echo "Opciones:"
    echo "  unit          - Ejecutar solo tests unitarios"
    echo "  feature       - Ejecutar solo tests de integración"
    echo "  all           - Ejecutar todos los tests"
    echo "  coverage      - Ejecutar con reporte de cobertura"
    echo "  config        - Ejecutar tests de configuración"
    echo "  factories     - Ejecutar tests de factorías"
    echo "  importer      - Ejecutar tests del importador"
    echo "  component     - Ejecutar tests del componente"
    echo ""
}

# Si no hay argumentos, mostrar uso
if [ $# -eq 0 ]; then
    show_usage
    exit 0
fi

case "$1" in
    unit)
        echo "🧪 Ejecutando tests unitarios..."
        docker exec php-app vendor/bin/phpunit tests/Unit --testdox
        ;;
    feature)
        echo "🔬 Ejecutando tests de integración..."
        docker exec php-app vendor/bin/phpunit tests/Feature --testdox
        ;;
    all)
        echo "🧪 Ejecutando TODOS los tests..."
        docker exec php-app vendor/bin/phpunit --testdox
        ;;
    coverage)
        echo "📊 Ejecutando tests con cobertura..."
        docker exec php-app vendor/bin/phpunit --coverage-text
        ;;
    config)
        echo "⚙️  Ejecutando tests de configuración..."
        docker exec php-app vendor/bin/phpunit tests/Unit/ConfigTest.php --testdox
        ;;
    factories)
        echo "🏭 Ejecutando tests de factorías..."
        docker exec php-app vendor/bin/phpunit tests/Unit/EmbeddingFactoryTest.php --testdox
        docker exec php-app vendor/bin/phpunit tests/Unit/DataSourceFactoryTest.php --testdox
        ;;
    importer)
        echo "📥 Ejecutando tests del importador..."
        docker exec php-app vendor/bin/phpunit tests/Unit/DataImporterTest.php --testdox
        ;;
    component)
        echo "🧩 Ejecutando tests del componente..."
        docker exec php-app vendor/bin/phpunit tests/Feature/ComponentTest.php --testdox
        ;;
    *)
        echo "❌ Opción no reconocida: $1"
        echo ""
        show_usage
        exit 1
        ;;
esac
