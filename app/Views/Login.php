<?php
    //include_once "../Controllers/ControladorUsuario.php";
    //include_once "../../config/Connection_BD.php";
    //$controllerUsuario = new UserController($conn);
    $showError = isset($_GET['error']) && $_GET['error'] === '1';
    if ($showError) {
        echo '<div id="mensajeError" style="display:block;visibility:hidden;">Credenciales inválidas</div>';
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login_IdentiQR</title>
        <script src="https://kit.fontawesome.com/b41a278b92.js" crossorigin="anonymous"></script> <!--ICONOS-->
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->
        <link rel="stylesheet" href="/IdentiQR/public/CSS/stylesLogin.css"> <!--CSS-->
    </head>
    <body>
        <!-- !Aquí se encontrara el encabezado, este podrá cambiar: nota-->
        <header id="HeaderIndex1" class="site-header">
            <div class="header-inner">
                <div class="header-left">
                    <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-SinFonde.png" alt="Logo IdentiQR" class="ImagenIndex1"/>
                <div class="brand">
                    <!-- <h1>IdentiQR</h1> -->
                    <!-- <p class="tagline">Control y registro mediante QR</p> -->
                </div>
                </div>

                <div class="header-right">
                <nav aria-label="Navegación principal">
                    <ul class="nav-list">
                    <li><a href="/IdentiQR/index.html" class="select"><i class="fa-regular fa-house"></i>Inicio</a></li>
                    <li><a href="#"><i class="fa-solid fa-plus"></i>Servicios</a></li>
                    <li><a href="#"><i class="fa-solid fa-envelope"></i>Contactanos</a></li>
                    </ul>
                </nav>

                <button class="btn__menu" id="btn_menu" aria-expanded="false" aria-label="Abrir menú">☰</button>
                </div>
            </div>
        </header>
        <!--*A partir de acá se inicializara la parte de la página general, sera nuestro tema central e identificación de lo que contendrá-->
        
        <hr>
        <!-- TODO: Aquí empezaremos con la información que tiene que ver con los datos o mayoritariamente del index principal (Recursos, etc.)-->
        <main>
            <div class="Login_ContenedorGeneral">
                <h2>Iniciar Sesión</h2>
                <h2><i class="fa-solid fa-user"></i></h2>
                <h2><i class="fa-thin fa-user"></i></h2>
                <form action="/IdentiQR/app/Controllers/ControladorUsuario.php?action=loginUsuario" method="POST">
                    <fieldset>
                        <legend>Ingresa tus credenciales</legend>
                        <label for="usuario">Usuario</label>
                            <input type="text" id="usuario" name="usuario" placeholder="Usuario/Correo" required>
                        <label for="password">Contraseña</label>
                            <input type="password" id="password" name="password" placeholder = "*****" required>
                        <button type="submit" name="enviarLogin">Entrar</button>
                    </fieldset>
                </form>
            </div>
        </main>

        <br>
        <script src="/IdentiQR/public/JavaScript/validacionLogin.js"></script>
    </body>
</html>