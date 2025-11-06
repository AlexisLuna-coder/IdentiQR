
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/IdentiQR/public/Media/img/Favicon.ico" type="image/x-icon"> <!--FAVICON-->
        <title>DireccionACADEMICA_IdentiQR</title>

        <!--TODO: Aquí se tendra que pasar a CSS-->
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
        <div class = "contenedorCentral">
            <div id="HeaderLogin">
                <h2><center>IdentiQR</center></h2>
                
            </div>
            <hr>
            <!-- TODO: Aquí empezaremos con la información que tiene que ver con los datos o mayoritariamente del index principal (Recursos, etc.)-->
            <section>
                <h2>Gestión de Alumnos</h2>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/RegistroAlumno.html#seccionRegistrarAlumno">Registrar Nuevo Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/RegistroAlumno.html#ConsultaModificacionAlumnos">Modificar un Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/RegistroAlumno.html#EliminacionAlumnos">Eliminar un Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/RegistroAlumno.html#ConsultaModificacionAlumnos">Buscar un Alumno</a>
            </section>
            <hr>

            <section>
                <h2>Gestión de justificantes</h2>
                <a href = "/IdentiQR/app/Views/dirDirAca/gestionJustificantes_Dir.html#generarJustificante">Generar justificante</a> 
                <a href = "/IdentiQR/app/Views/dirDirAca/gestionJustificantes_Dir.html#revisarJustificante">Revisar justificante</a> 
            </section>
        </div>

        <br>
        <footer class="FooterIndex1" id = "FooterIndex1">
            <div class="FooterIndex1">
                <div class="footer__info">
                    <h3>IdentiQR</h3>
                    <p>
                        ©2025 IdentiQR. Todos los derechos reservados.
                        <br>
                        Diseñado por: Lizbeth B. y Alexis S.
                    </p>
                </div>
                <div class="footer__links">
                    <!--*: Todo esto tiene que ver con los LINKS que se involucraran al diseñar el sitio web-->
                    <a href="mailto: IdentiQR.info@gmail.com">Contact Us</a>
                    <a href="#Terms_Index1">Terminos del servicio</a>
                </div>
            </div>

            <!-- Nuevo div de términos -->
            <div id="Terms_Index1">
                <p>
                    Toda información resguardada será de carácter relevante. 
                    No se podrá acceder a este sistema si no se cuenta con previo registro. 
                    Por ningún motivo el estudiante podrá acceder al sistema.
                </p>
            </div>
        </footer>
    </body>
</html>