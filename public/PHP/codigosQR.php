<?php
    // codigoQR_Prueba.php
    // Prueba de generación de código QR con Endroid\QrCode
    require_once __DIR__ . '/repositorioQR/vendor/autoload.php';
    require_once __DIR__ . '/Alumno.php';

    use Endroid\QrCode\Builder\Builder;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel;
    use Endroid\QrCode\Label\LabelAlignment;
    use Endroid\QrCode\Label\Font\OpenSans;
    use Endroid\QrCode\RoundBlockSizeMode;
    use Endroid\QrCode\Writer\PngWriter;
    use Endroid\QrCode\Color\Color;
    use Endroid\QrCode\Logo\Logo;



    class codigosQR{
  
        function generarQR_Alumno(Alumno $alumno):\Endroid\QrCode\Writer\Result\ResultInterface 
        {

            $data_qr = $alumno->recuperarDatosQR();
            $logoPath = __DIR__ . '/../Media/img/Logo.png'; //Esta variable nos permitira modificar donde se encuentra el LOGO
            $labelT = "IdentiQR-".$alumno->getMatricula();

            $builder = new Builder(
                writer: new PngWriter(),
                writerOptions: [],
                validateResult: false,
                data: $data_qr,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::High,   // aquí veremos si existe la constante y que el nivel de protección sea complicado
                size: 450,
                margin: 25,
                roundBlockSizeMode: RoundBlockSizeMode::Margin,
                logoPath: $logoPath,
                
                logoResizeToWidth: 200,

                foregroundColor: new Color(128, 0, 128),
                backgroundColor:new Color(230, 230, 250),
                labelText: $labelT,
                labelFont: new OpenSans(15),
                labelAlignment: LabelAlignment::Center
            );

            $result = $builder->build();
            return ($result);

        }


    }
        
?>