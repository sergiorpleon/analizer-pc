#!/bin/bash
# Script para regenerar el autoloader PSR-4 de Composer

echo "🔄 Regenerando autoloader PSR-4..."

# Verificar si Docker está corriendo
if docker-compose ps | grep -q "web.*Up"; then
    echo "✅ Docker está corriendo, ejecutando dentro del contenedor..."
    docker-compose exec web composer dump-autoload -o
else
    echo "⚠️  Docker no está corriendo, ejecutando localmente..."
    composer dump-autoload -o
fi

echo ""
echo "✅ Autoloader PSR-4 regenerado correctamente"
echo ""
echo "📚 Namespaces configurados:"
echo "   App\\ → src/"
echo "   App\\Tests\\ → tests/"
