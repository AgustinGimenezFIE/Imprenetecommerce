# Imprenet - Catálogo de Productos

Este es un sistema de catálogo desarrollado en Laravel.  
Permite administrar productos desde una interfaz tipo admin (`/admin`) y mostrarlos públicamente en el sitio (`/`).

## 🔧 Funcionalidades

- Listado público de productos
- Panel de administración para:
  - Crear productos
  - Editar productos
  - Eliminar productos
- Subida de imagen principal y adicionales
- Estructura separada con layout (header, footer, etc.)

## 📁 Estructura

- `resources/views/layouts/app.blade.php` → Layout base (header/footer)
- `resources/views/productos/index_public.blade.php` → Página pública
- `resources/views/productos/index.blade.php` → Panel admin
- `app/Http/Controllers/ProductoController.php` → Lógica de productos

## ⚙️ Requisitos

- PHP 8+
- Composer
- Laravel 10+
- Servidor que soporte Laravel (ej: Laravel Hosting Cloud en Donweb)

