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
    $statusAlert = $_GET['status'] ?? $statusAlert ?? '';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/IdentiQR/public/Media/img/Favicon.ico" type="image/x-icon"> <!--FAVICON-->
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesDirecciones.css">
        <script src="https://kit.fontawesome.com/b41a278b92.js" crossorigin="anonymous"></script> <!--ICONOS-->

        <script src="/IdentiQR/public/JavaScript/gestionesDirecciones.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <title>DireccionVinculacion_IdentiQR</title>
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
                </div>
            </div>
        </header>
        <div class = "contenedorCentral">
            <section>
                <h2>Gestión de Vinculación - Documentos de Alumnos</h2>
                <a href = "/IdentiQR/app/Views/dirVinculacion/gestionDocumentosAlumnos.php#generarDoc">Registrar Nuevo Documento Vinculación</a>
                <a href = "/IdentiQR/app/Views/dirVinculacion/gestionDocumentosAlumnos.php#modificarDoc">Modificar un Documento Vinculación</a>
                <a href = "/IdentiQR/app/Views/dirVinculacion/gestionDocumentosAlumnos.php#eliminarDoc">Eliminar un Documento Vinculación</a>
                <a href = "/IdentiQR/app/Views/dirVinculacion/gestionDocumentosAlumnos.php#consultarDoc">Buscar un Documento Vinculación</a>
            </section>

            <section>
                <h2>Reportes PDF - Consultar generales</h2>
                <div class="reporte-container">
                    <h3>Reporte Entre Fechas</h3>
                    <form id="formRepInd" action="/IdentiQR/redireccionAcciones.php?controller=reportsGeneral&action=repGen_Vinc" method="POST" novalidate>
                        <div id="camposFechas">
                            <label for="fe1">Fecha mínima (Fecha 1):</label>
                            <input type="date" name="fe1" id="fe1">
                            <label for="fe2">Fecha máxima(Fecha 2):</label>
                            <input type="date" name="fe2" id="fe2">
                            <div id="err-fechas" style="color:#b00; display:none;"></div>
                        </div>
                        <input type="hidden" id = "idDepto" name="idDepto" value="7">
                        <div style="margin-top:10px;">
                            <input type="submit" class="btn-submit" value="Generar Reporte de Citas del Día" name = "reporteIndividualizado_Vinc">
                        </div>
                    </form>
                </div>
            </section>
        </div>
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
