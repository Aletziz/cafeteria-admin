#!/bin/bash

# Build script para Render - Cafeteria Admin
echo "Iniciando build de Cafeteria Admin..."

# Instalar dependencias de Composer
echo "Instalando dependencias de PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# Verificar si existe package.json antes de instalar npm
if [ -f "package.json" ]; then
    echo "Instalando dependencias de Node.js..."
    npm ci
    echo "Compilando assets..."
    npm run build
else
    echo "No se encontró package.json, saltando build de assets"
fi

# Limpiar y optimizar Laravel
echo "Optimizando Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cachear configuraciones para producción
echo "Cacheando configuraciones..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear directorio de storage si no existe
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Establecer permisos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "Build completado exitosamente!"