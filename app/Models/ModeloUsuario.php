<?php
    //Aquí creamos el modelo del usuario
    class UsuarioModel {
        private $conn;
        //Construcción del contructor del modelo
        public function __construct($conn){
            $this->conn = $conn;
        }
        public function registrarUsuario(Usuario $unUsuario){
            //Lógica para las acciones dentro de la base de datos
            //Codigo para hacer el registro de usuarios
            //$sql_statement = "CALL registrarUsuariosSP(?, ?, ?, ?, ?, ?, ?, ?)"; // Aquí iría la llamada al procedimiento almacenado para registrar usuario 
            $sql_statement = "INSERT INTO Usuario (nombre, apellido_paterno, apellido_materno,genero, usr, email, passw, rol, idDepto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                die("Error al preparar la llamada: " . $this->conn->error);
            }

            $nombre = $unUsuario->getNombre();
            $apellido_paterno = $unUsuario->getApellidoPaterno();
            $apellido_materno = $unUsuario->getApellidoMaterno();
            $genero = $unUsuario->getGenero();
            $usr = $unUsuario->getUsr();
            $email = $unUsuario->getEmail();
            $passw = $unUsuario->getPassw();
            $rol = $unUsuario->getRol();
            $idDepto = (int)($unUsuario->getIdDepto());

            $stmt->bind_param("ssssssssi", 
                $nombre,
                $apellido_paterno,
                $apellido_materno,
                $genero,
                $usr,
                $email,
                $passw,
                $rol,
                $idDepto
            );

            // Ejecutar el procedimiento
            if (!$stmt->execute()) {
                die("Error al ejecutar registrarUsuarioSP: " . $stmt->error);
            }

            // Obtener el ID del usuario recién insertado
            // Obtener el id insertado usando la conexión (disponible aquí)
                $lastId = $this->conn->insert_id;
                $stmt->close();

                // Recuperar la fila completa insertada — trigger BEFORE INSERT ya ajustó usr y FechaRegistro
                $select = "SELECT * FROM Usuario WHERE id_usuario = ?";
                $stmt2 = $this->conn->prepare($select);
                if (!$stmt2){
                    die("Prepare select error: " . $this->conn->error);
                }
                $stmt2->bind_param("i", $lastId);
                if (!$stmt2->execute()){
                    die("Execute select error: " . $stmt2->error);
                }
                $res = $stmt2->get_result();
                if ($row = $res->fetch_assoc()) {
                    // Actualizamos el objeto Usuario con los valores reales de la BD
                    $unUsuario->setIdUsuario((int)$row['id_usuario']);
                    $unUsuario->setUsr($row['usr']);
                    $unUsuario->setFechaRegistro($row['FechaRegistro']);
                    $unUsuario->setPassw($row['passw']); // Limpiamos la contraseña por seguridad y la encriptamos 
                    // (Opcional) actualizar otros campos si quieres sincronizar todo
                } else {
                    echo "No se pudo recuperar el usuario insertado.";
                }
                $stmt2->close();
            return $unUsuario; // Retorno del objeto Usuario con los datos actualizados
        }

        public function loginUsuarioSP(string $usr, string $pw): Array /*String*/|bool 
        {
            //Codigo para hacer el login
            $sql = "call Login(?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                echo("Connection_BD->loginUsuario prepare error: " . $this->conn->error);
                return false;
            }

            $stmt->bind_param("ss", $usr, $pw);
            if (!$stmt->execute()) {
                echo("Connection_BD->loginUsuario execute error: " . $stmt->error);
                return false;
            }

            $res = $stmt->get_result();
            if ($res && $row = $res->fetch_assoc()) {
                $stmt->close();
                // Devuelve el rol del usuario
                //return $row['rol']; String
                return [
                    'idUsuario' => $row['idUsuario'] ?? null,
                    'usr'       => $row['usr'] ?? null,
                    'email'     => $row['email'] ?? null,
                    'rol'       => $row['rol'] ?? null
                ];
            }

            $stmt->close();
            return false;
        }

        
        /*Función para la modificación de un Usuario*/
        public function actualizarUsuario(Usuario $unUsuario){
            //Statement
            $sql_statement = "UPDATE usuario set nombre = ?, apellido_paterno = ?, apellido_materno = ?, /*genero = ?,*/ email = ?, passw = ?, rol = ?, idDepto = ? where id_usuario = ?";
            //Preparar el statement
            $stmt = $this ->conn->prepare($sql_statement);
            //Pasamos los parametros
            if (!$stmt) {
                die("Error al preparar actualización: " . $this->conn->error);
            }
            
            // Obtenemos los valores del objeto Usuario
            $nombre = $unUsuario->getNombre();
            $apellido_paterno = $unUsuario->getApellidoPaterno();
            $apellido_materno = $unUsuario->getApellidoMaterno();
            //$genero = $unUsuario->getGenero();
            $email = $unUsuario->getEmail();
            $passw = $unUsuario->getPassw();
            $rol = $unUsuario->getRol();
            $idDepto = (int)$unUsuario->getIdDepto();
            $id_usuario = (int)$unUsuario->getIdUsuario();

            // Enlazamos parámetros
            //$stmt->bind_param("sssssssii"
            $stmt->bind_param("ssssssii",
                $nombre,
                $apellido_paterno,
                $apellido_materno,
                //$genero,
                $email,
                $passw,
                $rol,
                $idDepto,
                $id_usuario
            );
            // Ejecutamos la actualización
            if (!$stmt->execute()) {
                die("Error al ejecutar actualización: " . $stmt->error);
            }

            $stmt->close();
            return true; // o puedes retornar el objeto actualizado
        }

        /*Función para la consulta del usuario */
        public function consultarUsuario($credencial){
            //Statement
            $sql_statement = "SELECT * FROM usuario where (usr = ? or email = ? or id_usuario = ?)";
            //Preparar el statement
            $stmt = $this -> conn ->prepare($sql_statement);
            //Pasamos el parametro
            $stmt -> bind_param("ssi", $credencial, $credencial, $credencial);
            $stmt -> execute();
            return $result = $stmt -> get_result();
            //return ($result -> fetch_assoc()); //Regresa un solo registro como un arreglo asociativo
        }

        /*Función para la consulta del usuario POR ID */
        public function consultarUsuarioPorID($credencial){
            //Statement
            $sql_statement = "SELECT * FROM usuario where (id_usuario = ?) or (email = ?) or (usr = ?)";
            //Preparar el statement
            $stmt = $this -> conn ->prepare($sql_statement);
            //Pasamos el parametro
            $stmt -> bind_param("iss", $credencial, $credencial, $credencial);
            $stmt -> execute();
            $result = $stmt -> get_result();
            return ($result -> fetch_assoc()); //Regresa un solo registro como un arreglo asociativo
        }
        public function obtenerTodosUsuarios() {
            $sql_statement = "SELECT * FROM usuario";
            $result = $this -> conn -> query($sql_statement);
            return $result; //Regresa el resultado de la consulta
        }
        /*Función para la eliminación de un usuario (Se puede eliminar por ID, por Correo o Usuario)*/
        public function eliminarUsuario($credencial){
            //Codigo para hacer el login
            $sql_statement = "call darBajaUsuario(?, @eliminado)";
            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                echo("Connection_BD->darBajaUsuario prepare error: " . $this->conn->errno);
                return false;
            }

            $stmt->bind_param("s", $credencial);
            if (!$stmt->execute()) {
                echo("Connection_BD->darBajaUsuario execute error: " . $stmt->error);
                return false;
            }
            $stmt->close();

            $res = $this->conn->query("SELECT @eliminado AS eliminado");
            if ($res && $row = $res->fetch_assoc()) {
                return (int)$row['eliminado'];
                $stmt->close();
            }
            return 0;
        }


    }

