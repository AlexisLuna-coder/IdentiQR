<?php
    $idDepto = 5; //Esta variable permitirá ser modificada para cada departamento
    $contro = "dirDAE";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">metodos
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesTramites_Direcciones.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <script src="/IdentiQR/public/JavaScript/gestionesDirecciones.js"></script>
        <title>DireccionAsuntosEstudiantiles_IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrará el encabezado, este podrá cambiar: nota-->
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
                            <li><a href="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php">REGRESAR</a></li>
                        </ul>
                    </nav>
                    <div class="btn__menu" id="btn_menu">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </header>
        <!--*A partir de acá se inicializará la parte de la página general, será nuestro tema central e identificación de lo que contendrá-->
        <div id="HeaderLogin">
            <h2><center>IdentiQR</center></h2>
            
        </div>
        <hr>
        <!-- TODO: Aquí empezaremos con la información que tiene que ver con los datos o mayoritariamente del index principal (Recursos, etc.)-->
        
        <div id = "generarJustificante">
            <h2>Gestión de Extracurricular - DAE</h2>
            <form action="/IdentiQR/redireccionAcciones.php?controller=dirDAE&action=updateDAE" method="POST">
                <fieldset>
                    <legend>Actualización de Trámite - <?php echo $row['FolioSeguimiento']; ?> </legend>
                    <!--!: Aquí se encontrará toda la información relevante para obtener un QR-->
                    <!--*A partir de acá se solicitará la información de la matrícula del estudiante escaneado-->
                    <label for="matriculaEscaneadoBD">Alumno: </label>
                    <input type="text" name="matriculaEscaneado" id="matriculaEscaneado" disabled value = "<?php echo $row['Matricula'];?>" > <!--Se encontrará deshabilitado porque NO SE MODIFICARÁ (solo se presentará-->
                    <input type="hidden" name="matriculaEscaneadoBD" id = "matriculaEscaneadoBD">

                    <input type="hidden" name="FolioRegistro" id = "FolioRegistro"       value = "<?php echo $row['FolioRegistro']?>">
                    <input type="hidden" name="FolioSeguimiento" id = "FolioSeguimiento" value = "<?php echo $row['FolioSeguimiento']?>">
                    
                    <label for="requisitos">Descripción/Requisitos: </label>
                        <textarea rows="4" cols="30" id = "Descripcion" name = "Descripcion" value = "">
                            <?php echo trim($row['descripcion']); ?>
                        </textarea> <!--NOTA: Considerar que puede quedar disabled-->
                    <hr>

                    <select name="estatusT" id="estatusT" required>
                        <option value="Pendiente" <?php if($row['estatusT'] == 'Pendiente') echo 'selected'; ?>>Pendiente</option>
                        <option value="En Proceso" <?php if($row['estatusT'] == 'En Proceso') echo 'selected'; ?>>En Proceso</option>
                        <option value="Aprobado" <?php if($row['estatusT'] == 'Aprobado') echo 'selected'; ?>>Aprobado</option>
                        <option value="Rechazado" <?php if($row['estatusT'] == 'Rechazado') echo 'selected'; ?>>Rechazado</option>
                        <option value="Completado" <?php if($row['estatusT'] == 'Completado') echo 'selected'; ?>>Completado</option>
                        <option value="Cancelado" <?php if($row['estatusT'] == 'Cancelado') echo 'selected'; ?>>Cancelado</option>
                    </select>
                    <input type="hidden" name="idDepto" id = "idDepto" value = "<?php echo $idDepto; ?>"> <!--CONSIDERAR QUE CAMBIARÁ EL VALUE-->

                    <input type="submit" value="Actualizar servicio (EXTRACURRICULAR)" name = "actualizarTramite_Tramite" onclick="alert('Datos actualizados con éxito')">
                </fieldset>
            </form>
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

        <script>
            window.addEventListener('popstate',function(event){
                history.pushState(null,null,window.location.pathname);
                history.pushState(null,null,window.location.pathname);
            },false);
        </script>
    </body>
</html>
