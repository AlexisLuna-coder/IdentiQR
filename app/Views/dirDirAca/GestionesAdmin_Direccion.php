<!-- 
    //Incluir el controlador de la Dirección/Departamento correspondiente
    //Incluir el controlador de la Dirección/Departamento correspondiente
/*
    // Ruta segura a ControladorDirecciones.php (desde app/Views/dirDirAca -> app/Controllers)
    $controladorPath = __DIR__ . '/../../Controllers/ControladorDirecciones.php';
    // Ruta segura a la conexión (project_root/config/Connection_BD.php)
    // desde app/Views/dirDirAca subimos 3 niveles: app/Views/dirDirAca -> app/Views -> app -> project_root
    $connectionPath   = __DIR__ . '/../../../config/Connection_BD.php';

    // Comprobaciones antes de incluir (mejor para depuración)
    if (!file_exists($controladorPath)) {
        die("Error: no se encontró el controlador en: $controladorPath");
    }
    if (!file_exists($connectionPath)) {
        die("Error: no se encontró Connection_BD en: $connectionPath");
    }

    require_once $controladorPath;
    require_once $connectionPath;

    // En caso de no tener una ruta, se envia al formulario de insertar usuario
    $controller = isset($_GET['controller']) ? $_GET['controller'] :  'dirDirAca';
    //
    $action = isset($_GET['action']) ? $_GET['action'] : 'inicio';

    switch($controller){
        case 'dirDirAca':
            $controllerInstance = new DirectionsController($conn);
            break;
        case 'alumno':
            $controllerInstance = new AlumnoController($conn);
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
        case 'update':
            $controllerInstance -> actualizarTramite();
            break;
        case 'updateManual':
            $controllerInstance -> actualizarTramiteManual();
            break;
        case 'delete': //Eliminar por FolioRegistro (desde la tabla - si se necesita en el futuro)
            $controllerInstance -> bajaTramiteFR();
            break;
        case 'deleteFS': //Eliminar por FolioSeguimiento (desde la tabla y formulario)
            $controllerInstance -> bajaTramiteFS();
            break;
        case 'inicio':
            break;
        default:
            echo "Error al encontrar el controlador";
            //include "app/views/form_index.php";
            break;
    }
*/
-->

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/IdentiQR/public/Media/img/Favicon.ico" type="image/x-icon"> <!--FAVICON-->
        <link rel="stylesheet" href="/IdentiQR/public/CSS/gestionesDirecciones.css">
        <title>DireccionACADEMICA_IdentiQR</title>
    </head>
    <body>
        <!-- !Aquí se encontrara el emcabezado, este podrá cambiar: nota-->
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
                        </ul>
                    </nav>
                    <div class="btn__menu" id="btn_menu">
                        <i class="fas fa-bars"></i>
                    </div>
                </div>
            </div>
        </header>
        <!--*Apartir de acá se inicializara la parte de la página general, sera nuestro tema central e identificación de lo que contendra-->

        <div id="HeaderLogin">
            <h2><center>IdentiQR</center></h2>

        </div>
        <hr>
        <!-- TODO: Aquí empezaremos con la información que tiene que ver con los datos o mayoritariamente del index principal (Recursos, etc.)-->
        <div class = "contenedorCentral">
            <section>
                <h2>Gestión de Alumnos</h2>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php#seccionRegistrarAlumno">Registrar Nuevo Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php#ConsultaModificacionAlumnos">Modificar un Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php#EliminacionAlumnos">Eliminar un Alumno</a>
                <a href = "/IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php#ConsultaModificacionAlumnos">Buscar un Alumno</a>
            </section>
            <hr>

            <section>
                <h2>Gestión de trámites</h2>
                <a href = "/IdentiQR/app/Views/dirDirAca/gestionJustificantes_Dir.php#generarJustificante">Generar trámite</a>
                <a href = "/IdentiQR/app/Views/dirDirAca/gestionJustificantes_Dir.php#revisarJustificante">Revisar trámite</a>
            </section>
        </div>

        <br>
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