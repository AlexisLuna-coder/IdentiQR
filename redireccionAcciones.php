<?php 
    //Incluir el controlador de la Dirección/Departamento correspondiente
    include_once "config/Connection_BD.php";
    //include_once "app/Controllers/ControladorAlumnos.php";
    include_once "app/Controllers/ControladorDirecciones.php";
    //include_once "app/Controllers/ControladorUsuario.php";
    
    // Crear la conexión a la base de datos
    $db = new Connection_BD();
    $conn = $db->getConnection();
    
    if (!$conn) {
        die("Error: No se pudo establecer conexión con la base de datos.");
    }
    
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
        case 'update':
            $controllerInstance -> actualizarTramite();
            break;
        case 'updateManual':
            $controllerInstance -> actualizarTramiteManual();
            break;
        case 'updateDAE':
            $controllerInstance ->actualizarTramite();
            break;
        case 'updateManualDAE':
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
        default:
            //echo "Error al encontrar el controlador";
            include "app/Views/index.html";
            //include "app/views/form_index.php";
            break;
    }
?>
