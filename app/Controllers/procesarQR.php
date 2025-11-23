<?php
    /*Todo este PHP (no se encuentra dentro del controlador, unicamente permitira ESCANEAR Y RECUPERAR LA INFORMACIÓN  DEL QR)
    -Permitira escanear HACIENDO USO de la libreria de PHP QRCodeScanner*/
    require_once __DIR__ . '/../config/Connection_BD.php';
    require_once __DIR__ . '/../Models/ModeloAlumno.php';
    require_once __DIR__ . '/../../public/PHP/repositorioQRCodeScan/vendor/autoload.php';
    use Zxing\QrReader;
    header('Content-Type: application/json');
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['imagen'])) {
            $imagenData = $data['imagen'];

            // Decodificar la imagen base64
            $imagenData = str_replace('data:image/png;base64,', '', $imagenData);
            $imagenData = str_replace(' ', '+', $imagenData);
            $imagenBinaria = base64_decode($imagenData);

            // Guardar temporalmente la imagen
            $tempFile = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
            file_put_contents($tempFile, $imagenBinaria);

            try {
                // Escanear el QR usando Zxing
                $qrcode = new QrReader($tempFile);
                $texto = $qrcode->text();

                if ($texto) {
                    // Aquí puedes procesar el texto del QR
                    // Por ejemplo, si contiene datos del alumno, puedes buscar en la BD
                    echo json_encode([
                        'success' => true,
                        'texto' => $texto
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'error' => 'No se pudo detectar un código QR en la imagen.'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Error al procesar la imagen: ' . $e->getMessage()
                ]);
            }

            // Limpiar el archivo temporal
            unlink($tempFile);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'No se recibió una imagen.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Método no permitido.'
        ]);
    }
?>
