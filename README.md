# Devstagram

**Devstagram** es una comunidad para desarrolladores donde compartir proyectos, descubrir el trabajo de otros perfiles y conectar a traves de publicaciones.

## Caracteristicas

- Registro, inicio y cierre de sesion.
- Perfiles con foto, ocupacion y publicaciones.
- Creacion, edicion y eliminacion de proyectos con imagen, repositorio de GitHub y enlace al sitio.
- Feed principal ordenado por actividad reciente.
- Busqueda de usuarios.
- Likes y seguimiento de perfiles.
- Comentarios y respuestas anidadas, con actualizacion asincrona mediante AJAX.

## Tecnologias

- PHP 8.3+
- Laravel 13
- MySQL o SQLite
- Vite 8
- Tailwind CSS 4
- Dropzone para carga de imagenes

## Instalacion

### Requisitos

- PHP 8.3 o superior
- Composer
- Node.js y npm
- MySQL, o SQLite habilitado en PHP

### Configuracion local

```bash
git clone https://github.com/Errold146/devstagram.git
cd devstagram
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configura las credenciales de base de datos en `.env` y ejecuta las migraciones:

```bash
php artisan migrate
```

Para iniciar el entorno de desarrollo:

```bash
composer run dev
```

Tambien puedes compilar los recursos para produccion:

```bash
npm run build
```

## Pruebas

```bash
php artisan test
```

## Estructura principal

```text
app/Http/Controllers/  Logica de autenticacion, perfiles y publicaciones
app/Models/            Modelos de usuarios, publicaciones, comentarios, likes y seguidores
resources/views/       Vistas Blade y componentes de interfaz
resources/js/          Comportamiento AJAX de comentarios y carga de imagenes

routes/web.php         Rutas de la aplicacion
```

## Licencia

Este proyecto se distribuye bajo la licencia MIT.
