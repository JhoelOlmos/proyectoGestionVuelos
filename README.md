# proyectoGestionVuelos
Sistema de Gestión de Vuelos y Reservas (Microservicios)
Este proyecto es un sistema web integral diseñado para la gestión de vuelos, naves (aeronaves) y reservas, implementado bajo una arquitectura basada en microservicios.

El sistema soporta dos roles principales: Administrador (gestión de usuarios, vuelos y naves) y Gestor (creación, consulta y cancelación de reservas).

-Características Clave

Arquitectura de Microservicios: Backend dividido en, al menos, dos servicios independientes: uno para Usuarios y Autenticación, y otro para Vuelos, Naves y Reservas.

Seguridad y Autenticación: Uso de un token aleatorio por sesión, almacenado en la base de datos y en el navegador, para proteger el acceso a los servicios privados.

Gestión Completa de Recursos: Permite la administración total de Usuarios, Vuelos y Naves (CRUD) por parte del administrador.

Operaciones de Reservas: Funcionalidades de creación, consulta y cancelación de reservas, restringidas al rol de Gestor
