<?php
session_start();
if (!isset($_SESSION['ci'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Frontend socios</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white p-8 rounded-2xl shadow-xl max-w-md w-full text-center">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">¡Bienvenido/a!</h1>
    <p class="text-gray-700 mb-4"> <strong>Proximamente..</strong> <br> <span class="text-greem-300">Sitio en desarrollo</span></p>
    

    <a href="/Frontend/landingPage/Home.html"
       class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
      Cerrar sesión
    </a>
  </div>
</body>
</html>

