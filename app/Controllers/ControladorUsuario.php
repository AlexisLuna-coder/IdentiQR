    <?php
        /*Desarrolladores
        Barreto Basave Lizbeth
        Sanchez Luna Alexis Sebastian*/
        require_once __DIR__ . '/../../config/Connection_BD.php';
        require_once __DIR__ . '/../../public/PHP/Usuario.php';
        require_once __DIR__ . '/../Models/ModeloUsuario.php';     
        Include_once __DIR__ . '/../../public/PHP/repositorioPHPMailer/enviarCorreo.php';

        /*Creamos la clase UserController para manejar todas las funciones REQUERIDAS en los usuarios*/
        class UserController {
            private $modelUser;
            public function __construct($conn){
                $this->modelUser = new UsuarioModel($conn);
                //Se crea una instancia del modelo
            }

            public function registrarUsuario(){
                if(isset($_POST['enviarRegistro_Usuario'])) {
                    //Definimos el mapa de caracteres para detectar CARACTERES 
                    $mapaAcentos = array(
                        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
                        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
                        'ü' => 'u', 'Ü' => 'U', 'ö' => 'o', 'Ö' => 'O'
                    );
                    
                    //Creamos variables SIN ACENTOS
                    $nombreLimpio = strtr($_POST['nombre'], $mapaAcentos);
                    $apPatLimpio  = strtr($_POST['apellido_paterno'], $mapaAcentos);
                    $apMatLimpio  = strtr($_POST['apellido_materno'], $mapaAcentos);

                    $Usuario =  new Usuario(
                        $nombreLimpio,  // Usamos la variable sin acentos ni tíldes
                        $apPatLimpio,   // Usamos la variable sin acentos ni tíldes
                        $apMatLimpio,   // Usamos la variable sin acentos ni tíldes
                        $_POST['genero'],
                        $_POST['email'],
                        $_POST['passw'],
                        $_POST['rol'],
                        (int)$_POST['idDepto']
                    );
                    
                    $usrN = $this -> modelUser -> registrarUsuario($Usuario);
                    // VERIFICACIÓN DE ERRORES
                    if ($usrN === "DUPLICADO") {
                        //Ya existe correo o matrícula
                        $resultadoExito = false;
                        $mensaje = "Error: El correo electrónico o la matrícula ya están registrados en el sistema.";
                        
                        // Cargamos la vista de nuevo para mostrar el error
                        include_once __DIR__ . '/../Views/gestionesGeneralesUsuarios/GestionesUsuarios.php';
                        return; // Detenemos la ejecución
                    }
                    if($usrN != null) {
                        echo "Se registro adecuadamente el usuario";
                        /*USANDO EL REPOSITORIO DE PHPMAILER PARA MANDAR EL CORREO*/
                        enviarCorreo($usrN);
                        /**********************************************************/
                        //Incluimos la vista del formulario
                        header("Location: /IdentiQR/app/Controllers/ControladorUsuario.php?action=consultarUsuario");
                        exit();
                    }
                }
            }
            
            /*
            ! Función consultarUsuario
            * Maneja la búsqueda y listado de usuarios.
            * Puede mostrar un usuario específico (por búsqueda) o todos los usuarios.
            */
            public function consultarUsuario(){
                $result = null;
                $mostrarMensajeBusqueda = false;
                $mensajeBusqueda = '';
                $accion = $_POST['accion'] ?? ''; // Capturamos la acción solicitada (por si viene de un input hidden o botón)
                //Si se envia el formulario
                if(isset($_POST['BusquedaUSUARIO_ConsultarUsuario']) or $accion === "buscar") {
                    $credencialBuscar = trim($_POST['idUsuario_ConsultarUSUARIO']);
                    if($credencialBuscar != null) {
                        $result = $this -> modelUser ->consultarUsuario($credencialBuscar);
                        if (!$result || $result->num_rows === 0) {
                            // No encontró coincidencias
                            $result = null;          // fuerza tabla vacía en la vista
                            $mostrarMensajeBusqueda = true;
                            $mensajeBusqueda = "No se encontraron coincidencias.";
                        }
                    } else {
                        echo "<p style='color:red'>No se encontró el usuario con el ID proporcionado.</p>";
                    }
                }
                // Si se presiona el botón "Consultar todo"
                // Si se accede por GET para consultar todos los usuarios
                if (isset($_GET['consultarTodo'])) {
                    $result = $this->modelUser->obtenerTodosUsuarios();

                    // Al consultar todo, no mostramos mensajes
                    $mostrarMensajeBusqueda = false;
                    $mensajeBusqueda = '';
                }
                include_once(__DIR__ . '/../Views/gestionesGeneralesUsuarios/GestionesUsuarios.php');
            }
            
            //Método para iniciar sesion (Valida las credenciales de acceso e inicia la sesión del usuario.)
            public function loginUsuario(){
                $statusAlert = null; // Variable para controlar la alerta (SweetAlert)
                if (isset($_POST['enviarLogin'])) {
                    $usuario = trim($_POST['usuario']) ?? '';
                    $password = trim($_POST['password']) ?? '';
                    // Llamada al modelo para verificar credenciales (Procedimiento)
                    $loginUSR = $this -> modelUser -> loginUsuarioSP($usuario, $password);
                    if (!$loginUSR) {
                        //Credenciales incorrectas
                        $statusAlert = 'error_credenciales'; 
                        include_once __DIR__ . '/../Views/Login.php';
                        //exit();
                    } else {
                        session_start();
                        //ARREGLO
                        $_SESSION['idUsuario'] = $loginUSR['idUsuario'];
                        $_SESSION['email']     = $loginUSR['email'];
                        $_SESSION['rol']       = $loginUSR['rol'];
                        $_SESSION['usr']       = $loginUSR['usr'];
                        // Redirigir al panel de redirección según rol
                        header("Location: /IdentiQR/app/Views/controlAccesoUsuario.php"); //
                        exit();
                    }
                } else {
                    // Si intentan entrar directo sin POST, mandar al inicio
                    header("Location: /IdentiQR/index.html");
                }
            }
            //Método para cerrar sesion
            public function logoutUsuario(){
                session_start();
                session_unset();  // Libera todas las variables de sesión
                session_destroy(); // Destruye la sesión
                header("Location: /IdentiQR/app/Views/Login.php");
                exit();
            }

            //Método para modificar datos de un usuario
            public function actualizarUsuario(){
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $id_browser = (int) $_GET['id'];
                    //Llamar al método el modelo para hacer la consulta
                    $row = $this -> modelUser -> consultarUsuarioPorID($id_browser);
                    // Cargar vista de modificación con la variable $row llena
                    include_once("../Views/gestionesGeneralesUsuarios/modificacionUsuario.php");
                    return;
                }
                /*EVALUAMOS SI ESTÁ VACÍO LA ACTUALIZACIÓN*/
                if(isset($_POST['actualizarDatosUSER'])){
                    /* Reconstruimos el objeto Usuario con los nuevos datos */
                    $usuario = new Usuario(
                        $_POST['nombre'],
                        $_POST['apellido_paterno'],
                        $_POST['apellido_materno'],
                        $_POST['genero'],
                        $_POST['email'],
                        $_POST['passw'],
                        $_POST['rol'],
                        (int)$_POST['idDepto']
                    );

                    // Agregamos los campos faltantes
                    $usuario->setIdUsuario((int)$_POST['id_usuario']); // Asignamos el ID que viene oculto en el form (necesario para el WHERE del update)
                    // Ejecutar update en BD
                    $resultado = $this->modelUser->actualizarUsuario($usuario);
                    // Definir mensajes de éxito/error para la vista
                    if ($resultado) {
                        $resultadoExito = true;
                        $mensaje = "Usuario actualizado correctamente.";
                    } else {
                        $resultadoExito = false;
                        $mensaje = "Error al actualizar el usuario.";
                    }
                    // Volver a cargar la vista de gestión general para mostrar el resultado
                    $viewPath = __DIR__ . '/../Views/gestionesGeneralesUsuarios/GestionesUsuarios.php'; 
                    //
                    if (file_exists($viewPath)) {
                        include_once $viewPath;
                    } else {
                        echo "Error: No se encuentra la vista en $viewPath";
                    }
                }
                return;
            }

            //Método para buscar al usuario
            public function buscarUsuario(){
                if(isset($_POST['buscarUsuarioBtn'])){
                    $credencial = trim($_POST['idUsuario_Buscar']);
                    // Validación básica
                    if(empty($credencial)){
                        echo "<script>alert('Por favor ingresa un usuario o correo para buscar.'); window.history.back();</script>";
                        exit;
                    }
                    
                    $resultado = $this->modelUser->consultarUsuario($credencial);

                    if($resultado && $resultado->num_rows === 1){
                        $usuario = $resultado->fetch_assoc();
                        // Redirigir a la página de edición con el ID del usuario encontrado
                        $id_usuario = $usuario['id_usuario'];
                        header("Location: /IdentiQR/app/Controllers/ControladorUsuario.php?action=updateUsuarioID&id=$id_usuario");
                        exit();
                    } else if($resultado && $resultado->num_rows > 1){
                        // Si hay más de uno, puedes mostrar lista o mensaje
                        echo "<script>alert('Se encontraron varios usuarios. Por favor, usa un filtro más específico.'); window.history.back();</script>";
                        exit();
                    } else {
                        // No encontrado
                        echo "<script>alert('No se encontró usuario con esas credenciales.'); window.history.back();</script>";
                        exit();
                    }
                }

            
            }
            //Método para hacer la eliminación del usuario
            public function eliminarUsuario() {
                $credencial = null;
                
                // Verificar si viene por GET (desde la tabla)
                if(isset($_GET['usuario'])){
                    $credencial = $_GET['usuario'];
                }
                // Verificar si viene por POST (desde el formulario de eliminación)
                elseif(isset($_POST['BajaUsuario_EliminarUsuario']) || (isset($_POST['accionEliminar']) && $_POST['accionEliminar'] === 'eliminarUsuario')){
                    $credencial = $_POST['idUsuario_BajaUSUARIO'];
                }
                
                // Validar que se haya recibido un usuario
                if (empty($credencial) || $credencial === "") {
                    $mensaje = "Debe proporcionar un usuario para eliminar.";
                    $resultadoExito = false;
                    include_once(__DIR__ . '/../Views/gestionesGeneralesUsuarios/GestionesUsuarios.php');
                    exit();
                }
                
                // Realizar la eliminación
                $rowsDeleted = $this->modelUser->eliminarUsuario($credencial);
                
                if($rowsDeleted >= 1){
                    $mensaje = "La eliminación del usuario $credencial fue correcta.";
                    $resultadoExito = true;
                } else {
                    $mensaje = "Error al eliminar el usuario.";
                    $resultadoExito = false;
                }
                
                include_once(__DIR__ . '/../Views/gestionesGeneralesUsuarios/GestionesUsuarios.php');
                exit();
            }

        }
        
        //Realizamos las validaciones
        // Realizamos la instancia del método de inserción
        $db = new Connection_BD();
        $conn = $db->getConnection(); 

        if (!isset($db) || !$db) {
            die("Error: La conexión a la base de datos no está definida.");
        }

        //Inclusión según parámetro 'action' en la URL del mismo CONTROLADOR
        $controladorUsuario = new UserController($conn);
        if(isset($_GET['action'])){
            $action = $_GET['action'];
            switch($action){
                case 'loginUsuario':
                    $controladorUsuario -> loginUsuario();
                    break;
                case 'logoutUsuario':
                    $controladorUsuario -> logoutUsuario();
                    break;
                case 'registroUsuario':
                    $controladorUsuario -> registrarUsuario();
                    break;
                case 'consultarUsuario':
                    $controladorUsuario -> consultarUsuario();
                    break;
                case 'updateUsuarioID':
                    $controladorUsuario -> actualizarUsuario();
                    break;
                case 'buscarUsuario':
                    $controladorUsuario -> buscarUsuario();
                    break;
                case 'eliminarUsuario':
                    $controladorUsuario -> eliminarUsuario();
                    break;
                case 'deleteUsuario':
                    $controladorUsuario -> eliminarUsuario();
                    break;
                default:
                    // Acción no reconocida, mandar a inicio
                    header("Location: /IdentiQR/index.html");
                    exit();
                    break;
            }
        } else {
            // Si no hay acción definida, intentar login por defecto (o mandar a inicio)
            $controladorUsuario -> loginUsuario(); 
        }
    ?>
