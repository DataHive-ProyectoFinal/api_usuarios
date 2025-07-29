# 📝 Instrucciones para la Aplicación del Sistema

> 📄 *Este documento también se encuentra disponible en la rama `testing` del repositorio `Frontend` en GitHub.*

---

## 📦 Repositorios necesarios

Clonar los siguientes repositorios desde GitHub:

- `Frontend` 
- `Api_usuarios` 
- `Backend` *(No es necesario)*
- `Base_de_datos`  *(No es necesario)*

> En cada uno, realizar un `git pull` desde la rama `main`.

---

##  Configuración de la Base de Datos

1. Abrir `phpMyAdmin`.
2. Crear una nueva base de datos para la cooperativa.
3. Importar el archivo `.sql` correspondiente desde el repositorio `Base_de_datos`.

---

##  Configuración en XAMPP

1. Iniciar **Apache** y **MySQL** desde el panel de XAMPP.
2. Copiar las carpetas `Frontend` y `api_usuarios` dentro de:
   ```
   C:\xampp\htdocs\
   ```

---

##  Ruta para el Registro de Usuario

1. Abrir la *landing page* en el navegador desde la terminal VS:
2. Hacer clic en **"¡Quiero registrarme!"**.
3. Completar el formulario y hacer clic en **"Registrarme"**.
4. La solicitud será enviada automáticamente al BackOffice del administrador.

---

##  Ruta para el Administrador

1. Para ver la lista de solicitudes, ingresar en el navegador:
   ```
   http://localhost/api_usuarios/admin_solicitudes.php
   ```
2. Hacer clic en **"Aceptar"** para aprobar una solicitud.
3. Se abrirá un formulario para crear el usuario.
4. La contraseña se genera automáticamente. Copiarla y pegarla en el campo **"Escribir contraseña"**.
5. Hacer clic en **"Crear Usuario"** para finalizar el proceso.

---

##  Ruta de Inicio de Sesión para el Socio

1. Desde el *Home* de la cooperativa, hacer clic en **"Ingresar"**.
2. Introducir la **cédula** y la **contraseña generada** anteriormente.
3. Al iniciar sesión, se mostrará un mensaje de "Sitio en mantenimiento".
4. Al hacer clic en **"Cerrar sesión"**, se redireccionará nuevamente al *Home*.

---


