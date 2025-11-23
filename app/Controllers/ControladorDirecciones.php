<?php
    /*Realizamos la instancia del controlador */
    require_once __DIR__ . '/../../config/Connection_BD.php';
    require_once __DIR__ . '/../Models/ModeloDirecciones.php';
    require_once __DIR__ . '/../Models/ModeloAlumno.php';
    require_once __DIR__ . '/../../public/libraries/FPDF/fpdf.php';
    
    /*Se crea la clase DirectionsController la cual será encargada de manejar todas las OPCIONES que realice cada dirección*/
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

            // Variable para controlar la alerta en la vista
            $statusAlert = null; 

            //Validar que el botón fue enviado y tiene datos - DirAcademica
            if(isset($_POST['registrarTramite_dirDirACA'])){
                $matricula = $_POST['matriculaEscaneadoBD']; // Aquí se escaneará el QR y obtendrá la matricula
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
                    // Generamos la descripción especifica para este DEPARTAMENTO/DIRECCIÓN
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
                        $statusAlert = 'success';
                    } else {
                        $statusAlert = 'error_bd'; // Fallo en BD
                    }
                } else {
                    $statusAlert = 'error_matricula';
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
                        $statusAlert = 'success';
                    } else {
                        $statusAlert = 'error_bd'; // Fallo en BD
                    }
                } else {
                    $statusAlert = 'error_matricula';
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
                        $statusAlert = 'success';
                    } else {
                        $statusAlert = 'error_bd'; // Fallo en BD
                    }
                } else {
                    $statusAlert = 'error_matricula';
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
                        $statusAlert = 'success';
                    } else {
                        $statusAlert = 'error_bd'; // Fallo en BD
                    }
                } else {
                    $statusAlert = 'error_matricula';
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
                        $statusAlert = 'success';
                    } else {
                        $statusAlert = 'error_bd'; // Fallo en BD
                    }
                } else {
                    $statusAlert = 'error_matricula';
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
                        $statusAlert = 'success';
                    } else {
                        $statusAlert = 'error_bd'; // Fallo en BD
                    }
                } else {
                    $statusAlert = 'error_matricula';
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
            $statusAlert = null; // Variable para el error
            if(isset($_POST['Actualizar_Tramite'])){
                $FolioRegistro = trim($_POST['FolioAct']);
                $idDepto = (int)($_POST['idDepto'] ?? $_GET['idDepto'] ?? 1);
                //Llamar al método del modelo para hacer la consulta
                $result = $this->directionModel->consultarTramitePorFolio($FolioRegistro);
                
                // Obtener el primer registro del resultado
                if($result && $result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    switch($idDepto){
                        case 2: include_once(__DIR__ . '/../Views/dirDirAca/modificacionTramite.php'); break;
                        case 3: include_once(__DIR__ . '/../Views/dirServEsco/modificacionTramite.php'); break;
                        case 4: include_once(__DIR__ . '/../Views/dirDDA/modificacionTramite.php'); break;
                        case 5: include_once(__DIR__ . '/../Views/dirDAE/modificacionTramite.php'); break;
                        case 6: include_once(__DIR__ . '/../Views/dirMedica/modificacionTramite.php'); break;
                        case 7: include_once(__DIR__ . '/../Views/dirVinculacion/modificacionTramite.php'); break;
                        default: include_once(__DIR__ . '/../Views/dirDirAca/modificacionTramite.php'); break;
                    }
                    return; // Terminamos aquí para que se quede en la vista de edición
                } else {
                    //Configuramos alerta de error
                    $statusAlert = 'error_folio';
                }
                //// Si llegamos aquí es porque hubo error. Cargamos la vista GESTIÓN (la principal)
                switch($idDepto){
                    case 2:
                        //Dirección académica - justificantes
                        include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
                        //exit();
                        break;
                    case 3:
                        //Servicio escolares
                        include_once(__DIR__ . '/../Views/dirServEsco/gestionDocumentosServEsco.php');
                        //exit();
                        break;
                    case 4:
                        //DDA
                        include_once(__DIR__ . '/../Views/dirDDA/gestionAsistenciaTutorias.php');
                        //exit();
                        break;
                    case 5:
                        //DAE
                        include_once(__DIR__ . '/../Views/dirDAE/gestionDocumentosDAE.php');
                        //exit();
                        break;
                    case 6:
                        //Médico
                        include_once(__DIR__ . '/../Views/dirMedica/gestionDocMed.php');
                        //exit();
                        break;
                    case 7:
                        //Vinculación
                        include_once(__DIR__ . '/../Views/dirVinculacion/gestionDocumentosAlumnos.php');
                        //exit();
                        break;
                    default:
                        include_once(__DIR__ . '/../Views/dirDirAca/gestionJustificantes_Dir.php');
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
    }
?>
