# Script PowerShell para regenerar el autoloader PSR-4 de Composer

Write-Host "🔄 Regenerando autoloader PSR-4..." -ForegroundColor Cyan

# Verificar si Docker está corriendo
$dockerRunning = docker-compose ps 2>$null | Select-String "web.*Up"

if ($dockerRunning) {
    Write-Host "✅ Docker está corriendo, ejecutando dentro del contenedor..." -ForegroundColor Green
    docker-compose exec web composer dump-autoload -o
} else {
    Write-Host "⚠️  Docker no está corriendo, ejecutando localmente..." -ForegroundColor Yellow
    composer dump-autoload -o
}

Write-Host ""
Write-Host "✅ Autoloader PSR-4 regenerado correctamente" -ForegroundColor Green
Write-Host ""
Write-Host "📚 Namespaces configurados:" -ForegroundColor Cyan
Write-Host "   App\ → src/"
Write-Host "   App\Tests\ → tests/"
