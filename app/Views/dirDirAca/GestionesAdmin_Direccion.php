<?php
    //Iniciar sesión SOLO si no existe una activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['rol'])) {
        header("Location: /IdentiQR/app/Views/Login.php");
        exit();
    }
    $usuarioActivo = $_SESSION['usr'] ?? 'Usuario';
    $rolActivo = $_SESSION['rol'] ?? 'Invitado';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/IdentiQR/public/Media/img/Favicon.ico" type="image/x-icon"> <!--FAVICON-->
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesDirecciones.css">
        <script src="https://kit.fontawesome.com/b41a278b92.js" crossorigin="anonymous"></script> <!--ICONOS-->
        <title>DireccionACADEMICA_IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrará el encabezado, este podrá cambiar: nota-->
        <header id="HeaderIndex1">
            <div class="container__header">
                <div class="logo">
                    <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-SinFonde.png" alt="Banner-IdentiQR" weight="200" height="200">
                </div>
                <div class="info-sesion">
                    <i class="fa-solid fa-user-circle"></i>
                    <span>Sesión activa: <strong><?php echo htmlspecialchars($usuarioActivo); ?></strong></span>
                    <br>
                    <small style="font-size: 0.8rem; font-weight: normal;"><?php echo $rolActivo; ?></small>
                </div>
                <div class="container__nav">
                    <nav id="nav">
                        <ul>
                            <li><a href="/IdentiQR/index.html" class="select">INICIO</a></li>
                            <li><a href="/IdentiQR/index.html#PresentacionDepartamentos">TEMAS</a></li>
                            <li><a href="/IdentiQR/index.html#contacto"><i class="fa-solid fa-envelope"></i>CONTACTOS</a></li>
                            <li><a href="/IdentiQR/app/Controllers/ControladorUsuario.php?action=logoutUsuario">Cerrar Sesión</a></li>
                        </ul>
                    </nav>
                    <!--
                    <div class="btn__menu" id="btn_menu">
                        <i class="fas fa-bars"></i>
                    </div>
                    -->
                </div>
            </div>
        </header>
        <!--*A partir de acá se inicializará la parte de la página general, será nuestro tema central e identificación de lo que contendrá-->

        <div id="HeaderLogin">
            <h2><center>IdentiQR</center></h2>

        </div>
        <hr>
        <!-- TODO: Aquí empezaremos con la información que tiene que ver con los datos o mayoritariamente del index principal (Recursos, etc.)-->
        <div class = "contenedorCentral">
            <section>
                <h2>Gestión de Alumnos</h2>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php#seccionRegistrarAlumno">Registrar Nuevo Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php#ConsultaModificacionAlumnos">Modificar un Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php#EliminacionAlumnos">Eliminar un Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php#ConsultaModificacionAlumnos">Buscar un Alumno</a>
            </section>
            <hr>

            <section>
                <h2>Gestión de trámites</h2>
                <a href = "/IdentiQR/app/Views/dirDirAca/gestionJustificantes_Dir.php#generarJustificante">Generar trámite</a>
                <a href = "/IdentiQR/app/Views/dirDirAca/gestionJustificantes_Dir.php#revisarJustificante">Revisar trámite</a>
            </section>

            <section>
                <h2>Reportes PDF - Consultar generales</h2>
                <div class="reporte-container">
                    <h3>Reporte de alumnos</h3>
                    <form id="formRepInd" action="/IdentiQR/redireccionAcciones.php?controller=reportsGeneral&action=alumnosGenerales2" method="POST" novalidate>
                        <input type="hidden" name="idDepto" value="2">
                        <div style="margin-top:10px;">
                            <input type="submit" class="btn-submit" value="Generar Reporte de Alumnos" name = "reporteIndividualizado_DirAca">
                        </div>
                    </form>
                </div>
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
        <input type="hidden" id="serverStatusAlert" value="<?php echo isset($statusAlert) ? $statusAlert : ''; ?>">
    </body>
</html>