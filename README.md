
# Sistema de Gestión de Instrumentos y Actos de Correduría

Este proyecto es una aplicación web desarrollada en Laravel para la gestión integral de instrumentos, actos y comparecientes en una correduría pública. Permite el registro, edición, consulta y administración de trámites, clientes, avisos y reportes relacionados con los instrumentos legales.

## Funcionalidades principales

- Registro y edición de instrumentos (actas, pólizas, etc.)
- Gestión de actos asociados a cada instrumento
- Administración de clientes y comparecientes
- Control de facturación y costos de trámites
- Generación de reportes estadísticos y extractos en PDF/Word
- Búsqueda y filtrado avanzado de instrumentos y actos
- Exportación de datos y generación de documentos
- Panel administrativo con roles y permisos

## Tecnologías utilizadas

- Laravel (backend y ORM)
- Blade (motor de plantillas)
- Bootstrap y jQuery (interfaz de usuario)
- DataTables (tablas interactivas)
- DomPDF y PhpWord (generación de PDF y Word)

## Instalación

1. Clona el repositorio:
	```bash
	git clone <url-del-repositorio>
	```
2. Instala dependencias:
	```bash
	composer install
	npm install && npm run dev
	```
3. Configura tu archivo `.env` y la base de datos.
4. Ejecuta las migraciones y seeders:
	```bash
	php artisan migrate --seed
	```
5. Inicia el servidor:
	```bash
	php artisan serve
	```

## Uso

Accede al panel administrativo para gestionar instrumentos, actos, clientes y reportes. El sistema permite agregar, editar y eliminar registros, así como generar documentos y reportes personalizados.


## Licencia

Este proyecto, desarrollado sobre Laravel Framework, está bajo la licencia MIT.