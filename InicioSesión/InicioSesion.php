<?php
include ("conexion.php");
//login
if (!empty($_POST)){
    $cedula = mysqli_real_escape_string($conexion,$_POST['ci']);
    $password = mysqli_real_escape_string($conexion,$_POST['contrasena']);
    $sql = "SELECT ci FROM usuarios 
                WHERE ci = '$cedula' AND contrasena = '$password' ";
    $resultado = $conexion->query($sql);
    $rows = $resultado->num_rows;
    if ($rows > 0){
        $row = $resultado->fetch_assoc();
        $_SESSION['ci']= $row["ci"];
        header ("Location: /frontcoop/homeSocio.html");
    }else{
        echo "<script> 
                alert('Cédula o Contraseña incorrecto');
                window.location = 'InicioSesion.php';
            </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
    <link rel="stylesheet" href="InicioSesion.css">
    <title>Inicio Sesión</title>
</head>
<body>
    <section class="contenido">
    <section class="bg-gray-100 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <a href="#" class="flex items-center mb-6">
            <img class="w-20 h-12 mr-2" src="/multimedia/logo-cooperativa.svg" alt="logo">  
        </a>
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Inicio Sesion
                </h1>
                <form class="space-y-4 md:space-y-6" action="conexion_html.php" method="POST">
                    <div>
                        <label for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cedula</label>
                        <input type="text" name="ci" id="cedula" maxlenght="8" oninput="this.value = this.value.replace(/\D/g, '')" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="12345678" required>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contraseña</label>
                        <input type="password" name="contrasena" id="password" minlength="8" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        <p id="error"></p>
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" name="ingresar">Iniciar Sesion</button>
                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                    ¿Aún no tienes una cuenta? <a href="#" class="font-medium text-primary-600 hover:underline dark:text-primary-500">Registrate</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
    </section>
</section>
    <script src="InicioSesion.js"></script>
</section>
</body>
</html>