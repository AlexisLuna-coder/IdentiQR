<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->
        <script src="https://kit.fontawesome.com/b41a278b92.js" crossorigin="anonymous"></script> <!--ICONOS-->


        <!-- BOOTSTRAP -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <link rel="stylesheet" href="/IdentiQR/public/CSS/stylesGestionAdmin.css">
        <script src="/IdentiQR/public/JavaScript/gestionesAdminGeneral.js"></script> <!--Aquí se encontraran los sweet-alert-->
        
        <title>Gestiones del Administrador_IdentiQR</title>
    </head>
    <body>
        <header id="HeaderAdminG" class = "HeaderAdminG">
            <div class="logo">
                <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-SinFonde.png" alt="Logo de IdentiQR" class="ImagenIndex1" id = "logoIndex" width="300" height="200">
            </div>
            <div class = "Usuario">
                <h1>Panel de Gestiones del Administrador</h1>
            </div>
            <nav class = "nav">
                <ul>
                    <!-- <li><a href="index.html">Inicio</a></li> -->
                    <li><a href="Usuarios.html">Gestión de Usuarios</a></li>
                    <!--
                        <li><a href="Productos.html">Gestión de Productos</a></li>
                        <li><a href="Pedidos.html">Gestión de Pedidos</a></li>
                    -->
                    <li><a href="/IdentiQR/app/Views/GestionesAdministradorG.php#reportesGenerales">Reportes</a></li>
                    <li><a href="/IdentiQR/app/Views/GestionesAdministradorG.php#ExportarDatos">Configuración</a></li>
                    <li><a href="/IdentiQR/app/Controllers/ControladorUsuario.php?action=logoutUsuario">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>
        <div class="container p-5 my-5 bg-dark text-white"> <!--TODO: Considerar quitar o dejar.-->
            <!-- !Aquí se encontrará los diferentes usos que podrá tener el admin, este podrá cambiar: nota-->    
            <div id = "ExportarDatos" class = "SeguridadDatos">
                <h2>Exportación y recuperación de Datos</h2>
                <a href="/IdentiQR/redireccionAcciones.php?controller=BackRest_DBs&action=backup" onclick="confirmarBackup(event, this.href)"> <!--NOTA. ACABAR-->
                    <button onclick=""><i class="fa-solid fa-download"></i>Respaldar Base de datos</button>
                </a>
                <form action="/IdentiQR/redireccionAcciones.php?controller=BackRest_DBs&action=restore" method="post" enctype="multipart/form-data" onsubmit="confirmarRestore(event)">
                    <label for="backupFile"><i class="fa-solid fa-file"></i>Suba un archivo .sql (máx 50MB):</label><br>
                        <input type="file" id="backupFile" name="backupFile" accept=".sql" required><br><br>
                        <button type="submit"><i class="fa-solid fa-file"></i>Cargar y restaurar</button>
                </form>
            </div>
                
            <main>
                <section id = "GestorUsuarios" class = "GestorUsuarios">
                    <h2><i class="fa-solid fa-user"></i>Gestión de Usuarios</h2>
                    <a href = "/IdentiQR/app/Views/gestionesGeneralesUsuarios/GestionesUsuarios.php?action=registroU#seccionRegistrarUsuario">Registrar Nuevo Usuario</a>
                    <a href = "/IdentiQR/app/Views/gestionesGeneralesUsuarios/GestionesUsuarios.php?action=modificarU#seccionModificarUsuario">Modificar un Usuario</a>
                    <a href = "/IdentiQR/app/Views/gestionesGeneralesUsuarios/GestionesUsuarios.php?action=eliminarU#seccionEliminarUsuario">Eliminar un Usuario</a>
                    <a href = "/IdentiQR/app/Views/gestionesGeneralesUsuarios/GestionesUsuarios.php?action=consultarUsuario#seccionConsultarUsuario">Buscar un Usuario</a>

                    <!--BOTON QUE SE USARÁ ÚNICAMENTE PARA LA REDIRECCIÓN Y ENVIO DE LOS NUEVOS CODIGOS QR-->
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modalConfirmacionQR">
                        Actualización MASIVA de QR
                    </button>
                </section>

                <hr>
                <section>
                    <h2><i class="fa-solid fa-users"></i>Gestión de Alumnos</h2>
                    <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php?action=registroA#seccionRegistrarAlumno">Registrar Nuevo Alumno</a>
                    <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php?action=modificarA#ConsultaModificacionAlumnos">Modificar un Alumno</a>
                    <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php?action=eliminarA#EliminacionAlumnos">Eliminar un Alumno</a>
                    <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php?action=consultarA#ConsultaModificacionAlumnos">Buscar un Alumno</a>
                </section>

                <hr>
                <section>
                    <h2><i class="fa-solid fa-file"></i>Gestión de Trámites</h2>
                    <!--*:  Aquí se van a incluir todos los trámites que se pueden realizar (De esto todos los departamentos)-->
                    <div id = "card-Dir">
                        <legend>Dirección Académica</legend>
                        <a href="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php">Gestión Dirección Académica - Justificantes - Recursamiento </a>
                    </div>
                    <hr>
                    <div id = "card-DirVinculacion">
                        <legend>Dirección de Vinculación</legend>
                        <a href="/IdentiQR/app/Views/dirVinculacion/GestionesAdmin_Vinculacion.php">Gestión de Vinculación - Documentos de Alumnos</a>
                    </div>
                    <hr>
                    <div id = "card-DirDDA">
                        <legend>Dirección de Desarrollo Académico</legend>
                        <a href="/IdentiQR/app/Views/dirDDA/GestionesAdmin_DesaAca.php">Gestión de DDA - Tutorías</a>
                    </div>
                    <hr>
                    <div id = "card-DirDAE">
                        <legend>Dirección de Asuntos Estudiantiles</legend>
                        <a href="/IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php">Gestión de DAE - Extracurriculares</a>
                    </div>
                    <hr>
                    <div id = "card-DirMedicina">
                        <legend>Dirección de Atención Médica de Primer Contacto</legend>
                        <a href="/IdentiQR/app/Views/dirMedica/GestionesAdmin_Medico.php">Gestión de citas con el médico - Medicina</a>
                    </div>
                    <hr>
                    <div id = "card-DirServEsco">
                        <legend>Dirección de Servicios Escolares</legend>
                        <a href="/IdentiQR/app/Views/dirServEsco/GestionesAdmin_ServEsco.php">Gestión de documentos Estudiantiles - DAE</a>
                    </div>
                    <hr>
                </section>
                <section id = "reportesGenerales">
                    <h2><i class="fa-solid fa-clipboard"></i>Reportes</h2>
                    <form id="formReportes" action="/IdentiQR/redireccionAcciones.php?controller=reportsGeneral&action=restore" method="post" onsubmit="return generarReporteAlert()">
                        <label for="tipoReporte">Tipo de Reporte:</label>
                        <select id="tipoReporte" name="tipoReporte">
                            <option value="" disabled selected>Selecciona una opción...</option>
                            <option value="Tramites">Tramites generales</option>
                            <option value="Usuarios">Usuarios</option>
                            <option value="Alumnos">Alumnos</option>
                        </select>
                        <button type="submit">Generar Reporte</button>
                    </form>
                </section>

                <!--SCRIPT PARA MODIFICAR EL ACTION / CONSIDERAR PONERLO EN OTRO .JS-->
                <script>
                (function(){
                    const mapping = {
                        'Tramites': 'tramitesGenerales',
                        'Usuarios': 'usuariosGenerales',
                        'Alumnos': 'alumnosGenerales'
                    };

                    const form = document.getElementById('formReportes');
                    const select = document.getElementById('tipoReporte');

                    // Al cambiar la selección actualizamos la acción del form (buena UX)
                    select.addEventListener('change', () => {
                        const val = select.value;
                        const act = mapping[val];
                        if (act) {
                            form.action = `/IdentiQR/redireccionAcciones.php?controller=reportsGeneral&action=${act}`;
                        }
                    });

                    // Por seguridad: antes de enviar, validamos selección y forzamos la acción correcta
                    form.addEventListener('submit', (e) => {
                        const val = select.value;
                        if (!val) {
                            e.preventDefault();
                            alert('Por favor selecciona el tipo de reporte que deseas generar.');
                            select.focus();
                            return;
                        }
                        const act = mapping[val];
                        if (act) {
                            form.action = `/IdentiQR/redireccionAcciones.php?controller=reportsGeneral&action=${act}`;
                        }
                    });
                })();
                </script>
            </main>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modalConfirmacionQR" tabindex="-1" role="dialog" aria-labelledby="modalConfirmacionQRLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalConfirmacionQRLabel">Confirmación masiva de QR de los alumnos</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Está seguro de que desea proceder con la modificación masiva de QR de los alumnos?
                        <br>Considere que esta actualización podrá demorar tiempo...
                    </div>
                    <div class="modal-footer">
                        <a href="/IdentiQR/app/Controllers/ControladorAlumnos.php?action=actualizarQR_Alumno">
                            <button type="button" class="btn btn-primary">Sí, continuar</button>
                        </a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No, regresar</button>
                    </div>
                </div>
            </div>
        </div>
        <!------------------------------------>
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
                    Por ningún motivo, el estudiante podrá acceder al sistema.
                </p>
                </div>
            </div>
        </footer>

        <!-- VARIABLE OCULTA PARA COMUNICAR PHP -> JS -->
        <input type="hidden" id="serverStatusBD" value="<?php echo $statusAlert; ?>">
    </body>
</html>