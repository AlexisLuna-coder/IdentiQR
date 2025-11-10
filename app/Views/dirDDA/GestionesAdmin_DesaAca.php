<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/IdentiQR/public/Media/img/Favicon.ico" type="image/x-icon"> <!--FAVICON-->
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesDirecciones.css">
        <title>DireccionACADEMICA_IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrara el emcabezado, este podrá cambiar: nota-->
        <header id="HeaderIndex1">
            <div class="container__header">
                <div class="logo">
                    <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-SinFonde.png" alt="Banner-IdentiQR" weight="200" height="200">
                </div>
                <div class="container__nav">
                    <nav id="nav">
                        <ul>
                            <li><a href="/IdentiQR/index.html" class="select">INICIO</a></li>
                            <li><a href="#">TEMAS</a></li>
                            <li><a href="#">CONTACTOS</a></li>
                        </ul>
                    </nav>
                    <div class="btn__menu" id="btn_menu">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </header>
        <!--*Apartir de acá se inicializara la parte de la página general, sera nuestro tema central e identificación de lo que contendra-->

        <div id="HeaderLogin">
            <h2><center>IdentiQR</center></h2>

        </div>
        <hr>
        <!-- TODO: Aquí empezaremos con la información que tiene que ver con los datos o mayoritariamente del index principal (Recursos, etc.)-->
        <div class = "contenedorCentral">
            <section>
                <h2>Gestión de Tutorias Alumnos - DDA</h2>
                <a href = "/IdentiQR/app/Views/dirDDA/gestionAsistenciaTutorias.php#generarTramite">Registrar Nuevo Documento DDA - Tutorias</a>
                <a href = "/IdentiQR/app/Views/dirDDA/gestionAsistenciaTutorias.php#modificarTramite">Modificar un Documento DDA - Tutorias</a>
                <a href = "/IdentiQR/app/Views/dirDDA/gestionAsistenciaTutorias.php#eliminarTramite">Eliminar un Documento DDA - Tutorias</a>
                <a href = "/IdentiQR/app/Views/dirDDA/gestionAsistenciaTutorias.php#revisarTramite">Buscar un Documento DDA - Tutorias</a>
            </section>
            <hr>

            <section>
                <h2>Consultas generales</h2>

            </section>
        </div>

        <br>
        <footer class="FooterIndex1" id="FooterIndex1">
            <div class="footer__container">
                <div class="footer__info">
                <h3>IdentiQR</h3>
                <p>
                    ©2025 IdentiQR. Todos los derechos reservados.<br>
                    Diseñado por: Lizbeth B. y Alexis S.
                </p>
                </div>
                <div class="footer__links">
                <a href="mailto:IdentiQR.info@gmail.com">Contact Us</a>
                <a href="#Terms_Index1">Términos del servicio</a>
                </div>
                <div class="footer__terms" id="Terms_Index1">
                <p>
                    Toda información resguardada será de carácter relevante. 
                    No se podrá acceder a este sistema si no se cuenta con previo registro. 
                    Por ningún motivo el estudiante podrá acceder al sistema.
                </p>
                </div>
            </div>
        </footer>
    </body>
</html>