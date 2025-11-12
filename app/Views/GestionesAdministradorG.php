<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->
        <script src="https://kit.fontawesome.com/b41a278b92.js" crossorigin="anonymous"></script> <!--ICONOS-->


        <!-- BOOTSTRAP -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

        <link rel="stylesheet" href="/IdentiQR/public/CSS/stylesGestionAdmin.css">
        
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
                    <li><a href="Reportes.html">Reportes</a></li>
                    <li><a href="Configuracion.html">Configuración</a></li>
                    <li><a href="/IdentiQR/app/Controllers/ControladorUsuario.php?action=logoutUsuario">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </header>
        <div class="container p-5 my-5 bg-dark text-white"> <!--TODO: Considerar quitar o dejar.-->
            <!-- !Aquí se encontrara los diferentes usos que podrá tener el admin, este podrá cambiar: nota-->    
            <div id = "ExportarDatos" class = "SeguridadDatos">
                <h2>Exportación y recuperación de Datos</h2>
                <a href="/IdentiQR/app/Models/backup.php">
                    <button onclick=""><i class="fa-solid fa-download"></i>Respaldar Base de datos</button>
                </a>
                <form action="/IdentiQR/app/Models/restore.php" method="post" enctype="multipart/form-data">
                    <label for="backupFile"><i class="fa-solid fa-file"></i>Suba un archivo .sql (máx 50MB):</label><br>
                        <input type="file" id="backupFile" name="backupFile" accept=".sql" required><br><br>

                    <!-- checkbox para elegir método si quieres (opcional) -->
                    <label>
                        <input type="checkbox" name="use_cli" value="1">
                    </label><br><br>

                    <button type="submit"><i class="fa-solid fa-file"></i>Cargar y restaurar</button>
                </form>
                
                <!-- Botones para exportar diferentes tipos de datos 
                <button onclick="exportarUsuarios()">Exportar Usuarios</button>
                <button onclick="exportarProductos()">Exportar Productos</button>
                <button onclick="exportarPedidos()">Exportar Pedidos</button>
                -->
            </div>
                
            <main>
                <section id = "GestorUsuarios" class = "GestorUsuarios">
                    <h2><i class="fa-solid fa-user"></i>Gestión de Usuarios</h2>
                    <a href = "/IdentiQR/app/Views/gestionesGeneralesUsuarios/GestionesUsuarios.php?action=registroU#seccionRegistrarUsuario">Registrar Nuevo Usuario</a>
                    <a href = "/IdentiQR/app/Views/gestionesGeneralesUsuarios/GestionesUsuarios.php?action=modificarU#seccionModificarUsuario">Modificar un Usuario</a>
                    <a href = "/IdentiQR/app/Views/gestionesGeneralesUsuarios/GestionesUsuarios.php?action=eliminarU#seccionEliminarUsuario">Eliminar un Usuario</a>
                    <a href = "/IdentiQR/app/Views/gestionesGeneralesUsuarios/GestionesUsuarios.php?action=consultarUsuario#seccionConsultarUsuario">Buscar un Usuario</a>
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
                    <h2><i class="fa-solid fa-file"></i>Gestión de Tramites</h2>
                    <!--*:  Aquí se van a incluir todos los tramites que se pueden realizar (Desto todos los departamentos)-->
                    <div id = "card-Dir">
                        <legend>Dirección Academica</legend>
                        <a href="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php">Gestión Dirección Acádemica - Justificantes_DirecciónAcademica</a>
                        <!-- <a href="gestionRecursamiento_Dir.html">Recursamiento_DirecciónAcademica</a> -->
                    </div>
                    <hr>
                    <div id = "card-DirVinculacion">
                        <legend>Dirección de Vinculación</legend>
                        <a href="dirVinculacion/gestionDocumentosAlumnos.php">Gestión de Vinculación - Documentos de Alumnos</a>
                    </div>
                    <hr>
                    <div id = "card-DirDDA">
                        <legend>Dirección de Desarrollo Academico</legend>
                        <a href="dirDDA/gestionAsistenciaTutorias.php">Gestión de DDA - Tutorias</a>
                    </div>
                    <hr>
                    <div id = "card-DirDAE">
                        <legend>Dirección de Asuntos Estudiantiles</legend>
                        <a href="dirDAE/gestionDocumentosDAE.php">Gestión de DAE - Extracurriculares</a>
                    </div>
                    <hr>
                    <div id = "card-DirMedicina">
                        <legend>Dirección de Atención Medica de Primer Contacto</legend>
                        <a href="dirMedica/gestionDocMed.php">Gestion de citas con el medico - Médicina</a>
                    </div>
                    <hr>
                    <div id = "card-DirServEsco">
                        <legend>Dirección de Servicios Escolares</legend>
                        <a href="dirServEsco/gestionDocumentosServEsco.php">Gestión de documentos Estudiantiles</a>
                    </div>
                    <hr>
                </section>
                <section>
                    <h2>Reportes</h2>
                    <form action="GenerarReporte.php" method="post">
                        <label for="tipoReporte">Tipo de Reporte:</label>
                        <select id="tipoReporte" name="tipoReporte">
                            <option value="Usuarios">Usuarios</option>
                            <option value="#">?</option>
                            <option value="#">?</option>
                        </select>
                        <button type="submit">Generar Reporte</button>
                    </form>
                </section>
            </main>
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