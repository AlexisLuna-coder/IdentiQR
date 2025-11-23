    <?php
        /*Desarrolladores
        Barreto Basave Lizbeth
        Sanchez Luna Alexis Sebastian*/
        require_once __DIR__ . '/../../config/Connection_BD.php';
        require_once __DIR__ . '/../../public/PHP/Usuario.php';
        require_once __DIR__ . '/../Models/ModeloUsuario.php';     
        Include_once __DIR__ . '/../../public/PHP/repositorioPHPMailer/enviarCorreo.php';

        class UserController {
            private $modelUser;
            public function __construct($conn){
                $this->modelUser = new UsuarioModel($conn);
                //Se crea una instancia del modelo
            }

            public function registrarUsuario(){
                

                if(isset($_POST['enviarRegistro_Usuario'])) {
                    $Usuario =  new Usuario(
                        $_POST['nombre'],
                        $_POST['apellido_paterno'],
                        $_POST['apellido_materno'],
                        $_POST['genero'],
                        $_POST['email'],
                        $_POST['passw'],
                        $_POST['rol'],
                        (int)$_POST['idDepto']
                    );
                    
                    $usrN = $this -> modelUser -> registrarUsuario($Usuario);

                    if($usrN != null) {
                        echo "Se registro adecuadamente el usuario";
                        /*USANDO EL REPOSITORIO DE PHPMAILER - VEAMOS SI FUNCIONA */
                        enviarCorreo($usrN);
                        /**********************************************************/
                        //Incluimos la vista del formulario
                        header("Location: /IdentiQR/app/Controllers/ControladorUsuario.php?action=consultarUsuario");
                        exit();
                    }
                }
            }
            
            public function consultarUsuario(){
                //$result = [];
                $result = null;
                $mostrarMensajeBusqueda = false;
                $mensajeBusqueda = '';
                $accion = $_POST['accion'] ?? '';
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
            
            //Método para iniciar sesion
            public function loginUsuario(){
                $statusAlert = null; // Variable para controlar la alerta //Nuevo: 2025-11-20 12:00am
                if (isset($_POST['enviarLogin'])) {
                    $usuario = trim($_POST['usuario']) ?? '';
                    $password = trim($_POST['password']) ?? '';

                    $loginUSR = $this -> modelUser -> loginUsuarioSP($usuario, $password);
                    if (!$loginUSR) {
                        // En lugar de hacer solo echo, redirigimos al formulario con un flag de error
                        //header("Location: ../Views/Login.php?error=1");
                        $statusAlert = 'error_credenciales';  //Nuevo: 2025-11-20 12:00am
                        include_once __DIR__ . '/../Views/Login.php'; //Nuevo: 2025-11-20 12:00am
                        //exit();
                    } else {
                        session_start();
                        //$_SESSION['rol'] = $loginUSR; //String
                        //ARREGLO
                        $_SESSION['idUsuario'] = $loginUSR['idUsuario'];
                        $_SESSION['email']     = $loginUSR['email'];
                        $_SESSION['rol']       = $loginUSR['rol'];
                        $_SESSION['usr']       = $loginUSR['usr'];
                        // Redirigir al script de redirección según rol
                        header("Location: /IdentiQR/app/Views/controlAccesoUsuario.php"); //
                        exit();
                    }
                } else {
                    //Incluimos la clase de la vista
                    //header("Location: /IdentiQR/index.html");
                    // Si intentan entrar directo sin POST
                    header("Location: /IdentiQR/index.html");
                }
            }
            //Método para cerrar sesion
            public function logoutUsuario(){
                session_start();
                session_unset();
                session_destroy();
                header("Location: /IdentiQR/app/Views/Login.php");
                exit();
            }
            //Método para modificar datos de un usuario
            public function actualizarUsuario(){
                if(isset($_GET['id']) && is_numeric($_GET['id'])){
                    $id_browser = (int) $_GET['id'];
                    //Llamar al método el modelo para hacer la consulta
                    $row = $this -> modelUser -> consultarUsuarioPorID($id_browser);
                    //echo "<script>alert('Consulta realizada correctamente.');</script>";

                    include_once("../Views/gestionesGeneralesUsuarios/modificacionUsuario.php");
                    return;
                }
                /*EVALUAMOS SI ESTÁ VACÍO LA ACTUALIZACIÓN*/
                if(isset($_POST['actualizarDatosUSER'])){
                    /*Creamos un constructor del USUARIO y mandamos la info nuevamente*/
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
                    $usuario->setIdUsuario((int)$_POST['id_usuario']);
                    $resultado = $this->modelUser->actualizarUsuario($usuario);
                    if ($resultado) {
                        echo "<script>alert('Usuario actualizado correctamente.'); window.location.href='/IdentiQR/app/Controllers/ControladorUsuario.php?action=consultarUsuario&consultarTodo=1';</script>";
                    } else {
                        echo "<script>alert('Error al actualizar el usuario.');</script>";
                    }

                    /*UPDATE usuario set nombre = ?, apellido_paterno = ?, apellido_materno = ?, genero = ?, email = ?, passw = ?, rol = ?, idDepto = ? where id_usuario = ? */
                }


                return;
            }

            //Método para buscar al usuario
            public function buscarUsuario(){
                if(isset($_POST['buscarUsuarioBtn'])){
                    $credencial = trim($_POST['idUsuario_Buscar']);
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
                    header("Location: /IdentiQR/index.html");
                    exit();
                    break;
            }
        } else {
            $controladorUsuario -> loginUsuario(); 
        }
    ?>
