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
            //Se crea una instancia del modelo e inicializa el atributo
        }

        /*Esta función permitirá realizar la inserción/registro dentro de Alumno*/
        public function insertarAlumno(){
            /* Este IF verifica que el método que fue mandado es un POST */
            if(isset($_POST['Enviar_Alumno'])) { //isset() Determina si una variable está definida y no es null
                // Esto mapea la letra con acento a la letra sin acento
                $mapaAcentos = array(
                'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                'ü' => 'u', 'Ü' => 'U'
                );

                try {
                    // Calcula la edad exacta para validar la restricción de negocio (mínimo 16 años)
                    $fechaNacimiento = new DateTime($_POST['FeNac']);
                    $hoy = new DateTime();
                    $edad = $hoy->diff($fechaNacimiento)->y; // Calcula la diferencia en años
                    if ($edad < 16) {
                        // Si es menor de 16 NO registramos
                        $resultadoExito = false;
                        $mensaje = "El alumno no puede ser registrado. Edad actual: $edad años. La edad mínima requerida es de 16 años.";
                        // Cargamos la vista con el error y detenemos la función
                        include_once __DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php';
                        return; 
                    }
                } catch (Exception $e) {
                    $resultadoExito = false;
                    $mensaje = "Error al validar la fecha de nacimiento.";
                    include_once __DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php';
                    return;
                }
                /*Aquí nos encargaremos de pasar los nombre obtenidos en el formulario (si tiene acentos) a pasarlo SIN */
                $nombreLimpio = strtr($_POST['nombre'], $mapaAcentos);
                $apPatLimpio  = strtr($_POST['ApPat'], $mapaAcentos);
                $apMatLimpio  = strtr($_POST['ApMat'], $mapaAcentos);
                $direccionLimpia = strtr($_POST['direccion'], $mapaAcentos);
                $ciudadLimpia = strtr($_POST['ciudad'], $mapaAcentos);
                $estadoLimpio = strtr($_POST['estado'], $mapaAcentos);
                
                // Crear objeto Alumno que almacena los datos del formulario
                $feIngreso = sprintf('%04d-09-01', $_POST['FeIngreso']); //https://www.php.net/manual/es/function.sprintf.php
                // Se genera la instancia del objeto Alumno con todos los datos recolectados del formulario
                $Alumno = new Alumno(
                    strtoupper($_POST['matricula']),
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

                if ($insert === "DUPLICADO") {
                    $resultadoExito = false;
                    $mensaje = "Error: La Matrícula o el Correo electrónico ya están registrados.";
                }else {
                    if ($insert) {
                        /*  Cuando se registra el alumno se va a instanciar la clase informaciónMedica
                        para poder crear el objeto y unirlo al alumno, una vez que fue registrado. 
                        Se guarda el registro médico del alumno*/
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
                        
                        // Genera el archivo físico del Código QR basado en los datos del objeto Alumno
                        $codigosQR = new codigosQR();
                        $rutaQR = $codigosQR->generarQR_Alumno($Alumno);
                        
                        // Crea un HASH único del QR para seguridad (evita falsificaciones) y lo guarda en BD
                        $hashQR = hash('sha256', $rutaQR->getString()); //Esta sólo permite identificar el QR (No de puede decodificar después)
                        $Alumno->setQRHash($hashQR);
                        
                        /*CODIFICACIÓN DEL QR - DECODIFICACIÓN DESPUÉS */
                        if(!$this->modelAlumno->asignarHashQR($Alumno)){
                            die("Error al asignar el código QR al alumno.");
                        }
                        
                        // Envía el correo electrónico con el QR adjunto al alumno recién registrado
                        include_once __DIR__ . '/../../public/PHP/repositorioPHPMailer/enviarCorreo.php';
                        enviarCorreoAlumno($Alumno, $rutaQR->getString());
                        $resultadoExito = true;
                        $mensaje = "Registro Exitoso";
                        } else {
                            $mensaje = "Error al registrar al alumno en la base de datos.";
                    }
                }
            }
            
            // Se incluye la vista final con los mensajes de éxito o error
            $viewPath = __DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php';
            if (file_exists($viewPath)) {
                include_once $viewPath;
                } else {
                error_log("Vista no encontrada: $viewPath");
                echo "<h2 style='color:red;'>Error: vista no encontrada.</h2>";
            }
        }

        public function actualizarAlumno(){
            if(isset($_POST['Actualizar_Alumno'])) {
                // Obtener la matrícula original para identificar el registro a actualizar
                $matriculaOriginal = $_POST['matricula_original'];

                // Crear objeto Alumno con los datos actualizados del formulario
                // Nota: Matricula y FeIngreso no se pueden modificar, usar valores originales
                $Alumno = new Alumno(
                    $matriculaOriginal, // Matrícula original
                    $_POST['nombre'],
                    $_POST['ApPat'],
                    $_POST['ApMat'],
                    $_POST['FeNac'],
                    $_POST['FeIngreso'], // Fecha de ingreso original
                    $_POST['correo'] ?? null,
                    $_POST['direccion'],
                    $_POST['telefono'],
                    $_POST['ciudad'],
                    $_POST['estado'],
                    (int)$_POST['carrera'],
                    null,
                    $_POST['genero'] ?? 'Otro'
                );

                // Actualizar datos del alumno en cascada (Desde sus datos personales, hasta los datos médicos)
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
                        // Error parcial (Alumno sí, médica no)
                        $resultadoExito = false;
                        $mensaje = "Alumno actualizado, pero hubo un error al actualizar la información médica.";
                    } else {
                        // ÉXITO TOTAL
                        $resultadoExito = true;
                        $mensaje = "Alumno actualizado exitosamente.";
                    }
                } else {
                    // Error al actualizar alumno
                    $resultadoExito = false;
                    $mensaje = "Error al actualizar los datos del alumno.";
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
                // Busca los datos de un alumno específico para rellenar el formulario de edición
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
            // Se recupera la consulta completa de alumnos para mostrarlos en la tabla de gestión
            $result = null;
            $mostrarMensajeBusqueda = false;
            $mensajeBusqueda = '';
            $resultadoExito = null; // Inicializamos como null

            // Si se presiona el botón "Consultar todo"
            if (isset($_POST['consultarTodo'])) {
                $result = $this->modelAlumno->obtenerTodosAlumnos();
                if ($result && $result->num_rows > 0) {
                    $resultadoExito = true;
                    $mensaje = "Consulta de todos los alumnos realizada correctamente.";
                } else {
                    $resultadoExito = false;
                    $mensaje = "No hay alumnos registrados en el sistema.";
                }
            }
            include_once(__DIR__ . '/../Views/gestionesGenerales/GestionesAlumnos.php');
        }

        public function procesarQR(){
            // Lógica para interpretar el contenido escaneado de un QR
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qrContent'])) {
                $qrContent = $_POST['qrContent'];
                // Extraer la matrícula del contenido del QR
                // Buscar "Matricula: " seguido de la matrícula
                if (preg_match('/Matricula:\s*([^\n\r]+)/', $qrContent, $matches)) {
                    $matricula = trim($matches[1]);
                    // Redirige al controlador solicitando la acción de 'obtenerAlumno' con la matrícula encontrada
                    echo 'redirect:/IdentiQR/app/Controllers/ControladorAlumnos.php?action=obtenerAlumno&matricula=' . urlencode($matricula); //https://www.ibm.com/docs/es/app-connect/11.0.0?topic=functions-urlencode-function
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
            //Nota. Esta función será el envio MÁSIVO de datos por medio del BOTON. Regenera y reenvía correos con nuevos QR a TODOS los alumnos
            //CONFIRMAR SI EL BOTÓN SE ENVIO//
            $result = $this->modelAlumno->obtenerTodosAlumnos();

            if(!$result){
                echo "No se cuenta con alumnos registrados.";
            }
            $listaAlumnos = [];
            $alumnoQR_Nuevo; //Esta es la variable que utilizaremos
            // Primero convierte los resultados crudos de la BD en objetos Alumno
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
        }
    }
    
    // Realizamos las Instancias de los metodos de inserción
    // Dentro de este mismo controlador, se manejan las RUTAS para acceder a toda la información y métodos.
    $db = new Connection_BD();
    $conn = $db->getConnection();
    if (!isset($db) || !$db) {
        die("Error: La conexión a la base de datos no está definida.");
    }
    $controladorAlumno = new AlumnoController($conn);
    // Switch que decide qué método ejecutar según el parámetro 'action' en la URL
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
