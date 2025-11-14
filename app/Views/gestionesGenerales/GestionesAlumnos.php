<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->

        <!--*: Da formato frente para el registro - MODIFICAR-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesAlumnos.css"> <!--CSS-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <script src="/IdentiQR/public/JavaScript/gestionesAlumnos.js"></script> <!-- JS -->
        <!--*: Da formato frente para el registro - MODIFICAR-->
        <title>IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrara el emcabezado, este podrá cambiar: nota-->
        <header id="HeaderIndex1">
            <div class="container__header">
                <div class="logo">
                    <a href="/IdentiQR/index.html">
                        <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-SinFonde.png" alt="Logo de IdentiQR" class="ImagenIndex1" id = "logoIndex" width="300" height="200">
                    </a>
                </div>
                <!-- <button class = "abrirMenu">abrir</button>-->
                <div class="container__nav">
                    <nav id="nav" class = "nav">
                        <ul class = "nav-list">
                            <li><a href="/IdentiQR/index.html" class="select">Inicio</a></li>
                            <li><a href="/IdentiQR/index.html#contacto">Contacto</a></li>
                            <li><a href="/IdentiQR/app/Controllers/ControladorUsuario.php?action=logoutUsuario">Cerrar Sesión</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <!--*Apartir de acá se inicializara la parte de la página general, sera nuestro tema central e identificación de lo que contendra-->

        <div id="Index1">
            <h1><center>IdentiQR</center></h1>
        </div>

        <hr>
        <p class = "Textos_GeneralIndex1">
            Genera tu código QR único para la identificación y mejora de la recopilación de tus datos, al realizar los trámites.
            <br>
            <i>Escanea cualquier código QR</i> para acceder a facilmente a los datos y mucho más con un solo toque.
        </p>
        <hr> <!-- TODO: Aquí empezaremos con la parte de ingreso o registro de los Alumnos-->
        <!-- *Formulario para el registro de los alumnos-->
        <!-- !Este formulario debe estar conectado a la base de datos-->

        <div class="contenedor-secciones">
            <section id="seccionRegistrarAlumno" class = "seccionRegistrarAlumno">
                <!-- <form action = "../Controllers/ControladorAlumnos.php" method="POST" > -->
                <form action = "/IdentiQR/app/Controllers/ControladorAlumnos.php?action=registroAlumno" method = "POST">
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
                            <input type="text" id="matricula" name="matricula" required>
                        </div>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="Nombre(s)">Nombre(s)</label>
                        <input type="text" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group row">
                        <label for="ApePat">Apellido paterno</label>
                        <input type="text" id="ApPat" name="ApPat" required>
                    </div>

                    <div class="form-group row">
                        <label for="ApeMat">Apellido materno</label>
                        <input type="text" id="ApMat" name="ApMat" required>
                    </div>
                    <div class="form-group row">
                        <label for="FechaNac">Fecha de nacimiento</label>
                        <input type="date" id="FeNac" name="FeNac" min = "1950-12-31"  max = "2100-12-31" required>
                    </div>
                    <!--TODO: Esto debe dejar o intentar que mediante un script se pueda Ingresar o registrar un correo cuando se ingresa la matricula-->
                    <div class="form-group row">
                        <label for="correo">Correo</label>
                        <input type="email" id="correo" name="correo" placeholder="matricula@upemor.edu.mx" required>
                    </div>
                    <div class="form-group row">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" required>
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
                        <input type="text" id="ciudad" name="ciudad" required>
                    </div>
                    <div class="form-group row">
                        <label for="estado">Estado</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-map-marker"></i>
                            </div>
                        </div>
                        <input type="text" id="estado" name="estado" required>
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
                    <!--TODO: Considerar que sea (Año de ingreso) para ver el tiempo hasta la fecha, que sea INT no DATE-->
                    <!--
                        <div class="form-group row">
                            <label for="FeIngreso">Fecha de Ingreso(Año):</label>
                            <input type="date" id="FeIngreso" name="FeIngreso" min = "2004-07-07" max = "2100-12-31" required>
                        </div>
                    -->
                    <div class="form-group row">
                        <label for="anioIngreso">Año de Ingreso</label>
                        <input type="number" id="FeIngreso" name="FeIngreso" min="2004" placeholder = "2006" required>
                        <script> //Nota.- 2025-05-10 SCRIPT Para la fecha
                            // poner el año actual como valor por defecto y como máximo
                            const input = document.getElementById("FeIngreso");
                            const year = new Date().getFullYear();
                            input.max = year;
                        </script>
                        <!-- <small>Se guardará como: AAAA-09-01</small> -->
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
                    <!--!: Esta parte del formulario permitira registrar el formulario de la ficha medica-->
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
                    <!--Este será el boton para enviar los datos-->
                    <div class="form-group row">
                        <div class="col-12 text-center">
                            <input type="submit" name="Enviar_Alumno" value = "Enviar_Alumno"  class="btn btn-primary" onclick = "registroAlumno()">
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
                    <!-- !: Aquí se deberá escanear el QR-->
                    <!--*: Implementar modulo para escanear el QR aquí y verificar-->
                </fieldset>
            </form>

            <form action="" id="formConsultaAlumnos" method="GET">
                <fieldset>
                    
                </fieldset>
            </form>

            <form action="/IdentiQR/app/Controllers/ControladorAlumnos.php?action=consultarAlumnos" id="formConsultaUsuario" method="POST">
                <fieldset>
                    <legend>Consulta todos los alumnos</legend>
                    <label for="idUsuario">Matrícula a buscar:</label>
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
                    <!--*: Implementar modulo para escanear el QR aquí y verificar-->
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

        <?php if (isset($resultadoExito) && $resultadoExito === true): ?>
            <script>
                // Llamar a tu función JS que muestra la alerta de éxito
                mostrarAlerta('success', '<?php echo addslashes($mensaje); ?>');
            </script>
        <?php elseif (isset($resultadoExito) && $resultadoExito === false): ?>
            <script>
                // Llamar a tu función JS que muestra la alerta de error
                mostrarAlerta('error', '<?php echo addslashes($mensaje); ?>');
            </script>
        <?php endif; ?>

        <!-- Modal -->
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
        <!--
        <script>
            let scanner = null;
            let video = document.getElementById('video');
            let estado = document.getElementById('estado');
            let datosQR = document.getElementById('datosQR');

            document.getElementById('btnEscanear').addEventListener('click', function(e) {
                e.preventDefault(); // Prevenir el envío del formulario
                abrirModal();
            });

            function abrirModal() {
                document.getElementById('modalEscanear').style.display = 'block';
                iniciarScanner();
            }

            function cerrarModal() {
                document.getElementById('modalEscanear').style.display = 'none';
                detenerScanner();
                datosQR.innerHTML = '<p class="text-muted">Acerque el Código QR a escanear.</p>';
                estado.innerHTML = '';
            }

            function iniciarScanner() {
                scanner = new Instascan.Scanner({ video: video });

                scanner.addListener('scan', function (content) {
                    estado.innerHTML = '<span class="text-success">QR detectado! Procesando...</span>';
                    datosQR.innerHTML = '<h6>Datos del QR:</h6><pre>' + content + '</pre>';
                    detenerScanner(); // Detener la cámara después de detectar el QR

                    // Enviar el contenido escaneado al controlador para procesar
                    fetch('/IdentiQR/app/Controllers/ControladorAlumnos.php?action=procesarQR', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'qrContent=' + encodeURIComponent(content)
                    })
                    .then(response => response.text())
                    .then(data => {
                        // Mostrar resultado y botones para confirmar redirección
                        if (data.includes('redirect:')) {
                            const url = data.split('redirect:')[1];
                            datosQR.innerHTML += '<br><p>¿Desea proceder con la redirección?</p>';
                            datosQR.innerHTML += '<button id="aceptarBtn" class="btn btn-success">Aceptar</button> ';
                            datosQR.innerHTML += '<button id="cancelarBtn" class="btn btn-danger">Cancelar</button> ';
                            datosQR.innerHTML += '<button id="usarMatriculaBtn" class="btn btn-info">Solamente usar Matricula</button>';

                            // Event listeners para los botones
                            document.getElementById('aceptarBtn').addEventListener('click', function() {
                                window.location.href = url;
                            });
                            document.getElementById('cancelarBtn').addEventListener('click', function() {
                                cerrarModal();
                            });
                            document.getElementById('usarMatriculaBtn').addEventListener('click', function() {
                                // Extraer la matrícula del contenido del QR
                                const lines = content.split('\n');
                                const matriculaLine = lines.find(line => line.startsWith('Matricula:'));
                                if (matriculaLine) {
                                    const matricula = matriculaLine.split(':')[1].trim();
                                    document.getElementById('idAlumno_BajaUSUARIO').value = matricula;
                                }
                                cerrarModal();
                            });
                        } else {
                            datosQR.innerHTML += '<br><span class="text-danger">' + data + '</span>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        datosQR.innerHTML += '<br><span class="text-danger">Error al procesar el QR.</span>';
                    });
                });

                Instascan.Camera.getCameras().then(function (cameras) {
                    if (cameras.length > 0) {
                        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                        let selectedCamera = cameras[0];
                        if (isMobile) {
                            // Preferir cámara trasera en dispositivos móviles
                            for (let cam of cameras) {
                                if (cam.name.toLowerCase().includes('back') || cam.name.toLowerCase().includes('rear') || cam.name.toLowerCase().includes('trasera')) {
                                    selectedCamera = cam;
                                    break;
                                }
                            }
                            // Si no se encontró por nombre, asumir que la última es la trasera
                            if (selectedCamera === cameras[0] && cameras.length > 1) {
                                selectedCamera = cameras[cameras.length - 1];
                            }
                        }
                        scanner.start(selectedCamera);
                        estado.innerHTML = 'Cámara activada. Escaneando...';
                    } else {
                        estado.innerHTML = '<span class="text-danger">No se encontraron cámaras.</span>';
                    }
                }).catch(function (e) {
                    estado.innerHTML = '<span class="text-danger">Error al acceder a la cámara: ' + e.message + '</span>';
                });
            }

            function detenerScanner() {
                if (scanner) {
                    scanner.stop();
                    scanner = null;
                }
            }

            // Cerrar modal al hacer clic fuera
            window.onclick = function(event) {
                if (event.target == document.getElementById('modalEscanear')) {
                    cerrarModal();
                }
            }
        </script>
    -->
    </body>
</html>
