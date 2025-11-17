<?php
    /*Realizamos la instancia del controlador */
    require_once __DIR__ . '/../../config/Connection_BD.php';
    require_once __DIR__ . '/../Models/ModeloDirecciones.php';
    require_once __DIR__ . '/../Models/ModeloAlumno.php';
    require_once __DIR__ . '/../../public/PHP/FPDF/fpdf.php';
    
    class DirectionsController{
        private $directionModel;
        private $alumnoModel;

        public function __construct($conn){
            $this->directionModel = new DireccionesModel($conn);
            $this->alumnoModel = new AlumnoModel($conn);
        }

        /*Funciones para la generación de los trÁmites. */
        //1. Función para ingresar dentro de TRáMITES
        public function registrarTramite(){
            //Obtenemos para ver las vistas
            $idDepto = (int)($_POST['idDepto'] ?? $_GET['idDepto'] ?? 1);
            //Validar que el botón fue enviado y tiene datos - DirAcademica
            if(isset($_POST['registrarTramite_dirDirACA'])){
                $matricula = $_POST['matriculaEscaneadoBD']; // Aquí se escaneará

                $idTramite = (int)$_POST['idTramite'];
                
                $fechaJustificante = $_POST['fechaJustificante'];
                $requisitos = $_POST['Requisitos'];

                /*AQUÍ SE RECUPERARÁN LOS DATOS DEL ALUMNO. */
                $resultDatos = $this->alumnoModel->recuperarDatosAlumnoPorMatricula($matricula);

                /*Hacemos la validación para recuperar los datos*/
                if($resultDatos){
                    $Nombre_AUX         = $resultDatos['Nombre'];
                    $ApePat_AUX         = $resultDatos['ApePat'];
                    $ApeMat_AUX         = $resultDatos['ApeMat'];
                    $FechaNac_AUX       = $resultDatos['FechaNac'];
                    $FeIngreso_AUX      = $resultDatos['FeIngreso'];
                    $Correo_AUX         = $resultDatos['Correo'];
                    $Direccion_AUX      = $resultDatos['Direccion'];
                    $Telefono_AUX       = $resultDatos['Telefono'];
                    $Ciudad_AUX         = $resultDatos['Ciudad'];
                    $Estado_AUX         = $resultDatos['Estado'];
                    $Genero_AUX         = $resultDatos['Genero'];
                    $idCarrera_AUX      = $resultDatos['idCarrera'];
                    $DescripcionCarrera_AUX = $resultDatos['descripcion']; // de tabla carrera
                    $PlanEstudios_AUX   = $resultDatos['planEstudios'];
                    $idDepto_AUX        = $resultDatos['idDepto'];         // de tabla carrera
                    $TipoSangre_AUX     = $resultDatos['TipoSangre'];
                    $Alergias_AUX       = $resultDatos['Alergias'];
                    $ContactoEmergencia_AUX = $resultDatos['contacto_emergencia'];
                    $FechaIngresoInfoMed_AUX = $resultDatos['fechaIngreso_InfoMed'];
                    $Cuatri_AUX          = $resultDatos['Cuatri'];          // función calcCuatrimestre()

                    //Concatenación de datos
                    $nombreCompleto = trim("$Nombre_AUX $ApePat_AUX $ApeMat_AUX");

                    //Validamos que tipo de servicio es
                    switch($idTramite){
                        case 11: 
                            $tram = "JUSTIFICANTE";
                            $fraseDia = "para el día";
                            break;
                        case 12:
                            $tram = "RECURSAMIENTO";
                            $fraseDia = "el día";
                            break;
                        default:
                            $tram = "JUSTIFICANTE";
                            $fraseDia = "para el día";
                            break;
                    }

                    // Generamos la descripción
                    $descripcionTotal = sprintf(
                        "El Alumno [%s] con matrícula [%s] del cuatrimestre [%s] de la carrera [%s] solicitó un <%s> %s [%s]. Requisitos o notas [%s]",
                        $nombreCompleto,
                        $matricula,
                        $Cuatri_AUX,
                        $DescripcionCarrera_AUX,
                        $tram,
                        $fraseDia,
                        $fechaJustificante,
                        $requisitos
                    );
                    $insert = $this->directionModel -> registrarTramite($matricula, $idTramite, $descripcionTotal);
                    
                    if($insert){
                        //echo "<br>Registro exitoso";
                    } else {
                        //echo "<br>Error en el registro";
                    }
                } else {
                    echo "<br>Error: No se encontró el alumno con la matrícula proporcionada";
                }
            }
            
            //Validar que el botón fue enviado y tiene datos - dirDAE
            if(isset($_POST['registrarTramite_dirDAE'])){
                $matricula = $_POST['matriculaEscaneadoBD']; // Aquí se escaneara

                $idTramite = (int)$_POST['idTramite'];
                
                $seleccionExtra = $_POST['seleccionExtra'];
                /*AQUÍ SE RECUPERARÁN LOS DATOS DEL ALUMNO. */
                $resultDatos = $this->alumnoModel->recuperarDatosAlumnoPorMatricula($matricula);

                /*Hacemos la validación para recuperar los datos*/
                if($resultDatos){
                    $Nombre_AUX         = $resultDatos['Nombre'];
                    $ApePat_AUX         = $resultDatos['ApePat'];
                    $ApeMat_AUX         = $resultDatos['ApeMat'];
                    $FechaNac_AUX       = $resultDatos['FechaNac'];
                    $FeIngreso_AUX      = $resultDatos['FeIngreso'];
                    $Correo_AUX         = $resultDatos['Correo'];
                    $Direccion_AUX      = $resultDatos['Direccion'];
                    $Telefono_AUX       = $resultDatos['Telefono'];
                    $Ciudad_AUX         = $resultDatos['Ciudad'];
                    $Estado_AUX         = $resultDatos['Estado'];
                    $Genero_AUX         = $resultDatos['Genero'];
                    $idCarrera_AUX      = $resultDatos['idCarrera'];
                    $DescripcionCarrera_AUX = $resultDatos['descripcion']; // de tabla carrera
                    $PlanEstudios_AUX   = $resultDatos['planEstudios'];
                    $idDepto_AUX        = $resultDatos['idDepto'];         // de tabla carrera
                    $TipoSangre_AUX     = $resultDatos['TipoSangre'];
                    $Alergias_AUX       = $resultDatos['Alergias'];
                    $ContactoEmergencia_AUX = $resultDatos['contacto_emergencia'];
                    $FechaIngresoInfoMed_AUX = $resultDatos['fechaIngreso_InfoMed'];
                    $Cuatri_AUX          = $resultDatos['Cuatri'];          // función calcCuatrimestre()

                    //Concatenación de datos
                    $nombreCompleto = trim("$Nombre_AUX $ApePat_AUX $ApeMat_AUX");
                    $requisitos = trim($Alergias_AUX. "- Sangre: $TipoSangre_AUX");

                    //Validamos que tipo de servicio es
                    switch($idTramite){
                        case 5: 
                            $tram = "Extracurricular";
                            $fraseDia = "Solicitó unirse al extracurricular";
                            break;
                        default:
                            $tram = "Extracurricular";
                            $fraseDia = "Solicitó unirse al extracurricular";
                            break;
                    }

                    // Generamos la descripción
                    $descripcionTotal = sprintf(
                        "El Alumno [%s] con matrícula [%s] del cuatrimestre [%s] de la carrera [%s] <%s> de [%s-%s]. Datos Médicos [$%s]",
                        $nombreCompleto,
                        $matricula,
                        $Cuatri_AUX,
                        $DescripcionCarrera_AUX,
                        $fraseDia,
                        $tram,
                        $seleccionExtra,
                        $requisitos
                    );
                    $insert = $this->directionModel -> registrarTramite($matricula, $idTramite, $descripcionTotal);
                    
                    if($insert){
                        //echo "<br>Registro exitoso";
                    } else {
                        //echo "<br>Error en el registro";
                    }
                } else {
                    echo "<br>Error: No se encontró el alumno con la matrícula proporcionada";
                }
            }

            //Valir que el botón fue enviado y tiene datos - dirDDA
            if(isset($_POST['registrarTramite_dirDDA'])){
                $matricula = $_POST['matriculaEscaneadoBD']; // Aquí se escaneara

                $idTramite = (int)$_POST['idTramite'];
                
                $cantTutorias = (int)$_POST['cantTutorias'];
                $cantTutoriasInd = isset($_POST['cantTutoriasInd']) ? (int)$_POST['cantTutoriasInd'] : 0;
                $tutor = trim($_POST['tutor']);
                
                /*AQUÍ SE RECUPERARÁN LOS DATOS DEL ALUMNO. */
                $resultDatos = $this->alumnoModel->recuperarDatosAlumnoPorMatricula($matricula);

                /*Hacemos la validación para recuperar los datos*/
                if($resultDatos){
                    $Nombre_AUX         = $resultDatos['Nombre'];
                    $ApePat_AUX         = $resultDatos['ApePat'];
                    $ApeMat_AUX         = $resultDatos['ApeMat'];
                    $FechaNac_AUX       = $resultDatos['FechaNac'];
                    $FeIngreso_AUX      = $resultDatos['FeIngreso'];
                    $Correo_AUX         = $resultDatos['Correo'];
                    $Direccion_AUX      = $resultDatos['Direccion'];
                    $Telefono_AUX       = $resultDatos['Telefono'];
                    $Ciudad_AUX         = $resultDatos['Ciudad'];
                    $Estado_AUX         = $resultDatos['Estado'];
                    $Genero_AUX         = $resultDatos['Genero'];
                    $idCarrera_AUX      = $resultDatos['idCarrera'];
                    $DescripcionCarrera_AUX = $resultDatos['descripcion']; // de tabla carrera
                    $PlanEstudios_AUX   = $resultDatos['planEstudios'];
                    $idDepto_AUX        = $resultDatos['idDepto'];         // de tabla carrera
                    $TipoSangre_AUX     = $resultDatos['TipoSangre'];
                    $Alergias_AUX       = $resultDatos['Alergias'];
                    $ContactoEmergencia_AUX = $resultDatos['contacto_emergencia'];
                    $FechaIngresoInfoMed_AUX = $resultDatos['fechaIngreso_InfoMed'];
                    $Cuatri_AUX          = $resultDatos['Cuatri'];          // función calcCuatrimestre()
                    $Periodo_AUX         = $resultDatos['Periodo'];
                    //Concatenación de datos
                    $nombreCompleto = trim("$Nombre_AUX $ApePat_AUX $ApeMat_AUX");
                    $requisitos = trim($Alergias_AUX. "- Sangre: $TipoSangre_AUX");
                    //Validamos que tipo de servicio es
                    switch($idTramite){
                        case 2: 
                            $tram = "Tutorias";
                            $fraseDia = "Asitio a tutorias GRUPALES: - ";
                            break;
                        default:
                            $tram = "Extracurricular";
                            $fraseDia = "Asitio a tutorias: - ";
                            break;
                    }

                    // Generamos la descripción
                    $descripcionTotal = trim(sprintf(
                        "El Alumno [%s] con matrícula [%s] del cuatrimestre [%s] con el Plan de estudio <%s> durante el periodo [%s] asistió [%s %d veces] y [%s - Individuales: %d veces] con el tutor/a: <%s>",
                        $nombreCompleto,        
                        $matricula,             
                        $Cuatri_AUX,            
                        $PlanEstudios_AUX,
                        $Periodo_AUX,           
                        $fraseDia,              
                        $cantTutorias,          
                        $tram,                  
                        $cantTutoriasInd,       
                        $tutor
                    ));
                    $insert = $this->directionModel -> registrarTramite($matricula, $idTramite, $descripcionTotal);
                    
                    if($insert){
                        //echo "<br>Registro exitoso";
                    } else {
                        //echo "<br>Error en el registro";
                    }
                } else {
                    echo "<br>Error: No se encontró el alumno con la matrícula proporcionada";
                }
            }

            //Valir que el botón fue enviado y tiene datos - dirMed
            if(isset($_POST['registrarTramite_dirMedica'])){
                $matricula = $_POST['matriculaEscaneadoBD']; // Aquí se escaneara
                $idTramite = (int)$_POST['idTramite'];
                $Temperatura =  (double)$_POST['temperatura'];
                $Altura =  (double)$_POST['altura'];
                $Peso = (double)$_POST['peso'];
                $fechaCita = $_POST['fechaCita'];
                $descripcionAdicional =  $_POST['descripcion'];
                /*AQUÍ SE RECUPERARÁN LOS DATOS DEL ALUMNO. */
                $resultDatos = $this->alumnoModel->recuperarDatosAlumnoPorMatricula($matricula);

                /*Hacemos la validación para recuperar los datos*/
                if($resultDatos){
                    $Nombre_AUX         = $resultDatos['Nombre'];
                    $ApePat_AUX         = $resultDatos['ApePat'];
                    $ApeMat_AUX         = $resultDatos['ApeMat'];
                    $FechaNac_AUX       = $resultDatos['FechaNac'];
                    $FeIngreso_AUX      = $resultDatos['FeIngreso'];
                    $Correo_AUX         = $resultDatos['Correo'];
                    $Direccion_AUX      = $resultDatos['Direccion'];
                    $Telefono_AUX       = $resultDatos['Telefono'];
                    $Ciudad_AUX         = $resultDatos['Ciudad'];
                    $Estado_AUX         = $resultDatos['Estado'];
                    $Genero_AUX         = $resultDatos['Genero'];
                    $idCarrera_AUX      = $resultDatos['idCarrera'];
                    $DescripcionCarrera_AUX = $resultDatos['descripcion']; // de tabla carrera
                    $PlanEstudios_AUX   = $resultDatos['planEstudios'];
                    $idDepto_AUX        = $resultDatos['idDepto'];         // de tabla carrera
                    $TipoSangre_AUX     = $resultDatos['TipoSangre'];
                    $Alergias_AUX       = $resultDatos['Alergias'];
                    $ContactoEmergencia_AUX = $resultDatos['contacto_emergencia'];
                    $FechaIngresoInfoMed_AUX = $resultDatos['fechaIngreso_InfoMed'];
                    $Cuatri_AUX          = $resultDatos['Cuatri'];          // función calcCuatrimestre()

                    //Concatenación de datos
                    $nombreCompleto = trim("$Nombre_AUX $ApePat_AUX $ApeMat_AUX");
                    $requisitos = trim($Alergias_AUX. "- Sangre: $TipoSangre_AUX");

                    //Validamos que tipo de servicio es
                    switch($idTramite){
                        case 13: 
                            $tram = "Cita Médica:";
                            $fraseDia = "Solicitó una";
                            break;
                        default:
                            $tram = "Cita Médica: ";
                            $fraseDia = "Solicitó una";
                            break;
                    }
                    // Generamos la descripción
                    $descripcionTotal = sprintf(
                        "El Alumno [%s] con matrícula [%s] del [%s] Cuatri de la carrera [%s] <%s> [%s - el día <%s>]. Datos médicos [Sangre: %s - Alergias: %s - Altura: %.2f - Peso: %.3f - Temperatura: %.2f°C]. Notas Adicionales: [%s]",
                        $nombreCompleto,
                        $matricula,
                        $Cuatri_AUX,
                        $DescripcionCarrera_AUX,
                        $fraseDia,
                        $tram,
                        $fechaCita,
                        $TipoSangre_AUX,
                        $Alergias_AUX,
                        $Altura,
                        $Peso,
                        $Temperatura,
                        $descripcionAdicional
                    );
                    $insert = $this->directionModel -> registrarTramite($matricula, $idTramite, $descripcionTotal);
                    
                    if($insert){
                        //echo "<br>Registro exitoso";
                    } else {
                        //echo "<br>Error en el registro";
                    }
                } else {
                    echo "<br>Error: No se encontró el alumno con la matrícula proporcionada";
                }
            }

            //Valir que el botón fue enviado y tiene datos - dirServEsco
            if(isset($_POST['registrarTramite_dirServEsco'])){
                $matricula = $_POST['matriculaEscaneadoBD']; // Aquí se escaneara
                $idTramite = (int)$_POST['idTramite'];
                $metodoPago = $_POST['metodoPago'];
                $descripcionAdicional = trim($_POST['descripcion']) !== '' ? $_POST['descripcion'] : "N/A";
                $montoPagado = (double)$_POST['montoPagado'];
                $fechaSolicitud = $_POST['fechaSolicitud'];
                $motivoConstancia = $_POST['motivoConstancia'];

                /*AQUÍ SE RECUPERARÁN LOS DATOS DEL ALUMNO. */
                $resultDatos = $this->alumnoModel->recuperarDatosAlumnoPorMatricula($matricula);

                /*Hacemos la validación para recuperar los datos*/
                if($resultDatos){
                    $Nombre_AUX         = $resultDatos['Nombre'];
                    $ApePat_AUX         = $resultDatos['ApePat'];
                    $ApeMat_AUX         = $resultDatos['ApeMat'];
                    $FechaNac_AUX       = $resultDatos['FechaNac'];
                    $FeIngreso_AUX      = $resultDatos['FeIngreso'];
                    $Correo_AUX         = $resultDatos['Correo'];
                    $Direccion_AUX      = $resultDatos['Direccion'];
                    $Telefono_AUX       = $resultDatos['Telefono'];
                    $Ciudad_AUX         = $resultDatos['Ciudad'];
                    $Estado_AUX         = $resultDatos['Estado'];
                    $Genero_AUX         = $resultDatos['Genero'];
                    $idCarrera_AUX      = $resultDatos['idCarrera'];
                    $DescripcionCarrera_AUX = $resultDatos['descripcion']; // de tabla carrera
                    $PlanEstudios_AUX   = $resultDatos['planEstudios'];
                    $idDepto_AUX        = $resultDatos['idDepto'];         // de tabla carrera
                    $TipoSangre_AUX     = $resultDatos['TipoSangre'];
                    $Alergias_AUX       = $resultDatos['Alergias'];
                    $ContactoEmergencia_AUX = $resultDatos['contacto_emergencia'];
                    $FechaIngresoInfoMed_AUX = $resultDatos['fechaIngreso_InfoMed'];
                    $Cuatri_AUX          = $resultDatos['Cuatri'];          // función calcCuatrimestre()
                    $Periodo_AUX         = $resultDatos['Periodo'];

                    //Concatenación de datos
                    $nombreCompleto = trim("$Nombre_AUX $ApePat_AUX $ApeMat_AUX");
                    $requisitos = trim($Alergias_AUX. "- Sangre: $TipoSangre_AUX");

                    //Validamos que tipo de servicio es
                    switch($idTramite){
                        case 5: 
                            $tram = "Tramite de Servicios escolares";
                            $fraseDia = "Solicitó el tramite";
                            break;
                        default:
                            $tram = "Tramite de Servicios escolares";
                            $fraseDia = "Solicitó el tramite";
                            break;
                    }
                    // Generamos la descripción
                    trim($descripcionTotal = sprintf(
                        "El Alumno [%s] con matrícula [%s] y correo [%s], inscrito en el %s Cuatri de [%s], <%s> |PERIODO: <%s> [%s - %d] el día <%s> |Motivo adicional: [%s] | Monto pagado: [$%.2f] | Método de pago: [%s]. Requerimientos extras: <%s>" ,
                        $nombreCompleto,
                        $matricula,
                        $Correo_AUX,
                        $Cuatri_AUX,
                        $DescripcionCarrera_AUX,
                        $Periodo_AUX,
                        $fraseDia,
                        $tram,
                        $idDepto,
                        $fechaSolicitud,
                        $motivoConstancia,
                        $montoPagado,
                        $metodoPago,
                        $descripcionAdicional
                    ));
                    $insert = $this->directionModel -> registrarTramite($matricula, $idTramite, $descripcionTotal);
                    
                    if($insert){
                        //echo "<br>Registro exitoso";
                    } else {
                        //echo "<br>Error en el registro";
                    }
                } else {
                    echo "<br>Error: No se encontró el alumno con la matrícula proporcionada";
                }
            }

            //Valir que el botón fue enviado y tiene datos - dirVinc
            if(isset($_POST['registrarTramite_dirVinc'])){
                $matricula = $_POST['matriculaEscaneadoBD']; // Aquí se escaneara

                $idTramite = (int)$_POST['idTramite']; //Tipo de Trámite 
                $descripcionExtra = $_POST['descripcionExtra'];
                $entregaDocumentos = $_POST['entregaDocumentos'];
                $fechaSolicitud = $_POST['fechaSolicitud'];
                
                // Recuperar los documentos seleccionados
                if (isset($_POST['docs']) && is_array($_POST['docs'])) {
                    $docs = $_POST['docs']; // ← Esto es un arreglo con los documentos seleccionados
                    $docsTexto = implode(', ', $docs); // Ejemplo: "INE, CV, CartaAceptacion"
                } else {
                    $docs = [];
                    $docsTexto = 'Ninguno';
                }

                

                /*AQUÍ SE RECUPERARÁN LOS DATOS DEL ALUMNO. */
                $resultDatos = $this->alumnoModel->recuperarDatosAlumnoPorMatricula($matricula);

                /*Hacemos la validación para recuperar los datos*/
                if($resultDatos){
                    $Nombre_AUX         = $resultDatos['Nombre'];
                    $ApePat_AUX         = $resultDatos['ApePat'];
                    $ApeMat_AUX         = $resultDatos['ApeMat'];
                    $FechaNac_AUX       = $resultDatos['FechaNac'];
                    $FeIngreso_AUX      = $resultDatos['FeIngreso'];
                    $Correo_AUX         = $resultDatos['Correo'];
                    $Direccion_AUX      = $resultDatos['Direccion'];
                    $Telefono_AUX       = $resultDatos['Telefono'];
                    $Ciudad_AUX         = $resultDatos['Ciudad'];
                    $Estado_AUX         = $resultDatos['Estado'];
                    $Genero_AUX         = $resultDatos['Genero'];
                    $idCarrera_AUX      = $resultDatos['idCarrera'];
                    $DescripcionCarrera_AUX = $resultDatos['descripcion']; // de tabla carrera
                    $PlanEstudios_AUX   = $resultDatos['planEstudios'];
                    $idDepto_AUX        = $resultDatos['idDepto'];         // de tabla carrera
                    $TipoSangre_AUX     = $resultDatos['TipoSangre'];
                    $Alergias_AUX       = $resultDatos['Alergias'];
                    $ContactoEmergencia_AUX = $resultDatos['contacto_emergencia'];
                    $FechaIngresoInfoMed_AUX = $resultDatos['fechaIngreso_InfoMed'];
                    $Cuatri_AUX          = $resultDatos['Cuatri'];          // función calcCuatrimestre()
                    $Periodo_AUX = $resultDatos['Periodo']; //Función calcPeriodo();

                    //Concatenación de datos
                    $nombreCompleto = trim("$Nombre_AUX $ApePat_AUX $ApeMat_AUX");
                    $requisitos = trim($Alergias_AUX. "- Sangre: $TipoSangre_AUX");

                    //Validamos que tipo de servicio es
                    switch($idTramite){
                        case 5:
                            $tram = "Estancia I";
                            $fraseDia = "Solicitó iniciar su primera estancia profesional";
                            break;
                        case 6:
                            $tram = "Estancia II";
                            $fraseDia = "Solicitó continuar con su segunda estancia profesional";
                            break;
                        case 7:
                            $tram = "Estadía";
                            $fraseDia = "Solicitó registrar su estadía profesional";
                            break;
                        case 8:
                            $tram = "Prácticas profesionales";
                            $fraseDia = "Solicitó realizar sus prácticas profesionales";
                            break;
                        case 9:
                            $tram = "Servicio social";
                            $fraseDia = "Solicitó iniciar su servicio social";
                            break;
                        default:
                            $tram = "Trámite general";
                            $fraseDia = "Solicitó un trámite institucional";
                            break;
                    }

                    // Generamos la descripción
                    $descripcionTotal = sprintf(
                        "El alumno [%s] [Matrícula: %s], de <%s°> Cuatri en la carrera <%s>, solicitó el trámite de [%s - %s - Fecha: <%s>]. Documentos entregados: [%s]. Documentos finales correctos y adecuados: [%s].",
                        $nombreCompleto,
                        $matricula,
                        $Cuatri_AUX,
                        $DescripcionCarrera_AUX,
                        $tram,
                        $fraseDia,
                        $fechaSolicitud,
                        $docsTexto,
                        $entregaDocumentos
                    );
                    $insert = $this->directionModel -> registrarTramite($matricula, $idTramite, $descripcionTotal);
                    
                    if($insert){
                        //echo "<br>Registro exitoso";
                    } else {
                        //echo "<br>Error en el registro";
                    }
                } else {
                    echo "<br>Error: No se encontró el alumno con la matrícula proporcionada";
                }
                }
                //Incluimos la vista
                switch($idDepto){
                    case 2:
                        //Dirección académica - justificantes
                        include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php'); 
                        exit();
                        break;
                    case 3:
                        //Servicio escolares
                        include_once(__DIR__ . '/../Views/dirServEsco/gestionDocumentosServEsco.php'); 
                        exit();
                        break;
                    case 4:
                        //DDA
                        include_once(__DIR__ . '/../Views/dirDDA/gestionAsistenciaTutorias.php'); 
                        exit();
                        break;
                    case 5:
                        //DAE
                        include_once(__DIR__ . '/../Views/dirDAE/gestionDocumentosDAE.php'); 
                        exit();
                        break;
                    case 6:
                        //Médico
                        include_once(__DIR__ . '/../Views/dirMedica/gestionDocMed.php'); 
                        exit();
                        break;
                    case 7:
                        //Vinculación
                        include_once(__DIR__ . '/../Views/dirVinculacion/gestionDocumentosAlumnos.php'); 
                        exit();
                        break;
                    default:
                        include_once(__DIR__ . '/../Views/Login.php');
            }
        }

        
        //2.0. Función para consultar TODOS LOS TRÁMITES

        //2.1. Función para consultar TODOS LOS TRÁMITES por DEPARTAMENTO
        public function consultarTramitesPorDEPTO(){
            $direccion = null; // default: null
            $idDepto = (int)($_POST['idDepto'] ?? $_GET['idDepto'] ?? 2);
                        
            if(isset($_POST['consultarTramite_Depto'])){
                // Obtener idDepto del POST o usar el valor por defecto
                $idDepto = isset($_POST['idDepto']) ? (int)$_POST['idDepto'] : 2;
                // Llamada al modelo (devuelve mysqli_result)
                $direccion = $this->directionModel->consultarTramitesPorDepto($idDepto);
                //Evaluamos que tipo de dirección es para Incluirlo
                switch($idDepto){
                    case 2:
                        //Dirección académica - justificantes
                        include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                        exit();
                        break;
                    case 3:
                        //Servicio escolares
                        include_once(__DIR__ . '/../Views/dirServEsco/gestionDocumentosServEsco.php');
                        exit();
                        break;
                    case 4:
                        //DDA
                        include_once(__DIR__ . '/../Views/dirDDA/gestionAsistenciaTutorias.php');
                        exit();
                        break;
                    case 5:
                        //DAE
                        include_once(__DIR__ . '/../Views/dirDAE/gestionDocumentosDAE.php');
                        exit();
                        break;
                    case 6:
                        //Médico
                        include_once(__DIR__ . '/../Views/dirMedica/gestionDocMed.php');
                        exit();
                        break;
                    case 7:
                        //Vinculación
                        include_once(__DIR__ . '/../Views/dirVinculacion/gestionDocumentosAlumnos.php');
                        exit();
                        break;
                    default:
                        include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                        break;
                }
            }
        }
        //2.2 Función para consultar TODOS los TRÁMITES ESPECIFICOS DE ALGÚN TIPO.
        public function consultarPorTipoTramite(){
            $direccion = null; // default: null
            $idTramite = null;
            $idDepto = null;
            
            if(isset($_POST['consultarTramite_idTramite'])){
                $idTramite = (int)$_POST['idTramite'];
                $idDepto = isset($_POST['idDepto']) ? (int)$_POST['idDepto'] : 2;

                // Llamada al modelo (devuelve mysqli_result)
                $direccion = $this->directionModel->consultarPorTipoTramite($idTramite);
            }

            switch($idDepto){
                case 2:
                    //Dirección académica - justificantes
                    include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                    exit();
                    break;
                case 3:
                    //Servicio escolares
                    include_once(__DIR__ . '/../Views/dirServEsco/gestionDocumentosServEsco.php');
                    exit();
                    break;
                case 4:
                    //DDA
                    include_once(__DIR__ . '/../Views/dirDDA/gestionAsistenciaTutorias.php');
                    exit();
                    break;
                case 5:
                    //DAE
                    include_once(__DIR__ . '/../Views/dirDAE/gestionDocumentosDAE.php');
                    exit();
                    break;
                case 6:
                    //Médico
                    include_once(__DIR__ . '/../Views/dirMedica/gestionDocMed.php');
                    exit();
                    break;
                case 7:
                    //Vinculación
                    include_once(__DIR__ . '/../Views/dirVinculacion/gestionDocumentosAlumnos.php');
                    exit();
                    break;
                default:
                    include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                    break;
            }
        }
        
        //2.3 Función para consultar TODOS los TRÁMITES realizados por algún alumno (por Matrícula)
        public function consultarPorMatricula(){
            $direccion = null; // default: null
            $matricula = null;
            $idDepto = null;
            
            if(isset($_POST['consultarTramite_Matricula'])){
                $matricula = $_POST['Matricula'];
                $idDepto = isset($_POST['idDepto']) ? (int)$_POST['idDepto'] : 2;

                // Llamada al modelo (devuelve mysqli_result)
                $direccion = $this->directionModel->consultarTramitesPorMatricula($matricula);
            }

            //Evaluamos que tipo de dirección es para Incluirlo
            switch($idDepto){
                case 2:
                    //Dirección académica - justificantes
                    include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                    exit();
                    break;
                case 3:
                    //Servicio escolares
                    include_once(__DIR__ . '/../Views/dirServEsco/gestionDocumentosServEsco.php');
                    exit();
                    break;
                case 4:
                    //DDA
                    include_once(__DIR__ . '/../Views/dirDDA/gestionAsistenciaTutorias.php');
                    exit();
                    break;
                case 5:
                    //DAE
                    include_once(__DIR__ . '/../Views/dirDAE/gestionDocumentosDAE.php');
                    exit();
                    break;
                case 6:
                    //Médico
                    include_once(__DIR__ . '/../Views/dirMedica/gestionDocMed.php');
                    exit();
                    break;
                case 7:
                    //Vinculación
                    include_once(__DIR__ . '/../Views/dirVinculacion/gestionDocumentosAlumnos.php');
                    exit();
                    break;
                default:
                    include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                    break;
            }
        }
        
        //2.4 Función para consultar TRÁMITES por Folio
        public function consultarPorFolio(){
            $direccion = null; // default: null
            $folio = null;
            $idDepto = null;
            
            if(isset($_POST['consultarTramite_Folio'])){
                $folio = $_POST['FolioRegistro'];
                $idDepto = isset($_POST['idDepto']) ? (int)$_POST['idDepto'] : 2;

                // Llamada al modelo (devuelve mysqli_result)
                $direccion = $this->directionModel->consultarTramitePorFolio($folio);
            }
            //Evaluamos que tipo de dirección es para Incluirlo
            switch($idDepto){
                case 2:
                    //Dirección académica - justificantes
                    include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                    exit();
                    break;
                case 3:
                    //Servicio escolares
                    include_once(__DIR__ . '/../Views/dirServEsco/gestionDocumentosServEsco.php');
                    exit();
                    break;
                case 4:
                    //DDA
                    include_once(__DIR__ . '/../Views/dirDDA/gestionAsistenciaTutorias.php');
                    exit();
                    break;
                case 5:
                    //DAE
                    include_once(__DIR__ . '/../Views/dirDAE/gestionDocumentosDAE.php');
                    exit();
                    break;
                case 6:
                    //Médico
                    include_once(__DIR__ . '/../Views/dirMedica/gestionDocMed.php');
                    exit();
                    break;
                case 7:
                    //Vinculación
                    include_once(__DIR__ . '/../Views/dirVinculacion/gestionDocumentosAlumnos.php');
                    exit();
                    break;
                default:
                    include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                    break;
            }
        }

        /*Función para realizar la actualización de datos dentro del Trámite*/
        public function actualizarTramite(){
            $row = null;
            $idDepto = (int)($_POST['idDepto'] ?? $_GET['idDepto'] ?? 1);
            if(isset($_GET['Folio'])){
                $FolioRegistro = $_GET['Folio'];
                //Llamar al método del modelo para hacer la consulta
                $result = $this->directionModel->consultarTramitePorFolio($FolioRegistro);
                
                // Obtener el primer registro del resultado
                if($result && $result->num_rows > 0){
                    $row = $result->fetch_assoc();
                }
                
                //Evaluamos que tipo de direccion es para Incluirlo
                switch($idDepto){
                    case 2:
                        //Dirección académica - justificantes
                        include_once(__DIR__ . '/../Views/dirDirAca/modificacionTramite.php');
                        //exit();
                        break;
                    case 3:
                        //Servicio escolares
                        include_once(__DIR__ . '/../Views/dirServEsco/modificacionTramite.php');
                        //exit();
                        break;
                    case 4:
                        //DDA
                        include_once(__DIR__ . '/../Views/dirDDA/modificacionTramite.php');
                        //exit();
                        break;
                    case 5:
                        //DAE
                        include_once(__DIR__ . '/../Views/dirDAE/modificacionTramite.php');
                        //exit();
                        break;
                    case 6:
                        //Médico
                        include_once(__DIR__ . '/../Views/dirMedica/modificacionTramite.php');
                        //exit();
                        break;
                    case 7:
                        //Vinculación
                        include_once(__DIR__ . '/../Views/dirVinculacion/modificacionTramite.php');
                        //exit();
                        break;
                    default:
                        include_once(__DIR__ . '/../Views/dirDirAca/modificacionTramite.php');
                        break;
                }
                return;
            }
            //ENVIAR INFO
            $redireccion_error_base = "/IdentiQR/index.html";
            if(isset($_POST['actualizarTramite_Tramite'])){
                $FolioRegistro = $_POST['FolioRegistro'];
                $FolioSeguimiento = $_POST['FolioSeguimiento'];
                $Descripcion = $_POST['Descripcion'];
                $estatusT = $_POST['estatusT'];    

                $update = $this -> directionModel -> actualizarTramite($Descripcion, $estatusT, $FolioRegistro, $FolioSeguimiento);
                if($update){
                    switch($idDepto){
                        case 2:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=consult");
                            exit();
                            break;
                        case 3:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirServEsco/GestionesAdmin_ServEsco.php?action=consult");
                            exit();
                            break;
                        case 4 :
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirDDA/GestionesAdmin_DesaAca.php?action=consult");
                            exit();
                            break;
                        case 5:
                            //$redireccion = "/IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php?action=consult";
                            header("Location: /IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php?action=consult");
                            exit();
                            break;
                        case 6 :
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirMedica/GestionesAdmin_Medico.php?action=consult");
                            exit();
                            break;
                        case 7:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirVinculacion/GestionesAdmin_Vinculacion.php?action=consult");
                            exit();
                            break;
                        default:
                            include_once(__DIR__ . '/../Views/dirDirAca/GestionesAdmin_Direccion.php'); //Vista de justificantes
                            break;
                    }
                } else {
                    // Redirección de ERROR (Volver al formulario de modificación con GET)
                    $url_error = "";
                    switch($idDepto){
                        case 2:
                            $url_error = "/IdentiQR/app/Views/dirDirAca/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=2&error=true";
                            break;
                        case 3:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            $url_error = "/IdentiQR/app/Views/dirServEsco/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=3&error=true";
                            break;
                        case 4 :
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            $url_error = "/IdentiQR/app/Views/dirDDA/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=4&error=true";
                            break;
                        case 5:
                            //$redireccion = "/IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php?action=consult";
                            $url_error = "/IdentiQR/app/Views/dirDAE/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=5&error=true";
                            break;
                        case 6 :
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            $url_error = "/IdentiQR/app/Views/dirMedica/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=6&error=true";
                            break;
                        case 7:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            $url_error = "/IdentiQR/app/Views/dirVinculacion/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=7&error=true";
                            break;
                        default:
                            $url_error = $redireccion_error_base;
                            break;
                    }
                    header("Location: $url_error");
                    exit(); 
                }
            }
            //include_once(__DIR__ . '/../Views/dirDirAca/modificacionTramite.php');
        }

        public function actualizarTramiteManual(){
            $row = null;
            if(isset($_POST['Actualizar_Tramite'])){
                $FolioRegistro = trim($_POST['FolioAct']);
                $idDepto = (int)($_POST['idDepto'] ?? $_GET['idDepto'] ?? 1);
                //Llamar al método del modelo para hacer la consulta
                $result = $this->directionModel->consultarTramitePorFolio($FolioRegistro);
                
                // Obtener el primer registro del resultado
                if($result && $result->num_rows > 0){
                    $row = $result->fetch_assoc();
                }
                //Evaluamos que tipo de direccion es para Incluirlo
                switch($idDepto){
                    case 2:
                        //Dirección académica - justificantes
                        include_once(__DIR__ . '/../Views/dirDirAca/modificacionTramite.php');
                        //exit();
                        break;
                    case 3:
                        //Servicio escolares
                        include_once(__DIR__ . '/../Views/dirServEsco/modificacionTramite.php');
                        //exit();
                        break;
                    case 4:
                        //DDA
                        include_once(__DIR__ . '/../Views/dirDDA/modificacionTramite.php');
                        //exit();
                        break;
                    case 5:
                        //DAE
                        include_once(__DIR__ . '/../Views/dirDAE/modificacionTramite.php');
                        //exit();
                        break;
                    case 6:
                        //Médico
                        include_once(__DIR__ . '/../Views/dirMedica/modificacionTramite.php');
                        //exit();
                        break;
                    case 7:
                        //Vinculación
                        include_once(__DIR__ . '/../Views/dirVinculacion/modificacionTramite.php');
                        //exit();
                        break;
                    default:
                        include_once(__DIR__ . '/../Views/dirDirAca/modificacionTramite.php');
                        break;
                }
                return;
            }
            //ENVIAR INFO
            if(isset($_POST['actualizarTramite_Tramite'])){
                $FolioRegistro = $_POST['FolioRegistro'];
                $FolioSeguimiento = $_POST['FolioSeguimiento'];
                $Descripcion = $_POST['Descripcion'];
                $estatusT = $_POST['estatusT'];    

                $update = $this -> directionModel -> actualizarTramite($Descripcion, $estatusT, $FolioRegistro, $FolioSeguimiento);
                if($update){
                    switch($idDepto){
                        case 2:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=consult");
                            exit();
                            break;
                        case 3:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirServEsco/GestionesAdmin_ServEsco.php?action=consult");
                            exit();
                            break;
                        case 4 :
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirDDA/GestionesAdmin_DesaAca.php?action=consult");
                            exit();
                            break;
                        case 5:
                            //$redireccion = "/IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php?action=consult";
                            header("Location: /IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php?action=consult");
                            exit();
                            break;
                        case 6 :
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirMedica/GestionesAdmin_Medico.php?action=consult");
                            exit();
                            break;
                        case 7:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            header("Location: /IdentiQR/app/Views/dirVinculacion/GestionesAdmin_Vinculacion.php?action=consult");
                            exit();
                            break;
                        default:
                            include_once(__DIR__ . '/../Views/dirDirAca/GestionesAdmin_Direccion.php'); //Vista de justificantes
                            break;
                    }
                } else {
                    // Redirección de ERROR (Volver al formulario de modificación con GET)
                    $url_error = "";
                    switch($idDepto){
                        case 2:
                            $url_error = "/IdentiQR/app/Views/dirDirAca/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=2&error=true";
                            break;
                        case 3:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            $url_error = "/IdentiQR/app/Views/dirServEsco/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=3&error=true";
                            break;
                        case 4 :
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            $url_error = "/IdentiQR/app/Views/dirDDA/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=4&error=true";
                            break;
                        case 5:
                            //$redireccion = "/IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php?action=consult";
                            $url_error = "/IdentiQR/app/Views/dirDAE/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=5&error=true";
                            break;
                        case 6 :
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            $url_error = "/IdentiQR/app/Views/dirMedica/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=6&error=true";
                            break;
                        case 7:
                            //$redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=update";
                            $url_error = "/IdentiQR/app/Views/dirVinculacion/modificacionTramite.php?Folio=$FolioSeguimiento&idDepto=7&error=true";
                            break;
                        default:
                            $url_error = $redireccion_error_base;
                            break;
                    }
                    header("Location: $url_error");
                    exit(); 
                }
            }
            //include_once(__DIR__ . '/../Views/dirDirAca/modificacionTramite.php');
        }

        /*Funciones para realizar la baja de los servicios/tramites */
        //1. Baja por FolioRegistro (desde la tabla)
        public function bajaTramiteFR(){
            $idDepto = (int)($_POST['idDepto'] ?? $_GET['idDepto'] ?? 1);
            if(isset($_GET['Folio'])){
                $FolioRegistro = $_GET['Folio']; // Mantener como string para preservar los ceros
                // Llamar al modelo para eliminar
                $eliminado = $this->directionModel->cancelarTramiteFR($FolioRegistro);
                
                // Incluir la vista primero
                $direccion = $this->directionModel->consultarTramitesPorDepto($idDepto);
                
                //Evaluamos que tipo de dirección es para Incluirlo
                switch($idDepto){
                    case 2:
                        //Dirección académica - justificantes
                        $redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=consult";
                        include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php'); 
                        //exit();
                        break;
                    case 3:
                        //Servicio escolares
                        $redireccion = "/IdentiQR/app/Views/dirServEsco/GestionesAdmin_ServEsco.php?action=consult";
                        include_once(__DIR__ . '/../Views/dirServEsco/gestionDocumentosServEsco.php'); 
                        //exit();
                        break;
                    case 4:
                        //DDA
                        $redireccion = "/IdentiQR/app/Views/dirDDA/GestionesAdmin_DesaAca.php?action=consult";
                        include_once(__DIR__ . '/../Views/dirDDA/gestionAsistenciaTutorias.php'); 
                        //exit();
                        break;
                    case 5:
                        //DAE
                        $redireccion = "/IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php?action=consult";
                        include_once(__DIR__ . '/../Views/dirDAE/gestionDocumentosDAE.php'); 
                        //exit();
                        break;
                    case 6:
                        //Médico
                        $redireccion = "/IdentiQR/app/Views/dirMedica/GestionesAdmin_Medico.php?action=consult";
                        include_once(__DIR__ . '/../Views/dirMedica/gestionDocMed.php'); 
                        //exit();
                        break;
                    case 7:
                        //Vinculación
                        $redireccion = "/IdentiQR/app/Views/dirVinculacion/GestionesAdmin_Vinculacion.php?action=consult";
                        include_once(__DIR__ . '/../Views/dirVinculacion/gestionDocumentosAlumnos.php'); 
                        //exit();
                        break;
                    default:
                        $redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=consult";
                        include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                        break;
                }
                
                // Mostrar mensaje después de cargar la vista
                if($eliminado > 0){
                    echo "<script>
                        Swal.fire({
                            title: 'Eliminado',
                            text: 'Se eliminó correctamente el Trámite con Folio de Registro: " . $FolioRegistro . "',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo eliminar el trámite con Folio: " . $FolioRegistro . "',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    </script>";
                }
            }
        }

        //2. Baja por FolioSeguimiento (desde el formulario)
        public function bajaTramiteFS(){
            $eliminado = 0;
            $idDepto = (int)($_POST['idDepto'] ?? $_GET['idDepto'] ?? 1);
            $redireccion = "/IdentiQR/index.html";
            
            if(isset($_POST['BajaServicio_Tramite']) || (isset($_POST['accionEliminar']) && $_POST['accionEliminar'] === 'eliminarTramite')){
                $FolioSeguimiento = $_POST['FolioSeguimiento'];

                // Llamar al modelo para eliminar
                $eliminado = $this->directionModel->cancelarTramiteFS($FolioSeguimiento);
            }
            
            // Incluir la vista con el resultado
            
            //Evaluamos que tipo de dirección es para Incluirlo
            switch($idDepto){
                case 2:
                    //Dirección académica - justificantes
                    $redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=consult";
                    include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php'); 
                    //exit();
                    break;
                case 3:
                    //Servicio escolares
                    $redireccion = "/IdentiQR/app/Views/dirServEsco/GestionesAdmin_ServEsco.php?action=consult";
                    include_once(__DIR__ . '/../Views/dirServEsco/gestionDocumentosServEsco.php'); 
                    //exit();
                    break;
                case 4:
                    //DDA
                    $redireccion = "/IdentiQR/app/Views/dirDDA/GestionesAdmin_DesaAca.php?action=consult";
                    include_once(__DIR__ . '/../Views/dirDDA/gestionAsistenciaTutorias.php'); 
                    //exit();
                    break;
                case 5:
                    //DAE
                    $redireccion = "/IdentiQR/app/Views/dirDAE/GestionesAdmin_DAE.php?action=consult";
                    include_once(__DIR__ . '/../Views/dirDAE/gestionDocumentosDAE.php'); 
                    //exit();
                    break;
                case 6:
                    //Médico
                    $redireccion = "/IdentiQR/app/Views/dirMedica/GestionesAdmin_Medico.php?action=consult";
                    include_once(__DIR__ . '/../Views/dirMedica/gestionDocMed.php'); 
                    //exit();
                    break;
                case 7:
                    //Vinculación
                    $redireccion = "/IdentiQR/app/Views/dirVinculacion/GestionesAdmin_Vinculacion.php?action=consult";
                    include_once(__DIR__ . '/../Views/dirVinculacion/gestionDocumentosAlumnos.php'); 
                    //exit();
                    break;
                default:
                    $redireccion = "/IdentiQR/app/Views/dirDirAca/GestionesAdmin_Direccion.php?action=consult";
                    include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                    break;
            }
            // Mostrar mensaje después de cargar la vista
            if($eliminado > 0){
                echo "<script>
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'El trámite ha sido eliminado exitosamente',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = '$redireccion';
                    });
                </script>";
            } elseif(isset($_POST['BajaServicio_Tramite']) || isset($_POST['accionEliminar'])){
                echo "<script>
                    Swal.fire({
                        title: 'Error',
                        text: 'No se pudo eliminar el trámite',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>";
            }
            
        }


        /*Apatir de acá se encontrarán las funciones para Generar los reportes para cada dirección.*/
        //idDepto = 2; Dirección acádemica
        //idDepto = 3; Servicios escolares
        //idDepto = 4; Dirección Desarrollo Academico
        //idDepto = 5; Dirección Asuntos Estudiantiles
        //idDepto = 6; Consultorio de atención de primer contacto
        public function reporteInd_DirMed(){
            date_default_timezone_set('America/Mexico_City');
            if(!isset($_POST['reporteIndividualizado_DirMed'])) return;
                $tipoReporte = $_POST['tipoReporte'] ?? 1;
                $hoy = date('Y-m-d');
                $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos
                $fe1 = (!empty($_POST['fe1'])) ? $_POST['fe1'] : $hoy;
                $fe2 = (!empty($_POST['fe2'])) ? $_POST['fe2'] : $hoy;
                $genero = (!empty($_POST['genero'])) ? $_POST['genero'] : "Otro";
                $idDepto = (int)($_POST['idDepto'] ?? 0);

                // Modelo (devuelve array de result sets)
                $allSets = $this->directionModel->reporteInd_DirMed(
                    $tipoReporte, $fe1, $fe2, $genero, $idDepto
                );
                if ($allSets === false) {
                    echo "Error al generar el reporte.";
                    return;
                }

                require_once __DIR__ . '/../../public/PHP/FPDF/fpdf.php';
                include __DIR__ . '/../../public/PHP/extraccionDatos_Tablas.php'; // funciones de extracción

                $pdf = new FPDF();
                $pdf->AliasNbPages();
                $pdf->SetAutoPageBreak(true, 50);
                $pdf->SetTitle(utf8_decode('REPORTE INDIVIDUALIZADO - DIRECCIÓN MEDICA'));
                $pdf->SetAuthor('IdentiQR');

                $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/

                $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                $logoPathDir = realpath(__DIR__ . '/../../public/Media/img/Consultorio_Index1.png');
                $generatedPages = 0;

                foreach ($allSets as $setIndex => $rows) {
                    if (empty($rows)) 
                        continue;
                    foreach ($rows as $r) {
                        $generatedPages++;
                        $pdf->AddPage();
                        // Header: logo + titulo + fecha
                        if ($logoPath && file_exists($logoPath)) {
                            $pdf->Image($logoPath, 12, 8, 28);
                        }
                        if (!empty($logoPathDir) && file_exists($logoPathDir)) {
                            // Logo derecho
                            // Posición: alineado al margen derecho
                            $rightX = $pdf->GetPageWidth() - 12 - 28; // (margen derecho = 12, ancho logo = 28)
                            $pdf->Image($logoPathDir, $rightX, 8, 28);
                        }                        
                        $pdf->SetFont('ShadowsIntoLight-Regular','',14);
                        $pdf->SetXY(40, 10);
                        $pdf->Cell(110, 7, utf8_decode('REPORTE INDIVIDUALIZADO - DIRECCIÓN MÉDICA'), 0, 1, 'C');

                        $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                        $pdf->SetXY(40, 17);
                        $pdf->Cell(110, 5, 'Fecha del reporte: ' . $hoy, 0, 1, 'C');

                        $pdf->SetDrawColor(180,180,180);
                        $pdf->Line(10, 30, $pdf->GetPageWidth() - 10, 30);
                        $pdf->Ln(6);

                        // Datos defensivos (NO decodear aqui para pasar a funciones)
                        $matriRaw   = $r['Matri']   ?? ($r['matricula'] ?? '');
                        $nomRaw     = $r['Nom']     ?? ($r['Nombre'] ?? '');
                        $apatRaw    = $r['Pat']     ?? ($r['ApePat'] ?? '');
                        $amatRaw    = $r['Mat']     ?? ($r['ApeMat'] ?? '');
                        $fehorRaw   = $r['FeHor']   ?? ($r['FechaHora'] ?? '');
                        $rawDescripcion = $r['DesServ'] ?? $r['DescripcionServ'] ?? ($r['descripcion'] ?? '');

                        // Imprimir: convertimos a ISO para FPDF al mostrar, pero mantenemos UTF-8 para parsing
                        $matri = utf8_decode($matriRaw);
                        $fullname = utf8_decode(trim("$nomRaw $apatRaw $amatRaw"));
                        $fehor = utf8_decode($fehorRaw);
                        $descForPrint = utf8_decode($rawDescripcion);

                        // Bloque superior - 2 columnas
                        $leftX  = 12;
                        $rightX = 120;
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $yStart = $pdf->GetY() + 5;

                        // Columna izquierda: datos personales
                        $pdf->SetXY($leftX, $yStart);
                        $pdf->Cell(30,6, utf8_decode('Matrícula:'), 0, 0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(60,6, $matri, 0, 1);

                        $pdf->SetXY($leftX, $pdf->GetY());
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(30,6, 'Nombre:', 0, 0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->MultiCell(78,6, $fullname, 0, 'L');

                        $pdf->SetXY($leftX, $pdf->GetY());
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(30,6,'Fecha/Hora:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(60,6, $fehor, 0, 1);

                        // Columna derecha: resumen médico -> PASAMOS raw UTF-8 a las funciones
                        $pdf->SetXY($rightX, $yStart);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(30,6,'Estatura:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,6, obtenerEstatura($rawDescripcion), 0, 1);

                        $pdf->SetXY($rightX, $pdf->GetY());
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(30,6,'Peso:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,6, obtenerPeso($rawDescripcion), 0, 1);

                        $pdf->SetXY($rightX, $pdf->GetY());
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(30,6,'Alergias:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,6, obtenerAlergias($rawDescripcion), 0, 1);

                        $pdf->SetXY($rightX, $pdf->GetY());
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(30,6,'Tipo sangre:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,6, obtenerTipoSangre($rawDescripcion), 0, 1);

                        $pdf->Ln(6);

                        // Caja de descripcion (usar descForPrint para mostrar)
                        $pdf->SetDrawColor(200,200,200);
                        $pdf->SetFillColor(245,245,245);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,7, utf8_decode('Descripción / Notas'), 1, 1, 'L', true);

                        $pdf->SetFont('Arial','',10);
                        // MultiCell con altura 6 para evitar saltos excesivos
                        $pdf->MultiCell(0,5, $descForPrint ?: utf8_decode('Sin descripción'), 1, 'L');
                        $pdf->Ln(4);
                        // Footer por página
                        $pageWidth = $pdf->GetPageWidth();
                        //$pdf->setXY(50,225);
                        $margin = 50;
                        $pdf->SetFont('Arial','I',9);
                        $pdf->SetTextColor(80);

                        // Texto a la izquierda (usamos '-' en vez de '•' para evitar '?')
                        $leftText = utf8_decode("Generado por: IdentiQR - Fecha del reporte: $hoy");

                        // Escribimos el texto izquierdo ocupando casi todo el ancho, luego sobrescribimos la parte derecha con la página
                        $pdf->SetX($margin);
                        $pdf->Cell($pageWidth - 2*$margin - 40, 6, $leftText, 0, 0, 'L');

                    // Número de página a la derecha (reserva 40mm para esto)
                    $pdf->SetX($pageWidth - $margin - 40);
                    $pdf->Cell(40, 6, utf8_decode('Página ') . $pdf->PageNo() . '/{nb}', 0, 0, 'R');

                    // restaurar color por si hace falta
                        $pdf->SetTextColor(0);

                        // Pie informativo
                        /*
                        $pdf->SetFont('Arial','I',9);
                        $pdf->SetTextColor(110);
                        $pdf->Cell(0,5, utf8_decode("Generado por: IdentiQR • Fecha del reporte: $hoy"), 0, 1, 'L');
                        $pdf->SetTextColor(0);
                        */
                    }
                }
                // Si no se generó nada
                if ($generatedPages === 0) {
                    $pdf->AddPage();
                    $pdf->SetFont('Arial','B',12);
                    $pdf->Cell(0,10, utf8_decode('No se encontraron registros para los parámetros indicados'), 0, 1, 'C');
                }

                // Entrega PDF
                if (headers_sent($file, $line)) {
                    error_log("No se puede enviar PDF, headers already sent in $file on line $line");
                    echo "No se pudo generar el PDF: ya se envió salida previamente.";
                    return;
                }

                //$pdf->Output('I', 'reporte_individualizado.pdf'); //Lo abre en el navegador
                $pdf->Output('D', 'reporte_individualizado_DirMedica-'.$fechaHora.'.pdf'); //Descargar directamente
        }
        
        /*Aquí se encontrará el reporte individualizado de PORCENTAJES */
        /*public function reporteInd_DirMed(){
            if(isset($_POST['reporteIndividualizado_DirMed'])){
                $tipoReporte = $_POST['tipoReporte'];
                // Fechas: si vienen vacías o no definidas, asignar la fecha de hoy
                $hoy = date('Y-m-d');

                $fe1 = (!empty($_POST['fe1'])) ? $_POST['fe1'] : $hoy;
                $fe2 = (!empty($_POST['fe2'])) ? $_POST['fe2'] : $hoy;

                // Género: si viene vacío o no existe, asignar "Otro"
                $genero = (!empty($_POST['genero'])) ? $_POST['genero'] : "Otro";

                // idDepto siempre viene desde tu form hidden
                $idDepto = $_POST['idDepto'];
                
                //Aquí tendremos que mandar a llamar.
                //$rows = $this->directionModel->reporteInd_DirMed($tipoReporte,$fe1,$fe2,$genero,$idDepto);
                $allSets = $this->directionModel->reporteInd_DirMed($tipoReporte,$fe1,$fe2,$genero,$idDepto);
                //$rows = $result->fetch_all(MYSQLI_ASSOC);
                //INSTANCIAMOS, CREAMOS UN OBJETO DE LA CLASE FPDF(PDF)
                $pdf = new FPDF();
                date_default_timezone_set('America/Mexico_City');
                $pdf->AliasNbPages();
                $fecha = $_POST['fechaReporte'] ?? date('Y-m-d');
                $title = utf8_decode('REPORTE INDIVIDUALIZADO - DIRECCIÓN MEDICA');
                //$pdf->AddPage();

                $pdf->SetTitle($title);
                $page = 0;

                foreach ($allSets as $setIndex => $rows) {
                    $page++;
                    $pdf->AddPage();
                    $pdf->SetFont('Arial','B',12);
                    $pdf->Cell(0,8,"Hoja $page - Consulta [".($setIndex+1)."]",0,1,'C');
                    $pdf->Ln(4);
                    $pdf -> SetCreator("IdentiQR", true);
                    // Page number
                    
                    if (empty($rows)) {
                        $pdf->SetFont('Arial','I',10);
                        $pdf->Cell(0,6,"(Sin datos en el Reporte)",0,1);
                        continue;
                    }

                    $pdf->SetFont('Arial','',11);
                    $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');

                    foreach ($rows as $r) {
                        if ($logoPath && file_exists($logoPath)) {
                            $pdf->Image($logoPath, 10, 8, 33);
                        }
                        $pdf->Cell(0,30, "Matricula: " . ($r['Matri'] ?? ''), 0, 1);
                        $pdf->Cell(0,10, "Nombre: " . (($r['Nom'] ?? '') . ' ' . ($r['Pat'] ?? '') . ' ' . ($r['Mat'] ?? '')), 0, 1);
                        $pdf->Cell(0,10, "Fecha Hora: " . ($r['FeHor'] ?? ''), 0, 1);
                        $pdf->MultiCell(0,5, "Descripcion: " . utf8_decode($r['DesServ'] ?? ''), 0, 1);

                        $pdf->Cell(0,10, "Estatura: " . obtenerEstatura($r['DesServ'] ?? ''), 0, 1);
                        $pdf->Cell(0,10, "Peso: " . obtenerPeso($r['DesServ'] ?? ''), 0, 1);
                        $pdf->Cell(0,10, "Alergias: " . obtenerAlergias($r['DesServ'] ?? ''), 0, 1);
                        $pdf->Cell(0,10, "Tipo de sangre: " . obtenerTipoSangre($r['DesServ'] ?? ''), 0, 1);

                        $pdf->Ln(3);
                        $pdf->Cell(0,0,'','T',1);
                        $pdf->Ln(3);
                    }
                    $pdf->Cell(0,10,'Page '.$pdf->PageNo().'/{nb}',0,0,'C');
                }


                $pdf->Output();
                
                //INCLUIMOS LA VISTA
                $vista = __DIR__ . '/../Views/dirMedica/GestionesAdmin_Medico.php';
                if (file_exists($vista)) {
                    include $vista; // o require_once $vista;
                }
            }
        }
        */
    }
?>
