<?php
    class DireccionesModel{
        private $conn;

        /*Método constructor del modelo de las Direcciones */
        public function __construct($conn){
            $this -> conn = $conn;
        }

        /*Funciones para generar los trámites.*/
        public function registrarTramite($Matricula,$idTramite,$descripcion){
            //$sql_statement = "INSERT INTO registroservicio (Matricula,idTramite,descripcion,estatusT) VALUES (?,?,?,?)";
            $sql_statement = "INSERT INTO registroservicio (Matricula,idTramite,descripcion) VALUES (?,?,?)";

            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                die("Error al preparar la llamada: " . $this->conn->error);
            }

            $stmt->bind_param("sis",$Matricula,$idTramite,$descripcion);
            return ($stmt->execute()); //Retornamos la ejecución.
        }

        /*Funciones para realizar la consulta de un trámite o todos*/
        //Realiza una consulta general a toda la tabla. Verifica que tramítes tiene
        public function consultarTramites(){
            $sql_statement = "SELECT * FROM registroservicio";
            $result = $this -> connection -> query($sql_statement);
            return $result; //Regresa el resultado de la consulta
        }
        
        //Realiza una consulta general a toda la tabla, donde se encuentren con un ALUMNO ESPECÍFICO
        public function consultarTramitesPorMatricula($Matricula){
            $sql_statement = "SELECT * FROM registroservicio
                            WHERE Matricula = ? ORDER BY registroservicio.FechaHora DESC;";
            //prepara la sentencia
            $statement = $this->conn->prepare($sql_statement);
            
            // Verifica si la preparación de la consulta falló
            if (!$statement) {
                throw new Exception("Error en prepare(): " . $this->conn->error);
            }

            //Vinculacionb de variables
            $statement->bind_param("s", $Matricula);
            
            // Ejecuta la consulta y verifica al mismo tiempo si hubo error
            if (!$statement->execute()) {
                throw new Exception("Error en execute(): " . $statement->error);
            }

            // Obtiene el conjunto de filas resultantes de la base de datos
            $result = $statement->get_result();
            return $result; //Regresa el resultado de la consulta
        }

        //Realiza una consulta general a toda la tabla, donde se encuentren con un DEPARTAMENTO ESPECÍFICO
        public function consultarTramitesPorDepto($idDepto){
            // SQL: unimos registroservicio con serviciotramite para filtrar por idDepto
            $sql_statement = "SELECT registroservicio.* FROM registroservicio INNER JOIN serviciotramite
                    ON registroservicio.idTramite = serviciotramite.idTramite
                    WHERE serviciotramite.idDepto = ? ORDER BY registroservicio.FechaHora DESC;";

            $statement = $this->conn->prepare($sql_statement);
            if (!$statement) {
                // Manejo de error si falla la preparación
                throw new Exception("Error en prepare(): " . $this->conn->error);
            }

            // Asegurarse de que $idDepto sea entero
            $idDepto = (int)$idDepto;
            //vinculación de variables
            $statement->bind_param("i", $idDepto);

            if (!$statement->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            $result = $statement->get_result();
            return $result; //Regresa el resultado de la consulta
        }

        //función para consultar tramites
        public function consultarPorTipoTramite($idTramite){
            //sentencia SQL
            $sql_statement = "SELECT registroservicio.* FROM registroservicio INNER JOIN serviciotramite
                    ON registroservicio.idTramite = serviciotramite.idTramite
                    WHERE registroservicio.idTramite = ? ORDER BY registroservicio.FechaHora DESC;";

            $statement = $this->conn->prepare($sql_statement);

            if (!$statement) {
                // Manejo de error si falla la preparación
                throw new Exception("Error en prepare(): " . $this->conn->error);
            }

            // Asegurarse de que $idTramite sea entero
            $idTramite = (int)$idTramite;
            //vinculación de variables
            $statement->bind_param("i", $idTramite);

            if (!$statement->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }

            // Obtiene el conjunto de filas resultantes de la base de datos
            $result = $statement->get_result();
            return $result; //Regresa el resultado de la consulta
        }

        //Realiza una consulta específica a un trámite dentro de la tabla por Folio
        public function consultarTramitePorFolio($FolioRegistro){
            // Define la consulta SQL 
            $sql_statement = "SELECT * FROM registroservicio 
                            WHERE FolioRegistro = ? OR FolioSeguimiento = ? 
                            ORDER BY registroservicio.FechaHora DESC;";
            $statement = $this->conn->prepare($sql_statement);

            // Si falla la preparación, lanza una excepción
            if (!$statement) {
                throw new Exception("Error en prepare(): " . $this->conn->error);
            }

            //Vinculación de parametros
            $statement->bind_param("ss", $FolioRegistro, $FolioRegistro);
            
            //Ejecuta la consulta y verifica si hubo error
            if (!$statement->execute()) {
                throw new Exception("Error en execute(): " . $statement->error);
            }
            
            $result = $statement->get_result();
            return $result; //Regresa el resultado de la consulta
        }

        /*Funciones para realizar las eliminaciones/bajas del trámite*/
        public function cancelarTramiteFR($FolioRegistro){
            //llama al procedimiento
            $sql_statement = "CALL cancelarTramite(?,@eliminado)";
            //prepara la consulta
            $stmt = $this->conn->prepare($sql_statement);

            // Verifica errores de preparación
            if (!$stmt) {
                echo("Connection_BD->cancelarTramite prepare error: " . $this->conn->errno);
                return false;
            }

            //vinculación de variables
            $stmt->bind_param("i", $FolioRegistro);

            //Ejecuta la consulta y verifica si hubo error
            if (!$stmt->execute()) {
                echo("Connection_BD->cancelarTramite execute error: " . $stmt->error);
                return false;
            }

            // Cierra la sentencia
            $stmt->close();

            // Consulta el valor de la variable de salida
            $res = $this->conn->query("SELECT @eliminado AS eliminado");

            // Si la eliminación es exitosa se devuelve eliminado
            if ($res && $row = $res->fetch_assoc()) {
                return (int)$row['eliminado'];
                $stmt->close();
            }
            return 0;
        }

        //método para eliminar un tramite por folio de seguimiento
        public function cancelarTramiteFS($FolioSeguimiento){
            //llama al prodecimiento
            $sql_statement = "CALL cancelarTramite2(?,@eliminado)";
            //prepara la sentencia
            $stmt = $this->conn->prepare($sql_statement);

            // Manejo de errores de preparación
            if (!$stmt) {
                echo("Connection_BD->cancelarTramite prepare error: " . $this->conn->errno);
                return false;
            }

            //vinculación de variables
            $stmt->bind_param("s", $FolioSeguimiento);

            //Ejecuta la consulta y verifica si hubo error
            if (!$stmt->execute()) {
                echo("Connection_BD->cancelarTramite execute error: " . $stmt->error);
                return false;
            }
            //cierra el statement
            $stmt->close();

            // Consulta el valor de la variable de salida
            $res = $this->conn->query("SELECT @eliminado AS eliminado");
            //eliminación exitosa
            if ($res && $row = $res->fetch_assoc()) {
                return (int)$row['eliminado'];
                $stmt->close();
            }
            return 0; //fallo
        }

        /*Funciones para hacer el la acrualización/update/modificación del trámite*/
        public function actualizarTramite($descripcion, $estatus, $FolioRegistro, $FolioSeguimiento){
            //sentencia SQL
            $sql_statement = "UPDATE registroservicio set descripcion = ?, estatusT = ? where (FolioRegistro = ?) or (FolioSeguimiento = ?)";
            //Crear el statement
            $statement = $this -> conn -> prepare($sql_statement);
            //vinculación de parametros
            $statement -> bind_param("ssis", $descripcion, $estatus, $FolioRegistro, $FolioSeguimiento);
            // Ejecuta y devuelve verdadero si tuvo éxito o false si falló
            return $statement -> execute();
        }
    }
?>