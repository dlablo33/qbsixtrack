<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false, // Deshabilitar advertencias de DomPDF para lanzar excepciones

    'public_path' => null, // Ruta pública, opcionalmente sobrescribe si es necesario

    /*
     * Dejavu Sans font is missing glyphs for converted entities, turn it off if you need to show € and £.
     */
    'convert_entities' => true,

    'options' => [
        /**
         * The location of the DOMPDF font directory
         *
         * El directorio donde DomPDF almacenará fuentes y métricas de fuentes
         * Asegúrate de que este directorio exista y sea escribible por el proceso del servidor web
         */
        'font_dir' => storage_path('fonts'),

        /**
         * The location of the DOMPDF font cache directory
         *
         * Directorio donde se almacenan en caché las métricas de fuentes usadas por DomPDF
         * Debe ser el mismo que DOMPDF_FONT_DIR
         */
        'font_cache' => storage_path('fonts'),

        /**
         * The location of a temporary directory
         *
         * Directorio temporal necesario para descargar imágenes remotas y para el uso del backend de PDFLib
         */
        'temp_dir' => sys_get_temp_dir(),

        /**
         * DomPDF's "chroot": Prevents DomPDF from accessing system files
         *
         * Todos los archivos locales abiertos por DomPDF deben estar en un subdirectorio de este directorio.
         * No establezcas esto como '/' para evitar riesgos de seguridad.
         */
        'chroot' => realpath(base_path()),

        /**
         * Protocol whitelist
         *
         * Protocolos y PHP wrappers permitidos en URIs
         */
        'allowed_protocols' => [
            'file://' => ['rules' => []],
            'http://' => ['rules' => []],
            'https://' => ['rules' => []],
        ],

        /**
         * Whether to enable font subsetting or not
         *
         * Habilita o deshabilita la subconjunción de fuentes
         */
        'enable_font_subsetting' => false,

        /**
         * The PDF rendering backend to use
         *
         * Valid settings: 'PDFLib', 'CPDF', 'GD', 'auto'
         */
        'pdf_backend' => 'CPDF',

        /**
         * PDF rendering resolution (DPI)
         *
         * Resolución DPI para imágenes y fuentes
         */
        'dpi' => 96,

        /**
         * Enable inline PHP
         *
         * Habilita la evaluación de PHP en línea dentro de <script type="text/php"> ... </script>
         */
        'enable_php' => false,

        /**
         * Enable inline JavaScript
         *
         * Habilita la inserción de código JavaScript en línea dentro de <script type="text/javascript"> ... </script>
         */
        'enable_javascript' => false,

        /**
         * Enable remote file access
         *
         * Permite el acceso a archivos remotos (imágenes y archivos CSS)
         */
        'enable_remote' => true,

        /**
         * Font height ratio applied to mimic browsers' line height
         *
         * Ratio de altura de la fuente para simular el espaciado de líneas de los navegadores
         */
        'font_height_ratio' => 1.1,

        /**
         * Use the HTML5 Lib parser
         *
         * Habilita el uso del analizador HTML5
         */
        'enable_html5_parser' => true,
    ],
];

