<?php

    class ReportModel{
        private $conn;

        public function __construct($conn){
            $this->conn = $conn;
        }

        /*Apatir de acá se encontrarán las funciones para Generar los reportes para cada dirección.*/
        //idDepto = 1; Administrador general - Sin dirección asociada
        public function reporteGeneral_Admin(){
            $statement_query = "SELECT registroservicio.*,serviciotramite.Descripcion as Descripcion, serviciotramite.idDepto as idDepto, departamento.Nombre as NombreDepto FROM registroservicio inner join serviciotramite on registroservicio.idTramite = serviciotramite.idTramite inner join departamento on servicioTramite.idDepto = departamento.idDepto;";    
            //$sql_query = "SELECT *, count(idDepto) as Cantidad FROM registroservicio inner join serviciotramite on registroservicio.idTramite = serviciotramite.idTramite group by serviciotramite.idDepto;"

            $result = $this->conn->query($statement_query);
            
            $data = []; //Declaramos el arreglo vacio para los datos que se encuentran en la consulta

            //Hacemos el ciclo que recuperará la información
            while($row = $result ->fetch_assoc()){
                //Arreglos dentro de arreglos
                $data[] = [$row['FolioRegistro'], $row['Matricula'],$row['idTramite'],$row['FechaHora'],$row['descripcion'],$row['estatusT'],$row['FolioSeguimiento'],$row['Descripcion'],(int)$row['idDepto'], $row['NombreDepto']];
            }

            //Retornamos el arreglo
            return $data;
        }
        public function reporteGeneral2_Admin_Pastel(){
            $sql_statement = "select departamento.Nombre, count(*) as Total FROM USUARIO INNER JOIN departamento ON usuario.idDepto = departamento.idDepto group by usuario.idDepto;";
            $result = $this -> conn -> query($sql_statement);
            $data = [];
            while ($row = $result -> fetch_assoc()){
                $data[] = [$row['Nombre'], (int) $row['Total']];
            }
            return $data;
        }


        public function reporteGeneral2_Usuarios_Admin(){
            $statement_query = "SELECT usuario.*, departamento.Nombre as NombreDepto, departamento.Descripcion as DescripcionDepto FROM USUARIO INNER JOIN departamento ON usuario.idDepto = departamento.idDepto;";

            $result = $this->conn->query($statement_query);
            
            $data = []; //Declaramos el arreglo vacio para los datos que se encuentran en la consulta

            //Hacemos el ciclo que recuperará la información
            while($row = $result ->fetch_assoc()){
                //Arreglos dentro de arreglos
                $data[] = [$row['id_usuario'], $row['nombre'],$row['apellido_paterno'],$row['apellido_materno'],$row['genero'],$row['usr'],$row['email'],$row['passw'],$row['rol'],(int)$row['idDepto'],$row['FechaRegistro'],$row['NombreDepto'],$row['DescripcionDepto']];
            }

            //Retornamos el arreglo
            return $data;
        }
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

        /*ESTA FUNCIÓN PERMITE REALIZAR UN CONTEO DE LA CANTIDAD DIARIA DE CITAS*/
        public function reporteDiario_Grafico($fe, $idDepto){
            $sql = "SELECT count(registroservicio.FechaHora) as Cantidad from registroservicio left join serviciotramite on 
					registroservicio.idTramite = serviciotramite.idTramite
                    where date(FechaHora) = ? and idDepto = ?;";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log("Connection_BD->reportePorDia_DirMed prepare error: " . $this->conn->error);
                return false;
            }

            // Forzar tipos
            $idD = (int)$idDepto;
            // Bind seguro
            if (!$stmt->bind_param("si", $fe, $idD)) {
                error_log("Connection_BD->reportePorDia_DirMed bind_param error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            $stmt->bind_param("si", $fe, $idD);
            if (!$stmt->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            //return $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            $count = isset($row['Cantidad']) ? (int)$row['Cantidad'] : 0;
            return $count; //Regresa el resultado de la consulta
        }

        public function reportePorDia_DirMed($fe, $idDepto){
            $sql = "SELECT
                a.Matricula,
                a.Nombre,
                a.ApePat,
                a.ApeMat,
                a.FechaNac,
                a.FeIngreso,
                a.Correo,
                a.Telefono,
                a.Ciudad,
                a.Estado,
                a.Genero,
                im.TipoSangre,
                im.Alergias,
                im.contacto_Emergencia,
                c.idCarrera,
                rs.folioRegistro AS IdRegistro,
                rs.descripcion AS DescripcionServ,
                rs.EstatusT AS EstatusServ,
                rs.FolioSeguimiento,
                rs.FechaHora AS FechaHoraServ,
                st.idTramite AS idTramite,
                st.idDepto AS idDepto
                FROM alumno a
                LEFT JOIN carrera c ON a.idCarrera = c.idCarrera
                LEFT JOIN informacionmedica im ON a.Matricula = im.Matricula
                LEFT JOIN registroservicio rs ON a.Matricula = rs.Matricula
                LEFT JOIN serviciotramite st ON st.idTramite = rs.idTramite
                WHERE
                    (date(rs.FechaHora) = ?)
                AND
                    (st.idDepto = ?)
                ORDER BY rs.FechaHora DESC;";
            
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                error_log("Connection_BD->reportePorDia_DirMed prepare error: " . $this->conn->error);
                return false;
            }

            // Forzar tipos
            $idD = (int)$idDepto;
            // Bind seguro
            if (!$stmt->bind_param("si", $fe, $idD)) {
                error_log("Connection_BD->reportePorDia_DirMed bind_param error: " . $stmt->error);
                $stmt->close();
                return false;
            }
            $stmt->bind_param("si", $fe, $idD);
            if (!$stmt->execute()) {
                // Manejo de error si falla la ejecución
                throw new Exception("Error en execute(): " . $statement->error);
            }
            //return $stmt->execute();
            $result = $stmt->get_result();
            return $result; //Regresa el resultado de la consulta
        }
        //idDepto = 7; Vinculación
    }
?>