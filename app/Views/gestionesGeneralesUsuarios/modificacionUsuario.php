<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--*: Bootstrap-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesUsuarios.css">
        <title>Actualización de datos usuarios... IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrará el encabezado, este podrá cambiar: nota-->
        <header id="HeaderIndex1">
            <div class="container__header">
                <div class="logo">
                    <img src="/IdentiQR/public/Media/img/IdentiQR-Eslogan-SinFonde.png" alt="IdentiQR Logo" height = "150px" weight = "150px">
                </div>
                <div class="container__nav">
                    <nav id="nav">
                        <ul>
                            <li><a href="/IdentiQR/index.html" class="select">INICIO</a></li>
                            <li><a href="/IdentiQR/index.html#Temas">TEMAS</a></li>
                            <li><a href="/IdentiQR/index.html#Contacto">CONTACTOS</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
        <div class="contenedor-secciones">
            <div class="Registro_ContenedorGeneral">
            <form action="/IdentiQR/app/Controllers/ControladorUsuario.php?action=updateUsuarioID" method="POST">
                <input type="hidden" name="id_usuario" value = <?php echo $row['id_usuario']; ?>>
                <fieldset>
                <legend>Actualizar usuario <?php echo $row['nombre'];?></legend>
                    <div class="form-group row">
                        <label for="usuario" class="col-4 col-form-label">Nombre(s)</label> 
                        <div class="col-8">
                            <input type="text" id="nombre" name="nombre" placeholder = "Nombre o nombres" value = "<?php echo $row['nombre'];?>" required>
                        </div>
                    </div>
                    <br><br>
                    
                    <div class="form-group row">
                        <label for="apellido_paterno" class="col-4 col-form-label">Apellido paterno:</label> 
                        <div class="col-8">
                            <input type="text" id="apellido_paterno" name="apellido_paterno" placeholder = "Ingresa tu apellido paterno" value = "<?php echo $row['apellido_paterno'];?>" required>
                        </div>
                    </div>
                    <br><br>

                    <div class="form-group row">
                        <label for="apellido_materno" class="col-4 col-form-label">Apellido materno:</label>
                        <div class="col-8">
                            <input type="text" id="apellido_materno" name="apellido_materno" placeholder = "Ingresa tu apellido materno" value = "<?php echo $row['apellido_materno'];?>" required>
                        </div>
                    </div>
                    <br><br>

                    <div class="form-group row">
                        <label for="genero" class="col-4 col-form-label">Género:</label>
                        <div class="col-8">
                            <select id="genero" name="genero" required>
                                <option value="" disabled <?php echo empty($row['genero']) ? 'selected' : ''; ?>>Selecciona una opción</option>
                                <option value="Masculino" <?php echo ($row['genero'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                <option value="Femenino" <?php echo ($row['genero'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                <option value="Otro" <?php echo ($row['genero'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
                            </select>
                        </div>
                    </div>
                    <br><br>
                    <!--*: Aquí se debe considerar que para registrar debe ser dominio UPEMOR-->
                    <div class="form-group row">
                        <label for="Email" class="col-4 col-form-label">Correo electrónico</label>
                        <div class="col-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa fa-address-book-o"></i>
                                    </div>
                                </div> 
                                <input type="email" id="email" name="email" placeholder = "example@upemor.edu.mx" value = "<?php echo $row['email'];?>" readonly>
                            </div>
                        </div>
                    </div>
                    <br><br>

                                        <div class="form-group row">
                        <label for="password" class="col-4 col-form-label">Contraseña:</label> 
                        <div class="col-8">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text"><i class="fa fa-lock"></i></div>
                                </div> 
                                <input type="password" id="passw" name="passw" placeholder="Ingresa nueva contraseña (o deja la actual)" value="<?php echo $row['passw'];?>"> <!--CONSIDERAR QUE SI NO SE QUIERE MODIFICAR, NO DEBERA SER readonly-->
                            </div>
                        </div>
                    </div>
                    <br><br>

                    <div class="form-group row">
                        <label for="rol" class="col-4 col-form-label">Rol o cargo:</label> 
                        <div class="col-8">
                            <select id="rol" name="rol" required>
                                <option value="" disabled <?php echo empty($row['rol']) ? 'selected' : ''; ?>>Selecciona una opción</option>                           
                                <option value="Administrador" <?php echo ($row['rol'] == 'Administrador') ? 'selected' : ''; ?>>Administrador</option>
                                <option value="Administrativo_Vinculacion" <?php echo ($row['rol'] == 'Administrativo_Vinculacion') ? 'selected' : ''; ?>>Vinculación</option>
                                <option value="Administrativo_ServicioEsco" <?php echo ($row['rol'] == 'Administrativo_ServicioEsco') ? 'selected' : ''; ?>>Servicios Escolares</option>
                                <option value="Administrativo_DesaAca" <?php echo ($row['rol'] == 'Administrativo_DesaAca') ? 'selected' : ''; ?>>Desarrollo Académico</option>
                                <option value="Administrativo_DAE" <?php echo ($row['rol'] == 'Administrativo_DAE') ? 'selected' : ''; ?>>Asuntos Estudiantiles</option>
                                <option value="Administrativo_Direccion" <?php echo ($row['rol'] == 'Administrativo_Direccion') ? 'selected' : ''; ?>>Dirección Académica</option>
                                <option value="Administrativo_Medico" <?php echo ($row['rol'] == 'Administrativo_Medico') ? 'selected' : ''; ?>>Consultorio Médico</option>
                            </select>
                        </div>
                    </div>
                    <br><br>
                    <!--TODO: Considerar que si selecciono alguna opción que tenga que ver con su DEPARTAMENTO, este se le asignará por predeterminado la opción
                    del departamento a escoger || NOTA. 2025-09-25-->
                    <div class="form-group row">
                        <label for="Departamento" class="col-4 col-form-label">Departamento:</label> 
                        <div class="col-8">
                            <select id="depto" name="idDepto_visible" required disabled>
                                <option value="" disabled <?php echo empty($row['idDepto']) ? 'selected' : ''; ?>>Selecciona una opción</option>
                                <option value="1" <?php echo ($row['idDepto'] == 1) ? 'selected' : ''; ?>>Ninguna específica</option>
                                <option value="2" <?php echo ($row['idDepto'] == 2) ? 'selected' : ''; ?>>Dirección Académica</option>
                                <option value="3" <?php echo ($row['idDepto'] == 3) ? 'selected' : ''; ?>>Servicios Escolares</option>
                                <option value="4" <?php echo ($row['idDepto'] == 4) ? 'selected' : ''; ?>>Dirección Desarrollo Académico (DDA)</option>
                                <option value="5" <?php echo ($row['idDepto'] == 5) ? 'selected' : ''; ?>>Dirección Asuntos Estudiantiles (DAE)</option>
                                <option value="6" <?php echo ($row['idDepto'] == 6) ? 'selected' : ''; ?>>Consultorio de atención de primer contacto</option>
                                <option value="7" <?php echo ($row['idDepto'] == 7) ? 'selected' : ''; ?>>Vinculación</option>
                            </select>
                        </div>
                    </div> 
                <input type="hidden" id="idDepto" name="idDepto" value="<?php echo $row['idDepto']; ?>">
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                    const selectRol = document.getElementById("rol");
                    const selectDepto = document.getElementById("depto");
                    // Inicializar el departamento basado en el rol actual
                    const currentRol = selectRol.value;
                    let initialDeptoValue = "";
                    switch (currentRol) {
                        case "Administrador":
                            initialDeptoValue = "1";
                            break;
                        case "Administrativo_Vinculacion":
                            initialDeptoValue = "7";
                            break;
                        case "Administrativo_ServicioEsco":
                            initialDeptoValue = "3";
                            break;
                        case "Administrativo_DesaAca":
                            initialDeptoValue = "4";
                            break;
                        case "Administrativo_DAE":
                            initialDeptoValue = "5";
                            break;
                        case "Administrativo_Direccion":
                            initialDeptoValue = "2";
                            break;
                        case "Administrativo_Medico":
                            initialDeptoValue = "6";
                            break;
                        default:
                            initialDeptoValue = "<?php echo $row['idDepto']; ?>";
                            break;
                    }
                    selectDepto.value = initialDeptoValue;
                    document.getElementById("idDepto").value = initialDeptoValue;
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
                <div class="form-group row">
                    <div class="offset-4 col-8">
                        <button name="actualizarDatosUSER" type="submit" class="btn">Actualizar datos</button>
                    </div>
                </div>
                </fieldset>
            </form>
            </div>
        </div>

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
                    <a href="#Terms_Index1">Términos del servicio</a>
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