<?php
    require_once __DIR__ . '/../../public/libraries/FPDF/fpdf.php';
    require_once __DIR__ . '/../../public/libraries/PHPLot/phplot.php';
    include __DIR__ . '/../../public/PHP/extraccionDatos_Tablas.php'; // funciones de extracción
    require_once __DIR__ . '/../Models/ModeloAlumno.php';
    require_once __DIR__ . '/../Models/ModeloReportes.php';
    require_once __DIR__ . '/../../config/Connection_BD.php';

    /*Creamos la Clase ReportController la cual CONTENDRÁ todos los métodos/funciones para generar los reportes necesarios en cada departamento*/
    class ReportsController{
        private $reportModel;
        private $alumnoModel;

        public function __construct($conn){
            $this->reportModel = new ReportModel($conn);
            $this->alumnoModel = new AlumnoModel($conn);
        }

        /*Apatir de acá se encontrarán las funciones para Generar los reportes para cada dirección.*/
        //idDepto = 1; Administrador general - Sin dirección asociada
        public function reporteGeneral1_Admin(){
            $data = $this->reportModel->reporteGeneral_Admin();
            //Validamos que este vacio
            if (empty($data)) {
                // Generar PDF con mensaje de error/sin datos
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, utf8_decode('Reporte General - Servicios/Trámites'), 0, 1, 'C');
                $pdf->Ln(20);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, utf8_decode('No se encontraron trámites registrados para este reporte.'), 0, 1, 'C');
                date_default_timezone_set('America/Mexico_City');
                $fechaHora = date('Y-m-d_H-i-s');
                $nombreRep = "ReporteAdmin_General_Tramites_SIN_DATOS_".$fechaHora.".pdf";
                $pdf->Output('D', $nombreRep);
                exit(); // Detener la ejecución para no intentar dibujar la gráfica
            }
            //Creamos nuestras variables contadoras
            $cont = [];
            $datosO = [];
            foreach($data as $r){
                // índice idDepto — convierto a entero por seguridad
                $idDepto = (int)$r[8];
                $nombre = trim($r[9]) ?: "Depto $idDepto";
                $key = $idDepto . ' - ' . $nombre;
                if(!isset($cont[$key])) {
                    $cont[$key] = 0;
                    $datosO[] = $key;
                }
                $cont[$key]++;
            }
            $plot_data = [];
            foreach($datosO as $lab){
                $plot_data[] = [$lab, $cont[$lab]];
            }
            //Creamos la gráfica
            $plot = new PHPLot(1000,800);//plot: Referencia a PHPLot
            $plot -> SetImageBorderType('plain'); //Tipo de borde
            $plot ->SetPlotType('bars'); //Indica el tipo de gráfica
            $plot -> SetDataType('text-data'); //El tipo de datos en la gráfica
            $plot -> SetDataValues($plot_data); //Añadir los valores de la gráfica
            //ESTILO DE LA GRÁFICA
            $plot -> SetTitle(utf8_decode('Registro servicios/tramites'));
            $plot -> SetXTitle('Departamento-idDepto');
            $plot -> SetYTitle(utf8_decode('Cantidad de trámites')); //Aquí se deberia hacer el conteo
            $plot -> SetShading(5); //Añadir una sombra para efecto 3D
            $plot -> SetDataColors(['#800080']);
            //Generar gráfica
            date_default_timezone_set('America/Mexico_City'); // Ajusta tu zona horaria
            $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos
            $filename = "public/Media/graphs/grafica_General1_".$fechaHora.".png";            
            $plot->SetOutputFile($filename);//Guardar imagen de la gráfica
            $plot -> SetIsInline(true); //Guardar gráfica en el sistema
            $plot -> DrawGraph(); // Genera la gráfica
            //Generar el PDF
            $pdf = new FPDF();
            $pdf -> AddPage();
            // Añade el encabezado y logos (si es que se desean)
            $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
            //Realiza una validación. Si la imagen existe, la pone dentro del PDF    
            if ($logoPath && file_exists($logoPath)) {
                $pdf->Image($logoPath, 25, 10, 35);
            }
            $pdf -> SetFont('Arial','B',16);
            $pdf -> Cell(0,10,utf8_decode('Reporte General - Servicios/Trámites'),0,1,'C');
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(0,6, utf8_decode('Generado: ' . date('Y-m-d H:i:s')), 0, 1, 'C');
            $pdf->Ln(4);
            $pdf->Ln(5);
            $pdf -> Image($filename,25,40,160,160);
            // La página NUEVA CREADA - Permitira imprimir las consultas que se obtuvieron de la BD
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,8, utf8_decode('Detalle de consultas registradas'), 0, 1, 'C');
            $pdf->Ln(2);
            // Encabezado de tabla
            $pdf->SetFont('Arial','B',9);
            // Definimos la anchura que tendra cada Celda impresa en el PDF
            $w = [
                'Folio' => 18,
                'Matricula' => 40,
                'IdTramite' => 18,
                'FechaHora' => 36,
                'Estatus' => 24,
                'Tramite' => 40
            ];
            $pdf->Ln(15);
            // Cabeceras
            $pdf->Cell($w['Folio'],7, utf8_decode('Folio'),1,0,'C');
            $pdf->Cell($w['Matricula'],7, utf8_decode('Matrícula'),1,0,'C');
            $pdf->Cell($w['IdTramite'],7, utf8_decode('IdTramite'),1,0,'C');
            $pdf->Cell($w['FechaHora'],7, utf8_decode('FechaHora'),1,0,'C');
            $pdf->Cell($w['Estatus'],7, utf8_decode('Estatus'),1,0,'C');
            $pdf->Cell($w['Tramite'],7, utf8_decode('Trámite'),1,0,'C');
            $pdf->Ln();

            // Filas para poner en cada descripción ó texto recuperado de la BD
            $pdf->SetFont('Arial','',9);
            $descWidth = 158; // Ancho para poner en la descripción
            foreach($data as $r){
                // Ingresamos/añadimos los encabezados y logos a cada HOJA (a la hoja en que se empiece a escribir)
                if ($logoPath && file_exists($logoPath)) {
                    $pdf->Image($logoPath, 185, 10, 30);
                }
                // Preparar datos
                $folio = utf8_decode($r[0]);
                $mat = utf8_decode($r[1]);
                $idTr = utf8_decode($r[2]);
                $fecha = utf8_decode($r[3]);
                $estatus = utf8_decode($r[5] ?: $r[6]);
                $tramiteDesc = utf8_decode($r[7]);
                $descripcion = utf8_decode($r[4]);
                // Insertamos la fila de los datos generales, sin incluir la descripción
                $pdf->Cell($w['Folio'],6, $folio,1,0,'C');
                $pdf->Cell($w['Matricula'],6, $mat,1,0,'C');
                $pdf->Cell($w['IdTramite'],6, $idTr,1,0,'C');
                $pdf->Cell($w['FechaHora'],6, $fecha,1,0,'C');
                $pdf->Cell($w['Estatus'],6, $estatus,1,0,'C');
                $pdf->Cell($w['Tramite'],6, (strlen($tramiteDesc)>30? substr($tramiteDesc,0,27).'...': $tramiteDesc),1,1,'L');
                // La segunda fila incluira la descripción, se encontrará debajo de FOLIO
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell($w['Folio'],18, utf8_decode('Descripción'),1,0,'C');
                // Celda de descripción usando MultiCell
                $pdf->SetFont('Arial','',9);
                $pdf->MultiCell($descWidth,6, $descripcion,1,'L');
                $pdf->Ln(7);
            }
            //Impresión/generar o mandar a Descargas el PDF generado
            $nombreRep = "ReporteAdmin_General_Tramites_-".$fechaHora.".pdf";
            //$pdf->Output('D', $nombreRep);
            $pdf->Output();
            
        }
        public function reporteGeneral2_Admin(){
            $dataPastel = $this->reportModel->reporteGeneral2_Admin_Pastel();
            //Si los datos no existen, o son NULOS(VACIOS), GENERARA UNA GRÁFICA TEMPORAL
            if (empty($dataPastel)) {
                // Generar PDF con mensaje de error/sin datos
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, utf8_decode('Reporte General - Servicios/Trámites'), 0, 1, 'C');
                $pdf->Ln(20);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, utf8_decode('No se encontraron resultados en la BD para este reporte.'), 0, 1, 'C');
                
                date_default_timezone_set('America/Mexico_City');
                $fechaHora = date('Y-m-d_H-i-s');
                $nombreRep = "ReporteAdmin_General_SIN_DATOS_".$fechaHora.".pdf";
                $pdf->Output('D', $nombreRep);
                exit(); // Detener la ejecución para no intentar dibujar la gráfica
            }
            //Creamos la gráfica
            $plot = new PHPLot(800,600);//plot: Referencia a PHPLot
            $plot -> SetDataValues($dataPastel); //Agregar los datos de la gráfica
            $plot -> SetPlotType("pie"); //pie - Pastel || Indicar que la gráfica es de pastel
            $plot -> SetDataType('text-data-single'); //Indicar que los datos se manejan como texto
            $plot -> SetTitle(utf8_decode('Porcentaje de Usuarios registrados por Dirección'));
            $plot -> SetLegend(array_column($dataPastel, 0)); //Indicar la simbología de la gráfica
            date_default_timezone_set('America/Mexico_City'); // Ajusta tu zona horaria
            $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos
            $filename = "public/media/graphs/graficaUsuariosRegistrados_".$fechaHora.".png";
            $plot -> SetOutputFile($filename);
            $plot -> setIsInline(true); //Se queda guardada en el sistema
            $plot -> DrawGraph();
            //GENERAR PDF
            $pdf = new FPDF();
            $pdf -> AddPage();
            // Encabezado y logo para incluir dentro de el Reporte
            $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');   
            if ($logoPath && file_exists($logoPath)) {
                $pdf->Image($logoPath, 25, 10, 45);
            }
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(0,10,'Reporte de Usuarios', 0,1,'C');
            $pdf->AliasNbPages();
            $pdf -> Image($filename,30,60,150,180);
            $pdf->Ln(10);
            $pdf->SetAutoPageBreak(true, 50);
            $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/
            $pdf->AddFont('DMSerifText-Regular','','DMSerifText-Regular.php'); // https://www.fpdf.org/makefont/
            $pdf->AddFont('DMSerifText-Italic','','DMSerifText-Italic.php'); // https://www.fpdf.org/makefont/
            $pdf->AddFont('Alegreya-VariableFont_wght','','Alegreya-VariableFont_wght.php'); // https://www.fpdf.org/makefont/
            //Aquí generamos los datos que tendrá el reporte
            $data2 = $this->reportModel->reporteGeneral2_Datos();
            $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
            $generatedPages = 0;
            foreach($data2 as $D) {
                $generatedPages++;
                $pdf->AddPage();
                if ($logoPath && file_exists($logoPath)) {
                    $pdf->Image($logoPath, 12, 8, 28);
                }
                $pdf->SetFont('DMSerifText-Regular','',14);
                $pdf->SetXY(40, 10);
                $pdf->Cell(110, 7, utf8_decode('REPORTE GENERAL - USUARIOS'), 0, 1, 'C');
                $pdf->SetFont('DMSerifText-Regular','',9);
                $pdf->SetXY(40, 17);
                $pdf->Cell(110, 5, 'Fecha del reporte: ' . $fechaHora, 0, 1, 'C');
                $pdf->SetDrawColor(180,180,180);
                $pdf->Line(10, 30, $pdf->GetPageWidth() - 10, 30);
                $pdf->Ln(6);

                $id = utf8_decode($D['id_usuario'] ??  ($D[0] ?? ''));
                $n = utf8_decode($D['nombre'] ??  ($D[1] ?? ''));
                $apP = utf8_decode($D['apellido_paterno'] ??  ($D[2] ?? ''));
                $apM = utf8_decode($D['apellido_materno'] ??  ($D[3] ?? ''));
                $gen = utf8_decode($D['genero'] ??  ($D[4] ?? ''));
                $usr = utf8_decode($D['usr'] ??  ($D[5] ?? ''));
                $ema = utf8_decode($D['email'] ??  ($D[6] ?? ''));
                $passw = utf8_decode($D['passw'] ??  ($D[7] ?? ''));
                $rol = utf8_decode($D['rol'] ??  ($D[8] ?? ''));
                $idDepto = utf8_decode($D['idDepto'] ??  ($D[9] ?? ''));
                $feReg = utf8_decode($D['FechaRegistro'] ??  ($D[10] ?? ''));
                $nomD = utf8_decode($D['NombreDepto'] ??  ($D[11] ?? ''));
                $nombreCompleto = ($n. " " . $apP . " " . $apM);
                $ladoIzqEnX  = 12;
                $ladoDerEnX = 120;
                $pdf->SetFont('DMSerifText-Regular','',11);
                $yStart = $pdf->GetY() + 5;
                /*ASIGNAMOS LAS CELDAS/CAJAS PARA LA INSERSIÓN DE LOS DATOS*/
                //LADO IZDA.
                $pdf->SetXY($ladoIzqEnX, $yStart);
                $pdf->Cell(30,6, utf8_decode('Usuario:'), 0, 0);
                $pdf->SetFont('Alegreya-VariableFont_wght','',11);
                $pdf->Cell(60,6, $usr, 0, 1);
                $pdf->SetXY($ladoIzqEnX, $pdf->GetY());
                $pdf->SetFont('DMSerifText-Regular','',11);
                $pdf->Cell(30,6, 'Nombre:', 0, 0);
                $pdf->SetFont('Alegreya-VariableFont_wght','',11);
                $pdf->MultiCell(78,6, $nombreCompleto, 0, 'L');
                $pdf->SetXY($ladoIzqEnX, $pdf->GetY());
                $pdf->SetFont('DMSerifText-Regular','',11);
                $pdf->Cell(30,6,'Registro: ',0,0);
                $pdf->SetFont('Alegreya-VariableFont_wght','',11);
                $pdf->Cell(30,6, $feReg, 0, 1);
                //LADO DCHA.
                $pdf->SetXY($ladoDerEnX, $yStart);
                $pdf->SetFont('DMSerifText-Regular','',11);
                $pdf->Cell(30,6,'Departamento:',0,0);
                $pdf->SetFont('Alegreya-VariableFont_wght','',11);
                $pdf->Cell(0,6, ($idDepto ."-".$nomD), 0, 1);
                $pdf->SetXY($ladoDerEnX, $pdf->GetY());
                $pdf->SetFont('DMSerifText-Regular','',11);
                $pdf->Cell(30,6,utf8_decode('Género:'),0,0);
                $pdf->SetFont('Alegreya-VariableFont_wght','',11);
                $pdf->Cell(0,6, $gen, 0, 1);
                $pdf->SetXY($ladoDerEnX, $pdf->GetY());
                $pdf->SetFont('DMSerifText-Regular','',11);
                $pdf->Cell(30,6,'Email:',0,0);
                $pdf->SetFont('Alegreya-VariableFont_wght','',11);
                $pdf->Cell(0,6, $ema, 0, 1);
                $pdf->SetXY($ladoDerEnX, $pdf->GetY());
                $pdf->SetFont('DMSerifText-Regular','',11);
                $pdf->Cell(30,6,'Rol:',0,0);
                $pdf->SetFont('Alegreya-VariableFont_wght','',11);
                $pdf->Cell(0,6, $rol, 0, 1);
                $pdf->Ln(6);
                // Footer por página
                $pageWidth = $pdf->GetPageWidth();
                $margin = 50;
                $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                $pdf->SetTextColor(80);
                $leftText = utf8_decode("Generado por: IdentiQR - Fecha del reporte: $fechaHora");
                $pdf->SetX($margin);
                $pdf->Cell($pageWidth - 2*$margin - 40, 6, $leftText, 0, 0, 'L');
                $pdf->SetX($pageWidth - $margin - 40);
                $pdf->Cell(40, 6, utf8_decode('Página ') . $pdf->PageNo() . '/{nb}', 0, 0, 'R');
                $pdf->SetTextColor(0);
            }
            // Si no se generó nada
            if ($generatedPages === 0) {
                $pdf->AddPage();
                $pdf->SetFont('Arial','B',12);
                $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                    
                if ($logoPath && file_exists($logoPath)) {
                    $pdf->Image($logoPath, 25, 10, 45);
                }
                $pdf->Cell(0,10, utf8_decode('No se encontraron registros para los parámetros indicados'), 0, 1, 'C');
            }
            
            $nombreRep_Dir = "reporteAdmin_General_Usuarios-".$fechaHora.".pdf";
            $pdf->Output('D', $nombreRep_Dir); //Descargar directamente
            //INCLUIMOS LA VISTA
            $vista = __DIR__ . '/../Views/GestionesAdministradorG.php';
            if (file_exists($vista)) {
                include_once $vista;
            }
        }

        public function reporteGeneral3_Admin(){
            $dataPastel = $this->reportModel->reporteGeneral3_AlumnosAdmin_Pastel();
            if (empty($dataPastel)) {
                // Generar PDF con mensaje de error/sin datos
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, utf8_decode('Reporte General - Servicios/Trámites'), 0, 1, 'C');
                $pdf->Ln(20);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, utf8_decode('No se encontraron resultados en la BD para este reporte de Alumnos.'), 0, 1, 'C');
                date_default_timezone_set('America/Mexico_City');
                $fechaHora = date('Y-m-d_H-i-s');
                $nombreRep = "ReporteAdmin_General_Alumnos_SIN_DATOS_".$fechaHora.".pdf";
                $pdf->Output('D', $nombreRep);
                exit(); // Detener la ejecución para no intentar dibujar la gráfica
            }
            
            //Creamos la gráfica
            $plot = new PHPLot(800,600);//plot: Referencia a PHPLot

            $plot -> SetDataValues($dataPastel); //Agregar los datos de la gráfica
            $plot -> SetPlotType("pie"); //pie - Pastel || Indicar que la gráfica es de pastel
            $plot -> SetDataType('text-data-single'); //Indicar que los datos se manejan como texto
            $plot -> SetTitle(utf8_decode('Porcentaje de Alumnos registrados por Carrera'));

            $plot -> SetLegend(array_column($dataPastel, 0)); //Indicar la simbología de la gráfica

            date_default_timezone_set('America/Mexico_City'); // Ajusta tu zona horaria
            $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos

            $filename = "public/media/graphs/graficaAlumnosRegistrados_".$fechaHora.".png";

            $plot -> SetOutputFile($filename);
            $plot -> setIsInline(true); //Se queda guardada en el sistema
            $plot -> DrawGraph();

            //GENERAR PDF
            $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
            $pdf = new FPDF('L', 'mm', 'A4');
            $pdf -> AddPage();
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(0,10,'Reporte de Alumnos', 0,1,'C');
            $pdf->AliasNbPages();
            if ($logoPath && file_exists($logoPath)) {
                $pdf->Image($logoPath, 12, 8, 28);
                //pdf -> Image(ruta, X, Y, ancho, alto);
            }
            $pdf -> Image($filename,50,50,200,120);
            $pdf->Ln(10);
            
            $pdf->SetAutoPageBreak(true, 50);

            $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/
            $pdf->AddFont('DMSerifText-Regular','','DMSerifText-Regular.php'); // https://www.fpdf.org/makefont/
            $pdf->AddFont('DMSerifText-Italic','','DMSerifText-Italic.php'); // https://www.fpdf.org/makefont/
            $pdf->AddFont('Alegreya-VariableFont_wght','','Alegreya-VariableFont_wght.php'); // https://www.fpdf.org/makefont/

            //Aquí generamos los datos que tendrá el reporte
            $data2 = $this->reportModel->reporteGeneral3_Datos();
            $cont = 0;

            //Aquí empezamos a agregar las paginas para el reporte
            $pdf->AddPage();
            //Creamos el titulo del documento
            $pdf -> setFont('Alegreya-VariableFont_wght','', 16); //Cambia el tipo de letra
            $pdf -> Cell(0,10,"Reporte de Alumnos",0,1,'C'); //Título del documento - Ancho-Altura-Texto-Tipo de borde-Salto de linea-Alineación
            $pdf->Ln(12); //Agregar un salto de línea de 10px | Tamaño del salto de linea (Tamaño)
            if ($logoPath && file_exists($logoPath)) {
                $pdf->Image($logoPath, 12, 8, 28);
            }
            
            //Asignamos los anchos que se tendrá para la hoja tamaño A4
            $w1 = 40;  // Col 1: Matrícula / Carrera
            $w2 = 118; // Col 2: Nombre / Fecha Inicio
            $w3 = 119; // Col 3: Correo / Fecha Fin

            //Header
            $pdf->SetFont('Arial', 'B', 10);
            
            // Fila Superior de Títulos
            $pdf->SetFillColor(30, 30, 30);    // Gris casi negro
            $pdf->SetTextColor(255, 255, 255); // Blanco
            $pdf->Cell($w1, 8, utf8_decode('MATRÍCULA'), 1, 0, 'C', true);        
            $pdf->Cell($w2, 8, 'NOMBRE COMPLETO', 1, 0, 'C', true);        
            $pdf->Cell($w3, 8, utf8_decode('CORREO ELECTRÓNICO'), 1, 1, 'C', true);
            
            // Fila Inferior de Títulos
            $pdf->SetFillColor(100, 100, 100); // Gris medio
            $pdf->Cell($w1, 8, 'CARRERA', 1, 0, 'C', true);        
            $pdf->Cell($w2, 8, utf8_decode('FECHA EXPEDICIÓN QR'), 1, 0, 'C', true);        
            $pdf->Cell($w3, 8, utf8_decode('FECHA VENCIMIENTO QR'), 1, 1, 'C', true);
            
            $pdf->SetTextColor(0, 0, 0);

            $pdf->SetFont('Arial', '', 10); 
            $cont = 0; 

            foreach($data2 as $D){
                // Verificar si cabe el bloque
                if ($pdf->GetY() > 175) { 
                    $pdf->AddPage();
                    $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                    if ($logoPath && file_exists($logoPath)) {
                        $pdf->Image($logoPath, 25, 10, 45);
                    }
                    // Repetir Cabecera en nueva página
                    $pdf->SetFont('Arial', 'B', 10);
                    $pdf->SetFillColor(30, 30, 30);     
                    $pdf->SetTextColor(255, 255, 255); 
                    $pdf->Cell($w1, 8, utf8_decode('MATRÍCULA'), 1, 0, 'C', true);        
                    $pdf->Cell($w2, 8, 'NOMBRE COMPLETO', 1, 0, 'C', true);        
                    $pdf->Cell($w3, 8, utf8_decode('CORREO ELECTRÓNICO'), 1, 1, 'C', true);
                    
                    $pdf->SetFillColor(100, 100, 100);
                    $pdf->Cell($w1, 8, 'CARRERA', 1, 0, 'C', true);        
                    $pdf->Cell($w2, 8, utf8_decode('FECHA EXPEDICIÓN QR'), 1, 0, 'C', true);        
                    $pdf->Cell($w3, 8, utf8_decode('FECHA VENCIMIENTO QR'), 1, 1, 'C', true);
                    
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Arial', '', 10); 
                }

                $cont++;
                $matricula = utf8_decode($D[0]);
                
                // Concatenar y disminuir la cant. de caracteres
                $nombreFull = $D[1] . " " . $D[2] . " " . $D[3];
                $nombre     = utf8_decode(substr($nombreFull, 0, 55));
                $carrera    = utf8_decode($D[16] ?? ''); 
                $correo     = utf8_decode(substr($D[6], 0, 55));
                $fechaIni   = utf8_decode($D[14]);
                $fechaFin   = utf8_decode($D[15]);

                if ($cont % 2 == 0) {
                    $pdf->SetFillColor(235, 235, 235); 
                } else {
                    $pdf->SetFillColor(255, 255, 255); 
                }
                // Esto hace que se vea abierto por abajo, conectando con la fila siguiente
                $pdf->SetFont('Arial', 'B', 10); // Negrita para resaltar
                $pdf->Cell($w1, 8, $matricula, 'LTR', 0, 'C', true);
                $pdf->Cell($w2, 8, '  ' . $nombre, 'LTR', 0, 'L', true);
                $pdf->Cell($w3, 8, '  ' . $correo, 'LTR', 1, 'L', true); // Salto de línea
                // Esto cierra el bloque
                $pdf->SetFont('Arial', '', 9); 
                $pdf->Cell($w1, 8, $carrera, 'LBR', 0, 'C', true);
                $pdf->Cell($w2, 8, $fechaIni, 'LBR', 0, 'C', true);
                $pdf->Cell($w3, 8, $fechaFin, 'LBR', 1, 'C', true); // Salto de línea
            }
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Total de Alumnos: ' . number_format($cont, 0), 0, 1, 'R');

            $nombreRep_Dir = "reporteGeneral_Administracion3_".$fechaHora.".pdf";
            $pdf-> Output('D', $nombreRep_Dir);
            exit();
            $vista = __DIR__ . '/../Views/GestionesAdministradorG.php';
            if (file_exists($vista)) {
                include_once $vista; // o require_once $vista;
            }
            exit();
        }

        //idDepto = 2; Dirección acádemica
        public function reporteGeneral_DirAca(){
            if(isset($_POST['reporteIndividualizado_DirAca'])){
                $dataPastel = $this->reportModel->reporteGeneral3_AlumnosAdmin_Pastel();
                $dataPastel2 = $this->reportModel->reporteGeneral_DirAca_Pastel();
                if (empty($dataPastel) || empty($dataPastel2) || empty($dataPastel) && empty($dataPastel2)) {
                    // Generar PDF con mensaje de error/sin datos
                    $pdf = new FPDF();
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', 'B', 16);
                    $pdf->Cell(0, 10, utf8_decode('Reporte General - Servicios/Trámites'), 0, 1, 'C');
                    $pdf->Ln(20);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Cell(0, 10, utf8_decode('No se encontraron resultados en la BD para este reporte.'), 0, 1, 'C');
                    
                    date_default_timezone_set('America/Mexico_City');
                    $fechaHora = date('Y-m-d_H-i-s');
                    $nombreRep = "ReporteAdmin_Gen_SIN_DATOS_".$fechaHora.".pdf";
                    $pdf->Output('D', $nombreRep);
                    exit(); // Detener la ejecución para no intentar dibujar la gráfica
                }
                
                //Creamos la gráfica 1
                $plot = new PHPLot(800,600);//plot: Referencia a PHPLot
                $plot -> SetDataValues($dataPastel); //Agregar los datos de la gráfica
                $plot -> SetPlotType("pie"); //pie - Pastel || Indicar que la gráfica es de pastel
                $plot -> SetDataType('text-data-single'); //Indicar que los datos se manejan como texto
                $plot -> SetTitle(utf8_decode('Porcentaje de Alumnos registrados por Carrera'));

                $plot -> SetLegend(array_column($dataPastel, 0)); //Indicar la simbología de la gráfica

                date_default_timezone_set('America/Mexico_City'); // Ajusta la zona horaria
                $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos

                $filename = "public/media/graphs/graficaAlumnosRegistrados_".$fechaHora.".png";

                $plot -> SetOutputFile($filename);
                $plot -> setIsInline(true); //Se queda guardada en el sistema
                $plot -> DrawGraph();

                //Generamos la segunda grafica
                $plot2 = new PHPLot(800,600);//plot: Referencia a PHPLot
                $plot2 -> SetDataValues($dataPastel2); //Agregar los datos de la gráfica
                $plot2 -> SetPlotType("pie"); //pie - Pastel || Indicar que la gráfica es de pastel
                $plot2 -> SetDataType('text-data-single'); //Indicar que los datos se manejan como texto
                $plot2 -> SetTitle(utf8_decode('Porcentaje de Tramites realizados por alumnos'));

                $plot2 -> SetLegend(array_column($dataPastel2, 0)); //Indicar la simbología de la gráfica

                $filename2 = "public/media/graphs/graficaAlumnosTramites_".$fechaHora.".png";

                $plot2 -> SetOutputFile($filename2);
                $plot2 -> setIsInline(true); //Se queda guardada en el sistema
                $plot2 -> DrawGraph();

                //GENERAR PDF
                $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                $pdf = new FPDF('L', 'mm', 'A4');
                $pdf -> AddPage();
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(0,10,'Reporte de Alumnos y Tramites', 0,1,'C');
                $pdf->AliasNbPages();
                if ($logoPath && file_exists($logoPath)) {
                    $pdf->Image($logoPath, 12, 8, 28);
                    //pdf -> Image(ruta, X, Y, ancho, alto);
                }
                
                $pdf -> Image($filename,50,50,200,120);
                $pdf->Ln(10);
                $pdf->AddPage();
                $pdf -> Image($filename2,50,50,200,120);
                $pdf->Ln(10);
                
                $pdf->SetAutoPageBreak(true, 50);

                $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Regular','','DMSerifText-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Italic','','DMSerifText-Italic.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('Alegreya-VariableFont_wght','','Alegreya-VariableFont_wght.php'); // https://www.fpdf.org/makefont/
                //Aquí generamos los datos que tendrá el reporte
                $data2 = $this->reportModel->reporteGeneral3_Datos();
                $cont = 0;
                //Aquí empezamos a agregar las paginas para el reporte
                $pdf->AddPage();
                //Creamos el titulo del documento
                $pdf -> setFont('Alegreya-VariableFont_wght','', 16); //Cambia el tipo de letra
                $pdf -> Cell(0,10,"Reporte de Alumnos",0,1,'C'); //Título del documento - Ancho-Altura-Texto-Tipo de borde-Salto de linea-Alineación
                $pdf->Ln(12); //Agregar un salto de línea de 10px | Tamaño del salto de linea (Tamaño)
                if ($logoPath && file_exists($logoPath)) {
                    $pdf->Image($logoPath, 12, 8, 28);
                }
                
                // Asignamos a las variables los ANCHOS TOTALES
                $w1 = 40;  // Col 1: Matrícula / Carrera
                $w2 = 118; // Col 2: Nombre / Fecha Inicio
                $w3 = 119; // Col 3: Correo / Fecha Fin

                // Header
                $pdf->SetFont('Arial', 'B', 10);
                
                // Fila Superior de Títulos
                $pdf->SetFillColor(30, 30, 30);    
                $pdf->SetTextColor(255, 255, 255); // Blanco
                $pdf->Cell($w1, 8, utf8_decode('MATRÍCULA'), 1, 0, 'C', true);        
                $pdf->Cell($w2, 8, 'NOMBRE COMPLETO', 1, 0, 'C', true);        
                $pdf->Cell($w3, 8, utf8_decode('CORREO ELECTRÓNICO'), 1, 1, 'C', true);
                
                // Fila Inferior de Títulos
                $pdf->SetFillColor(100, 100, 100); 
                $pdf->Cell($w1, 8, 'CARRERA', 1, 0, 'C', true);        
                $pdf->Cell($w2, 8, utf8_decode('FECHA EXPEDICIÓN QR'), 1, 0, 'C', true);        
                $pdf->Cell($w3, 8, utf8_decode('FECHA VENCIMIENTO QR'), 1, 1, 'C', true);
                
                $pdf->SetTextColor(0, 0, 0);

                $pdf->SetFont('Arial', '', 10); 
                $cont = 0; 

                foreach($data2 as $D){
                    // Verificar si cabe el bloque
                    if ($pdf->GetY() > 175) { 
                        $pdf->AddPage();
                        // Repetir Cabecera en nueva página
                        $pdf->SetFont('Arial', 'B', 10);
                        $pdf->SetFillColor(30, 30, 30);     
                        $pdf->SetTextColor(255, 255, 255); 
                        $pdf->Cell($w1, 8, utf8_decode('MATRÍCULA'), 1, 0, 'C', true);        
                        $pdf->Cell($w2, 8, 'NOMBRE COMPLETO', 1, 0, 'C', true);        
                        $pdf->Cell($w3, 8, utf8_decode('CORREO ELECTRÓNICO'), 1, 1, 'C', true);
                        
                        $pdf->SetFillColor(100, 100, 100);
                        $pdf->Cell($w1, 8, 'CARRERA', 1, 0, 'C', true);        
                        $pdf->Cell($w2, 8, utf8_decode('FECHA EXPEDICIÓN QR'), 1, 0, 'C', true);        
                        $pdf->Cell($w3, 8, utf8_decode('FECHA VENCIMIENTO QR'), 1, 1, 'C', true);
                        
                        $pdf->SetTextColor(0, 0, 0);
                        $pdf->SetFont('Arial', '', 10); 
                    }
                    $cont++;
                    $matricula = utf8_decode($D[0]);
                    
                    // Unimos el nombre a max. 55 caracteres
                    $nombreFull = $D[1] . " " . $D[2] . " " . $D[3];
                    $nombre     = utf8_decode(substr($nombreFull, 0, 55));
                    
                    $carrera    = utf8_decode($D[16] ?? ''); 
                    
                    // Correo a max. 55 caracteres (En caso de ser MUUUUUUUUY LARGO)
                    $correo     = utf8_decode(substr($D[6], 0, 55));
                    $fechaIni   = utf8_decode($D[14]);
                    $fechaFin   = utf8_decode($D[15]);
                    if ($cont % 2 == 0) {
                        $pdf->SetFillColor(235, 235, 235); 
                    } else {
                        $pdf->SetFillColor(255, 255, 255); 
                    }


                    // Esto hace que se vea abierto por abajo, conectando con la fila siguiente
                    $pdf->SetFont('Arial', 'B', 10); // Negrita para resaltar
                    $pdf->Cell($w1, 8, $matricula, 'LTR', 0, 'C', true);
                    $pdf->Cell($w2, 8, '  ' . $nombre, 'LTR', 0, 'L', true); // '  ' es un margen visual
                    $pdf->Cell($w3, 8, '  ' . $correo, 'LTR', 1, 'L', true); // Salto de línea

                    // Esto cierra el bloque
                    $pdf->SetFont('Arial', '', 9); // Fuente normal un poco más pequeña
                    $pdf->Cell($w1, 8, $carrera, 'LBR', 0, 'C', true);
                    $pdf->Cell($w2, 8, $fechaIni, 'LBR', 0, 'C', true);
                    $pdf->Cell($w3, 8, $fechaFin, 'LBR', 1, 'C', true);
                }

                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Total de Alumnos: ' . number_format($cont, 0), 0, 1, 'R');

                $nombreRep_Dir = "reporteGeneral_DirAca_".$fechaHora.".pdf";
                //$pdf-> Output('D', $nombreRep_Dir);
                $pdf->Output();
                exit();
                $vista = __DIR__ . '/../Views/GestionesAdministradorG.php';
                if (file_exists($vista)) {
                    include_once $vista; // o require_once $vista;
                }
                exit();
            }
        }
        public function reporteGeneral2_DirAca(){
            date_default_timezone_set('America/Mexico_City');
            $fechaHora = date('Y-m-d_H-i-s');
            //Este reporte solo generará un GRÁFICO
            $data = $this->reportModel->reporteGeneral2_DirAca_Barras();
            //Validamos
            if (empty($data)) {
                // Generar PDF con mensaje de error/sin datos
                $pdf = new FPDF();
                $pdf->AddPage();
                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, utf8_decode('Reporte General - Servicios/Trámites'), 0, 1, 'C');
                $pdf->Ln(20);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, utf8_decode('No se encontraron trámites registrados para este reporte.'), 0, 1, 'C');
                
                date_default_timezone_set('America/Mexico_City');
                $fechaHora = date('Y-m-d_H-i-s');
                $nombreRep = "ReporteAdmin_General_Tramites_SIN_DATOS_".$fechaHora.".pdf";
                $pdf->Output('D', $nombreRep);
                exit(); // Detener la ejecución para no intentar dibujar la gráfica
            }
            //Creamos la gráfica
            $plot = new PHPLot(800,600);//plot: Referencia a PHPLot
            $plot -> SetImageBorderType('plain'); //Tipo de borde
            $plot ->SetPlotType('bars'); //Indica el tipo de gráfica
            $plot -> SetDataType('text-data'); //El tipo de datos en la gráfica
            $plot -> SetDataValues($data); //Añadir los valores de la gráfica
            
            //ESTILO DE LA GRÁFICA
            $plot -> SetTitle('Tramite-Descripción');
            $plot -> SetXTitle(utf8_decode('Descripción del trámite'));
            $plot -> SetYTitle('Total de registros');
            $plot -> SetShading(5); //Añadir una sombra para efecto 3D
            $plot -> SetDataColors(['#800080']);
            //Generar gráfica
            $filename = "public/media/graphs/grafica_Tramites_DirAcademica_".$fechaHora.".png"; //Ruta de imagen
            $plot -> SetOutputFile($filename); //Guardar imagen de la gráfica
            $plot -> SetIsInline(true); //Guardar gráfica en el sistema
            $plot -> DrawGraph(); // Genera la gráfica
            //Generar el PDF
            $pdf = new FPDF();
            $pdf -> AddPage();
            $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
            $logoPathDir = realpath(__DIR__ . '/../../public/Media/img/DireccionAca_Index1.png');
            if ($logoPath && file_exists($logoPath)) {
                $pdf->Image($logoPath, 15, 5, 45);
            }
            if ($logoPathDir && file_exists($logoPathDir)) {
                $pdf->Image($logoPathDir, $pdf->GetPageWidth() - 55, 5, 45);
            }
            $pdf -> SetFont('Arial','B',16);
            $pdf -> Cell(0,10,'Reporte de Tramites realizados',0,1,'C');
            $pdf -> SetFont('Arial','B',10);
            $pdf -> Cell(0,10,'fecha: ' . $fechaHora,0,1,'C');
            $pdf -> Image($filename,30,40,150,100);
            $nombreRep_Dir = "reporteGeneral_DirAcademica_TramitesRealizados_".$fechaHora.".pdf";
            $pdf-> Output('D', $nombreRep_Dir);
            exit();
        }
        
        //idDepto = 3; Servicios escolares
        public function reporteGeneral_ServEsco(){
            if(isset($_POST['reporteIndividualizado_ServEsco'])){
                $dataPastel = $this->reportModel->reporteGeneral_ServEsco_Pastel();   
                //Validamos que la gráfica NO se encuentre vacia
                if (empty($dataPastel)) {
                    // Generar PDF con mensaje de error/sin datos
                    $pdf = new FPDF();
                    $pdf->AddPage();
                    $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php');
                    $pdf->SetFont('ShadowsIntoLight-Regular', '', 16);
                    $pdf->Cell(0, 10, utf8_decode('Reporte General - Servicios/Trámites'), 0, 1, 'C');
                    $pdf->Ln(20);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Cell(0, 10, utf8_decode('No se encontraron trámites registrados para este reporte.'), 0, 1, 'C');
                    
                    date_default_timezone_set('America/Mexico_City');
                    $fechaHora = date('Y-m-d_H-i-s');
                    $nombreRep = "Reporte_General_Tramites_SIN_DATOS_".$fechaHora.".pdf";
                    $pdf->Output('D', $nombreRep);
                    //$pdf->Output();
                    exit(); // Detener la ejecución para no intentar dibujar la gráfica
                }             
                
                //Creamos la gráfica 1
                $plot = new PHPLot(800,600);//plot: Referencia a PHPLot
                $plot -> SetDataValues($dataPastel); //Agregar los datos de la gráfica
                $plot -> SetPlotType("pie"); //pie - Pastel || Indicar que la gráfica es de pastel
                $plot -> SetDataType('text-data-single'); //Indicar que los datos se manejan como texto
                $plot -> SetTitle(utf8_decode('Porcentaje de Alumnos registrados por Carrera'));

                //$dataPaste_Decode = utf8_decode($dataPastel);
                $plot -> SetLegend(array_column($dataPastel, 0)); //Indicar la simbología de la gráfica

                date_default_timezone_set('America/Mexico_City'); // Ajusta tu zona horaria
                $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos

                $filename = "public/media/graphs/graficaAlumnosRegistrados_".$fechaHora.".png";

                $plot -> SetOutputFile($filename);
                $plot -> setIsInline(true); //Se queda guardada en el sistema
                $plot -> DrawGraph();

                //GENERAR PDF
                $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                $logoPathDir = realpath(__DIR__ . '/../../public/Media/img/ServicioEscolares_Index1.png');
                $pdf = new FPDF();
                $pdf -> AddPage();
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(0,10,'Reporte de Alumnos y Tramites', 0,1,'C');
                $pdf->AliasNbPages();
                // Header: logo + titulo + fecha
                if ($logoPath && file_exists($logoPath)) {
                    $pdf->Image($logoPath, 12, 8, 28);
                }
                //pdf -> Image(ruta, X, Y, ancho, alto);
                $pdf -> Image($filename,5,50,200,120);
                $pdf->Ln(10);
                
                $pdf->SetAutoPageBreak(true, 50);

                $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Regular','','DMSerifText-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Italic','','DMSerifText-Italic.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('Alegreya-VariableFont_wght','','Alegreya-VariableFont_wght.php'); // https://www.fpdf.org/makefont/

                //Aquí generamos los datos que tendrá el reporte
                $data2 = $this->reportModel->reporteGeneral_ServEsco_Datos();
                
                $cont = 0;

                //Aquí empezamos a agregar las paginas para el reporte
                $pdf->AddPage();
                //Creamos el titulo del documento
                $pdf -> setFont('Alegreya-VariableFont_wght','', 16); //Cambia el tipo de letra
                $pdf -> Cell(0,10,"Reporte de Tramites en Serviciós escolares",0,1,'C'); //Título del documento - Ancho-Altura-Texto-Tipo de borde-Salto de linea-Alineación
                $pdf->Ln(12); //Agregar un salto de línea de 10px | Tamaño del salto de linea (Tamaño)
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
                
                //Asignamos el tamaño para el ancho (A4)
                $w1 = 40;  // Col 1: Matrícula / Carrera
                $w2 = 118; // Col 2: Nombre / Fecha Inicio
                $w3 = 119; // Col 3: Correo / Fecha Fin

                // HEADER
                $pdf->SetFont('Arial', 'B', 10);
                
                $cont = 0;
                //Validar que cuente con información
                if(empty($data2) || $data2 === false || $data2 === null){
                    $pdf-> SetFont('Arial','B',12);
                    $pdf->SetFillColor(0,0,0); //Agregar un color a la celda
                    $pdf->SetTextColor(255,255,255); //Cambiar el texto a blanco
                    $pdf->Cell(150,10,utf8_decode("No se cuenta con información"),1,1,'C',true);
                } else {
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
                    foreach($data2 as $rows){
                        // Verificar si cabe el bloque de 2 filas (aprox 16mm)
                        //Creamos o añadimos la información
                        $pdf-> SetFont('Alegreya-VariableFont_wght','',12);
                        $pdf-> SetFont('Alegreya-VariableFont_wght','',12);
                        $pdf -> Cell(30,20,utf8_decode("Matrícula"),1,0,'C',false);    
                        $pdf -> Cell(60,20,utf8_decode("FolioRegistro"),1,0,'C',false);    
                        $pdf -> Cell(60,20,utf8_decode("FolioRastreo"),1,0,'C',false);    
                        $pdf -> Cell(30,20,utf8_decode("Estatus"),1,0,'C',false);
                        $pdf->Ln();

                        $pdf->SetTextColor(0,0,0);
                        $pdf->SetFillColor(230, 230, 250); //Agregar un color a la celda - ESTO MODIFICA LAS CELDAS
                        $pdf->Cell(30,8,utf8_decode($rows[1] ?? ''),1,0,'C',true);
                        $pdf->Cell(60,8,utf8_decode($rows[0] ?? ''),1,0,'C',true);
                        $pdf->Cell(60,8,utf8_decode($rows[6] ?? ''),1,0,'C',true);
                        $pdf->Cell(30,8,utf8_decode($rows[5] ?? ''),1,0,'C',true);
                        $pdf->Ln();

                        $pdf -> Cell(180,10,utf8_decode("Descripción"),1,0,'C',false); 
                        $pdf->Ln();
                        $pdf->SetFillColor(230, 230, 250); //Agregar un color a la celda
                        $pdf -> Multicell(180,10,utf8_decode($rows[4] ?? ''),1,0,'C',false); 

                        $pdf->Ln(1);

                        //Datos extras
                        $pdf -> Cell(40,10,utf8_decode("Método Págo"),1,0,'C',false);
                        $pdf -> Cell(30,10,utf8_decode("Costo"),1,0,'C',false);
                        $pdf -> Cell(60,10,utf8_decode("Motivo"),1,0,'C',false);
                        $pdf -> Cell(50,10,utf8_decode("Requerimientos"),1,0,'C',false);
                        $pdf->Ln();
                        $pdf->SetFillColor(230, 230, 250); //Agregar un color a la celda
                        $pdf -> Cell(40,10,utf8_decode(obtenerMetodoPago($rows[4] ?? '')),1,0,'C',true); 
                        //$pdf->Ln();
                        $pdf->SetFillColor(230, 230, 250); //Agregar un color a la celda
                        $pdf -> Cell(30,10,utf8_decode(obtenerCostoPagado($rows[4] ?? '')),1,0,'C',true); 
                        $pdf -> Cell(60,10,utf8_decode(obtenerMotivo($rows[4] ?? '')),1,0,'C',true); 
                        $pdf -> Cell(50,10,utf8_decode(obtenerRequerimientosExtras($rows[4] ?? '')),1,0,'C',true); 
                        $pdf->Ln(15);
                        $cont++;
                    }
                }

                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Total de Tramites realizados: ' . number_format($cont, 0), 0, 1, 'R');

                $nombreRep_Dir = "reporteGeneral_ServEsco_".$fechaHora.".pdf";
                $pdf-> Output('D', $nombreRep_Dir);
                $pdf->Output();
                exit();
                $vista = __DIR__ . '/../Views/GestionesAdministradorG.php';
                if (file_exists($vista)) {
                    include_once $vista; // o require_once $vista;
                }
                exit();
            }
        }
        //idDepto = 4; Dirección Desarrollo Academico
        public function reporteGeneral_DDA(){
            if(isset($_POST['reporteIndividualizado_DDA'])){
                $tipoReporte = $_POST['tipoReporte'] ?? 1;
                $hoy = date('Y-m-d');
                $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos

                $fe1 = (!empty($_POST['fe1'])) ? $_POST['fe1'] : $hoy;
                $fe2 = (!empty($_POST['fe2'])) ? $_POST['fe2'] : $hoy;

                $idDepto = (int)($_POST['idDepto'] ?? 0);
                //GENERAR PDF
                $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                $logoPathDir = realpath(__DIR__ . '/../../public/Media/img/DDA_Index1.png');

                $pdf = new FPDF();
                $pdf -> AddPage();

                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(0,10,'Reporte de Alumnos y tutorias', 0,1,'C');
                $pdf->AliasNbPages();
                //pdf -> Image(ruta, X, Y, ancho, alto);
                //$pdf -> Image($filename,50,50,200,120);
                $pdf->Ln(10);
                
                $pdf->SetAutoPageBreak(true, 50);
                $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Regular','','DMSerifText-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Italic','','DMSerifText-Italic.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('Alegreya-VariableFont_wght','','Alegreya-VariableFont_wght.php'); // https://www.fpdf.org/makefont/

                //Aquí generamos los datos que tendrá el reporte
                $data2 = $this->reportModel->reporteGeneral_DAE($fe1,$fe2,$idDepto);

                if(empty($data2) || $data2 === false || $data2 === null){
                    $pdf-> SetFont('Arial','B',12);
                    $pdf->SetFillColor(0,0,0); //Agregar un color a la celda
                    $pdf->SetTextColor(255,255,255); //Cambiar el texto a blanco
                    $pdf->Cell(150,10,utf8_decode("No se cuenta con información"),1,1,'C',true);
                } else {
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

                    //Si no esta vacio - Hacemos el while/for-each
                    while($rows = $data2->fetch_assoc()){
                        //Creamos o añadimos la información
                        $pdf-> SetFont('Alegreya-VariableFont_wght','',12);
                        $pdf-> SetFont('Alegreya-VariableFont_wght','',12);
                        $pdf -> Cell(30,20,utf8_decode("Matrícula"),1,0,'C',false);    
                        $pdf -> Cell(60,20,utf8_decode("FolioRegistro"),1,0,'C',false);    
                        $pdf -> Cell(60,20,utf8_decode("FolioRastreo"),1,0,'C',false);    
                        $pdf -> Cell(30,20,utf8_decode("Estatus"),1,0,'C',false);
                        $pdf->Ln();

                        $pdf->SetTextColor(0,0,0);
                        $pdf->SetFillColor(230, 230, 250); //Agregar un color a la celda - ESTO MODIFICA LAS CELDAS
                        $pdf->Cell(30,8,utf8_decode($rows['Matricula'] ?? ''),1,0,'C',true);
                        $pdf->Cell(60,8,utf8_decode($rows['FolioRegistro'] ?? ''),1,0,'C',true);
                        $pdf->Cell(60,8,utf8_decode($rows['FolioSeguimiento'] ?? ''),1,0,'C',true);
                        $pdf->Cell(30,8,utf8_decode($rows['estatusT'] ?? ''),1,0,'C',true);
                        $pdf->Ln();

                        $pdf -> Cell(180,10,utf8_decode("Descripción"),1,0,'C',false); 
                        $pdf->Ln();
                        $pdf->SetFillColor(230, 230, 250); //Agregar un color a la celda
                        $pdf -> Multicell(180,10,utf8_decode($rows['descripcion'] ?? ''),1,0,'C',false); 

                        $pdf->Ln(1);
                        //Datos extras
                        $pdf -> Cell(180,10,utf8_decode("Tutor/a"),1,0,'C',false);
                        $pdf->Ln();
                        $pdf->SetFillColor(230, 230, 250); //Agregar un color a la celda
                        $pdf -> Cell(180,10,utf8_decode(obtenerTutor($rows['descripcion'] ?? '')),1,0,'C',true); 
                        $pdf->Ln(15);
                    }
                    $data2->free();
                    
                }
                $margin = 50;
                $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                $pdf->SetTextColor(80);

                
                $leftText = utf8_decode("Generado por: IdentiQR - Fecha del reporte: $hoy");

                $pdf->SetX($margin);
                $pdf->Cell(200 - 2*$margin - 40, 6, $leftText, 0, 0, 'L');

                $nombreRep_Dir = "reporteGeneral_DirDDA_".$fechaHora.".pdf";
                $pdf-> Output('D', $nombreRep_Dir);
                exit();
            }
            
        }
        //idDepto = 5; Dirección Asuntos Estudiantiles
        public function reporteGeneral_DAE(){
            if(isset($_POST['reporteIndividualizado_DAE'])){
                $tipoReporte = $_POST['tipoReporte'] ?? 1;
                $hoy = date('Y-m-d');
                $fechaHora = date('Y-m-d_H-i-s'); //Año-Mes-Dia_Hora-Minutos-Segundos

                $fe1 = (!empty($_POST['fe1'])) ? $_POST['fe1'] : $hoy;
                $fe2 = (!empty($_POST['fe2'])) ? $_POST['fe2'] : $hoy;

                $idDepto = (int)($_POST['idDepto'] ?? 0);
                //GENERAR PDF
                $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                $logoPathDir = realpath(__DIR__ . '/../../public/Media/img/DireccionAca_Index1.png');

                $pdf = new FPDF();
                $pdf -> AddPage();
                $pdf->SetFont('Arial','B',16);
                $pdf->Cell(0,10,'Reporte de Alumnos', 0,1,'C');
                $pdf->AliasNbPages();
                
                //pdf -> Image(ruta, X, Y, ancho, alto);
                //$pdf -> Image($filename,50,50,200,120);
                $pdf->Ln(10);
                
                $pdf->SetAutoPageBreak(true, 50);
                $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Regular','','DMSerifText-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Italic','','DMSerifText-Italic.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('Alegreya-VariableFont_wght','','Alegreya-VariableFont_wght.php'); // https://www.fpdf.org/makefont/

                //Aquí generamos los datos que tendrá el reporte
                $data2 = $this->reportModel->reporteGeneral_DAE($fe1,$fe2,$idDepto);

                if(empty($data2) || $data2 === false || $data2 === null){
                    $pdf-> SetFont('Arial','B',12);
                    $pdf->SetFillColor(0,0,0); //Agregar un color a la celda
                    $pdf->SetTextColor(255,255,255); //Cambiar el texto a blanco
                    $pdf->Cell(150,10,utf8_decode("No se cuenta con información"),1,1,'C',true);
                } else {
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

                    //Si no esta vacio - Hacemos el while/for-each
                    while($rows = $data2->fetch_assoc()){
                        
                        $pdf-> SetFont('Alegreya-VariableFont_wght','',12);
                        $pdf -> Cell(30,20,utf8_decode("Matrícula"),1,0,'C',false);    
                        $pdf -> Cell(60,20,utf8_decode("FolioRegistro"),1,0,'C',false);    
                        $pdf -> Cell(60,20,utf8_decode("FolioRastreo"),1,0,'C',false);    
                        $pdf -> Cell(30,20,utf8_decode("Estatus"),1,0,'C',false);
                        $pdf->Ln(15);
                        //Creamos o añadimos la información
                        $pdf-> SetFont('Alegreya-VariableFont_wght','',12);
                        $pdf->SetTextColor(0,0,0);
                        $pdf->SetFillColor(175, 238, 238); //Agregar un color a la celda - ESTO MODIFICA LAS CELDAS
                        $pdf->Cell(30,8,utf8_decode($rows['Matricula'] ?? ''),1,0,'C',true);
                        $pdf->Cell(60,8,utf8_decode($rows['FolioRegistro'] ?? ''),1,0,'C',true);
                        $pdf->Cell(60,8,utf8_decode($rows['FolioSeguimiento'] ?? ''),1,0,'C',true);
                        $pdf->Cell(30,8,utf8_decode($rows['estatusT'] ?? ''),1,0,'C',true);
                        $pdf->Ln();

                        $pdf -> Cell(180,10,utf8_decode("Descripción"),1,0,'C',false); 
                        $pdf->Ln();
                        $pdf->SetFillColor(175, 238, 238); //Agregar un color a la celda
                        $pdf -> Multicell(180,10,utf8_decode($rows['descripcion'] ?? ''),1,0,'C',false); 
                        $pdf->Ln(2);
                        //Datos extras
                        $pdf -> Cell(180,10,utf8_decode("Extracurricular"),1,0,'C',false);
                        $pdf->Ln();
                        $pdf->SetFillColor(255,255,0); //Agregar un color a la celda
                        $pdf -> Cell(180,10,utf8_decode(obtenerExtracurricular($rows['descripcion'] ?? '')),1,0,'C',true); 
                        $pdf->Ln(15);
                    }
                    $data2->free();
                    
                }
                $margin = 50;
                $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                $pdf->SetTextColor(80);
                $leftText = utf8_decode("Generado por: IdentiQR - Fecha del reporte: $hoy");

                $pdf->SetX($margin);
                $pdf->Cell(200 - 2*$margin - 40, 6, $leftText, 0, 0, 'L');
                $nombreRep_Dir = "reporteGeneral_DirDAE_".$fechaHora.".pdf";
                $pdf-> Output('D', $nombreRep_Dir);
            }
            
        }
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
                $allSets = $this->reportModel->reporteInd_DirMed(
                    $tipoReporte, $fe1, $fe2, $genero, $idDepto
                );
                if ($allSets === false) {
                    echo "Error al generar el reporte.";
                    return;
                }

                

                $pdf = new FPDF();
                $pdf->AliasNbPages();
                $pdf->SetAutoPageBreak(true, 50);
                $pdf->SetTitle(utf8_decode('REPORTE INDIVIDUALIZADO - DIRECCIÓN MEDICA'));
                $pdf->SetAuthor('IdentiQR');

                $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Regular','','DMSerifText-Regular.php'); // https://www.fpdf.org/makefont/
                $pdf->AddFont('DMSerifText-Italic','','DMSerifText-Italic.php'); // https://www.fpdf.org/makefont/

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
                        $pdf->SetFont('DMSerifText-Regular','',14);
                        $pdf->SetXY(40, 10);
                        $pdf->Cell(110, 7, utf8_decode('REPORTE INDIVIDUALIZADO - DIRECCIÓN MÉDICA'), 0, 1, 'C');

                        $pdf->SetFont('DMSerifText-Regular','',9);
                        $pdf->SetXY(40, 17);
                        $pdf->Cell(110, 5, 'Fecha del reporte: ' . $hoy, 0, 1, 'C');

                        $pdf->SetDrawColor(180,180,180);
                        $pdf->Line(10, 30, $pdf->GetPageWidth() - 10, 30);
                        $pdf->Ln(6);

                        $matriRaw   = $r['Matri']   ?? ($r['matricula'] ?? '');
                        $nomRaw     = $r['Nom']     ?? ($r['Nombre'] ?? '');
                        $apatRaw    = $r['Pat']     ?? ($r['ApePat'] ?? '');
                        $amatRaw    = $r['Mat']     ?? ($r['ApeMat'] ?? '');
                        $fehorRaw   = $r['FeHor']   ?? ($r['FechaHora'] ?? '');
                        $rawDescripcion = $r['DesServ'] ?? $r['DescripcionServ'] ?? ($r['descripcion'] ?? '');
                        // Para presentarlo lo pasamos a UTF8_DECODE
                        $matri = utf8_decode($matriRaw);
                        $fullname = utf8_decode(trim("$nomRaw $apatRaw $amatRaw"));
                        $fehor = utf8_decode($fehorRaw);
                        $descForPrint = utf8_decode($rawDescripcion);

                        $leftX  = 12;
                        $rightX = 120;
                        $pdf->SetFont('DMSerifText-Regular','',11);
                        $yStart = $pdf->GetY() + 5;

                        // Columna izquierda: datos personales
                        $pdf->SetXY($leftX, $yStart);
                        $pdf->Cell(30,6, utf8_decode('Matrícula:'), 0, 0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(60,6, $matri, 0, 1);

                        $pdf->SetXY($leftX, $pdf->GetY());
                        $pdf->SetFont('DMSerifText-Regular','',11);
                        $pdf->Cell(30,6, 'Nombre:', 0, 0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->MultiCell(78,6, $fullname, 0, 'L');

                        $pdf->SetXY($leftX, $pdf->GetY());
                        $pdf->SetFont('DMSerifText-Regular','',11);
                        $pdf->Cell(30,6,'Fecha/Hora:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(60,6, $fehor, 0, 1);

                        // Columna derecha: resumen médico -> PASAMOS UTF-8
                        $pdf->SetXY($rightX, $yStart);
                        $pdf->SetFont('DMSerifText-Regular','',11);
                        $pdf->Cell(30,6,'Estatura:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,6, obtenerEstatura($rawDescripcion), 0, 1);

                        $pdf->SetXY($rightX, $pdf->GetY());
                        $pdf->SetFont('DMSerifText-Regular','',11);
                        $pdf->Cell(30,6,'Peso:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,6, obtenerPeso($rawDescripcion), 0, 1);

                        $pdf->SetXY($rightX, $pdf->GetY());
                        $pdf->SetFont('DMSerifText-Regular','',11);
                        $pdf->Cell(30,6,'Alergias:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,6, obtenerAlergias($rawDescripcion), 0, 1);

                        $pdf->SetXY($rightX, $pdf->GetY());
                        $pdf->SetFont('DMSerifText-Regular','',11);
                        $pdf->Cell(30,6,'Tipo sangre:',0,0);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,6, obtenerTipoSangre($rawDescripcion), 0, 1);

                        $pdf->Ln(6);

                        // Caja de descripcion 
                        $pdf->SetDrawColor(200,200,200);
                        $pdf->SetFillColor(245,245,245);
                        $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                        $pdf->Cell(0,7, utf8_decode('Descripción / Notas'), 1, 1, 'L', true);

                        $pdf->SetFont('DMSerifText-Italic','',10);
                        // MultiCell con altura 6 para evitar saltos excesivos
                        $pdf->MultiCell(0,5, $descForPrint ?: utf8_decode('Sin descripción'), 1, 'L');
                        $pdf->Ln(4);
                        // Footer por página
                        $pageWidth = $pdf->GetPageWidth();
                        $margin = 50;
                        $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                        $pdf->SetTextColor(80);
                        $leftText = utf8_decode("Generado por: IdentiQR - Fecha del reporte: $hoy");
                        $pdf->SetX($margin);
                        $pdf->Cell($pageWidth - 2*$margin - 40, 6, $leftText, 0, 0, 'L');

                    $pdf->SetX($pageWidth - $margin - 40);
                    $pdf->Cell(40, 6, utf8_decode('Página ') . $pdf->PageNo() . '/{nb}', 0, 0, 'R');

                    $pdf->SetTextColor(0);
                    }
                }
                // Si no se generó nada
                if ($generatedPages === 0) {
                    $pdf->AddPage();
                    $pdf->SetFont('Arial','B',12);
                    $pdf->Cell(0,10, utf8_decode('No se encontraron registros para los parámetros indicados'), 0, 1, 'C');
                }

                // GENERA EL PDF
                if (headers_sent($file, $line)) {
                    error_log("No se puede enviar PDF, headers already sent in $file on line $line");
                    echo "No se pudo generar el PDF: ya se envió salida previamente.";
                    return;
                }
                //$pdf->Output('D', 'reporte_individualizado_DirMedica-'.$fechaHora.'.pdf'); //Descargar directamente
                $pdf->Output();
                //INCLUIMOS LA VISTA
                $vista = __DIR__ . '/../Views/dirMedica/GestionesAdmin_Medico.php';
                if (file_exists($vista)) {
                    include $vista; // o require_once $vista;
                }
        }
        public function reportePorDia_DirMed(){
            date_default_timezone_set('America/Mexico_City');
            
            if (!isset($_POST['reporteCitasDia'])) return;
            $fe = $_POST['fechaReporte'];
            $idDepto = (int)$_POST['idDepto'];
            $fechaHora = date('Y-m-d_H-i-s');

            // Obtener datos del Modelo
            $dataInfo = $this->reportModel->reporteDiario_Grafico($fe, $idDepto);
            $datosGrafica = $dataInfo['datosGrafica']; 
            $cant = $dataInfo['total'];
            // Obtener el detalle de las filas (Citas individuales)
            $result = $this->reportModel->reportePorDia_DirMed($fe, $idDepto);

            // Si no hay datos, generar PDF de error
            if ($cant == 0 || empty($datosGrafica)) {
                $pdf = new FPDF();
                $pdf->AddPage();
                // Intenta cargar la fuente, si falla usa Arial
                try {
                    $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php');
                    $pdf->SetFont('ShadowsIntoLight-Regular', '', 16);
                } catch (Exception $e) {
                    $pdf->SetFont('Arial', '', 16);
                }
                $pdf->Cell(0, 10, utf8_decode('Reporte General - Servicios/Trámites'), 0, 1, 'C');
                $pdf->Ln(20);
                $pdf->SetFont('Arial', '', 12);
                $pdf->Cell(0, 10, utf8_decode('No se encontraron trámites registrados para este reporte.'), 0, 1, 'C');
                $nombreRep = "Reporte_General_Tramites_SIN_DATOS_".$fechaHora.".pdf";
                $pdf->Output('D', $nombreRep);
                exit(); 
            }    

            // Normalizar filas de la base de datos a Array
            $rows = [];
            if ($result instanceof mysqli_result) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
            } elseif (is_array($result)) {
                $rows = $result;
            }

            // GENERACIÓN DE GRÁFICA CON PHPLOT (TEMP)
            // Crear ruta temporal única
            $tempImg = sys_get_temp_dir() . '/grafica_' . md5(uniqid()) . '.png';

            // Configurar PHPlot
            $plot = new PHPlot(600, 300); // 600x300 pixeles
            $plot->SetImageBorderType('none');
            $plot->SetDataType('text-data');   
            $plot->SetDataValues($datosGrafica); // Array [['Masculino', 10], ['Femenino', 5]]
            $plot->SetPlotType('bars');
            
            // Estilos visuales
            $plot->SetTitle("Distribucion por Genero");
            $plot->SetBackgroundColor('white');
            $plot->SetDataColors(array('#1E90FF', '#DC143C', '#DAA520', '#2E8B57')); // Azul, Rojo, Amarillo, Verde
            $plot->SetXTickLabelPos('none');
            $plot->SetXTickPos('none');
            
            // Guardar en archivo en lugar de imprimir
            $plot->SetIsInline(true);
            $plot->SetOutputFile($tempImg); 
            $plot->DrawGraph();

            //Inicializamos FPDF
            $pdf = new FPDF('P','mm','Letter');
            $pdf->AliasNbPages();
            $pdf->SetAutoPageBreak(true, 18);
            // Márgenes
            $left = 18; $top = 20; $right = 18; $bottom = 10;
            $pdf->SetMargins($left, $top, $right);
            $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png'); 
            $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php');
            $pdf->AddPage();
            // Marco Decorativo
            $pdf->SetDrawColor(120,120,120);
            $pdf->SetLineWidth(0.6);
            $pdf->Rect($left-2, $top-8, $pdf->GetPageWidth() - ($left+$right) + 4, $pdf->GetPageHeight() - ($top+$bottom) + 6);
            // Logo
            if ($logoPath && file_exists($logoPath)) {
                $imgW = 60;
                $xImg = ($pdf->GetPageWidth() - $imgW) / 2;
                $pdf->Image($logoPath, $xImg, $top - 20, $imgW);
            }
            // Títulos
            $pdf->SetY($top + 30);
            $pdf->SetFont('ShadowsIntoLight-Regular','',16);
            $pdf->Cell(0, 8, utf8_decode('REPORTE DIARIO - DIRECCIÓN MÉDICA'), 0, 1, 'C');
            $pdf->SetFont('ShadowsIntoLight-Regular','',11);
            $pdf->Cell(0,6, utf8_decode("Fecha del reporte: $fe"), 0, 1, 'C');
            $pdf->Cell(0,6, utf8_decode("Dirección (id): $idDepto"), 0, 1, 'C');
            $pdf->Ln(6);
            // Cantidad Total
            $pdf->SetFont('ShadowsIntoLight-Regular','',12);
            $pdf->Cell(0,6, utf8_decode("Cantidad total de citas:"), 0,1,'L');
            $pdf->SetFont('ShadowsIntoLight-Regular','',22);
            $pdf->SetTextColor(0,80,160);
            $pdf->Cell(0,12, "  [ $cant ]", 0,1,'L');
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(2);
            // ! INCLUIMOS LA GRÁFICA EN EL REPORTE
            $chartX = $left + 15; // Un poco centrado
            $chartY = $pdf->GetY();
            
            // Insertamos la imagen (120mm de ancho, 60mm de alto)
            if (file_exists($tempImg)) {
                $pdf->Image($tempImg, $chartX, $chartY, 150, 75);
                // Borrar imagen temporal
                unlink($tempImg);
            }
            // Movemos el cursor abajo de la gráfica
            $pdf->SetY($chartY + 75);
            $pdf->SetFont('ShadowsIntoLight-Regular','',10);
            $pdf->MultiCell(0,5, utf8_decode("Gráfico generado dinámicamente basado en la distribución por género de las citas del día."), 0, 'C');
            $pdf->Ln(5);

            // DETALLE DE CITAS (TARJETAS)
            $pdf->Ln(80);
            $pdf->SetFont('ShadowsIntoLight-Regular','',14);
            $pdf->Cell(0,6, utf8_decode("Detalle de Citas - Listado Completo"), 0,1,'C');
            $pdf->Ln(4);
            $pdf->SetFont('ShadowsIntoLight-Regular','',10);
            $cardW = $pdf->GetPageWidth() - $left - $right - 4;
            $cardH = 48; 
            $gap = 6;
            $y = $pdf->GetY();
            $pageH = $pdf->GetPageHeight();
            for ($i = 0; $i < count($rows); $i++) {
                $r = $rows[$i];
                // Verificamos salto de página
                if ($y + $cardH + $gap > $pageH - $bottom) {
                    // Footer manual antes de saltar
                    $pdf->SetY(5);
                    $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                    $pdf->SetTextColor(100,100,100);
                    $pdf->Cell(0,6, utf8_decode('Generado por IdentiQR - '.date('Y-m-d H:i:s')), 0, 0, 'L');
                    $pdf->Cell(0,6, utf8_decode('Página '.$pdf->PageNo().'/{nb}'), 0, 0, 'R');
                    $pdf->SetTextColor(0,0,0); // Reset color
                    $pdf->AddPage();
                    // Redibujar marco en nueva página
                    $pdf->SetDrawColor(120,120,120);
                    $pdf->SetLineWidth(0.6);
                    $pdf->Rect($left-2, $top-8, $pdf->GetPageWidth() - ($left+$right) + 4, $pdf->GetPageHeight() - ($top+$bottom) + 6);

                    $pdf->SetY($top + 6);
                    $pdf->SetFont('ShadowsIntoLight-Regular','',12);
                    $pdf->Cell(0,6, utf8_decode("Continuación de Detalle de Citas..."), 0,1,'L');
                    $pdf->Ln(4);

                    $y = $pdf->GetY();
                    $pdf->SetFont('ShadowsIntoLight-Regular','',10);
                }

                // Dibujar Tarjeta
                $x = $left;
                $pdf->SetDrawColor(180,180,180);
                $pdf->SetFillColor(248,248,250);
                $pdf->Rect($x, $y, $cardW, $cardH, 'DF');
                
                // Datos
                $pdf->SetXY($x + 6, $y + 4);
                $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                $folio = $r['IdRegistro'] ?? '';
                $pdf->Cell(0,5, utf8_decode("Cita ".($i+1)."  -  Folio: ".$folio), 0,1);

                $pdf->SetXY($x + 6, $y + 10);
                $pdf->SetFont('ShadowsIntoLight-Regular','',10);
                $nombre = strtoupper(trim(($r['Nombre'] ?? '') . ' ' . ($r['ApePat'] ?? '') . ' ' . ($r['ApeMat'] ?? '')));
                $matricula = $r['Matricula'] ?? '';
                $fechaHoraServ = $r['FechaHoraServ'] ?? '';
                $estatus = $r['EstatusServ'] ?? '';
                $carrera = $r['idCarrera'] ?? '';

                $pdf->Cell($cardW*0.6, 5, utf8_decode("Nombre: ".$nombre), 0, 0);
                $pdf->Cell($cardW*0.4 - 12, 5, utf8_decode("Matrícula: ".$matricula), 0, 1);

                $pdf->SetXY($x + 6, $y + 16);
                $pdf->Cell(90, 5, utf8_decode("Fecha/Hora: ".$fechaHoraServ), 0, 0);
                $pdf->Cell(60, 5, utf8_decode("Estatus: ".$estatus), 0, 0);
                $pdf->Cell(0, 5, utf8_decode("Carrera: ".$carrera), 0, 1);

                // Descripción
                $pdf->SetXY($x + 6, $y + 22);
                $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                $desc = trim($r['DescripcionServ'] ?? '');
                $desc = preg_replace('/\s+/', ' ', $desc);
                $descMax = 360;
                $descPrint = (mb_strlen($desc) > $descMax) ? mb_substr($desc, 0, $descMax) . '...' : $desc;
                $pdf->MultiCell($cardW - 12, 4, utf8_decode("Descripción: ".$descPrint), 0, 'L');

                $y += $cardH + $gap;
                $pdf->SetY($y);
            }

            // Footer final y Salida
            $pdf->SetY(-10);
            $pdf->SetFont('ShadowsIntoLight-Regular','',9);
            $pdf->SetTextColor(100,100,100);
            $pdf->Cell(0,6, utf8_decode('Generado por IdentiQR - '.date('Y-m-d H:i:s')), 0, 0, 'L');
            $pdf->Cell(0,6, utf8_decode('Página '.$pdf->PageNo().'/{nb}'), 0, 0, 'R');

            $nombreRep_Dir = "Reporte_Diario_Grafico_".$fechaHora.".pdf";
            $pdf->Output('D', $nombreRep_Dir);
            //$pdf->Output();
            exit();
        }
        
        //idDepto = 7; Vinculación
        public function reporteGeneral_Vinc(){
            if(isset($_POST['reporteIndividualizado_Vinc'])){
                // Recuperamos los datos para hacer la bsuqueda
                $hoy = date('Y-m-d');
                $fe1 = (!empty($_POST['fe1'])) ? $_POST['fe1'] : $hoy;
                $fe2 = (!empty($_POST['fe2'])) ? $_POST['fe2'] : $hoy;
                
                $idDeptoPost = (int)($_POST['idDepto'] ?? 0);
                $idDepto = ($idDeptoPost > 0) ? $idDeptoPost : 7; 
                
                //Obtener datos del modelo
                $dataPastel = $this->reportModel->reporteGeneral_Vinc_Datos();
                $resultData = $this->reportModel->reporteGeneral_Vinc($fe1, $fe2, $idDepto);

                //Validamos que los datos para las gráficas NO SEAN VACIOS; de lo contrario no continuara
                if (empty($dataPastel) || empty($resultData) || empty($dataPastel) && empty($resultData)) {
                    // Generar PDF con mensaje de error/sin datos
                    $pdf = new FPDF();
                    $pdf->AddPage();
                    $pdf->SetFont('Arial', 'B', 16);
                    $pdf->Cell(0, 10, utf8_decode('Reporte General - Servicios/Trámites'), 0, 1, 'C');
                    $pdf->Ln(20);
                    $pdf->SetFont('Arial', '', 12);
                    $pdf->Cell(0, 10, utf8_decode('No se encontraron trámites registrados para este reporte.'), 0, 1, 'C');
                    
                    date_default_timezone_set('America/Mexico_City');
                    $fechaHora = date('Y-m-d_H-i-s');
                    $nombreRep = "ReporteAdmin_General_Tramites_SIN_DATOS_".$fechaHora.".pdf";
                    $pdf->Output('D', $nombreRep);
                    exit(); // Detener la ejecución para no intentar dibujar la gráfica
                }
                
                //Generar Gráfica
                $plot = new PHPLot(800,600);
                $plot->SetDataValues($dataPastel);
                $plot->SetPlotType("pie");
                $plot->SetDataType('text-data-single');
                $plot->SetTitle(utf8_decode('Porcentaje de Tramites más realizados entre ['. $fe1 . " al " . $fe2 . "]"));
                if(!empty($dataPastel)){
                    $plot->SetLegend(array_column($dataPastel, 0));
                }
                
                date_default_timezone_set('America/Mexico_City');
                $fechaHora = date('Y-m-d_H-i-s');
                
                $graphDir = __DIR__ . "/../../public/Media/graphs/";
                if (!is_dir($graphDir)) mkdir($graphDir, 0777, true);
                $filename = $graphDir . "graficaVinculacionTramites_".$fechaHora.".png";

                $plot->SetOutputFile($filename);
                $plot->setIsInline(true);
                $plot->DrawGraph();

                // Generamos/instanciamos el PDF de FPDF (De tipo A4)
                $pdf = new FPDF('L', 'mm', 'A4'); 
                $pdf->AliasNbPages();
                $pdf->AddPage();

                $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                $logoPathDir = realpath(__DIR__ . '/../../public/Media/img/Vinculacion_Index1.png');
                
                if ($logoPath && file_exists($logoPath)) {
                    $pdf->Image($logoPath, 25, 10, 45);
                }
                if ($logoPathDir && file_exists($logoPathDir)) {
                    $pdf->Image($logoPathDir, $pdf->GetPageWidth() - 55, 10, 45);
                }

                $pdf->SetFont('Arial', 'B', 16);
                $pdf->Cell(0, 10, utf8_decode('REPORTE DE TRÁMITES DE VINCULACIÓN'), 0, 1, 'C');
                
                $pdf->SetFont('Arial', '', 10);
                $pdf->Cell(0, 5, 'Periodo: ' . $fe1 . ' al ' . $fe2, 0, 1, 'C');
                $pdf->Ln(5);

                // Imagen de la gráfica
                if (file_exists($filename)) {
                    $xImg = ($pdf->GetPageWidth() - 110) / 2; 
                    $pdf->Image($filename, $xImg, $pdf->GetY(), 145, 180);
                    $pdf->Ln(90); 
                }
                $pdf->AddPage();
                // CONFIGURACIÓN DE LA TABLA BONITA
                // Anchos (Total ~275mm)
                $w = [45, 25, 75, 20, 50, 30, 30, 10]; // 0:Folio, 1:Matrícula, 2:Nombre, 3:Carrera, 4:Trámite, 5:Fecha, 6:Estatus
                $imprimirCabecera = function($pdf, $w) {
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetFillColor(0, 0, 0); // Negro elegante
                    $pdf->SetTextColor(255, 255, 255);
                    
                    $pdf->Cell($w[0], 8, 'FOLIO', 1, 0, 'C', true);
                    $pdf->Cell($w[1], 8, utf8_decode('MATRÍCULA'), 1, 0, 'C', true);
                    $pdf->Cell($w[2], 8, 'NOMBRE DEL ALUMNO', 1, 0, 'C', true);
                    $pdf->Cell($w[3], 8, 'CARR.', 1, 0, 'C', true);
                    $pdf->Cell($w[4], 8, utf8_decode('TRÁMITE'), 1, 0, 'C', true);
                    $pdf->Cell($w[5], 8, 'FECHA', 1, 0, 'C', true);
                    $pdf->Cell($w[6], 8, 'ESTATUS', 1, 1, 'C', true);
                    
                    $pdf->SetTextColor(0, 0, 0); // Restaurar color negro
                    $pdf->SetFont('Arial', '', 8);
                };

                if ($pdf->GetY() > 170) $pdf->AddPage(); 
                $imprimirCabecera($pdf, $w);

                $cont = 0;
                // Validamos si resultData tiene filas
                if($resultData && $resultData->num_rows > 0) {
                    foreach($resultData as $rows){
                        if ($logoPath && file_exists($logoPath)) {
                            $pdf->Image($logoPath, 5, 8, 15);
                        }
                        if ($logoPathDir && file_exists($logoPathDir)) {
                            $pdf->Image($logoPathDir, $pdf->GetPageWidth() - 15, 8, 15);
                        }
                        if ($pdf->GetY() > 180) {
                            $pdf->AddPage();
                            $imprimirCabecera($pdf, $w);
                        }
                        
                        $cont++;

                        $folio      = utf8_decode($rows['FolioSeguimiento']);
                        $matricula  = utf8_decode($rows['Matricula']);
                        $tramite    = utf8_decode($rows['Descripcion'] ?? 'N/A'); 
                        $fechaRaw   = $rows['FechaHora']; 
                        $fecha      = date('d/m/Y', strtotime($fechaRaw)); // Formato corto
                        $estatus    = utf8_decode($rows['estatusT']);

                        $descLarga = $rows['descripcion']; 
                        $nombre = "Desconocido";
                        $carrera = "N/A";

                        if (preg_match('/El alumno \[(.*?)\]/u', $descLarga, $matches)) {
                            $nombre = $matches[1];
                        }
                        if (preg_match('/carrera <(.*?)>/u', $descLarga, $matchesC)) {
                            $carrera = $matchesC[1];
                        }
                        // Recortar nombre para que quepa en la celda
                        $nombreRecortado = utf8_decode(substr($nombre, 0, 45)); 

                        $fill = ($cont % 2 == 0);
                        if($fill) {
                            $pdf->SetFillColor(230, 230, 230); // Gris claro
                        } else {
                            $pdf->SetFillColor(255, 255, 255); // Blanco
                        }
                        // Imprimir fila
                        $pdf->Cell($w[0], 7, $folio, 1, 0, 'C', true); // Solo últimos caracteres del folio si es muy largo
                        $pdf->Cell($w[1], 7, $matricula, 1, 0, 'C', true);
                        $pdf->Cell($w[2], 7, ' ' . $nombreRecortado, 1, 0, 'L', true);
                        $pdf->Cell($w[3], 7, utf8_decode($carrera), 1, 0, 'C', true);
                        $pdf->Cell($w[4], 7, $tramite, 1, 0, 'C', true); 
                        $pdf->Cell($w[5], 7, $fecha, 1, 0, 'C', true);
                        $pdf->Cell($w[6], 7, $estatus, 1, 1, 'C', true); // Salto de línea
                    }
                } else {
                    $pdf->Ln(5);
                    $pdf->SetFont('Arial', 'I', 10);
                    $pdf->Cell(0, 10, utf8_decode('No se encontraron trámites en este periodo.'), 1, 1, 'C');
                }
                // Total
                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell(0, 10, 'Total de Tramites: ' . $cont, 0, 1, 'R');

                if (file_exists($filename)) unlink($filename);
                //Llamar al Output para descarga
                $nombreRep_Dir = "reporteGeneral_DirVinculacion_".$fechaHora.".pdf";
                $pdf-> Output('D', $nombreRep_Dir);
                //$pdf->Output();
                exit();
            }
        }
    }
?>