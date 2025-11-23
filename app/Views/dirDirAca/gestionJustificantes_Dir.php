<?php
    $idDepto = 2; //Esta variable permitira ser modificada para cada departamentp
    $contro = "dirDirAca";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesTramites_Direcciones.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <script src="/IdentiQR/public/JavaScript/gestionesDirecciones.js"></script>
        <title>DireccionACADEMICA_IdentiQR</title>
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
                            <li><a href="/IdentiQR/index.html#PresentacionDepartamentos">TEMAS</a></li>
                            <li><a href="/IdentiQR/index.html#contacto"><i class="fa-solid fa-envelope"></i>CONTACTOS</a></li>
                            <li><a href="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php">REGRESAR</a></li> <!--AQUÍ SE REGRESARÁ-->
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
            <h2>Gestión de trámites</h2>
            <!--<form action="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=insert" method="POST"> --> 
            <form id="formGenerarJustificante" action="/IdentiQR/redireccionAcciones.php?controller=dirDirAca&action=insert" method="POST">
                <fieldset>
                    <legend>Generar trámite</legend>
                    <!--!: Aquí se encontrará toda la información relevante para obtener un QR y generar el justificante-->
                    <!--*A partir de acá se solicitará la información de la matrícula del estudiante escaneado-->
                    <label for = "codigoQR_Estudiante">Escanear_QR</label> <!--*: Aquí debería abrir la cámara para escanear-->
                    <button type="button" id="btnEscanear">Escanear QR</button>
                    <input type="text" name="matriculaEscaneado" id="matriculaEscaneado" disabled> <!--Se encontrará deshabilitado porque NO SE MODIFICARÁ (solo se presentará-->
                    <input type="hidden" name="matriculaEscaneadoBD" id = "matriculaEscaneadoBD">

                    <!--A PARTIR DE ABAJO COMENZAREMOS CON LOS DATOS GENERALES-->
                    <br><br>
                    <label for="idTramite">Trámite a realizar: </label>
                        <select name="idTramite" id="idTramite" required>
                            <option no value=""></option>
                            <option value="0011">Justificante</option>
                            <option value="0012">Recursamiento</option>
                        </select>
                    <br><br>
                    
                    <label for="fechaSolicitud">Fecha de solicitud: </label>
                        <input type="date" name="fechaJustificante" id="fechaJustificante">
                    <br><br>

                    <label for="requisitos">Requisitos: </label>
                        <textarea rows="2" cols="30" id = "Requisitos" name = "Requisitos" placeholder="Requisitos o notas aquí, si no aplica dejar en blanco. Ejemplo [Motivo de salud, personal, etc. ], Ejemplo [POO,FPOO,ABD,...]"></textarea>
                    <hr>

                    <input type="hidden" name="idDepto" value="<?php echo $idDepto;?>">
                    <input type="submit" value="Registrar servicio (Justificante o Recursamiento)" name = "registrarTramite_dirDirACA">
                </fieldset>
            </form>
        </div>

        <div id = "revisarJustificante">
                <!--Aquí se incluirá la tabla del justificante hecho.-->
                <!--<form action="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=consult" method = "POST"> -->
                <form action="/IdentiQR/redireccionAcciones.php?controller=dirDirAca&action=consult" method="POST" onsubmit="consultarConCarga(event)">    
                    <!-- Selección de tipo de búsqueda -->
                    <fieldset>
                        <legend>Consultar por:</legend>
                        <div class="opcionConsulta">
                            <input type="radio" id="consultaTodo" name="tipoConsulta" value="todos" onclick="mostrarCampoConsulta('todos')">
                            <label for="consultaTodo">Todo</label>
                        </div>

                        <div class="opcionConsulta">
                            <input type="radio" id="consultaMatricula" name="tipoConsulta" value="matricula" onclick="mostrarCampoConsulta('matricula')">
                            <label for="consultaMatricula">Matrícula</label>
                        </div>

                        <div class="opcionConsulta">
                            <input type="radio" id="consultaFolio" name="tipoConsulta" value="folio" onclick="mostrarCampoConsulta('folio')">
                            <label for="consultaFolio">Folio</label>
                        </div>

                        <div class="opcionConsulta">
                            <input type="radio" id="consultaTramite" name="tipoConsulta" value="tramite" onclick="mostrarCampoConsulta('tramite')">
                            <label for="consultaTramite">Tipo de Trámite</label>
                        </div>
                    </fieldset>
                    <br>

                    <!-- Campo: Mostrar Todos -->
                    <div id="campoTodosConsulta" style="display:none;">
                        <p>Se mostrarán todos los trámites del departamento.</p>
                        <input type="hidden" name="idDepto" value="<?php echo $idDepto;?>"> <!--NOTA: Considerar que este tipo HIDDEN el valor siempre cambiara-->
                        <input type="submit" value="Mostrar Todos los Trámites" name="consultarTramite_Depto">
                    </div>

                    <!-- Campo: Matricula (con escaneo QR) -->
                    <div id="campoMatriculaConsulta" style="display:none;">
                        <label for="matriculaConsulta">Escanear QR para consultar por Matrícula:</label>
                        <button type="button" id="btnEscanearConsulta">Escanear QR</button>
                        <input type="text" id="matriculaConsultaVisible" placeholder="Matrícula escaneada" disabled>
                        <input type="hidden" name="Matricula" id="matriculaConsulta">
                        <input type="hidden" name="idDepto" value="<?php echo $idDepto;?>">
                        <br><br>
                        <input type="submit" value="Consultar por Matrícula" name="consultarTramite_Matricula">
                    </div>

                    <!-- Campo: Folio -->
                    <div id="campoFolioConsulta" style="display:none;">
                        <label for="folioConsulta">Ingrese Folio:</label>
                        <input type="text" name="FolioRegistro" id="folioConsulta" placeholder="Ej. FOL12345">
                        <input type="hidden" name="idDepto" value="<?php echo $idDepto;?>">
                        <input type="submit" value="Consultar por Folio" name="consultarTramite_Folio">
                    </div>

                    <!-- Campo: Tipo de Trámite -->
                    <div id="campoTramiteConsulta" style="display:none;">
                        <label for="idTramiteConsulta">Seleccione el trámite:</label>
                        <select name="idTramite" id="idTramiteConsulta">
                            <option value="">Seleccione...</option>
                            <option value="0011">Justificante</option>
                            <option value="0012">Recursamiento</option>
                        </select>
                        <input type="hidden" name="idDepto" value="<?php echo $idDepto;?>">
                        <input type="submit" value="Consultar por Trámite" name="consultarTramite_idTramite">
                    </div>
                    </form> 
                        <br>
                    <table border = "1">
                        <thead>
                            <th>FolioRegistro</th>
                            <th>FolioSeguimiento</th>
                            <th>Trámite</th>
                            <th>Fecha y Hora</th>
                            <th>Matrícula</th>
                            <th>Descripción</th>
                            <th>Estatus</th>
                            <th>ACCIONES</th>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($direccion) && $direccion !== null && $direccion !== 0){
                                    while($row = $direccion->fetch_assoc()){
                            ?>
                                    <tr>
                                        <td><?php echo $row['FolioRegistro'];?></td>
                                        <td><?php echo $row['FolioSeguimiento']; ?></td>
                                        <td><?php echo $row['idTramite']; ?></td>
                                        <td><?php echo $row['FechaHora']; ?></td>
                                        <td><?php echo $row['Matricula']; ?></td>
                                        <td><?php echo $row['descripcion']; ?></td>
                                        <td><?php echo $row['estatusT']; ?></td>
                                        <td>
                                            <a href="/IdentiQR/redireccionAcciones.php?action=update&Folio=<?php echo $row['FolioSeguimiento'] ?? ''; ?>">
                                                <button type="button">Editar</button>
                                            </a>
                                            <button type="button" onclick="confirmarEliminacion('<?php echo $contro?>','<?php echo $row['FolioSeguimiento'] ?? ''; ?>','<?php echo $idDepto;?>')">Eliminar</button>
                                        </td>
                                    </tr>
                            <?php
                                    }
                                } else {
                            ?>
                                <tr>
                                    <td colspan="8">No hay trámites para mostrar. Presione "ConsultarTramites" para cargar los datos.</td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table> 
            </div>
        </div>
        
        <div id = "modificarJustificante">
            <!-- <form action="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=updateManual" method = "POST"> -->
            <form action="/IdentiQR/redireccionAcciones.php?controller=dirDirAca&action=updateManual" method="POST">
                <fieldset>
                    <table></table>
                    <legend>Actualizar trámite</legend>
                    <!--!: Aquí se encontrara toda la información relevante para obtener un QR y generar el justificante-->
                    <label for="folioConsulta">Ingrese Folio:</label>
                    <input type="text" name="FolioAct" id="FolioAct" placeholder="Ej. FOL12345 o [0001,0002]"> <!--*: Aquí debería abrir la camara para escanear-->
                    <input type="submit" value="Actualizar registro" name = "Actualizar_Tramite" onclick="alert('Redirección a página de actualización')" onclick="seleccionarAccion(event, 'buscar')>
                </fieldset>
            </form>
        </div>

        <div id = "eliminarJustificante">
            <!--<form action="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=deleteFS" method="POST" onsubmit="return confirmarEliminacionFS(event)"> --> 
            <form action="/IdentiQR/redireccionAcciones.php?action=deleteFS" method="POST" onsubmit="return confirmarEliminacionFS(event)">
                <fieldset>
                    <legend>Eliminar trámite por Folio de Seguimiento</legend>
                    <label for="FolioSeguimiento">Folio de Seguimiento a eliminar: </label>
                    <input type="text" name="FolioSeguimiento" id="FolioSeguimiento" placeholder="Ej. MATRICULA-DATOS-4LETRAS etc. (Consultar en su vista)" required>
                    <input type="hidden" name="idDepto" value="<?php echo $idDepto;?>">
                    <br><br>
                    <input type="submit" value="Eliminar Trámite" name="BajaServicio_Tramite">
                </fieldset>
            </form>
        </div>
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
                    <!--*: Todo esto tiene que ver con los LINKS que se involucrarán al diseñar el sitio web-->
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
        <input type="hidden" id="serverStatusAlert" value="<?php echo isset($statusAlert) ? $statusAlert : ''; ?>">
    </body>
</html>
