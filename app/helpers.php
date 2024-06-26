<?php

if (!function_exists('convertirNumeroALetras')) {
    /**
     * Convierte un número en formato decimal a letras.
     *
     * @param  float  $numero
     * @return string
     */
    function convertirNumeroALetras($numero)
    {
        $numero = str_replace(',', '', $numero);
    $numero = number_format($numero, 2, '.', '');

    $numeros = explode('.', $numero);

    $enteros = (int) $numeros[0];
    $decimal = (int) $numeros[1];

    $unidades = [
        0 => 'CERO', 1 => 'UN', 2 => 'DOS', 3 => 'TRES', 4 => 'CUATRO', 5 => 'CINCO',
        6 => 'SEIS', 7 => 'SIETE', 8 => 'OCHO', 9 => 'NUEVE', 10 => 'DIEZ',
        11 => 'ONCE', 12 => 'DOCE', 13 => 'TRECE', 14 => 'CATORCE', 15 => 'QUINCE',
        20 => 'VEINTI', 30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA',
        60 => 'SESENTA', 70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA'
    ];

    $centenas = [
        0 => '', 100 => 'CIEN', 200 => 'DOSCIENTOS', 300 => 'TRESCIENTOS',
        400 => 'CUATROCIENTOS', 500 => 'QUINIENTOS', 600 => 'SEISCIENTOS',
        700 => 'SETECIENTOS', 800 => 'OCHOCIENTOS', 900 => 'NOVECIENTOS'
    ];

    $miles = [
        0 => '', 1000 => 'MIL', 1000000 => 'MILLÓNES'
    ];

    $output = '';

    if ($enteros == 0) {
        $output .= 'CERO';
    }

    if ($enteros >= 1000000) {
        $millones = floor($enteros / 1000000);
        $output .= $unidades[$millones] . ' ' . $miles[1000000] . ' ';
        $enteros %= 1000000;
    }

    if ($enteros >= 1000) {
        $miles_enteros = floor($enteros / 1000);
        $output .= convertirNumeroALetras($miles_enteros) . ' ' . $miles[1000] . ' ';
        $enteros %= 1000;
    }

    if ($enteros >= 100) {
        $output .= $centenas[$enteros - $enteros % 100] . ' ';
        $enteros %= 100;
    }

    if ($enteros >= 10) {
        $output .= $unidades[$enteros - $enteros % 10] . ' ';
        $enteros %= 10;
    }

    if ($enteros > 0) {
        $output .= $unidades[$enteros];
    }

    $output .= ' PESOS';

    if ($decimal == 0) {
        $output .= ' 00/100 M.N.';
    } else {
        $output .= ' ' . ($decimal < 10 ? '0' : '') . $decimal . '/100 M.N.';
    }

    return $output;
    }
}

