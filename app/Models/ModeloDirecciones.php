<?php
    class DireccionesModel{
        private $conn;

        /*Metodo constructor del modelo de las Direcciones */
        public function __construct($conn){
            $this -> conn = $conn;
        }

        /*Funciones para generar los tramites.*/
        public function registrarTramite(){
            $sql_statement = "INSERT INTO registroservicio (Matricula,idTramite,descripcion,estatusT) VALUES (?,?,?,?)";

            $stmt = $this->conn->prepare($sql_statement);
            if (!$stmt) {
                die("Error al preparar la llamada: " . $this->conn->error);
            }

            /*PASAMOS LOS PARAMETROS - CON ATRIBUTOS DE LA CLASE TramiteServicio(falta crear)*/
            /*
            $Matricula = $unTramite->getMatricula();
            $idTramite = (int)$unTramite->getIdTramite();
            $descripcion = $unTramite->getDescripcion();
            $estatusT = $unTramite->getEstatusT();
            */
            /*PASAMOS LOS PARAMETROS - CON VARIABLES*/
            $stmt->bind_param("siss",$Matricula,$idTramite,$descripcion,$estatusT);
            return ($stmt->execute()); //Retornamos la ejecución.
        }

        /*Funciones para realizar la consulta de un Tramite o Todos*/
        //Realiza una consulta GENERAL A TODA LA TABLA
        public function consultarTramites(){
            $sql_statement = "SELECT * FROM registroservicio";
            $result = $this -> connection -> query($sql_statement);
            return $result; //Regresa el resultado de la consulta
        }
        //Realiza una consulta GENERAL a toda la tabla, DONDE se encuentren con un DEPARTAMENTO ESPECIFICO
        public function consultarTramitesPorDEPTO($Matricula){
            $sql_statement = "SELECT * FROM registroservicio
                            WHERE Matricula = ? ORDER BY registroservicio.FechaHora DESC;";
            $statement = $this -> connection -> prepare($sql_statement);

            $statement -> bind_param("i", $Matricula);
            $statement -> execute();
            $result = $stmt->get_result();
            //$rows = $result->fetch_all(MYSQLI_ASSOC);
            return $result; //Regresa el resultado de la consulta
            //return $rows;
        }
        //Realiza una consulta especifica a un TRAMITE dentro de la tabla
        public function consultarTramitePorID($Folio){
            //Lógica para la aplicación dentro de la base de datos
            $sql_statement = "SELECT * FROM registroservicio where (FolioRegistro = ?) or (FolioSeguimiento = ?)";
            $statement = $this -> connection -> prepare($sql_statement);
            //Validar si el $Folio es numerico
            if(is_numeric($Folio)){
                $FolioRegistro = $Folio;
            }

            $statement -> bind_param("is", $FolioRegistro, $Folio);
            $statement -> execute();
            $result = $statement -> get_result();
            return ($result -> fetch_assoc()); //Regresa un solo registro como un arreglo asociativo
        }
        //Realiza una consulta GENERAL a toda la tabla, DONDE se haya realizado POR UN MISMO ALUMNO.

        /*Funciones para realizar las eliminaciones/bajas del tramite*/
        public function cancelarTramite($FolioRegistro){
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
    }




?>