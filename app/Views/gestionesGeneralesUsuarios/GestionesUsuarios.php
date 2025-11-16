
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->
        <script src="/IdentiQR/public/JavaScript/confirmacionBotones.js"></script> <!--SCRIPT-->
        <title>Gestión de usuarios-IdentiQR</title>
        <!--
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f4f4f4;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            .Login_ContenedorGeneral {
                background: #fff;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                width: 320px;
            }
            .Login_ContenedorGeneral h2 {
                text-align: center;
                margin-bottom: 1.5rem;
            }
            .Login_ContenedorGeneral label {
                display: block;
                margin-bottom: 0.5rem;
            }
            .Login_ContenedorGeneral input[type="text"],
            .Login_ContenedorGeneral input[type="password"] {
                width: 100%;
                padding: 0.5rem;
                margin-bottom: 1rem;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            .Login_ContenedorGeneral button {
                width: 100%;
                padding: 0.7rem;
                background: #007bff;
                color: #fff;
                border: none;
                border-radius: 4px;
                font-size: 1rem;
                cursor: pointer;
            }
            .Login_ContenedorGeneral button:hover {
                background: #0056b3;
            }
        </style>
        -->
        <!--TODO: Aquí sera la libreria para mostrar las alertas-->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesUsuarios.css"> <!--CSS-->
        <!-- <link rel="stylesheet" href="/IdentiQR/public/CSS/usuariosStyles.css"> -->

    </head>
    <body>
        <!-- !Aquí se encontrara el encabezado, este podrá cambiar: nota-->
        <header id="HeaderIndex1">
            <div class="container__header">
                <div class="logo">
                    <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-SinFonde.png" alt="IdentiQR Logo" height = "150px" weight = "150px">
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
        <!--*A partir de acá se inicializara la parte de la página general, sera nuestro tema central e identificación de lo que contendrá-->
        
        <div id="HeaderLogin">
            <h2><center>IdentiQR</center></h2>
            
        </div>
        <hr>
        <!-- TODO: Aquí empezaremos con la información que tiene que ver con los datos o mayoritariamente del index principal (Recursos, etc.)-->

        <div class="contenedor-secciones">
            <div id="seccionRegistrarUsuario" class = "Registro_ContenedorGeneral">
            <h2>Registro</h2>
            <form action="/IdentiQR/app/Controllers/ControladorUsuario.php?action=registroUsuario" method="POST">
                <fieldset>
                    <legend>Registra nuevo usuario</legend>
                    <label for="usuario">Nombre(s)</label>
                        <input type="text" id="nombre" name="nombre" placeholder = "Nombre o nombres" required>
                    <br><br>
                    <label for="apellido paterno">Apellido paterno: </label>
                        <input type="text" id="apellido_paterno" name="apellido_paterno" placeholder = "Ingresa tu apellido paterno" required>
                    <br><br>
                    <label for="apellido materno">Apellido materno: </label>
                        <input type="text" id="apellido_materno" name="apellido_materno" placeholder = "Ingresa tu apellido materno" required>
                    <!--*: Aquí se debe considerar que para registrar debe ser dominio UPEMOR-->
                    <br><br>

                    <label for="genero">Género: </label>
                        <select id="genero" name="genero" required>
                            <option disabled selected>Selecciona una opción</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    <br><br>
                    <label for = "Email">Correo electrónico</label>
                        <input type="email" id="email" name="email" placeholder = "example@upemor.edu.mx" required>
                    <br><br>
                    <label for="password">Contraseña</label>
                        <input type="password" id="passw" name="passw" placeholder = "Ingresa tu contraseña" required>
                    <br><br>
                    <label for = "Rol o cargo">Rol</label>
                    <select id="rol" name="rol" required>
                        <option disabled selected>Selecciona una opción</option>
                        <option value="Administrador">Administrador</option>
                        <option value="Administrativo_Vinculacion">Vinculación</option>
                        <option value="Administrativo_ServicioEsco">Servicios Escolares</option>
                        <option value="Administrativo_DesaAca">Desarrollo Académico</option>
                        <option value="Administrativo_DAE">Asuntos Estudiantiles</option>
                        <option value="Administrativo_Direccion">Dirección Académica</option>
                        <option value="Administrativo_Medico">Consultorio Médico</option>
                    </select>
                    <br><br>
                    <!--TODO: Considerar que si selecciono alguna opción que tenga que ver con su DEPARTAMENTO, este se le asignara por predeterminado la opción
                    del departamento a escoger || NOTA. 2025-09-25-->
                    
                    <label for = "Departamento">Departamento</label>
                    <select id="depto" name="idDepto" required disabled>
                        <option value="" disabled selected>Selecciona una opción</option>
                        <option value="1">Ninguna especifica</option>
                        <option value="2">Direccion Académica</option>
                        <option value="3">Servicios Escolares</option>
                        <option value="4">Dirección Desarrollo Académico(DDA)</option>
                        <option value="5">Dirección Asuntos Estudiantiles(DAE)</option>
                        <option value="6">Consultorio de atención de primer contacto</option>
                        <option value="7">Vinculación</option>
                    </select>
                    <input type="hidden" id="idDepto" name="idDepto" value="">
                    
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const selectRol = document.getElementById("rol");
                            const selectDepto = document.getElementById("depto");
                            // Desactiva el campo de departamento desde el inicio
                            //selectDepto.readonly = true;
                            selectRol.addEventListener("change", function() {
                                let rolSeleccionado = this.value;
                                let deptoValue = "";
                                switch (rolSeleccionado) {
                                    case "Administrador":
                                        deptoValue = "1"; // Ninguna específica
                                        break;
                                    case "Administrativo_Vinculacion":
                                        deptoValue = "7"; // Vinculación
                                        break;
                                    case "Administrativo_ServicioEsco":
                                        deptoValue = "3"; // Servicios Escolares
                                        break;
                                    case "Administrativo_DesaAca":
                                        deptoValue = "4"; // Dirección Desarrollo Académico (DDA)
                                        break;
                                    case "Administrativo_DAE":
                                        deptoValue = "5"; // Dirección Asuntos Estudiantiles (DAE)
                                        break;
                                    case "Administrativo_Direccion":
                                        deptoValue = "2"; // Dirección Académica
                                        break;
                                    case "Administrativo_Medico":
                                        deptoValue = "6"; // Consultorio médico
                                        break;
                                    default:
                                        deptoValue = "";
                                        break;
                                }
                                selectDepto.value = deptoValue; // para mostrar en select
                                document.getElementById("idDepto").value = deptoValue; // actualizar el input hidden
                            });
                        });
                    </script>
                    <br><br>
                    <button type="submit" name = "enviarRegistro_Usuario" onclick = "mostrarRegistro()">Registrar usuario</button>
                </fieldset>
            </form>
        </div>
        <hr>
        <div id="seccionConsultarUsuario" class = "Registro_ContenedorGeneral">
            <form action="/IdentiQR/app/Controllers/ControladorUsuario.php?action=consultarUsuario" id="formConsultaUsuario" method="POST">
                <fieldset>
                    <legend>Consulta un usuario existente o todos</legend>
                    <label for="idUsuario">Correo/Usuario del Usuario:</label>
                        <input type="text" id="idUsuario_ConsultarUSUARIO" name="idUsuario_ConsultarUSUARIO" placeholder="Usuario/Correo@dominio.org.mx" required>
                    <!-- Campo hidden para saber qué botón fue presionado -->
                    <input type="hidden" name="accion" id="accion" value="">

                    <button type="submit" name="BusquedaUSUARIO_ConsultarUsuario" onclick="seleccionarAccion(event, 'buscar')">Buscar Usuario</button>
                    <button type="button" id="BusquedaUSUARIO_ConsultarTODO" onclick="consultarTodo(event)">Consultar todo</button>
                </fieldset>
            </form>

            <div id="resultadoConsulta">
                <!-- Aquí se mostrarán los resultados de la consulta -->
                <table border = "1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Genero</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Departamento</th>
                            <th>Fecha de registro</th>
                            <th>Usuario</th>
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
                            while($row = $result ->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $row['id_usuario'] ?></td>            
                                <td><?php echo $row['nombre'] ?></td>            
                                <td><?php echo $row['apellido_paterno'] ?></td>            
                                <td><?php echo $row['apellido_materno'] ?></td>
                                <td><?php echo $row['genero'] ?></td>                 
                                <td><?php echo $row['email'] ?></td>                 
                                <td><?php echo $row['rol'] ?></td>                 
                                <td><?php echo $row['idDepto'] ?></td>                 
                                <td><?php echo $row['FechaRegistro'] ?></td>  
                                <td><?php echo $row['usr']?></td>               
                                <td>
                                    <a href="/IdentiQR/app/Controllers/ControladorUsuario.php?action=updateUsuarioID&id=<?php echo $row['id_usuario']?>">
                                        <button>Editar</button>
                                    </a>

                                    <button type="button" 
                                            onclick="confirmacionEliminacionUsuarioTabla(event, '<?php echo $row['usr']?>')">
                                        Eliminar Usuario
                                    </button>
                                </td>
                            </tr>
                        <?php 
                            }
                        ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="11" style="text-align:center; font-style: italic;">
                                No se encontraron usuarios con esos criterios o al consultar.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <div id="seccionModificarUsuario" class = "Registro_ContenedorGeneral">
            <h2>Modificar Usuario</h2>
            <form action="/IdentiQR/app/Controllers/ControladorUsuario.php?action=buscarUsuario" method="POST">
                <fieldset>
                    <legend>Modifica un usuario existente</legend>
                    <label for="idUsuario">Correo/Usuario del Usuario:</label>
                    <input type="text" id="idUsuario_Buscar" name="idUsuario_Buscar" placeholder="Usuario/Correo@dominio.org.mx" required>
                    <button type="submit" name="buscarUsuarioBtn">Buscar y Modificar Usuario</button>
                </fieldset>
            </form>
                    
        </div>

        <hr>
        <div id="seccionEliminarUsuario" class = "Registro_ContenedorGeneral">
            <h2>Eliminar Usuario</h2>
            <form action="/IdentiQR/app/Controllers/ControladorUsuario.php?action=eliminarUsuario" method="POST" id = "formBajaUsuario">
                <fieldset>
                    <legend>Elimina un usuario existente</legend>
                    <label for="idUsuario">Usuario:</label>
                        <input type="text" id="idUsuario_BajaUSUARIO" name="idUsuario_BajaUSUARIO" placeholder="Usuario" required>
                    <input type="hidden" name="accionEliminar" value="eliminarUsuario">
                        <button type="submit" name="BajaUsuario_EliminarUsuario" id="BajaUsuario_EliminarUsuario" 
                        onclick = "return confirmacionEliminacionUsr(event)">Eliminar Usuario</button>
                </fieldset> 
            </form>
        </div>
        </div>

        <hr>
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
        <!--AQUÍ ESTARÁ TODO LA UNIFICACIÓN PARA LOS BOTONES DE (CONFIRMACIÓN).-->
        <script src = "/IdentiQR/public/JavaScript/confirmacionBotones.js?v=2.0"></script>
        
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
    </body>
</html>