<?php
    require_once __DIR__ . '/../../public/libraries/FPDF/fpdf.php';
    require_once __DIR__ . '/../../public/libraries/PHPLot/phplot.php';
    include __DIR__ . '/../../public/PHP/extraccionDatos_Tablas.php'; // funciones de extracción
    require_once __DIR__ . '/../Models/ModeloAlumno.php';
    require_once __DIR__ . '/../Models/ModeloReportes.php';
    require_once __DIR__ . '/../../config/Connection_BD.php';

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
            // Preparar arreglo para PHPlot: formato text-data => [ ['Etiqueta', 'Valor'], ... ]
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
            $pdf -> SetFont('Arial','B',16);
            $pdf -> Cell(0,10,utf8_decode('Reporte General - Servicios/Trámites'),0,1,'C');
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(0,6, utf8_decode('Generado: ' . date('Y-m-d H:i:s')), 0, 1, 'C');
            $pdf->Ln(4);
            $pdf -> Image($filename,25,40,160,160);

            // --- Nueva página: tabla con todas las consultas ---
            $pdf->AddPage();
            $pdf->SetFont('Arial','B',12);
            $pdf->Cell(0,8, utf8_decode('Detalle de consultas registradas'), 0, 1, 'C');
            //foreach($data as $r){
                //$pdf->Cell(0,8,$r['NombreDepto'],0,1,'C');
                //$nombreDepto = isset($r[9]) ? $r[9] : ''; // índice 9 = NombreDepto según tu array
                //$pdf->Cell(0,8, utf8_decode($nombreDepto), 0, 1, 'C');
            //}
            $pdf->Ln(2);

            // Encabezado de tabla
            $pdf->SetFont('Arial','B',9);
            // Definir anchos de columna (ajusta según lo que necesites)
            $w = [
                'Folio' => 18,
                'Matricula' => 40,
                'IdTramite' => 18,
                'FechaHora' => 36,
                'Estatus' => 24,
                'Tramite' => 40
            ];
            // Cabeceras
            $pdf->Cell($w['Folio'],7, utf8_decode('Folio'),1,0,'C');
            $pdf->Cell($w['Matricula'],7, utf8_decode('Matrícula'),1,0,'C');
            $pdf->Cell($w['IdTramite'],7, utf8_decode('IdTramite'),1,0,'C');
            $pdf->Cell($w['FechaHora'],7, utf8_decode('FechaHora'),1,0,'C');
            $pdf->Cell($w['Estatus'],7, utf8_decode('Estatus'),1,0,'C');
            $pdf->Cell($w['Tramite'],7, utf8_decode('Trámite'),1,0,'C');

            // Para la descripción usamos la columna restante: calculamos el ancho disponible
            $descWidth = 80; // Ancho fijo para la columna de descripción
            
            //$pdf->Cell($descWidth,7, utf8_decode('Descripción'),1,1,'C');
            //$pdf->Cell($w['Folio'],7,'',1,0); // espacio para alinear
            $pdf->Ln();
            
            // Filas
            $pdf->SetFont('Arial','',9);

            $descWidth = 158; // ancho fijo para descripción

            foreach($data as $r){
                // Preparar datos
                $folio = utf8_decode($r[0]);
                $mat = utf8_decode($r[1]);
                $idTr = utf8_decode($r[2]);
                $fecha = utf8_decode($r[3]);
                $estatus = utf8_decode($r[5] ?: $r[6]);
                $tramiteDesc = utf8_decode($r[7]);
                $descripcion = utf8_decode($r[4]);

                // --- Primera fila (sin descripción) ---
                $pdf->Cell($w['Folio'],6, $folio,1,0,'C');
                $pdf->Cell($w['Matricula'],6, $mat,1,0,'C');
                $pdf->Cell($w['IdTramite'],6, $idTr,1,0,'C');
                $pdf->Cell($w['FechaHora'],6, $fecha,1,0,'C');
                $pdf->Cell($w['Estatus'],6, $estatus,1,0,'C');
                $pdf->Cell($w['Tramite'],6, (strlen($tramiteDesc)>30? substr($tramiteDesc,0,27).'...': $tramiteDesc),1,1,'L');

                // --- Segunda fila: Descripción debajo de Folio ---
                //$pdf->Ln();
                $pdf->SetFont('Arial','B',9);
                $pdf->Cell($w['Folio'],18, utf8_decode('Descripción'),1,0,'C');
                

                // Celda de descripción usando MultiCell
                $pdf->SetFont('Arial','',9);
                $pdf->MultiCell($descWidth,6, $descripcion,1,'L');

                $pdf->Ln(7);
            }
            $pdf -> Output();
            //$pdf->Output('D', 'Reporte_General_Tramites_'.$fechaHora.'.pdf');
        }

        public function reporteGeneral2_Admin(){
            $dataPastel = $this->reportModel->reporteGeneral2_Admin_Pastel();
            $data = $this->reportModel->reporteGeneral2_Usuarios_Admin();
            
            //Creamos la gráfica
            $plot = new PHPLot(800,600);//plot: Referencia a PHPLot

            $plot -> SetDataValues($dataPastel); //Agregar los datos de la gráfica
            $plot -> SetPlotType("pie"); //pie - Pastel || Indicar que la gráfica es de pastel
            $plot -> SetDataType('text-data-single'); //Indicar que los datos se manejan como texto
            $plot -> SetTitle('Porcentaje por Usuarios registrados');

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
            $pdf->SetFont('Arial','B',16);
            $pdf->Cell(0,10,'Reporte de marcas', 0,1,'C');

            //pdf -> Image(ruta, X, Y, ancho, alto);
            $pdf -> Image($filename,30,30,150,100);
            $pdf->Ln(10);

            $pdf->Output();
        }
        //Falta implementar. Reporte de Alumnos (Cuantos hombres y mujeres), Usuarios (Cuantos Hombres/Mujes y Cuantos de cada Carrera)
        //idDepto = 2; Dirección acádemica
        //Falta implementar cuantos tramites se realizarón
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

                        // Columna derecha: resumen médico -> PASAMOS raw UTF-8 a las funciones
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

                        // Caja de descripcion (usar descForPrint para mostrar)
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
                        //$pdf->setXY(50,225);
                        $margin = 50;
                        $pdf->SetFont('ShadowsIntoLight-Regular','',9);
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

                $pdf->Output('I', 'reporte_individualizado.pdf'); //Lo abre en el navegador - Nota. Estoy usandolo para pruebas
                //$pdf->Output('D', 'reporte_individualizado_DirMedica-'.$fechaHora.'.pdf'); //Descargar directamente
                //INCLUIMOS LA VISTA
                $vista = __DIR__ . '/../Views/dirMedica/GestionesAdmin_Medico.php';
                if (file_exists($vista)) {
                    include $vista; // o require_once $vista;
                }
        }

        public function reportePorDia_DirMed(){
            
            /*date_default_timezone_set('America/Mexico_City');
            $result;
            $cant = 0;
            //Validar que el formulario NO VAYA VACIO
            if(isset($_POST['reporteCitasDia'])){
                $fe = $_POST['fechaReporte']; 
                $idDepto = (int)$_POST['idDepto'];

                $cant = $this->reportModel->reporteDiario_Grafico($fe,$idDepto);
                //echo $cant;
                $result = $this->reportModel->reportePorDia_DirMed($fe, $idDepto);
                //Instanciamos la clase FPDF

                $pdf = new FPDF();
                $pdf->AliasNbPages();
                $pdf->SetAutoPageBreak(true, 50);
                $pdf->SetTitle(utf8_decode('REPORTE DIARIO - DIRECCIÓN MEDICA'));
                $pdf->SetAuthor('IdentiQR');
                $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // https://www.fpdf.org/makefont/

                //Margenes
                $left   = 20;
                $top    = 20;
                $right  = 20;
                $bottom = 20;
                // Dibujar rectángulo (marco)
                $pageWidth  = $pdf->GetPageWidth();
                $pageHeight = $pdf->GetPageHeight();
                $pdf->SetMargins(20, 20, 20);   // 20mm por cada lado
                $pdf->SetLeftMargin($left);
                $pdf->SetRightMargin($right);
                $pdf->SetTopMargin($top);
                

                $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png');
                $logoPathDir = realpath(__DIR__ . '/../../public/Media/img/Consultorio_Index1.png');
                $generatedPages = 0;
                $pdf->AddPage();
                $pdf->SetLineWidth(0.5); // grosor de la línea
                $pdf->Rect(
                    $left,
                    $top,
                    $pageWidth - $left - $right,
                    $pageHeight - $top - $bottom
                );
                // Header: logo + titulo + fecha
                if ($logoPath && file_exists($logoPath)) {
                    $pdf->Image($logoPath, 75, 8, 60);
                
            }
            */
            date_default_timezone_set('America/Mexico_City');
            if (!isset($_POST['reporteCitasDia'])) return;

            $fe = $_POST['fechaReporte'];
            $idDepto = (int)$_POST['idDepto'];

            $cant = $this->reportModel->reporteDiario_Grafico($fe, $idDepto); // int
            $result = $this->reportModel->reportePorDia_DirMed($fe, $idDepto);

            // Normalizar filas
            $rows = [];
            if ($result instanceof mysqli_result) {
                $rows = $result->fetch_all(MYSQLI_ASSOC);
                $result->free();
            } elseif (is_array($result)) {
                $rows = $result;
            }

            // Iniciar FPDF
            $pdf = new FPDF('P','mm','Letter');
            $pdf->AliasNbPages();
            $pdf->SetAutoPageBreak(true, 18);

            // Márgenes y datos de layout
            $left = 18; $top = 20; $right = 18; $bottom = 10;
            $pdf->SetMargins($left, $top, $right);

            $logoPath = realpath(__DIR__ . '/../../public/Media/img/Logo.png'); // tu logo
            $pdf->AddFont('ShadowsIntoLight-Regular','','ShadowsIntoLight-Regular.php'); // si no tienes fuente extra puedes borrar esta línea

            // ---- FUNCIONES "IN-LINE" HECHAS CON LLAMADAS FPDF (no definimos funciones) ----

            // --- PORTADA ---
            $pdf->AddPage();

            // Marco sutil
            $pdf->SetDrawColor(120,120,120);
            $pdf->SetLineWidth(0.6);
            $pdf->Rect($left-2, $top-8, $pdf->GetPageWidth() - ($left+$right) + 4, $pdf->GetPageHeight() - ($top+$bottom) + 6);

            // Logo centrado (si existe)
            if ($logoPath && file_exists($logoPath)) {
                $imgW = 60;
                $xImg = ($pdf->GetPageWidth() - $imgW) / 2;
                $pdf->Image($logoPath, $xImg, $top - 20, $imgW);
            }

            // Título - debajo del logo
            $pdf->SetY($top + 30);
            $pdf->SetFont('ShadowsIntoLight-Regular','',16);
            $pdf->Cell(0, 8, utf8_decode('REPORTE DIARIO - DIRECCIÓN MÉDICA'), 0, 1, 'C');

            $pdf->SetFont('ShadowsIntoLight-Regular','',11);
            $pdf->Cell(0,6, utf8_decode("Fecha del reporte: $fe"), 0, 1, 'C');
            $pdf->Cell(0,6, utf8_decode("Dirección (id): $idDepto"), 0, 1, 'C');
            $pdf->Ln(6);

            // Cantidad destacada
            $pdf->SetFont('ShadowsIntoLight-Regular','',12);
            $pdf->Cell(0,6, utf8_decode("Cantidad de citas:"), 0,1,'L');
            $pdf->SetFont('ShadowsIntoLight-Regular','',22);
            $pdf->SetTextColor(0,80,160);
            $pdf->Cell(0,12, "  [ $cant ]", 0,1,'L');
            $pdf->SetTextColor(0,0,0);
            $pdf->Ln(4);

            // Dibujar gráfico de barra simple
            $chartX = $left;
            $chartY = $pdf->GetY();
            $chartW = $pdf->GetPageWidth() - $left - $right - 70;
            $chartH = 14;
            $pdf->SetLineWidth(0.4);
            $pdf->SetDrawColor(150,150,150);
            $pdf->Rect($chartX, $chartY, $chartW, $chartH);
            $maxVal = max(10, (int)$cant);
            $barW = ($cant / $maxVal) * ($chartW - 4);
            if ($barW > 0) {
                $pdf->SetFillColor(30,120,180);
                $pdf->Rect($chartX + 2, $chartY + 2, $barW, $chartH - 4, 'F');
            }
            $pdf->SetFont('ShadowsIntoLight-Regular','',10);
            $pdf->SetXY($chartX + $chartW + 6, $chartY);
            $pdf->MultiCell(60,5, utf8_decode("Gráfico: cantidad total de citas en la fecha.\nValor: $cant"), 0, 'L');
            $pdf->Ln(18);

            // Si no hay filas mostrar mensaje y salir
            if (count($rows) === 0) {
                $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                $pdf->Cell(0,6, utf8_decode('No hay citas registradas para la fecha/dirección seleccionada.'), 0,1,'C');
                // Footer manual
                $pdf->SetY(-16);
                $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                $pdf->SetTextColor(100,100,100);
                $pdf->Cell(0,6, utf8_decode('Generado por IdentiQR - '.date('Y-m-d H:i:s')), 0, 0, 'L');
                $pdf->Cell(0,6, utf8_decode('Página '.$pdf->PageNo().'/{nb}'), 0, 0, 'R');

                $pdf->Output('I', 'REPORTE_DIARIO_DirMed_'.$fe.'.pdf');
                exit;
            }

            // --- DETALLES: tarjetas por cita (página(s) siguientes) ---
            $pdf->SetFont('ShadowsIntoLight-Regular','',12);
            $pdf->Cell(0,6, utf8_decode("Detalle de Citas - Fecha: $fe | Dirección: $idDepto"), 0,1,'L');
            $pdf->Ln(4);

            $pdf->SetFont('ShadowsIntoLight-Regular','',10);
            $cardW = $pdf->GetPageWidth() - $left - $right - 4;
            $cardH = 48; // altura tarjeta
            $gap = 6;
            $y = $pdf->GetY();
            $pageH = $pdf->GetPageHeight();

            for ($i = 0; $i < count($rows); $i++) {
                $r = $rows[$i];

                // Si no hay espacio suficiente, crear nueva página y redibujar header (title) manualmente
                if ($y + $cardH + $gap > $pageH - $bottom) {
                    // footer manual de la pagina anterior
                    $pdf->SetY(-16);
                    $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                    $pdf->SetTextColor(100,100,100);
                    $pdf->Cell(0,6, utf8_decode('Generado por IdentiQR - '.date('Y-m-d H:i:s')), 0, 0, 'L');
                    $pdf->Cell(0,6, utf8_decode('Página '.$pdf->PageNo().'/{nb}'), 0, 0, 'R');

                    $pdf->AddPage();
                    // re-dibujar marco sutil
                    $pdf->SetDrawColor(120,120,120);
                    $pdf->SetLineWidth(0.6);
                    $pdf->Rect($left-2, $top-8, $pdf->GetPageWidth() - ($left+$right) + 4, $pdf->GetPageHeight() - ($top+$bottom) + 6);

                    // repetir encabezado de sección
                    $pdf->SetY($top + 6);
                    $pdf->SetFont('ShadowsIntoLight-Regular','',12);
                    $pdf->Cell(0,6, utf8_decode("Detalle de Citas - Fecha: $fe | Dirección: $idDepto"), 0,1,'L');
                    $pdf->Ln(4);

                    $y = $pdf->GetY();
                    $pdf->SetFont('ShadowsIntoLight-Regular','',10);
                }

                $x = $left;
                // tarjeta con fondo claro y borde
                $pdf->SetDrawColor(180,180,180);
                $pdf->SetFillColor(248,248,250);
                $pdf->Rect($x, $y, $cardW, $cardH, 'DF');

                // Encabezado de tarjeta
                $pdf->SetXY($x + 6, $y + 4);
                $pdf->SetFont('ShadowsIntoLight-Regular','',11);
                $folio = $r['IdRegistro'] ?? '';
                $pdf->Cell(0,5, utf8_decode("Cita ".($i+1)."  -  Folio: ".$folio), 0,1);

                // Datos principales
                $pdf->SetXY($x + 6, $y + 10);
                $pdf->SetFont('ShadowsIntoLight-Regular','',10);
                $nombre = strtoupper(trim(($r['Nombre'] ?? '') . ' ' . ($r['ApePat'] ?? '') . ' ' . ($r['ApeMat'] ?? '')));
                $matricula = $r['Matricula'] ?? '';
                $fechaHora = $r['FechaHoraServ'] ?? '';
                $estatus = $r['EstatusServ'] ?? '';
                $carrera = $r['idCarrera'] ?? '';

                $pdf->Cell($cardW*0.6, 5, utf8_decode("Nombre: ".$nombre), 0, 0);
                $pdf->Cell($cardW*0.4 - 12, 5, utf8_decode("Matrícula: ".$matricula), 0, 1);

                $pdf->SetXY($x + 6, $y + 16);
                $pdf->Cell(90, 5, utf8_decode("Fecha/Hora: ".$fechaHora), 0, 0);
                $pdf->Cell(60, 5, utf8_decode("Estatus: ".$estatus), 0, 0);
                $pdf->Cell(0, 5, utf8_decode("Carrera: ".$carrera), 0, 1);

                // Descripción (limitada para no romper tarjeta)
                $pdf->SetXY($x + 6, $y + 22);
                $pdf->SetFont('ShadowsIntoLight-Regular','',9);
                $desc = trim($r['DescripcionServ'] ?? '');
                $desc = preg_replace('/\s+/', ' ', $desc);
                $descMax = 360;
                $descPrint = (mb_strlen($desc) > $descMax) ? mb_substr($desc, 0, $descMax) . '...' : $desc;
                $pdf->MultiCell($cardW - 12, 4, utf8_decode("Descripción: ".$descPrint), 0, 'L');

                // avanzar cursor
                $y += $cardH + $gap;
                $pdf->SetY($y);
            }

            // último footer
            $pdf->SetY(255);
            $pdf->SetFont('ShadowsIntoLight-Regular','',9);
            $pdf->SetTextColor(100,100,100);
            $pdf->Cell(0,6, utf8_decode('Generado por IdentiQR - '.date('Y-m-d H:i:s')), 0, 0, 'L');
            $pdf->Cell(0,6, utf8_decode('Página '.$pdf->PageNo().'/{nb}'), 0, 0, 'R');

            // Entregar PDF al navegador
            //$pdf->Output('I', 'REPORTE_DIARIO_DirMed_'.$fe.'.pdf');
            // --- FIN: Generación PDF ---
            $pdf->Output();
        }    
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
?>