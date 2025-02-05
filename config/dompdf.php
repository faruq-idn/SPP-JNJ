<?php

return array(
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false,   // Throw an Exception on warnings from dompdf

    'public_path' => null,  // Override the public path if needed

    /*
    |--------------------------------------------------------------------------
    | Orientation
    |--------------------------------------------------------------------------
    |
    | Portrait (default) or landscape
    |
    */
    'orientation' => 'portrait',

    /*
    |--------------------------------------------------------------------------
    | Paper
    |--------------------------------------------------------------------------
    |
    | Paper size and orientation for the document
    |
    */
    'default_paper_size' => 'a4',

    /*
    |--------------------------------------------------------------------------
    | Font Cache
    |--------------------------------------------------------------------------
    |
    | By default, Dompdf will attempt to load fonts from the local directory.
    | If the font isn't available locally, it will be fetched from the same
    | location as where Dompdf fetches fonts by default.
    |
    */
    'font_cache' => storage_path('fonts/'),

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    |
    | Dompdf options. See https://github.com/dompdf/dompdf/wiki/Usage#options
    |
    */
    'options' => array(
        'font_height_ratio' => 0.9,
        'dpi' => 96,
        'defaultFont' => 'DejaVu Sans',
        'fontHeightRatio' => 0.9,
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'isJavascriptEnabled' => true,
        'isFontSubsettingEnabled' => true,
        'enable_php' => false,
        'enable_javascript' => true,
        'enable_remote' => true,
        'font_height_ratio' => 1,
        'enable_html5_parser' => true,
    ),

    'font_family' => array(
        'dejavu sans' => array(
            'normal' => storage_path('fonts/DejaVuSans.ttf'),
            'bold' => storage_path('fonts/DejaVuSans-Bold.ttf'),
            'italic' => storage_path('fonts/DejaVuSans-Oblique.ttf'),
            'bold_italic' => storage_path('fonts/DejaVuSans-BoldOblique.ttf')
        ),
    )
);
