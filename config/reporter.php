<?php

return [

    /**
     * The default driver
     */
    'default_driver' => 'pdf',


    /**
     * Configuration of the default supported drivers: pdf, xlsx, csv
     */
    'drivers' => [

        'pdf' => [
            'class' => null,
            'config' => [
                'orientation' => 'Landscape',
                'format' => 'a4',
            ]
        ],


        'xlsx' => [
            'class' => null,
            'config' => []
        ],


        'csv' => [
            'class' => null,
            'config' => []
        ],
    ],

];
