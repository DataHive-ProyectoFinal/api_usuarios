<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Inicio sesion de Socios</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Ingreso de Socios</h2>

    <form action="/prueba%20proyecto/api_usuarios/procesar_login.php" method="POST" class="space-y-4">
      <div>
        <label for="ci" class="block text-gray-700 font-medium">Cédula de Identidad:</label>
        <input type="text" name="ci" id="ci" required
               class="w-full px-3 py-2 border rounded-lg bg-gray-50" />
      </div>

      <div>
        <label for="contrasena" class="block text-gray-700 font-medium">Contraseña:</label>
        <input type="password" name="contrasena" id="contrasena" required
               class="w-full px-3 py-2 border rounded-lg bg-gray-50" />
      </div>

      <button type="submit"
              class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition font-semibold">
        Iniciar sesion
      </button>
    </form>
  </div>
</body>
</html>
