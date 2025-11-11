# Proyecto Portafolio - Sistema de Evaluación Docente

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-10.0-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.2-purple?style=for-the-badge&logo=bootstrap)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

</div>

## 📋 Descripción del Proyecto

**Proyecto Portafolio** es una aplicación web diseñada para gestionar el **proceso de evaluación docente** de forma integral. Este sistema permite a supervisores evaluar el desempeño de docentes mediante criterios específicos, hacer seguimiento del progreso académico y generar reportes detallados de evaluación.

La aplicación está dirigida a instituciones educativas que necesitan un sistema robusto y escalable para la evaluación del personal docente.

### 🎯 Objetivo Principal

Proporcionar una plataforma centralizada para:
- 📊 **Gestionar evaluaciones docentes** por semestre y tipo de curso
- 👥 **Administrar usuarios** con diferentes roles (Docentes, Supervisores, Administradores)
- 📈 **Calcular y monitorear el progreso** de evaluaciones mediante criterios ponderados
- 📋 **Organizar criterios de evaluación** por secciones temáticas
- 🔐 **Controlar acceso** mediante roles y permisos específicos

---

## 🏗️ Arquitectura del Proyecto

### Stack Tecnológico

- **Backend**: Laravel 10.10 (PHP 8.1+)
- **Frontend**: Bootstrap 5.2 + Blade Templates + Vite
- **Base de Datos**: MySQL/MariaDB
- **Admin Panel**: AdminLTE 3.14
- **Autenticación**: Laravel Sanctum
- **Testing**: Pest PHP 2.0
- **Control de Versiones**: Git

### Estructura de la Base de Datos

El proyecto utiliza las siguientes tablas principales:

| Tabla | Descripción |
|-------|-------------|
| `TUsuario` | Usuarios del sistema (Docentes, Supervisores, Administradores) |
| `TUsuarioRoles` | Relación muchos-a-muchos entre usuarios y roles |
| `TAsignacion` | Asignación de docentes a supervisores por semestre |
| `TEvaluacion` | Evaluaciones creadas para docentes |
| `TDetalleEvaluacion` | Detalles específicos de cada evaluación |
| `TCriterioEvaluacion` | Criterios de evaluación disponibles |
| `TSeccionesEvaluacion` | Secciones temáticas que agrupan criterios |
| `TSemestre` | Semestres académicos |

---

## 🚀 Funcionalidades Principales

### 👤 Gestión de Usuarios y Roles

- ✅ Crear, editar y eliminar usuarios
- ✅ Asignar roles específicos (Docente, Supervisor, Administrador)
- ✅ Sistema de autenticación seguro
- ✅ Manejo de sesiones con roles activos

### 📊 Módulo de Evaluaciones

- ✅ Crear evaluaciones por docente y semestre
- ✅ Seleccionar tipo de curso (presencial, virtual, híbrido, etc.)
- ✅ Registrar criterios de evaluación
- ✅ Calcular progreso automáticamente
- ✅ Seguimiento de evaluaciones anteriores

### 📈 Cálculo de Progreso

El sistema calcula el progreso de las evaluaciones mediante:
- **Criterios ponderados**: Cada criterio tiene un peso específico
- **Evaluaciones acumulativas**: Se consideran evaluaciones anteriores del mismo período
- **Tipo de curso**: Los criterios pueden aplicar a todos o a tipos específicos
- **Progreso total**: Suma del peso de criterios cumplidos / peso total de criterios

```php
Progreso (%) = (Peso Cumplido / Peso Total) × 100
```

### 🔒 Políticas de Autorización

- Supervisores solo pueden ver sus docentes asignados
- Docentes solo pueden ver sus propias evaluaciones
- Administradores tienen acceso completo
- Sistema de permisos basado en Spatie Laravel-Permission

### 📋 Gestión de Criterios

- Organización de criterios por secciones temáticas
- Criterios específicos por tipo de curso
- Gestión de pesos para cálculo de progreso
- Interfaz administrativa para agregar/modificar criterios

---

## 📁 Estructura del Proyecto

```
proyecto-portafolio/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controladores de la aplicación
│   │   ├── Middleware/           # Middleware personalizado
│   │   └── Kernel.php
│   ├── Models/                   # Modelos Eloquent
│   │   ├── User.php
│   │   ├── Asignacion.php
│   │   ├── Evaluacion.php
│   │   ├── DetalleEvaluacion.php
│   │   ├── CriterioEvaluacion.php
│   │   ├── SeccionEvaluacion.php
│   │   └── Semestre.php
│   ├── Policies/                 # Políticas de autorización
│   └── Exceptions/
├── database/
│   ├── migrations/               # Migraciones de BD
│   └── seeders/                  # Datos iniciales
├── resources/
│   ├── views/                    # Vistas Blade
│   ├── css/
│   ├── js/
│   └── sass/
├── routes/
│   ├── web.php                   # Rutas principales
│   └── api.php
├── tests/
│   ├── Feature/
│   └── Unit/
└── config/                       # Configuración
```

---

## 🔧 Instalación y Configuración

### Requisitos Previos

- PHP 8.1 o superior
- Composer
- Node.js y npm
- MySQL/MariaDB

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/Jos3phil/proyecto_portafolio.git
   cd proyecto_portafolio
   ```

2. **Instalar dependencias PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias JavaScript**
   ```bash
   npm install
   ```

4. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurar base de datos en `.env`**
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=portafolio_bd
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Compilar assets con Vite**
   ```bash
   npm run build
   ```

8. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

   La aplicación estará disponible en `http://localhost:8000`

---

## 💻 Comandos Útiles

```bash
# Desarrollo
npm run dev                    # Compilar assets en tiempo real
php artisan serve            # Iniciar servidor de desarrollo

# Producción
npm run build                # Compilar assets optimizados

# Base de datos
php artisan migrate          # Ejecutar migraciones
php artisan db:seed          # Ejecutar seeders
php artisan migrate:refresh  # Refrescar BD

# Testing
php artisan pest             # Ejecutar tests
php artisan pest --filter=EvaluacionTest  # Ejecutar test específico

# Cache
php artisan cache:clear     # Limpiar caché
php artisan config:cache    # Cachear configuración
```

---

## 👥 Roles y Permisos

### 1. **Administrador (ADMINISTRADOR)**
- Acceso total al sistema
- Gestionar usuarios y roles
- Configurar criterios de evaluación
- Ver todas las evaluaciones

### 2. **Supervisor (SUPERVISOR)**
- Ver docentes asignados
- Crear y gestionar evaluaciones de sus docentes
- Ver reportes de progreso
- Generar informes

### 3. **Docente (DOCENTE)**
- Ver sus propias evaluaciones
- Consultar criterios de evaluación
- Visualizar su progreso

---

## 📊 Modelos Principales

### User (TUsuario)
```php
- id_usuario (PK)
- Nombre
- email
- password
- Relaciones: roles(), asignaciones(), docentesAsignados()
```

### Evaluacion (TEvaluacion)
```php
- id_evaluacion (PK)
- id_asignacion (FK)
- id_semestre (FK)
- tipo_curso
- fecha_evaluacion
- progreso
```

### Asignacion (TAsignacion)
```php
- id_asignacion (PK)
- id_supervisor (FK)
- id_docente (FK)
- id_semestre (FK)
```

### CriterioEvaluacion (TCriterioEvaluacion)
```php
- id_criterio (PK)
- id_seccion (FK)
- nombre_criterio
- descripcion
- peso
- tipo_curso
```

---

## 🧪 Testing

El proyecto incluye tests automatizados usando **Pest PHP**:

```bash
# Ejecutar todos los tests
php artisan pest

# Ejecutar tests con salida detallada
php artisan pest -vvv

# Ejecutar tests específicos
php artisan pest tests/Feature/EvaluacionTest.php
```

---

## 📈 Características Destacadas

### ✨ Cálculo Inteligente de Progreso
- Suma ponderada de criterios evaluados
- Soporte para evaluaciones acumulativas
- Cálculo dinámico según tipo de curso
- Historial de evaluaciones anteriores

### 🎨 Interfaz Intuitiva
- Panel administrativo con AdminLTE
- Diseño responsivo con Bootstrap
- Navegación clara y accesible
- Componentes reutilizables

### 🔐 Seguridad
- Autenticación con Laravel Sanctum
- Autorización basada en políticas
- Protección CSRF
- Encriptación de contraseñas

### 📱 Responsive Design
- Optimizado para móviles, tablets y desktops
- Interfaz moderna y accesible
- Carga rápida de componentes

---

## 🤝 Contribución

Las contribuciones son bienvenidas. Para contribuir:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

---

## 📝 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 👨‍💻 Autor

**Proyecto Portafolio** - Sistema de Evaluación Docente
- GitHub: [@Jos3phil](https://github.com/Jos3phil)
- Fecha: Noviembre 2025

---

## 📞 Soporte

Para reportar bugs o sugerir mejoras, por favor abre un [issue](https://github.com/Jos3phil/proyecto_portafolio/issues) en GitHub.

---

<div align="center">

**Hecho con ❤️ usando Laravel**

</div>
