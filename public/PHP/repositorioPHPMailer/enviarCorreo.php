<?php
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //https://github.com/PHPMailer/PHPMailer

    //Load Composer's autoloader (created by composer, not included with PHPMailer)
    require 'vendor/autoload.php';

    function enviarCorreo(Usuario $user, $passw){
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'identiqr.info@gmail.com';                     //SMTP username
            $mail->Password   = 'cldfvbragfaluqsz';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //----------------------VARIABLES PARA EL CORREO----------------------

            $destino = $user->getEmail();  // Usamos el correo del objeto Usuario
            $usr = $user -> getUsr();
            $nombre = $user -> getNombre();
            $apellidoP = $user -> getApellidoPaterno();
            $apellidoM = $user -> getApellidoMaterno();
            //$passw = $user -> getPassw();

            //--------------------------------------------------------------------
            //Recipients
            $mail->setFrom('indentiqr.info@gmail.com', 'IdentiQR-Info');
            $mail->addAddress($destino, $usr);     //Add a recipient
            $mail->addReplyTo('indentiqr.info@gmail.com', 'IdentiQR-Information');

            //Content
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Bienvenido a IdentiQR - ¡Tu registro está completo! (Non-reply)';
            
            // Construir mensaje HTML (estilos inline para compatibilidad)
            $htmlBody = '
            <!doctype html>
            <html>
            <head>
            <meta charset="utf-8">
            </head>
            <body style="font-family:Arial,Helvetica,sans-serif;background:#f6f8fa;padding:20px;margin:0;">
            <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 6px rgba(0,0,0,0.06);">
                <tr>
                <td style="background:#970ddb;padding:20px;color:#ffffff;text-align:center;">
                    <h1 style="margin:0;font-size:20px;">IdentiQR</h1>
                    <div style="font-size:13px;opacity:0.9;">Confirmación de registro</div>
                </td>
                </tr>
                <tr>
                <td style="padding:24px;color:#333333;">
                    <p style="font-size:16px;margin:0 0 12px;">Hola <strong>' . $nombre . ' ' . $apellidoP . ' ' . $apellidoM . '</strong>,</p>
                    <p style="margin:0 0 12px;line-height:1.5;">
                    ¡Gracias por registrarte en <strong>IdentiQR</strong>! Nos alegra darte la bienvenida.
                    </p>

                    <p style="margin:14px 0 8px;"><strong>Tus datos de acceso</strong></p>
                    <table cellpadding="6" cellspacing="0" style="border-collapse:collapse;margin-bottom:12px;">
                        <tr>
                            <td style="background:#f1f5f9;border-radius:4px;padding:10px 12px;font-family:monospace;">Usuario:</td>
                            <td style="padding-left:12px;font-family:monospace;"><strong>' . $usr . '</strong></td>
                        </tr>
                        <tr>
                            <td style="background:#f1f5f9;border-radius:4px;padding:10px 12px;font-family:monospace;">Contraseña:</td>
                            <td style="padding-left:12px;font-family:monospace;"><strong>' . $passw . '</strong></td>
                        </tr>
                    </table>

                    <p style="font-size:13px;color:#555;margin:0 0 16px;">
                    La próxima vez que accedas, utiliza el usuario anterior. Si no solicitaste este registro, por favor contacta al administrador.
                    </p>

                    <hr style="border:none;border-top:1px solid #e9ecef;margin:18px 0;">

                    <p style="font-size:13px;color:#777;margin:0;">
                    Saludos,<br>
                    <strong>Equipo IdentiQR</strong>
                    </p>
                </td>
                </tr>
                <tr>
                <td style="background:#f8fafb;padding:12px 16px;font-size:12px;color:#8898a6;text-align:center;">
                    &copy; ' . date('Y') . ' IdentiQR — Este es un correo automático, por favor no responda a esta dirección.
                </td>
                </tr>
            </table>
            </body>
            </html>
            ';
            $mail->Body = $htmlBody;
            //$mail->Body    = $mensaje;

            // Texto plano alternativo
                $textoPlano = "Hola " . $nombre . " " . $apellidoP. " " .$apellidoM . "\n\n"
                . "Gracias por registrarte en IdentiQR.\n\n"
                . "Tus datos de acceso:\n"
                . "Usuario: $usr\n\n"
                . "Si no realizaste este registro, contacta al administrador.\n\n"
                . "Saludos,\nEquipo IdentiQR";
            $mail->AltBody = $textoPlano;

            $mail->send();
            echo 'El mensaje ha sido enviado correctamente';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function enviarCorreoAlumno(Alumno $alumno, String $qrData){
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'identiqr.info@gmail.com';                     //SMTP username
            $mail->Password   = 'cldfvbragfaluqsz';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet = 'UTF-8';

            //----------------------VARIABLES PARA EL CORREO----------------------

            $destino = $alumno->getCorreo();  // Usamos el correo del objeto Usuario
            $mat = $alumno -> getMatricula();

            //--------------------------------------------------------------------
            //Recipients
            $mail->setFrom('indentiqr.info@gmail.com', 'IdentiQR-Info');
            $mail->addAddress($destino, $mat);     //Add a recipient
            $mail->addReplyTo('indentiqr.info@gmail.com', 'IdentiQR-Information');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            $qrFilename = $mat . '.png';
            $mail->addStringAttachment($qrData, $qrFilename, 'base64', 'image/png');

            //Content
            
                                            //Set email format to HTML
            $mail->Subject = 'Bienvenido a IdentiQR - ¡Tu registro está completo! (Non-reply)';
            // Construir mensaje HTML (estilos inline para compatibilidad)
            
            $htmlBody = '
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, Helvetica, sans-serif; background: #f6f8fa; margin: 0; padding: 20px; }
                        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
                        .header { background: #4B0082; padding: 20px; color: #fff; text-align: center; }
                        .content { padding: 24px; color: #333; }
                        .footer { background: #f8fafb; padding: 12px 16px; font-size: 12px; color: #8898a6; text-align: center; }
                        table { border-collapse: collapse; margin-bottom: 12px; }
                        td { padding: 10px; font-family: monospace; }
                        .label { background: #f1f5f9; border-radius: 4px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>IdentiQR</h1>
                            <div>Confirmación de registro</div>
                        </div>
                        <div class="content">
                            <p>Hola <strong>'. $alumno->getNombre() .' '. $alumno->getApePat() .' '. $alumno->getApeMat() .'</strong>,</p>
                            <p>¡Gracias por registrarte en <strong>IdentiQR</strong>! Nos alegra darte la bienvenida.</p>
                            <table>
                                <tr>
                                    <td class="label">Matricula:</td>
                                    <td><strong>'. $mat.'</strong></td>
                                </tr>
                            </table>
                            
                            <p>Adjunto encontrarás tu código QR para realizar tus servicios de manera mas facil y eficiente.</p>
                            <p>Si no realizaste este registro, contacta al administrador.</p>
                            
                            <hr>
                            <p>Saludos,<br><strong>Equipo IdentiQR</strong></p>
                        </div>
                        <div class="footer">
                            &copy; '.date('Y').' IdentiQR — Este es un correo automático, por favor no responda a esta dirección.
                        </div>
                    </div>
                </body>
                </html>
            ';
            
            //$mail->Body    = $mensaje;

            // Texto plano alternativo
            $textoPlano = "Hola " . $alumno->getNombre() . " " . $alumno->getApePat() . " " . $alumno->getApeMat() . ",\n\n";
                $textoPlano .= "¡Gracias por registrarte en IdentiQR! Nos alegra darte la bienvenida.\n\n";
                $textoPlano .= "Tus datos de acceso:\n";
                $textoPlano .= "Usuario/Matricula: $mat\n\n";
                $textoPlano .= "Adjunto encontrarás tu código QR para acceso.\n";
                $textoPlano .= "Si no realizaste este registro, contacta al administrador.\n\n";
                $textoPlano .= "Saludos cordiales,\nEquipo IdentiQR.";

            $mail->isHTML(true);  
            $mail->Body = $htmlBody;
            $mail->AltBody = $textoPlano;

            $mail->send();
            
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function enviarCorreoAlumnoQRActualizado(Alumno $alumno, String $qrData){
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();                                          //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'identiqr.info@gmail.com';                     //SMTP username
            $mail->Password   = 'cldfvbragfaluqsz';                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet = 'UTF-8';

            //----------------------VARIABLES PARA EL CORREO----------------------

            $destino = $alumno->getCorreo();  // Usamos el correo del objeto Usuario
            $mat = $alumno -> getMatricula();

            //--------------------------------------------------------------------
            //Recipients
            $mail->setFrom('indentiqr.info@gmail.com', 'IdentiQR-Info-Actualización');
            $mail->addAddress($destino, $mat);     //Add a recipient
            $mail->addReplyTo('indentiqr.info@gmail.com', 'IdentiQR-Information');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
            $qrFilename = $mat . '.png';
            $mail->addStringAttachment($qrData, $qrFilename, 'base64', 'image/png');

            //Content
            
            //Set email format to HTML
            $mail->Subject = 'Bienvenido a IdentiQR - ¡Tu actualización del QR fue correcta! (Non-reply)';
            // Construir mensaje HTML (estilos inline para compatibilidad)
            
            $htmlBody = '
                <html>
                <head>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, Helvetica, sans-serif; background: #f6f8fa; margin: 0; padding: 20px; }
                        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.06); }
                        .header { background: #4B0082; padding: 20px; color: #fff; text-align: center; }
                        .content { padding: 24px; color: #333; }
                        .footer { background: #f8fafb; padding: 12px 16px; font-size: 12px; color: #8898a6; text-align: center; }
                        table { border-collapse: collapse; margin-bottom: 12px; }
                        td { padding: 10px; font-family: monospace; }
                        .label { background: #f1f5f9; border-radius: 4px; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <div class="header">
                            <h1>IdentiQR</h1>
                            <div>Actualización de información cuatrimestral</div>
                        </div>
                        <div class="content">
                            <p>Hola <strong>'. $alumno->getNombre() .' '. $alumno->getApePat() .' '. $alumno->getApeMat() .'</strong>,</p>
                            <p>¡Se realizó una actualización cuatrimestral de tu código QR <strong>IdentiQR</strong>!.</p>
                            <table>
                                <tr>
                                    <td class="label">Matricula:</td>
                                    <td><strong>'. $mat.'</strong></td>
                                </tr>
                            </table>
                            
                            <p>Adjunto encontrarás tu código QR renovado el cual AHORA deberás usar para realizar tus tramites y servicios de manera mas facil y eficiente.</p>
                            <p>Cualquier duda, contacta al administrador.</p>
                            
                            <hr>
                            <p>Saludos,<br><strong>Equipo IdentiQR</strong></p>
                        </div>
                        <div class="footer">
                            &copy; '.date('Y').' IdentiQR — Este es un correo automático, por favor no responda a esta dirección.
                        </div>
                    </div>
                </body>
                </html>
            ';
            
            //$mail->Body    = $mensaje;

            // Texto plano alternativo
            $textoPlano = "Hola " . $alumno->getNombre() . " " . $alumno->getApePat() . " " . $alumno->getApeMat() . ",\n\n";
                $textoPlano .= "¡Se realizó la renovación cuatrimestral de tu codigo QR.\n\n";
                $textoPlano .= "Tus datos de acceso:\n";
                $textoPlano .= "Usuario/Matricula: $mat\n\n";
                $textoPlano .= "Adjunto encontrarás tu código QR renovado el cual AHORA deberás usar para realizar tus tramites y servicios de manera mas facil y eficiente.\n";
                $textoPlano .= "Cualquier duda, contacta al administrador.\n\n";
                $textoPlano .= "Saludos cordiales,\nEquipo IdentiQR.";

            $mail->isHTML(true);  
            $mail->Body = $htmlBody;
            $mail->AltBody = $textoPlano;

            $mail->send();
            
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

?>