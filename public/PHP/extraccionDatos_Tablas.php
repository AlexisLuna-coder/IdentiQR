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
            return trim($coincidencia[1]) . "m"; // Añade la unidad
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

?>
