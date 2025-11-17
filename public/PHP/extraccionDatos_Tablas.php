<?php
    /*FUNCIONES PARA LA DIRECCIÓN DAE */
    function obtenerExtracurricular($descripcion) {
        // Extrae todo lo que esté entre corchetes []
        preg_match_all('/\[(.*?)\]/u', $descripcion, $matches);
        $arr = $matches[1] ?? [];

        // Recorremos las coincidencias buscando "extracurricular" en la misma o en la siguiente
        for ($i = 0; $i < count($arr); $i++) {
            $item = trim($arr[$i]);

            // Si el corchete contiene la palabra 'extracurricular'
            if (stripos($item, 'extracurricular') !== false) {
                // 1) Intentar extraer lo que venga después de separadores comunes (-, :, >)
                $after = preg_replace('/(?i).*extracurricular[\s\-\:\>\–]*\s*/u', '', $item);
                $after = trim($after, " \t\n\r\0\x0B-:>."); // recorta separadores sobrantes

                if ($after !== '') {
                    return $after;
                }

                // 2) Si no hay texto después, mirar el siguiente corchete (p. ej. [Extracurricular] [Básquetbol])
                if (isset($arr[$i + 1]) && trim($arr[$i + 1]) !== '') {
                    return trim($arr[$i + 1]);
                }

                // 3) Si nada de lo anterior, intentar una búsqueda más amplia en la descripción
                // buscar "Extracurricular" y palabras cercanas en la misma frase
                if (preg_match('/Extracurricular[\s\-\:\>]*([^\]\.\,\n]+)/iu', $descripcion, $m)) {
                    $cand = trim($m[1], " []\t\n\r\0\x0B-:.");
                    if ($cand !== '') return $cand;
                }

                // Si no se pudo, devolver "No especificado"
                return "No especificado";
            }
        }

        // Si no apareció la palabra en ningun corchete, intentar patrón alterno (p. ej. "de [Extracurricular-Cine debate]")
        if (preg_match('/Extracurricular[\s\-\:\>]*([A-Za-zÁÉÍÓÚáéíóúÑñ0-9\-\s]+)/u', $descripcion, $m)) {
            $cand = trim($m[1], " []\t\n\r\0\x0B-:.");
            if ($cand !== '') return $cand;
        }

        return "No especificado";
    }

    /*FUNCIONES PARA LA DIRECCIÓN DDA */
    //Función para obtener el tutor
    function obtenerTutor($descripcion) {
        // Busca el texto dentro de <...> que esté después de la palabra "tutor"
        if (preg_match('/tutor(?:\/a)?:?\s*<([^>]+)>/iu', $descripcion, $coincidencia)) {
            return trim($coincidencia[1]);
        }

        // Si no se encuentra con la palabra tutor, toma el último <...> (que normalmente es el tutor)
        if (preg_match_all('/<([^>]+)>/u', $descripcion, $coincidencias)) {
            $ultimo = end($coincidencias[1]);
            return trim($ultimo);
        }

        return "No especificado";
    }

    /*FUNCIONES PARA LA DIRECCIÓN MEDICA */
    //Funciones obtenerTemperatura
    function obtenerTemperatura($descripcion) {
        if (!$descripcion) return "N/A";

        // Buscar patrones como: Temperatura: 36.5°C  o Temperatura - 36.5  o Temp:36
        if (preg_match('/Temperatur(?:a)?\s*[:\-]?\s*([0-9]+(?:[.,][0-9]+)?)\s*°?\s*C?/iu', $descripcion, $m)) {
            $valor = str_replace(',', '.', $m[1]);
            return (float)$valor;
        }

        // patrón corto "Temp: 36"
        if (preg_match('/\bTemp(?:eratura)?\s*[:\-]?\s*([0-9]+(?:[.,][0-9]+)?)/iu', $descripcion, $m)) {
            return (float)str_replace(',', '.', $m[1]);
        }

        return "N/A";
    }
    //Funcion obtenerEstatura
    function obtenerEstatura($descripcion) {
        // Busca 'Altura:' seguido de un número decimal (dígitos, punto, dígitos)
        if (preg_match('/Altura:\s*([\d\.]+)/u', $descripcion, $coincidencia)) {
            return trim($coincidencia[1]) . "cm"; // Añade la unidad
        }
        return "N/A";
    }
    //Función obtenerPeso
    function obtenerPeso($descripcion) {
        // Busca 'Peso:' seguido de un número decimal (dígitos, punto, dígitos)
        if (preg_match('/Peso:\s*([\d\.]+)/u', $descripcion, $coincidencia)) {
            return trim($coincidencia[1]) . "kg"; // Añade la unidad
        }
        return "N/A";
    }
    //Función obtenerAlergias
    function obtenerAlergias($descripcion) {
        // Busca 'Alergias:' seguido de cualquier texto (.+?) hasta que encuentra ' - Altura'
        if (preg_match('/Alergias:\s*(.*?)\s*-\s*Altura/u', $descripcion, $coincidencia)) {
            return trim($coincidencia[1]);
        }
        return "S/A";
    }
    //Función obtenerTipoSangre
    function obtenerTipoSangre($descripcion) {
        // Busca 'Sangre:' seguido de cualquier caracter que no sea un guion medio '-'
        if (preg_match('/Sangre:\s*([^-\s]+)/u', $descripcion, $coincidencia)) {
            return trim($coincidencia[1]);
        }
        return "N/A";
    }

    /*FUNCIONES PARA LA DIRECCIÓN DE SERV ESCOLARES*/ 
    //Función obtenerMetodoPago
    function obtenerMetodoPago($descripcion) {
        if (!$descripcion) return "No especificado";

        // Buscar patrones como: "Método de pago: [TDD]" o "Metodo de pago - TDD" o "Método de pago: TDD."
        if (preg_match('/Metodo(?: de)? pago\s*[:\-]?\s*\[?\s*([^\]\.\|\n\r]+?)\s*\]?(\.|$|\||\s)/iu', $descripcion, $m)) {
            $metodo = trim($m[1]);
            return $metodo !== '' ? $metodo : "No especificado";
        }

        // Búsqueda alternativa por palabras clave comunes
        if (preg_match('/\b(TDD|TDC|Transferencia|Deposito|Depósito|Efectivo)\b/iu', $descripcion, $m2)) {
            return trim($m2[1]);
        }

        return "No especificado";
    }
    //Función obtenerCostoPagado
    function obtenerCostoPagado($descripcion) {
        // Busca 'Monto pagado: [$', seguido de un número decimal (el monto)
        // El patrón asegura que el número termine en un corchete ]
        if (preg_match('/Monto pagado:\s*\[\$([\d\.\,]+)\]/u', $descripcion, $coincidencia)) {
            // Formateamos el resultado como moneda
            $monto = str_replace(',', '', $coincidencia[1]); // Quitar comas si existen
            return "$" . number_format((float)$monto, 2); 
        }
        return "N/A";
    }

    //Función obtenerMotivo
    /*function obtenerMotivo($descripcion) {
        // Busca 'Motivo adicional:' seguido de un espacio y luego el contenido de los corchetes [ ]
        if (preg_match('/Motivo adicional:\s*\[([^\]]+)\]/u', $descripcion, $coincidencia)) {
            return trim($coincidencia[1]);
        }
        return "No especificado";
    }*/
    function obtenerMotivo($descripcion) {
        if (!$descripcion) return "No especificado";

        // Buscar "Motivo adicional: [ ... ]"
        if (preg_match('/Motivo(?: adicional)?\s*[:\-]?\s*\[([^\]]+)\]/iu', $descripcion, $m)) {
            $val = trim($m[1]);
            if ($val === '' || strcasecmp($val, 'NO APLICA') === 0 || strcasecmp($val, 'N/A') === 0) {
                return 'N/A';
            }
            return $val;
        }

        // Buscar "Motivo: texto" hasta punto, barra o pipe
        if (preg_match('/Motivo(?: adicional)?\s*[:\-]?\s*([^|\.\n\r]+)/iu', $descripcion, $m2)) {
            $val = trim($m2[1]);
            if ($val === '' || strcasecmp($val, 'NO APLICA') === 0 || strcasecmp($val, 'N/A') === 0) {
                return 'N/A';
            }
            return rtrim($val, ". ");
        }

        // Si no aparece, buscar campos "Motivo" en otras partes
        if (preg_match('/\b(motivo|raz[oó]n)\b\s*[:\-]?\s*([^\|\.\n\r]+)/iu', $descripcion, $m3)) {
            $val = trim($m3[2]);
            return $val !== '' ? $val : 'N/A';
        }

        return "No especificado";
    }
    //Función obtenerRequerimientosExtra
    function obtenerRequerimientosExtras($descripcion) {
        if (!$descripcion) return "N/A";

        // 1) Buscar entre <...>
        if (preg_match('/Requerimientos\s*extras\s*[:\-]?\s*<\s*([^>]+?)\s*>/iu', $descripcion, $m)) {
            $val = trim($m[1]);
            return $val === '' ? 'N/A' : $val;
        }

        // 2) Buscar entre [...]
        if (preg_match('/Requerimientos\s*extras\s*[:\-]?\s*\[\s*([^\]]+?)\s*\]/iu', $descripcion, $m)) {
            $val = trim($m[1]);
            return $val === '' ? 'N/A' : $val;
        }

        // 3) Buscar texto tras la etiqueta hasta pipe, punto final o fin de línea
        if (preg_match('/Requerimientos\s*extras\s*[:\-]?\s*([^|\.\n\r]+)/iu', $descripcion, $m)) {
            $val = trim($m[1]);
            // Si viene vacío o es "NO APLICA" o "N/A" devolvemos N/A
            if ($val === '' || strcasecmp($val, 'NO APLICA') === 0 || strcasecmp($val, 'N/A') === 0) {
                return 'N/A';
            }
            return rtrim($val, " .");
        }

        return "N/A";
    }

    /*FUNCIONES PARA VINCULACIÓ*/ 
    function obtenerDocumentosFinales($descripcion) {
        // Busca Si / Sí / No dentro de corchetes o menor-mayor
        if (preg_match('/Do[c|m]umentos\s+finales[^\[\<]*[\:\-]?\s*(?:\[([^\]]+)\]|\<([^>]+)\>)/iu', $descripcion, $coincidencia)) {
            $valor = $coincidencia[1] ?? $coincidencia[2] ?? '';
            $valor = trim($valor);
            if (preg_match('/^(si|sí)$/iu', $valor)) return 'Sí';
            if (preg_match('/^no$/iu', $valor)) return 'No';
            return $valor !== '' ? $valor : 'N/A';
        }
        return "N/A";
    }

    function obtenerDocumentosEntregados($descripcion) {
        // Busca la etiqueta 'Documentos entregados:' y captura el contenido del corchete siguiente [ ]
        if (preg_match('/Documentos entregados:\s*\[([^\]]+)\]/u', $descripcion, $coincidencia)) {
            // Devuelve la lista de documentos, limpiando el espacio extra.
            return trim($coincidencia[1]);
        }
        return "Ninguno";
    }

    /*function obtenerTramiteRealizado($descripcion) {
        if (!$descripcion) return "No especificado";

        // 1) Buscar "trámite de [ ... ]" entre corchetes
        if (preg_match('/tr[aá]mite(?:\s+de)?\s*[:\-]?\s*\[\s*([^\]]+?)\s*\]/iu', $descripcion, $m)) {
            return trim($m[1]) ?: "No especificado";
        }

        // 2) "solicitó el trámite de [ ... ]" o "solicitó el trámite: [ ... ]"
        if (preg_match('/solicit[oó]\s+(?:el\s+)?tr[aá]mite[^\[\<\:\n\r]*[\:\-]?\s*(?:\<\s*([^>]+?)\s*\>|\[\s*([^\]]+?)\s*\])/iu', $descripcion, $m2)) {
            $val = isset($m2[1]) && $m2[1] !== '' ? $m2[1] : (isset($m2[2]) ? $m2[2] : '');
            return trim($val) ?: "No especificado";
        }

        // 3) Si no hay corchetes, buscar "trámite de ..." hasta punto o pipe
        if (preg_match('/tr[aá]mite(?:\s+de)?\s*[:\-]?\s*([^\.\|\<\[\n\r]+)/iu', $descripcion, $m3)) {
            $val = trim($m3[1]);
            return $val !== '' ? rtrim($val, " .") : "No especificado";
        }

        // 4) fallback: devolver primer contenido entre corchetes que contenga palabra típica
        if (preg_match_all('/\[\s*([^\]]+?)\s*\]/u', $descripcion, $matches)) {
            foreach ($matches[1] as $candidate) {
                if (preg_match('/(Estancia|Reinscrip|Inscrip|Constancia|Reposicion|Credencial|Estancia|Servicio|Tramite)/iu', $candidate)) {
                    return trim($candidate);
                }
            }
            // si no hay coincidencias específicas, devolver el primer corchete
            return trim($matches[1][0]);
        }

        return "No especificado";
    }*/
    
    function obtenerTramiteRealizado($descripcion) {
        // Busca la frase 'solicitó el trámite de' y captura todo el contenido del siguiente corchete [ ]
        if (preg_match('/solicitó el trámite de\s*\[([^\]]+)\]/u', $descripcion, $coincidencia)) {
            // Quita la posible fecha de la descripción si fue concatenada
            $tramite = preg_replace('/ - Fecha: <[^>]+>/u', '', $coincidencia[1]);
            return trim($tramite);
        }
        return "N/A";
    }
    
?>
