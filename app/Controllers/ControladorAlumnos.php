<?php
    require_once __DIR__ . '/../../config/Connection_BD.php';
    require_once __DIR__ . '/../../public/PHP/Alumno.php';
    require_once __DIR__ . '/../Models/ModeloAlumno.php';
    require_once __DIR__ . '/../../public/PHP/codigosQR.php';

    /*Creamos la clase del controlador de Alumnos */
    class AlumnoController{
        private $modelAlumno;
        public function __construct($conn){
            $this->modelAlumno = new AlumnoModel($conn);
            //Se crea una instancia del modelo
        }
        /*Esta función permitirá realizar la inserción/registro dentro de Alumno*/
        public function insertarAlumno(){
            /* Este IF verifica que el método que fue mandado es un POST */
            if(isset($_POST['Enviar_Alumno'])) { //isset() Determina si una variable está definida y no es null
                // Crear objeto Alumno que almacena los datos del formulario
                $feIngreso = sprintf('%04d-09-01', $_POST['FeIngreso']); //https://www.php.net/manual/es/function.sprintf.php
                $Alumno = new Alumno(
                    $_POST['matricula'],
                    $_POST['nombre'],
                    $_POST['ApPat'],
                    $_POST['ApMat'],
                    $_POST['FeNac'],
                    $feIngreso, //$_POST['FeIngreso'],
                    $_POST['correo'] ?? null,
                    $_POST['direccion'],
                    $_POST['telefono'],
                    $_POST['ciudad'],
                    $_POST['estado'],
                    (int)$_POST['carrera'],
                    null,
                    $_POST['genero'] ?? 'Otro'
                );
                // Se construye el objeto de tipo ALUMNO y se procesan los datos, posteriormente se mandan al modelo para la inserción
                $insert =  $this->modelAlumno->insertarAlumno($Alumno);
                if ($insert) {
                    /*  Cuando se registra el alumno se va a instanciar la clase informaciónMedica
                    para poder crear el objeto y unirlo al alumno, una vez que fue registrado. */
                    $informacionMedica = new InformacionMedica(
                        $Alumno->getMatricula(),
                        $_POST['tipoSangre'],
                        $_POST['alergias'],
                        $_POST['contactoEmergencia']
                    );
                    $insertInfoMed = $this->modelAlumno->insertarInfoMedica($informacionMedica);
                    if(!$insertInfoMed){
                        echo "<h2 style='color: red;'>Error al registrar la información médica</h2>";
                    }
                    /*AQUÍ SE DEBE GENERAR EL CÓDIGO QR */
                    $codigosQR = new codigosQR();
                    $rutaQR = $codigosQR->generarQR_Alumno($Alumno);
                    /*CODIFICACIÓN DEL QR - GENERANDO ÚNICAMENTE SU HASH */
                    $hashQR = hash('sha256', $rutaQR->getString()); //Esta sólo permite identificar el QR (No de puede decodificar después)
                    $Alumno->setQRHash($hashQR);

                    /*CODIFICACIÓN DEL QR - DECODIFICACIÓN DESPUÉS */
                    //$hashQR_B64 = base64_encode($rutaQR->getString()); //Esta sí permite guardar todo el QR COMPLETO (Si se puede decodificar después)
                    //$Alumno->setQRHash($hashQR_B64);
                    if(!$this->modelAlumno->asignarHashQR($Alumno)){
                        die("Error al asignar el código QR al alumno.");
                    }
                    /*USANDO EL MÉTODO DEL ARCHIVO enviarCorreo.php 2025-10-15 */
                    include_once __DIR__ . '/../../public/PHP/repositorioPHPMailer/enviarCorreo.php';
                    enviarCorreoAlumno($Alumno, $rutaQR->getString());
                    echo "<script>registroAlumno();</script>";
                    //echo "<h2 style='color: green;'>Registro exitoso</h2>";
                } else {
                    //echo "<h2 style='color: red;'>Registro fallido</h2>";
                }
            }
            //include_once '/../../Views/RegistroAlumno.html';
            $viewPath = __DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php';
            if (file_exists($viewPath)) {
                include_once $viewPath;
                } else {
                // Manejo de error amigable (en dev puedes usar echo, en prod loguear)
                error_log("Vista no encontrada: $viewPath");
                echo "<h2 style='color:red;'>Error: vista no encontrada.</h2>";
            }
            //include_once __DIR__ . '/../Views/gestionesGenerales/RegistroAlumno.html';
        }

        public function actualizarAlumno(){
            if(isset($_POST['Actualizar_Alumno'])) {
                // Obtener la matrícula original para identificar el registro a actualizar
                $matriculaOriginal = $_POST['matricula_original'];
                // Crear objeto Alumno con los datos actualizados del formulario
                // Nota: Matricula y FeIngreso no se pueden modificar, usar valores originales
                $Alumno = new Alumno(
                    $matriculaOriginal, // Mantener matrícula original
                    $_POST['nombre'],
                    $_POST['ApPat'],
                    $_POST['ApMat'],
                    $_POST['FeNac'],
                    $_POST['FeIngreso'], // Mantener fecha de ingreso original
                    $_POST['correo'] ?? null,
                    $_POST['direccion'],
                    $_POST['telefono'],
                    $_POST['ciudad'],
                    $_POST['estado'],
                    (int)$_POST['carrera'],
                    null,
                    $_POST['genero'] ?? 'Otro'
                );
                // Actualizar datos del alumno
                $update = $this->modelAlumno->actualizarAlumno($Alumno, $matriculaOriginal);
                if ($update) {
                    // Actualizar información médica
                    $informacionMedica = new InformacionMedica(
                        $matriculaOriginal, // Usar matrícula original
                        $_POST['tipoSangre'],
                        $_POST['alergias'],
                        $_POST['contactoEmergencia']
                    );
                    $updateInfoMed = $this->modelAlumno->actualizarInfoMedica($informacionMedica);
                    if(!$updateInfoMed){
                        echo "<h2 style='color: red;'>Error al actualizar la información médica</h2>";
                    } else {
                        echo "<h2 style='color: green;'>Alumno actualizado exitosamente.</h2>";
                    }
                } else {
                    echo "<h2 style='color: red;'>Error al actualizar el alumno</h2>";
                }
            }

            // Redirigir de vuelta a la página de gestiones
            $viewPath = __DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php';
            if (file_exists($viewPath)) {
                include_once $viewPath;
            } else {
                error_log("Vista no encontrada: $viewPath");
                echo "<h2 style='color:red;'>Error: vista no encontrada.</h2>";
            }
        }

        public function obtenerAlumno(){
            if(isset($_GET['matricula'])) {
                $matricula = $_GET['matricula'];
                $alumnoData = $this->modelAlumno->obtenerAlumnoPorMatricula($matricula);

                if($alumnoData) {
                    // Mostrar la vista de modificación con los datos del alumno
                    $row = $alumnoData;
                    include_once __DIR__ . '/../Views/gestionesGenerales/modificacionAlumno.php';
                } else {
                    echo "<h2 style='color: red;'>Alumno no encontrado</h2>";
                    $viewPath = __DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php';
                    include_once $viewPath;
                }
            }
        }

        public function consultarAlumnos(){
            $result = null;
            $mostrarMensajeBusqueda = false;
            $mensajeBusqueda = '';

            // Si se presiona el botón "Consultar todo"
            if (isset($_POST['consultarTodo'])) {
                $result = $this->modelAlumno->obtenerTodosAlumnos();
                $mostrarMensajeBusqueda = false;
                $mensajeBusqueda = '';
            }

            include_once(__DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php');
        }

        public function procesarQR(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qrContent'])) {
                $qrContent = $_POST['qrContent'];

                // Extraer la matrícula del contenido del QR
                // Buscar "Matricula: " seguido de la matrícula
                if (preg_match('/Matricula:\s*([^\n\r]+)/', $qrContent, $matches)) {
                    $matricula = trim($matches[1]);

                    // Redirigir a la página de modificación con la matrícula
                    echo 'redirect:/IdentiQR/app/Controllers/ControladorAlumnos.php?action=obtenerAlumno&matricula=' . urlencode($matricula);
                } else {
                    echo 'Error: No se pudo extraer la matrícula del QR.';
                }
            } else {
                echo 'Error: Solicitud inválida.';
            }
        }

        public function eliminarAlumno(){
            $matricula = null;
            
            // Verificar si viene por GET (desde la tabla)
            if(isset($_GET['matricula'])){
                $matricula = $_GET['matricula'];
            }
            // Verificar si viene por POST (desde el formulario de eliminación)
            elseif(isset($_POST['BajaAlumno_EliminarUsuario']) || (isset($_POST['accionEliminar']) && $_POST['accionEliminar'] === 'eliminarAlumno')){
                $matricula = $_POST['idAlumno_BajaUSUARIO'];
            }
            
            // Validar que se haya recibido una matrícula
            if (empty($matricula) || $matricula === "") {
                $mensaje = "Debe proporcionar una matrícula para eliminar.";
                $resultadoExito = false;
                include_once(__DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php');
                exit();
            }
            
            // Realizar la eliminación
            $rowsDeleted = $this->modelAlumno->eliminarAlumno($matricula);
            
            if($rowsDeleted >= 1){
                $mensaje = "La eliminación del Alumno con matrícula [". $matricula ."] fue correcta.";
                $resultadoExito = true;
            } else {
                $mensaje = "Error al eliminar el alumno.";
                $resultadoExito = false;
            }
            
            include_once(__DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php');
            exit();
        }

        /*MÉTODO/FUNCIÓN PARA QUE SE ACTUALICE EL NUEVO CÓDIGO QR*/ 
        public function actualizarQR(){
            //CONFIRMAR SI EL BOTÓN SE ENVIO//
            $result = $this->modelAlumno->obtenerTodosAlumnos();

            if(!$result){
                echo "No se cuenta con alumnos registrados.";
            }
            $listaAlumnos = [];
            $alumnoQR_Nuevo; //Esta es la variable que utilizaremos

            while ($row = $result->fetch_assoc()) {
                $alumnoQR_Nuevo = new Alumno(
                    $row['Matricula'],
                    $row['Nombre'],
                    $row['ApePat'],
                    $row['ApeMat'],
                    $row['FechaNac'],
                    $row['FeIngreso'],
                    $row['Correo'] ?? null,
                    $row['Direccion'] ?? "Sin dirección",
                    $row['Telefono'] ?? "Sin teléfono",
                    $row['Ciudad'] ?? "Desconocida",
                    $row['Estado'] ?? "Desconocido",
                    (int)$row['IdCarrera'],
                    $row['qrHash'] ?? null,
                    $row['Genero'] ?? "Otro"
                );

                // Guardas el nuevo objeto en una lista
                $listaAlumnos[] = $alumnoQR_Nuevo;
            }

            //Usamos un for-each para iterar SOBRE TODOS LOS ALUMNOS. Y Actualizar/generar un nuevo qr.)
            $codigosQR = new codigosQR();
            foreach ($listaAlumnos as $alumno) {
                $rutaQR = $codigosQR->generarQR_Alumno($alumno);
                // Generar un hash único basado en el contenido del QR
                $hashQR = hash('sha256', $rutaQR->getString());
                $alumno->setQRHash($hashQR);
                if(!$this->modelAlumno->asignarHashQR($alumno)){
                    die("Error al asignar el código QR al alumno.");
                }

                // Mostrar mensaje de éxito
                include_once __DIR__ . '/../../public/PHP/repositorioPHPMailer/enviarCorreo.php';
                enviarCorreoAlumnoQRActualizado($alumno, $rutaQR->getString());
            }

            //Incluye la vista del administrador.
            header("Location: /IdentiQR/app/Views/GestionesAdministradorG.php");
            exit();
            //echo "<h2 style='color: green;'>Códigos QR actualizados exitosamente para todos los alumnos.</h2>";
        }

    }
    
    // Realizamos la instancia del método de inserción
    // Asegúrate que $conn exista
    $db = new Connection_BD();
    $conn = $db->getConnection();

    if (!isset($db) || !$db) {
        die("Error: La conexión a la base de datos no está definida.");
    }

    $controladorAlumno = new AlumnoController($conn);
    if(isset($_GET['action'])){
        $action = $_GET['action'];
        switch($action){
            case 'registroAlumno':
                $controladorAlumno->insertarAlumno();
                break;
            case 'updateAlumno':
                $controladorAlumno->actualizarAlumno();
                break;
            case 'obtenerAlumno':
                $controladorAlumno->obtenerAlumno();
                break;
            case 'consultarAlumnos':
                $controladorAlumno->consultarAlumnos();
                break;
            case 'eliminarAlumno':
                $controladorAlumno->eliminarAlumno();
                break;
            case 'procesarQR':
                $controladorAlumno->procesarQR();
                break;
            case 'actualizarQR_Alumno':
                $controladorAlumno->actualizarQR();
                break;
            default:
                header("Location: /IdentiQR/index.html");
                exit();
                break;
        }
    } else {
        header("Location: /IdentiQR/app/Views/gestionesGenerales/GestionesAlumnos.php");
    }
?>
