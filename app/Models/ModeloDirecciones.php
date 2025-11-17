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

            /*PASAMOS LOS PARÁMETROS - CON ATRIBUTOS DE LA CLASE TramiteServicio(falta crear)*/
            /*
            $Matricula = $unTramite->getMatricula();
            $idTramite = (int)$unTramite->getIdTramite();
            $descripcion = $unTramite->getDescripcion();
            $estatusT = $unTramite->getEstatusT();
            */
            /*PASAMOS LOS PARÁMETROS - CON VARIABLES*/
            //$stmt->bind_param("siss",$Matricula,$idTramite,$descripcion,$estatusT);
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
            $statement = $this->conn->prepare($sql_statement);
            
            if (!$statement) {
                throw new Exception("Error en prepare(): " . $this->conn->error);
            }

            $statement->bind_param("s", $Matricula);
            
            if (!$statement->execute()) {
                throw new Exception("Error en execute(): " . $statement->error);
            }
            
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
            $statement->bind_param("i", $idDepto);

            if (!$statement->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            $result = $statement->get_result();
            return $result; //Regresa el resultado de la consulta
        }

        public function consultarPorTipoTramite($idTramite){
            //Lógica 
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
            $statement->bind_param("i", $idTramite);

            if (!$statement->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            $result = $statement->get_result();
            return $result; //Regresa el resultado de la consulta
        }

        //Realiza una consulta específica a un trámite dentro de la tabla por Folio
        public function consultarTramitePorFolio($FolioRegistro){
            $sql_statement = "SELECT * FROM registroservicio 
                            WHERE FolioRegistro = ? OR FolioSeguimiento = ? 
                            ORDER BY registroservicio.FechaHora DESC;";
            $statement = $this->conn->prepare($sql_statement);
            
            if (!$statement) {
                throw new Exception("Error en prepare(): " . $this->conn->error);
            }

            $statement->bind_param("ss", $FolioRegistro, $FolioRegistro);
            
            if (!$statement->execute()) {
                throw new Exception("Error en execute(): " . $statement->error);
            }
            
            $result = $statement->get_result();
            return $result; //Regresa el resultado de la consulta
        }
        /*Funciones para realizar las eliminaciones/bajas del trámite*/
        public function cancelarTramiteFR($FolioRegistro){
            $sql_statement = "CALL cancelarTramite(?,@eliminado)";
            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                echo("Connection_BD->cancelarTramite prepare error: " . $this->conn->errno);
                return false;
            }

            $stmt->bind_param("i", $FolioRegistro);
            if (!$stmt->execute()) {
                echo("Connection_BD->cancelarTramite execute error: " . $stmt->error);
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
        public function cancelarTramiteFS($FolioSeguimiento){
            $sql_statement = "CALL cancelarTramite2(?,@eliminado)";
            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                echo("Connection_BD->cancelarTramite prepare error: " . $this->conn->errno);
                return false;
            }

            $stmt->bind_param("s", $FolioSeguimiento);
            if (!$stmt->execute()) {
                echo("Connection_BD->cancelarTramite execute error: " . $stmt->error);
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

        /*Funciones para hacer el la acrualización/update/modificación del trámite*/
        public function actualizarTramite($descripcion, $estatus, $FolioRegistro, $FolioSeguimiento){
            $sql_statement = "UPDATE registroservicio set descripcion = ?, estatusT = ? where (FolioRegistro = ?) or (FolioSeguimiento = ?)";
            //Crear el statement
            $statement = $this -> conn -> prepare($sql_statement);
            $statement -> bind_param("ssis", $descripcion, $estatus, $FolioRegistro, $FolioSeguimiento);
            return $statement -> execute();
        }


        /*Apatir de acá se encontrarán las funciones para Generar los reportes para cada dirección.*/
        //idDepto = 2; Dirección acádemica
        //idDepto = 3; Servicios escolares
        //idDepto = 4; Dirección Desarrollo Academico
        //idDepto = 5; Dirección Asuntos Estudiantiles
        //idDepto = 6; Consultorio de atención de primer contacto
        public function reporteInd_DirMed($consultaReporte,$fe1,$fe2,$gen, $idDepto){
            // Preparar la llamada
            $sql = "CALL reporteInd_DirMed(?,?,?,?,?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log("Connection_BD->reporteInd_DirMed prepare error: " . $this->conn->error);
                return false;
            }

            // Forzar tipos
            $opc = (int)$consultaReporte;
            $idD = (int)$idDepto;
            // Bind seguro
            if (!$stmt->bind_param("isssi", $opc, $fe1, $fe2, $gen, $idD)) {
                error_log("Connection_BD->reporteInd_DirMed bind_param error: " . $stmt->error);
                $stmt->close();
                return false;
            }

            if (!$stmt->execute()) {
                error_log("Connection_BD->reporteInd_DirMed execute error: " . $stmt->error);
                $stmt->close();
                return false;
            }

            $allSets = [];

            // ---- IMPORTANTE: usar las funciones del propio stmt para moverse entre result sets ----
            // Repetimos mientras haya result sets producidos por el SP
            do {
                // Intentamos obtener el result set actual con get_result() (requiere mysqlnd)
                $rows = [];

                if (method_exists($stmt, 'get_result')) {
                    $res = $stmt->get_result();
                    if ($res !== false && $res !== null) {
                        // fetch_all si hay filas
                        $rows = $res->fetch_all(MYSQLI_ASSOC);
                        $res->free();
                    } else {
                        // get_result devolvió false/null: posible SELECT sin filas o problema
                        // dejamos $rows = [] para mantener consistencia de sets
                        $rows = [];
                    }
                } else {
                    // Si get_result no existe: intentar store_result y bind_result dinámico es complejo.
                    // Para no romper la conexión devolvemos array vacío para este set.
                    // (Si necesitas compatibilidad total sin mysqlnd usa la versión multi_query fallback.)
                    $rows = [];
                }

                $allSets[] = $rows;

                // Avanzar al siguiente result set **usando el stmt** (no la conexión)
                // next_result() mueve al siguiente result set y devuelve TRUE si existía.
                // more_results() consulta si hay más.
                // NOTA: usar aquí $stmt en vez de $this->conn evita "commands out of sync".
            } while ($stmt->more_results() && $stmt->next_result());

            // Cerrar stmt
            $stmt->close();

            return $allSets;    
        }
        //idDepto = 7; Vinculación
    }
?>