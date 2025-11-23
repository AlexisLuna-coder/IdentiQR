<?php 
    //Incluir el controlador de la Dirección/Departamento correspondiente
    include_once "config/Connection_BD.php";
    //include_once "app/Controllers/ControladorAlumnos.php";
    include_once "app/Controllers/ControladorDirecciones.php";
    include_once "app/Controllers/ControladorBD.php";
    include_once "app/Controllers/ControladorReportes.php";
    //include_once "app/Controllers/ControladorUsuario.php";
    
    // Crear la conexión a la base de datos
    $db = new Connection_BD();
    $conn = $db->getConnection();
    
    if (!$conn) {
        die("Error: No se pudo establecer conexión con la base de datos.");
    }
    // ---------------------------------------------------------------------------------------
    // VALIDACIÓN DE SESIÓN CON SWEETALERT
    // ---------------------------------------------------------------------------------------
    session_start();
    if (!isset($_SESSION['rol'])) {
        // Cerramos PHP para escribir HTML limpio
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Acceso Denegado</title>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f0f2f5; }
            </style>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let timerInterval;
                    Swal.fire({
                        title: 'Acceso Restringido',
                        html: 'Debes iniciar sesión para continuar.<br>Redirigiendo al Login...',
                        icon: 'warning',
                        timer: 2500, // 2000 milisegundos = 2 segundos
                        timerProgressBar: true, // Muestra la barra de tiempo disminuyendo
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false, // Ocultamos el botón, es automático
                        willClose: () => {
                            // Se ejecuta justo antes de cerrar
                            clearInterval(timerInterval);
                        }
                    }).then((result) => {
                        // Cuando el timer termina (o se cierra la alerta), redirigimos
                        window.location.href = '/IdentiQR/app/Views/Login.php';
                    });
                });
            </script>
        </body>
        </html>
        <?php
        exit; // IMPORTANTE: Detener la ejecución aquí
    }
    //---------------------------------------------------------------------------------------
    

    // En caso de no tener una ruta, se envia al formulario de insertar usuario
    $controller = isset($_GET['controller']) ? $_GET['controller'] :  'dirDirAca';
    //
    $action = isset($_GET['action']) ? $_GET['action'] : 'inicio';

    switch($controller){
        case 'dirDirAca':
            $controllerInstance = new DirectionsController($conn);
            break;
        case 'dirDAE':
            $controllerInstance = new DirectionsController($conn);
            break;
        case 'dirDDA':
            $controllerInstance = new DirectionsController($conn);
            break;
        case 'dirMedica':
            $controllerInstance = new DirectionsController($conn);
            break;
        case 'dirServEsco':
            $controllerInstance = new DirectionsController($conn);
            break;
        case 'dirVinc':
            $controllerInstance = new DirectionsController($conn);
            break;
        case 'BackRest_DBs': //Back: Backup y Rest: Restore
            $controllerInstance = new ControllerBD($conn);
            break;
        case 'reportsGeneral':
            $controllerInstance = new ReportsController($conn);
            break;
        /*case 'alumno':
            $controllerInstance = new AlumnoController($conn);
            break;
            */
        default:
            echo "<br> Error al encontrar el controlador";
            exit();
    }

    switch($action){
        case 'insert': //Llamar a registrarTramite
            $controllerInstance -> registrarTramite();
            break;
        case 'consult': //Llamar a consultarTramitesPorDEPTO
            // Verificar qué tipo de consulta se está realizando
            if(isset($_POST['consultarTramite_Depto'])){
                $controllerInstance -> consultarTramitesPorDEPTO();
            } elseif(isset($_POST['consultarTramite_Matricula'])){
                $controllerInstance -> consultarPorMatricula();
            } elseif(isset($_POST['consultarTramite_Folio'])){
                $controllerInstance -> consultarPorFolio();
            } elseif(isset($_POST['consultarTramite_idTramite'])){
                $controllerInstance -> consultarPorTipoTramite();
            } else {
                $controllerInstance -> consultarTramitesPorDEPTO();
            }
            break;
        /*Casos de update para direcciones */
        case 'update': //DirAca
            $controllerInstance -> actualizarTramite();
            break;
        case 'updateManual': //DirAca
            $controllerInstance -> actualizarTramiteManual();
            break;
        case 'updateDAE': //DAE
            $controllerInstance ->actualizarTramite();
            break;
        case 'updateManualDAE': //DAE
            $controllerInstance -> actualizarTramiteManual();
            break; 
        case 'updateDDA': //DDA
            $controllerInstance ->actualizarTramite();
            break;
        case 'updateManualDDA': //DDA
            $controllerInstance -> actualizarTramiteManual();
            break;
        case 'updateMedica': //Medica
            $controllerInstance ->actualizarTramite();
            break;
        case 'updateManualMedica': //Medica
            $controllerInstance -> actualizarTramiteManual();
            break; 
        case 'updateServEsco': //Servicios escolares
            $controllerInstance ->actualizarTramite();
            break;
        case 'updateManualServEsco': //Servicios escolares
            $controllerInstance -> actualizarTramiteManual();
            break; 
        case 'updateVinc': //Vinculación
            $controllerInstance ->actualizarTramite();
            break;
        case 'updateManualVinc': //Vinculación
            $controllerInstance -> actualizarTramiteManual();
            break; 
        /**************************************** */   

        case 'delete': //Eliminar por FolioRegistro (desde la tabla - si se necesita en el futuro)
            $controllerInstance -> bajaTramiteFR();
            break;
        case 'deleteFS': //Eliminar por FolioSeguimiento (desde la tabla y formulario)
            $controllerInstance -> bajaTramiteFS();
            break;
        case 'inicio':
            break;
        /*AQUÍ ESTARÁN LAS ACCIONES PARA EL BACKUP Y RESTORE DE LA BASE DE DATOS*/
        case 'backup':
            $controllerInstance -> backupDBs();
            break;
        case 'restore':
            $controllerInstance -> restoreDBs();
            break;
        /*AQUÍ ESTARÁN  LAS ACCIONES QUE SE HARA PARA LOS REPORTES INDIVIDUALIZADOS DE CADA DIRECCIÓN*/
        //idDepto = 1; Sin dirección asignada - ADMINISTRADOR GENERAL
        case 'tramitesGenerales':
            $controllerInstance -> reporteGeneral1_Admin();
            break;
        case 'usuariosGenerales':
            $controllerInstance -> reporteGeneral2_Admin();
            break;    
        case 'alumnosGenerales':
            $controllerInstance -> reporteGeneral3_Admin();
            break;
        //idDepto = 2; Dirección acádemica
        case 'alumnosGenerales2':
            $controllerInstance -> reporteGeneral_DirAca();
            break;
        //idDepto = 3; Servicios escolares
        case 'repGen_ServEsco':
            $controllerInstance -> reporteGeneral_ServEsco();
            break;
        //idDepto = 4; Dirección Desarrollo Academico
        case 'repGen_DDA':
            $controllerInstance -> reporteGeneral_DDA();
            break;
        //idDepto = 5; Dirección Asuntos Estudiantiles
        case 'repGen_DAE':
            $controllerInstance -> reporteGeneral_DAE();
            break;
        //idDepto = 6; Consultorio de atención de primer contacto
        case 'repInd_DirMed':
            $controllerInstance ->reporteInd_DirMed();
            break;
        case 'reporteCitasDia':
            $controllerInstance ->reportePorDia_DirMed();
            break;
        //idDepto = 7; Vinculación
        case 'repGen_Vinc':
            $controllerInstance -> reporteGeneral_Vinc();
        default:
            //echo "Error al encontrar el controlador";
            include "app/Views/index.html";
            //include "app/views/form_index.php";
            break;
        

    }
?>
