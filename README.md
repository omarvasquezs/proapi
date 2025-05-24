# Plantilla Base de Laravel

Esta es una plantilla base para proyectos Laravel que incluye una estructura organizada y configuraciones comunes.

## Requisitos

- PHP >= 8.2
- Composer
- Node.js y NPM
- MySQL o PostgreSQL

## Características Principales

- **Dockerización completa**: Entorno de desarrollo con Docker (PHP, Nginx, MySQL, Redis)
- **Patrón Repositorio**: Separación clara entre lógica de negocio y acceso a datos
- **API Resources**: Transformación eficiente de modelos a respuestas JSON
- **Sistema de autenticación Sanctum**: Con cookies HttpOnly y autenticación por nombre de usuario
- **Roles y Permisos**: Integración completa con Spatie Permission
- **CORS optimizado**: Middleware personalizado para aplicaciones SPA

## Instalación

### Instalación con Docker (Recomendado)

1. Clona este repositorio:
```bash
git clone [URL_DEL_REPOSITORIO]
cd [NOMBRE_DEL_PROYECTO]
```

2. Copia el archivo de entorno:
```bash
cp .env.example .env
```

3. Configura las variables de entorno en el archivo `.env`

4. Inicia los contenedores Docker:
```bash
docker-compose up -d
```

5. Entra al contenedor y ejecuta los comandos de instalación:
```bash
docker-compose exec app bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
```

El comando `migrate:fresh --seed` realizará las siguientes acciones:
- Eliminar todas las tablas existentes
- Crear todas las tablas según las migraciones
- Ejecutar los seeders para crear:
  - Roles predefinidos (admin, usuario, editor, supervisor, invitado)
  - Permisos organizados por grupos
  - Usuario administrador por defecto (username=superadmin / password=superadmin123)
  - Usuario de prueba (solo en entorno de desarrollo)

### Instalación tradicional

1. Clona este repositorio:
```bash
git clone [URL_DEL_REPOSITORIO]
cd [NOMBRE_DEL_PROYECTO]
```

2. Instala las dependencias de PHP:
```bash
composer install
```

3. Copia el archivo de entorno:
```bash
cp .env.example .env
```

4. Genera la clave de la aplicación:
```bash
php artisan key:generate
```

5. Configura tu base de datos en el archivo `.env`

6. Ejecuta las migraciones y seeders:
```bash
php artisan migrate:fresh --seed
```

7. Instala las dependencias de Node.js:
```bash
npm install
```

8. Compila los assets:
```bash
npm run dev
```

## Sistema de Autenticación

El sistema utiliza Laravel Sanctum con cookies HttpOnly para una mayor seguridad en comparación con tokens JWT almacenados en localStorage.

### Características principales

- **Autenticación por nombre de usuario**: A diferencia del enfoque predeterminado de Laravel que usa correo electrónico, esta plantilla utiliza el nombre de usuario (username) como identificador principal para la autenticación.
- **Cookies HttpOnly**: Los tokens de autenticación se almacenan en cookies HttpOnly, no accesibles por JavaScript.
- **Protección CSRF**: Configuración adecuada para prevenir ataques CSRF.
- **Restablecimiento de contraseña**: El sistema permite restablecer contraseñas usando el nombre de usuario.

### Credenciales por defecto

- **Administrador**: username=superadmin / password=superadmin123
- **Usuario regular** (solo en entorno de desarrollo): username=usuario_test / password=password

### Endpoints de autenticación

- `POST /api/register`: Registrar un nuevo usuario
- `POST /api/login`: Iniciar sesión (requiere username y password)
- `POST /api/logout`: Cerrar sesión (requiere autenticación)
- `GET /api/user`: Obtener información del usuario autenticado
- `POST /api/forgot-password`: Solicitar restablecimiento de contraseña (requiere username)
- `POST /api/reset-password`: Restablecer contraseña

## Sistema de Roles y Permisos

El proyecto utiliza Spatie Permission para la gestión de roles y permisos.

### Roles predefinidos

- **admin**: Acceso completo al sistema
- **usuario**: Acceso básico con permisos limitados
- **editor**: Permisos para ver y exportar datos
- **supervisor**: Permisos para supervisión y acceso a logs
- **invitado**: Permisos de solo lectura

### Grupos de permisos

- **usuarios**: ver-usuarios, crear-usuarios, editar-usuarios, eliminar-usuarios, asignar-roles
- **roles**: ver-roles, crear-roles, editar-roles, eliminar-roles
- **configuracion**: ver-configuracion, editar-configuracion
- **reportes**: ver-reportes, exportar-reportes
- **logs**: ver-logs, limpiar-logs

### Seeders para roles y permisos

El proyecto incluye varios seeders para inicializar el sistema de roles y permisos:

- **RoleSeeder**: Crea los roles básicos
- **PermissionSeeder**: Crea todos los permisos organizados por grupos
- **RolePermissionSeeder**: Asigna los permisos adecuados a cada rol
- **AdminUserSeeder**: Crea un usuario administrador por defecto

Si necesitas ejecutar un seeder específico:

```bash
# Ejecutar solo el seeder de roles
php artisan db:seed --class=RoleSeeder

# Ejecutar solo el seeder de permisos
php artisan db:seed --class=PermissionSeeder

# Ejecutar solo el seeder de usuario administrador
php artisan db:seed --class=AdminUserSeeder
```

## Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Resources/     # API Resources para transformación de modelos
├── Models/
├── Repositories/      # Patrón repositorio para acceso a datos
└── Services/          # Lógica de negocio
bootstrap/
├── app.php
└── cache/
config/
database/
├── migrations/
└── seeders/
routes/
resources/
docker/              # Configuraciones Docker
storage/
tests/
```

## Características Incluidas

- Estructura de directorios organizada
- Configuración básica de Laravel
- Sistema de autenticación con Sanctum (cookies)
- Configuración de base de datos
- Configuración de pruebas
- Patrón Repositorio implementado
- API Resources para transformación de datos
- Docker para desarrollo y despliegue

## Librerías Incluidas

### Dependencias Principales
- `laravel/framework` - Framework Laravel
- `laravel/sanctum` - Autenticación API
- `laravel/tinker` - Interacción con la consola
- `maatwebsite/excel` - Manejo de archivos Excel
- `spatie/laravel-permission` - Sistema de roles y permisos
- `predis/predis` - Cliente de Redis para caché y sesiones

### Dependencias de Desarrollo
- `fakerphp/faker` - Generación de datos falsos
- `laravel/pail` - Monitoreo de logs
- `laravel/pint` - Formateo de código PHP
- `laravel/sail` - Desarrollo con Docker
- `mockery/mockery` - Pruebas
- `nunomaduro/collision` - Manejo de errores
- `phpunit/phpunit` - Pruebas unitarias

## Desarrollo

### Desarrollo con Docker

```bash
# Iniciar todos los servicios
docker-compose up -d

# Ver logs
docker-compose logs -f

# Ejecutar comandos de artisan
docker-compose exec app php artisan migrate

# Acceder al shell
docker-compose exec app bash
```

### Desarrollo tradicional

```bash
# Iniciar el servidor
php artisan serve

# Compilar assets
npm run dev

# Iniciar todos los servicios (servidor, cola, logs y vite)
composer dev
```

## Patrones implementados

### Patrón Repositorio

Este proyecto implementa el patrón repositorio para separar la lógica de acceso a datos:

```php
// Ejemplo de uso en un controlador
public function index(UserRepository $userRepository)
{
    $users = $userRepository->getUsersWithRoles();
    return UserResource::collection($users);
}
```

### API Resources

Los API Resources para transformación de modelos están disponibles:

```php
// Ejemplo de uso en un controlador
public function show(UserRepository $userRepository, $id)
{
    $user = $userRepository->findOrFail($id);
    return new UserResource($user);
}
```

## Pruebas

Para ejecutar las pruebas:

```bash
php artisan test
```

## Configuración de CORS y Cookies

Para configurar correctamente CORS y las cookies de autenticación, debes añadir las siguientes variables a tu archivo `.env`:

```
# Configuración de CORS
ALLOWED_ORIGINS=http://localhost:5173,http://localhost:3000,http://localhost:8000

# Configuración de Sanctum (para cookies)
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:3000,localhost:8000
SESSION_DOMAIN=localhost
SESSION_SECURE_COOKIE=false # Cambia a true en producción con HTTPS
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Redis para sesión y caché
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

Este proyecto utiliza un middleware personalizado (`CorsRestriction`) que reemplaza el middleware CORS predeterminado de Laravel para un mejor control sobre las solicitudes CORS. 

El middleware está registrado tanto en el archivo `bootstrap/app.php` (siguiendo la estructura de Laravel 12) como en `app/Http/Kernel.php` (estructura tradicional) para garantizar compatibilidad.

Las principales características de la configuración CORS son:
- Validación de orígenes permitidos desde las variables de entorno
- Soporte para credenciales (cookies) en las solicitudes CORS
- Gestión adecuada de solicitudes preflight OPTIONS
- Bloqueo de orígenes no autorizados

El sistema de autenticación está configurado para trabajar con cookies en lugar de tokens en localStorage, lo que proporciona mayor seguridad para aplicaciones SPA.

## Licencia

Este proyecto está bajo la Licencia MIT. 