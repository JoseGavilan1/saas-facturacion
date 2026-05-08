# 💼 Sistema de Facturación SaaS

Una aplicación web fullstack moderna diseñada para la gestión integral de clientes, catálogo de productos y emisión automatizada de facturas. Este proyecto está construido con un enfoque en la reactividad del frontend y la robustez del backend, aplicando patrones de diseño de software y manejo seguro de bases de datos relacionales.

## 🚀 Tecnologías Utilizadas

* **Backend:** PHP 8, Laravel (Framework)
* **Frontend:** Vue 3 (Composition API), Inertia.js, Tailwind CSS
* **Base de Datos:** MySQL / SQLite
* **Generación de Documentos:** DomPDF
* **Testing de Correos:** Mailtrap

## ✨ Características Principales

* **Dashboard Interactivo:** Panel de control con métricas en tiempo real sobre el rendimiento del negocio (total facturado, clientes activos, facturas emitidas).
* **Gestión de Clientes (CRUD):** Registro y administración de clientes.
* **Catálogo de Productos:** Control de inventario de servicios y productos con precios netos.
* **Motor de Facturación Reactivo:** Creación de facturas interactiva con cálculo automático de subtotales, IVA (19%) y total final en tiempo real.
* **Generación de PDF:** Exportación instantánea de las facturas emitidas a formato PDF con un diseño limpio y profesional.
* **Notificaciones por Correo:** Envío automatizado de la factura (PDF adjunto) al correo electrónico del cliente tras su emisión utilizando Mailable y colas de trabajo.
* **Seguridad Relacional:** Prevención de borrado en cascada (Integrity Constraints) para proteger el historial contable si se intenta eliminar un cliente o producto vinculado a una factura existente.

## ⚙️ Instalación y Despliegue Local

Sigue estos pasos para ejecutar el proyecto en tu entorno local:

1. **Clonar el repositorio**
   ```bash
   git clone [https://github.com/TU_USUARIO/TU_REPOSITORIO.git](https://github.com/JoseGavilan1/saas-facturacion.git)
   cd TU_REPOSITORIO
