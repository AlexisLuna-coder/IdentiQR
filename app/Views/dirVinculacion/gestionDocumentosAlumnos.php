<?php
    include_once __DIR__ . '/../../../public/PHP/extraccionDatos_Tablas.php'; // Permite hacer uso de los métodos
    $idDepto = 7; //Esta variable permitirá ser modificada para cada departamento
    $contro = "dirVinc";
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="/IdentiQR/public/Media/img/Favicon.ico"/> <!--FAVICON-->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesTramitesDireccionesGenerales.css">
        <script src="/IdentiQR/public/JavaScript/gestionesDirecciones.js"></script>
        

        <script type="text/javascript" src="https://rawcdn.githack.com/schmich/instascan-builds/master/instascan.min.js"></script>
        <!--<script type="text/javascript" src="/IdentiQR/public/JavaScript/instascan.min.js"></script>-->
        <title>DirecciónVinculación_Documentos_IdentiQR</title>
    </head>
    <body>
        <title>DirecciónVinculación_Documentos_IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrará el emnabezado, este podrá cambiar: nota-->
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
                            <li><a href="/IdentiQR/app/Views/dirVinculacion/GestionesAdmin_Vinculacion.php">REGRESAR</a></li> <!--AQUÍ SE REGRESARÁ-->
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
        
        <div id = "generarTramite">
            <h2>Gestión de Vinculación</h2>
            <!--<form action="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=insert" method="POST"> --> 
            <form id="formGenerarTramite" action="/IdentiQR/redireccionAcciones.php?controller=<?php echo $contro; ?>&action=insert" method="POST">
                <fieldset>
                    <legend>Generar Trámites o servicios - Vinculación</legend>
                    <!--*A partir de acá se solicitará la información de la matrícula del estudiante escaneado-->
                    <label for = "codigoQR_Estudiante">Escanear_QR</label> <!--*: Aquí debería abrir la camara para escanear-->
                    <button type="button" id="btnEscanear">Escanear QR</button>
                    <input type="text" name="matriculaEscaneado" id="matriculaEscaneado" disabled> <!--Se encontrará deshabilitado porque NO SE MODIFICARÁ (solo se presentará-->
                    <input type="hidden" name="matriculaEscaneadoBD" id = "matriculaEscaneadoBD">

                    <!--APARTIR DE AQUÍ SE INCLUYE TODO EL TRÁMITE QUE SE PUEDE HACER EN VINCULACIÓN-->
                    <br><br>
                    <label for="idTramite">Trámite a realizar: </label>
                        <select name="idTramite" id="idTramite" required>
                            <option no value=""></option>
                            <option value="0005">Estancia I</option>
                            <option value="0006">Estancia II</option>
                            <option value="0007">Estadía</option>
                            <option value="0008">Prácticas profesionales</option>
                            <option value="0009">Servicio social</option>
                        </select>
                    <br>

                    <!--CONSIDERAR QUE FECHA DE SOLICITUD PUEDA SER OCULTO - LA DE HOY-->
                    <label for="fechaSolicitud">Fecha de Solicitud:</label>
                        <input type="date" name="fechaSolicitud" id="fechaSolicitud" readonly>
                    <br>
                    <fieldset>
                        <legend>Documentos entregados</legend>
                        <label><input type="checkbox" name="docs[]" value="INE"> INE / Identificación oficial</label><br>
                        <label><input type="checkbox" name="docs[]" value="Constancia"> Constancia de estudios / Kardex</label><br>
                        <label><input type="checkbox" name="docs[]" value="Comprobante"> Comprobante de inscripción</label><br>
                        <label><input type="checkbox" name="docs[]" value="CV"> Currículum Vitae</label><br>
                        <label><input type="checkbox" name="docs[]" value="CartaMotivos"> Carta de motivos / solicitud</label><br>
                        <label><input type="checkbox" name="docs[]" value="CartaAceptacion"> Carta de aceptación (empresa / tutor)</label><br>
                        <label><input type="checkbox" name="docs[]" value="PlanActividades"> Plan de actividades</label><br>
                        <label><input type="checkbox" name="docs[]" value="Seguro"> Seguro / carta responsiva</label><br>
                        <label><input type="checkbox" name="docs[]" value="ComprobantePago"> Comprobante de pago</label><br>
                        <label><input type="checkbox" name="docs[]" value="Otro"> Otros documentos adicionales</label>
                    </fieldset>

                    <label for="descripcionExtra">Descripción adicional (Si no aplica - N/A): </label>
                    <textarea id="descripcionExtra" name="descripcionExtra" rows="2" cols="40"
                        placeholder="[Ejemplo. NOTAS O URGENCIA]..."></textarea>
                    <br>

                    <label for="entregaDocumentos">¿Se entregaron todos los documentos adecuadamente?</label><br>
                        <select name="entregaDocumentos" id="entregaDocumentos" required>
                        <option value="" disabled selected>Seleccione una opción</option>
                        <option value="Si">Sí</option>
                        <option value="No">No</option>
                    </select>
                    
                    <hr>
                    <input type="hidden" name="idDepto" value="<?php echo $idDepto;?>">
                    <input type="submit" value="Registrar tramite - Vinculación" name="registrarTramite_dirVinc">
                </fieldset>
            </form>
        </div>

        <div id = "revisarTramite">
                <!--Aquí se incluira la tabla del trámites hecho.-->
                <!--<form action="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=consult" method = "POST"> -->
                <form action="/IdentiQR/redireccionAcciones.php?controller=<?php echo $contro; ?>&action=consult" method="POST" onsubmit="consultarConCarga(event)">    
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
                        <input type="hidden" name="idDepto" value ="<?php echo $idDepto;?>"> <!--NOTA: Considerar que este tipo HIDDEN el valor siempre cambiará-->
                        <input type="submit" value="Mostrar Todos los Trámites" name="consultarTramite_Depto">
                    </div>

                    <!-- Campo: Matrícula (con escaneo QR) -->
                    <div id="campoMatriculaConsulta" style="display:none;">
                        <label for="matriculaConsulta">Escanear QR para consultar por Matrícula:</label>
                        <button type="button" id="btnEscanearConsulta">Escanear QR</button>
                        <input type="text" id="matriculaConsultaVisible" placeholder="Matrícula escaneada" disabled>
                        <input type="hidden" name="Matricula" id="matriculaConsulta">
                        
                        <br><br>
                        <input type="submit" value="Consultar por Matrícula" name="consultarTramite_Matricula">
                    </div>

                    <!-- Campo: Folio -->
                    <div id="campoFolioConsulta" style="display:none;">
                        <label for="folioConsulta">Ingrese Folio:</label>
                        <input type="text" name="FolioRegistro" id="folioConsulta" placeholder="Ej. FOL12345">
                        <input type="submit" value="Consultar por Folio" name="consultarTramite_Folio">
                    </div>

                    <!-- Campo: Tipo de Trámite -->
                    <div id="campoTramiteConsulta" style="display:none;">
                        <label for="idTramiteConsulta">Seleccione el trámite:</label>
                        <select name="idTramite" id="idTramiteConsulta">
                            <option no value=""></option>
                            <option value="0005">Estancia I</option>
                            <option value="0006">Estancia II</option>
                            <option value="0007">Estadia</option>
                            <option value="0008">Prácticas profesionales</option>
                            <option value="0009">Servicio social</option>
                        </select>
                        <input type="submit" value="Consultar por Trámite" name="consultarTramite_idTramite">
                    </div>
                    </form> 
                    <br>
                    <table border = "1">
                        <thead>
                            <th>Folio de Registro</th>
                            <th>Folio de Seguimiento</th>
                            <th>Trámite</th>
                            <th>Fecha y Hora</th>
                            <th>Matrícula</th>
                            <th>Descripción</th>
                            <th>Estatus</th>
                            <th>Docs. Correctos</th>
                            <th>Docs. Entregados</th>
                            <th>Trámite</th>
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
                                    <td><?php echo obtenerDocumentosFinales($row['descripcion']); ?></td>
                                    <td><?php echo obtenerDocumentosEntregados($row['descripcion']); ?></td>
                                    <td><?php echo obtenerTramiteRealizado($row['descripcion']); ?></td>
                                    <td>
                                        <a href="/IdentiQR/redireccionAcciones.php?controller=<?php echo $contro;?>&action=updateServEsco&Folio=<?php echo $row['FolioSeguimiento'] ?? ''; ?>&idDepto=<?php echo $idDepto; ?>">
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
                                    <td colspan="10">No hay trámites para mostrar. Presione "ConsultarTrámites" para cargar los datos.</td>
                                </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                    </table> 
            </div>
        </div>
        
        <div id = "modificarTramite">
            <!-- <form action="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=updateManual" method = "POST"> -->
            <form action="/IdentiQR/redireccionAcciones.php?controller=<?php echo $contro;?>&action=updateManualServEsco" method="POST">
                <fieldset>
                    <table></table>
                    <legend>Actualizar Trámite de Servicio (Papeleo)</legend>
                    <!--!: Aquí se encontrará toda la información relevante para obtener un QR -->
                    <label for="folioConsulta">Ingrese Folio:</label>
                    <input type="text" name="FolioAct" id="FolioAct" placeholder="Ej. FOL12345 o [0001,0002]"> <!--*: Aquí debería abrir la cámara para escanear-->
                    <input type="hidden" name="idDepto" value="<?php echo $idDepto;?>">
                    <input type="submit" value="Actualizar registro" name = "Actualizar_Tramite" onclick="alert('Redirección a página de actualización')">
                </fieldset>
            </form>
        </div>

        <div id = "eliminarTramite">
            <!--<form action="/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=deleteFS" method="POST" onsubmit="return confirmarEliminacionFS(event)"> --> 
            <form action="/IdentiQR/redireccionAcciones.php?controller=<?php echo $contro;?>&action=deleteFS" method="POST" onsubmit="return confirmarEliminacionFS(event)">
                <fieldset>
                    <legend>Eliminar Trámite de Servicios por Folio de Seguimiento</legend>
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
