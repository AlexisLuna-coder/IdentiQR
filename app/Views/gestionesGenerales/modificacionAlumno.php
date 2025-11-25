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
        <script src="/IdentiQR/public/JavaScript/gestionesAlumnos.js" defer></script> <!-- JS -->
        <script src="https://kit.fontawesome.com/b41a278b92.js" crossorigin="anonymous"></script> <!--ICONOS-->
        <!--*: Da formato frente para el registro - MODIFICAR-->

        <title>Modificación de Alumno - IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrará el emcabezado, este podrá cambiar: nota-->
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
        <!--*Apartir de acá se inicializará la parte de la página general, será nuestro tema central e identificación de lo que contendrá-->

        <div id="Index1">
            <h1><center>Modificación de Alumno</center></h1>
        </div>

        <hr>
        <p class = "Textos_GeneralIndex1">
            Modifica los datos del alumno seleccionado.
            <br>
            <i>Escanea el código QR del alumno para cargar sus datos automáticamente.</i>
        </p>
        <hr>

        <section id="seccionModificarAlumno" class = "seccionModificarAlumno">
            <form action = "/IdentiQR/app/Controllers/ControladorAlumnos.php?action=updateAlumno" method = "POST">
                <input type="hidden" name="matricula_original" value="<?php echo $row['Matricula']; ?>">
                <fieldset>
                    <legend><h3>Modificación de Alumno: <?php echo $row['Nombre'] . ' ' . $row['ApePat'] . ' ' . $row['ApeMat']; ?></h3></legend>
                    <p>Por favor, modifica los datos del alumno.</p>
                    <hr>
                    <div class="form-group row">
                        <label for="Matricula">Matrícula</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-address-card"></i>
                            </div>
                        </div>
                        <input type="text" id="matricula" name="matricula" value="<?php echo $row['Matricula']; ?>" required readonly>
                    </div>
                    <br>
                    <div class="form-group row">
                        <label for="Nombre(s)">Nombre(s)</label>
                        <input type="text" id="nombre" name="nombre" value="<?php echo $row['Nombre']; ?>" required>
                    </div>
                    <div class="form-group row">
                        <label for="ApePat">Apellido paterno</label>
                        <input type="text" id="ApPat" name="ApPat" value="<?php echo $row['ApePat']; ?>" required>
                    </div>

                    <div class="form-group row">
                        <label for="ApeMat">Apellido materno</label>
                        <input type="text" id="ApMat" name="ApMat" value="<?php echo $row['ApeMat']; ?>" required>
                    </div>
                    <div class="form-group row">
                        <label for="FechaNac">Fecha de nacimiento</label>
                        <input type="date" id="FeNac" name="FeNac" min = "1950-12-31"  max = "2100-12-31" value="<?php echo $row['FechaNac']; ?>" required readonly>
                    </div>
                    <!--TODO: Esto debe dejar o intentar que mediante un script se pueda Ingresar o registrar un correo cuando se ingresa la matrícula-->
                    <div class="form-group row">
                        <label for="correo">Correo</label>
                        <input type="email" id="correo" name="correo" placeholder="matricula@upemor.edu.mx" value="<?php echo $row['Correo']; ?>" required readonly>
                    </div>
                    <div class="form-group row">
                        <label for="direccion">Dirección</label>
                        <input type="text" id="direccion" name="direccion" value="<?php echo $row['Direccion']; ?>" required>
                    </div>
                    <div class="form-group row">
                        <label for="telefono">Teléfono</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-phone"></i>
                            </div>
                        </div>
                        <input type="tel" id="telefono" name="telefono" placeholder="777-###-####" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php echo $row['Telefono']; ?>" required>
                    </div>
                    <div class="form-group row">
                        <label for="ciudad">Ciudad</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-map"></i>
                            </div>
                        </div>
                        <input type="text" id="ciudad" name="ciudad" value="<?php echo $row['Ciudad']; ?>" required>
                    </div>
                    <div class="form-group row">
                        <label for="estado">Estado</label>
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-map-marker"></i>
                            </div>
                        </div>
                        <input type="text" id="estado" name="estado" value="<?php echo $row['Estado']; ?>" required>
                    </div>
                    <div class="form-group row">
                        <label for="genero">Género</label>
                        <select type="select" id="genero" name="genero" value="<?php echo $row['Genero']; ?>" required>
                            <option value="Masculino" <?php if($row['Genero'] == 'Masculino') echo 'selected'; ?>>Masculino</option>
                            <option value="Femenino" <?php if($row['Genero'] == 'Femenino') echo 'selected'; ?>>Femenino</option>
                            <option value="Otro" <?php if($row['Genero'] == 'Otro') echo 'selected'; ?>>Otro</option>
                            <option value="PrefieroNoDecirlo" <?php if($row['Genero'] == 'PrefieroNoDecirlo') echo 'selected'; ?>>Prefiero no decirlo</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="anioIngreso">Año de Ingreso</label>
                        <input type="number" id="FeIngreso" name="FeIngreso" min="2004" placeholder = "2006" value="<?php echo date('Y', strtotime($row['FeIngreso'])); ?>" required readonly>
                        <script> //Nota.- 2025-05-10 SCRIPT Para la fecha
                            // poner el año actual como valor por defecto y como máximo
                            const input = document.getElementById("FeIngreso");
                            const year = new Date().getFullYear();
                            input.max = year;
                        </script>
                    </div>
                    <div class="form-group row">
                        <label for="Carrea">Carrera </label>
                        <select type="select" id="carrera" name="carrera" value="<?php echo $row['idCarrera']; ?>" required readonly>
                            <option value="1" <?php if($row['idCarrera'] == 1) echo 'selected'; ?>>ITI-H18</option>
                            <option value="2" <?php if($row['idCarrera'] == 2) echo 'selected'; ?>>TSU-DS-NM24</option>
                            <option value="3" <?php if($row['idCarrera'] == 3) echo 'selected'; ?>>IET-H18</option>
                            <option value="4" <?php if($row['idCarrera'] == 4) echo 'selected'; ?>>TSU-RC-NM24</option>
                        </select>
                    </div>
                    <br>
                </fieldset>
                <!--!: Esta parte del formulario permitirá registrar el formulario de la ficha médica-->
                <fieldset id = "fichaMedica">
                    <legend>Ficha médica</legend>
                    <div>
                    <label for="tipoSangre">Tipo de sangre</label>
                        <select type = "select" id = "tipoSangre" name  = "tipoSangre" value="<?php echo $row['TipoSangre']; ?>" required>
                            <option value = "A+" <?php if($row['TipoSangre'] == 'A+') echo 'selected'; ?>>A+</option>
                            <option value = "O+" <?php if($row['TipoSangre'] == 'O+') echo 'selected'; ?>>O+</option>
                            <option value = "B+" <?php if($row['TipoSangre'] == 'B+') echo 'selected'; ?>>B+</option>
                            <option value = "AB+" <?php if($row['TipoSangre'] == 'AB+') echo 'selected'; ?>>AB+</option>
                            <option value = "A-" <?php if($row['TipoSangre'] == 'A-') echo 'selected'; ?>>A-</option>
                            <option value = "O-" <?php if($row['TipoSangre'] == 'O-') echo 'selected'; ?>>O-</option>
                            <option value = "B-" <?php if($row['TipoSangre'] == 'B-') echo 'selected'; ?>>B-</option>
                            <option value = "AB-" <?php if($row['TipoSangre'] == 'AB-') echo 'selected'; ?>>AB-</option>
                        </select>
                    </div>
                    <div>
                    <label for="alergias">Alergias</label>
                        <input type="text" id="alergias" name="alergias" placeholder="Escribe 'Sin_Alergias' si aplica" value="<?php echo $row['Alergias']; ?>">
                    </div>
                    <div>
                    <label for="contactoEmergencia">Contacto de emergencia (teléfono)</label>
                        <input type="tel" id="contactoEmergencia" name="contactoEmergencia" placeholder="777-###-####" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" value="<?php echo $row['contacto_emergencia']; ?>">
                    </div>
                </fieldset>
                <!--Este será el botón para enviar los datos-->
                <div class="form-group row">
                    <div class="offset-4 col-8">
                        <input type="submit" name="Actualizar_Alumno" value = "Actualizar_Alumno"  class="btn btn-primary">
                    </div>
                </div>
            </form>
        </section>

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
    </body>
</html>
