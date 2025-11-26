<?php
    //Iniciar sesión SOLO si no existe una sesión activa
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    //Valida si existe una sesión activa (según el ROL). Si no, redirige forzosamente al Login (Evita acceso por URL directa)
    if (!isset($_SESSION['rol'])) {
        header("Location: /IdentiQR/app/Views/Login.php");
        exit();
    }
    $usuarioActivo = $_SESSION['usr'] ?? 'Usuario';
    $rolActivo = $_SESSION['rol'] ?? 'Invitado';
    //Calcula la fecha de hace 16 años para restringir el registro de menores en el HTML
    $fechaMaximaPermitida = date('Y-m-d', strtotime('-16 years')); //Convierte una cadena de texto con una fecha y hora en inglés a una marca de tiempo Unix. https://www.php.net/manual/es/function.strtotime.php
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->
        <!--*: Da formato para el registro -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> <!--BOOTSTRAP-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> <!--BOOTSTRAP-->
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesAlumnos.css"> <!--CSS-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!--PARA LAS ALERTAS BONITAS-->
        <script src="https://kit.fontawesome.com/b41a278b92.js" crossorigin="anonymous"></script> <!--ICONOS-->
        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script> <!--PARA HACER USO DE LA CAMARA WEB-->
        <script src="/IdentiQR/public/JavaScript/gestionesAlumnos.js"></script> <!-- JS -->
        <title>GestionAlumnos_IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrará el encabezado, este podrá cambiar: nota-->
        <header id="HeaderIndex1">
            <div class="container__header">
                <div class="logo">
                    <a href="/IdentiQR/index.html">
                        <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-SinFonde.png" alt="Logo de IdentiQR" class="ImagenIndex1" id = "logoIndex" width="300" height="200">
                    </a>
                </div>
                <div class="container__nav">
                    <nav id="nav" class = "nav">
                        <ul class = "nav-list">
                            <li><a href="/IdentiQR/index.html" class="select">INICIO</a></li>
                            <li><a href="/IdentiQR/index.html#PresentacionDepartamentos">TEMAS</a></li>
                            <li><a href="/IdentiQR/index.html#contacto"><i class="fa-solid fa-envelope"></i>CONTACTOS</a></li>
                            <li><a href="/IdentiQR/app/Controllers/ControladorUsuario.php?action=logoutUsuario">Cerrar Sesión</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <!--*A partir de acá se inicializara la parte de la página general, áa nuestro tema central e identificación de lo que contendrá-->
        <div id="Index1">
            <h1><center>IdentiQR</center></h1>
        </div>
        <hr>
        <p class = "Textos_GeneralIndex1">
            Genera tu código QR único para la identificación y mejora de la recopilación de tus datos, al realizar los trámites.
            <br>
            <i>Escanea cualquier código QR</i> para acceder a fácilmente a los datos y mucho más con un solo toque.
        </p>
        <hr> 
        <!-- TODO: Aquí empezaremos con la parte de ingreso o registro de los Alumnos-->
        <!-- *Formulario para el registro de los alumnos-->
        <div class="contenedor-secciones">
            <section id="seccionRegistrarAlumno" class = "seccionRegistrarAlumno">
                <form action = "/IdentiQR/app/Controllers/ControladorAlumnos.php?action=registroAlumno" method = "POST" onsubmit = "return validarEdadAlumno(event)">
                    <fieldset>
                    <legend><h3>Formulario de Registro - Alta de alumno</h3></legend>
                    <p>Por favor, rellena este formulario para registrar un alumno.</p>
                    <hr>
                    <div class="form-group row">
                        <label for="Matricula">Matrícula</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="fa fa-address-card"></i>
                                </div>
                            </div>
                            <input type="text" id="matricula" name="matricula" placeholder = "ABCO23####" required>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="Nombre(s)">Nombre(s)</label>
                        <input type="text" id="nombre" name="nombre" placeholder = "Ingresa tu nombre" required>
                    </div>
                    <div class="form-group row">
                        <label for="ApePat">Apellido paterno</label>
                        <input type="text" id="ApPat" name="ApPat" placeholder = "Ingresa tu Apellido Paterno" required>
                    </div>
                    <div class="form-group row">
                        <label for="ApeMat">Apellido materno</label>
                        <input type="text" id="ApMat" name="ApMat" placeholder = "Ingresa tu Apellido Materno"required>
                    </div>
                    <div class="form-group row">
                        <label for="FechaNac">Fecha de nacimiento</label>
                        <input type="date" id="FeNac" name="FeNac" 
                            min="2002-01-01" 
                            max="<?php echo $fechaMaximaPermitida; ?>" 
                            required>
                        <small class="form-text text-muted">Solo se permiten alumnos mayores de 16 años.</small>
                    </div>
                    <div class="form-group row">
                        <label for="correo">Correo</label>
                        <input type="email" id="correo" name="correo" placeholder="matricula@upemor.edu.mx" required>
                    </div>
                    <div class="form-group row">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" placeholder = "Dirección" required>
                    </div>
                    <div class="form-group row">
                        <label for="telefono">Teléfono</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-phone"></i>
                            </div>
                        </div>
                        <input type="tel" id="telefono" name="telefono" placeholder="777-###-####" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>
                    </div>
                    <div class="form-group row">
                        <label for="ciudad">Ciudad</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-map"></i>
                            </div>
                        </div>
                        <input type="text" id="ciudad" name="ciudad" placeholder = "Ciudad" required>
                    </div>
                    <div class="form-group row">
                        <label for="estado">Estado</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-map-marker"></i>
                            </div>
                        </div>
                        <input type="text" id="estado" name="estado" placeholder = "Estado" required>
                    </div>
                    <div class="form-group row">
                        <label for="genero">Género</label>
                        <select type="select" id="genero" name="genero" required>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                            <option value="PrefieroNoDecirlo">Prefiero no decirlo</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="anioIngreso">Año de Ingreso</label>
                        <input type="number" id="FeIngreso" name="FeIngreso" min="2023" placeholder = "2006" required>
                        <script> 
                            // poner el año actual como valor por defecto y como máximo
                            const input = document.getElementById("FeIngreso");
                            const year = new Date().getFullYear();
                            input.max = year;
                        </script>
                    </div>
                    <div class="form-group row">
                        <label for="Carrea">Carrera </label>
                        <select type="select" id="carrera" name="carrera" required>
                            <option value="1">ITI-H18</option>
                            <option value="2">TSU-DS-NM24</option>
                            <option value="3">IET-H18</option>
                            <option value="4">TSU-RC-NM24</option>
                        </select>
                    </div>
                    <br>
                    </fieldset>
                    <!--!: Esta parte del formulario permitirá registrar el formulario de la ficha médica-->
                    <fieldset id = "fichaMedica">
                        <legend>Ficha médica</legend>
                        <div>
                        <label for="tipoSangre">Tipo de sangre</label>
                            <select type = "select" id = "tipoSangre" name  = "tipoSangre" required>
                                <option value = "A+">A+</option>
                                <option value = "O+">O+</option>
                                <option value = "B+">B+</option>
                                <option value = "AB+">AB+</option>
                                <option value = "A-">A-</option>
                                <option value = "O-">O-</option>
                                <option value = "B-">B-</option>
                                <option value = "AB-">AB-</option>
                            </select>
                        </div>
                        <div>
                        <label for="alergias">Alergias</label>
                            <input type="text" id="alergias" name="alergias" placeholder="Escribe 'Sin_Alergias' si aplica">
                        </div>
                        <div>
                        <label for="contactoEmergencia">Contacto de emergencia (teléfono)</label>
                            <input type="tel" id="contactoEmergencia" name="contactoEmergencia" placeholder="777-###-####" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}">
                        </div>
                    </fieldset>
                    <!--Este será el botón para enviar los datos-->
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <input type="submit" name="Enviar_Alumno" value = "Enviar_Alumno"  class="btn btn-primary">
                        </div>
                    </div>
                </form>
        </section>

        <hr>
        <section id = "ConsultaModificacionAlumnos" class = "ConsultaModificacionAlumnos">
            <form action = "/IdentiQR/app/Views/redireccionPaginas.php" method="POST" >
                <fieldset>
                    <legend><h3>Consulta y Modificación de Alumnos</h3></legend>
                    <p>Por favor, rellena este formulario para consultar o modificar un alumno.</p>
                    <hr>
                    <div class="form-group row">
                        <button id="btnEscanear" type="button" class="btn btn-primary">Escanear QR</button>
                    </div>
                </fieldset>
            </form>

            <form action="" id="formConsultaAlumnos" method="GET">
                <fieldset>
                    
                </fieldset>
            </form>

            <form action="/IdentiQR/app/Controllers/ControladorAlumnos.php?action=consultarAlumnos" id="formConsultaUsuario" method="POST" onsubmit="return consultarConCarga(event)">
                <fieldset>
                    <legend>Consulta todos los alumnos</legend>
                    <input type="hidden" name="consultarTodo" value="1">
                        <button type="submit" class="btn btn-primary" name = "consultarTodo">Consultar Todos los Alumnos</button>
                    <!-- Campo hidden para saber qué botón fue presionado -->
                    <input type="hidden" name="accion" id="accion" value="">
                </fieldset>
            </form>

            <div id="resultadoConsulta">
                <!-- Aquí se mostrarán los resultados de la consulta -->
                <table border="1">
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nombre</th>
                            <th>Apellido P.</th>
                            <th>Apellido M.</th>
                            <th>Cuatri</th>
                            <th>Fecha Nacimiento</th>
                            <th>Tipo Sangre</th>
                            <th>Correo</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <?php
                        if (!isset($result)) {
                            $result = null;
                        }
                    ?>
                    <tbody>
                        <?php
                            if($result && $result->num_rows > 0):
                        ?>
                        <?php
                            while($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $row['Matricula'] ?></td>
                                <td><?php echo $row['Nombre'] ?></td>
                                <td><?php echo $row['ApePat'] ?></td>
                                <td><?php echo $row['ApeMat'] ?></td>
                                <td><?php echo $row['Cuatrimestre'] ?></td>
                                <td><?php echo $row['FechaNac'] ?></td>
                                <td><?php echo $row['TipoSangre'] ?></td>
                                <td><?php echo $row['Correo'] ?></td>
                                <td>
                                    <a href="/IdentiQR/app/Controllers/ControladorAlumnos.php?action=obtenerAlumno&matricula=<?php echo $row['Matricula']?>">
                                        <button type="button">Editar</button>
                                    </a>
                                    <button type="button" 
                                            onclick="confirmacionEliminacionAlumnoTabla(event, '<?php echo $row['Matricula']?>')">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align:center; font-style: italic;">
                                No se encontraron alumnos o no se ha realizado una consulta.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <hr>
        <section id = "EliminacionAlumnos" class = "EliminacionAlumnos">
            <form action = "/IdentiQR/app/Controllers/ControladorAlumnos.php?action=eliminarAlumno" method="POST" id = "formBajaAlumno">
                <fieldset>
                    <legend><h3>Eliminación de Alumnos existentes</h3></legend>
                    <p>Por favor, rellena este formulario para eliminar un alumno.</p>
                    <hr>
                    <!-- !: Aquí se deberá escanear el QR-->
                    <label for="Matricula">Matrícula: </label>
                        <input type="text" id="idAlumno_BajaUSUARIO" name="idAlumno_BajaUSUARIO" placeholder="MATO250###" required>
                    <input type="hidden" name="accionEliminar" value="eliminarAlumno">
                        <button type="submit" name="BajaAlumno_EliminarUsuario" id="BajaAlumno_EliminarUsuario" 
                        onclick = "return confirmacionEliminacionAlumno(event)">Eliminar Alumno</button>
                </fieldset>
            </form>
        </section>
        </div>

        <hr>
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

        <!-- ALERTAS PHP -> JS -->
        <?php if (isset($resultadoExito) && $resultadoExito === true): ?>
            <script> 
                // Si viene de registro, usa la función especial
                <?php if(isset($_POST['Enviar_Alumno'])): ?>
                    registroAlumno(); 
                <?php else: ?>
                    // Si viene de consulta u otra cosa
                    mostrarAlerta('success', '<?php echo addslashes($mensaje); ?>');
                <?php endif; ?>
            </script>
        <?php elseif (isset($resultadoExito) && $resultadoExito === false): ?>
            <script> 
                mostrarAlerta('error', '<?php echo addslashes($mensaje); ?>'); 
            </script>
        <?php endif; ?>


        <!-- Modal usado para el escaneo de la camara-->
        <div id="modalEscanear" class="modal" style="display: none;">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Escanear QR</h5>
                        <button type="button" class="close" onclick="cerrarModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="scanner-container" style="display: flex; gap: 20px;">
                            <!-- Sección Izquierda: Cámara -->
                            <div class="camera-section" style="flex: 1;">
                                <h6 class="text-center">Cámara</h6>
                                <video id="video" style="width: 100%; border: 2px solid #be00f3ff; border-radius: 5px;"></video>
                                <div id="estado" class="mt-3 text-center"></div>
                            </div>
                            <!-- Sección Derecha: Datos Escaneados -->
                            <div class="data-section" style="flex: 1;">
                                <h6 class="text-center">Datos Escaneados</h6>
                                <div id="datosQR" style="background-color: #e9ecef; padding: 15px; border-radius: 5px; min-height: 300px; max-height: 500px; overflow-y: auto;">
                                    <p class="text-muted">Acerque el Código QR a escanear.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="cerrarModal()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
