<?php

return [

    /*
     * Enable or disable the Indexer.
     */
    'enabled' => env('INDEXER_ENABLED', true),

    /*
     * Specify whether to check queries in ajax requests.
     */
    'check_ajax_requests' => true,

    /*
     * These tables will be watched by Indexer and specified indexes will be tested.
     */
    'watched_tables' => [
        //
    ],

    /*
    * When you don't use "watched_tables" option, Indexer watches all tables.
    * Using this option, you can ignore specified tables to be watched.
    */
    'ignore_tables' => [
        'users',
    ],

    /*
     * These paths/patterns will NOT be handled by Indexer.
     */
    'ignore_paths' => [
        '*auth*',
        '*user*',
        '*password*',
        '*applogs__*',
        '*crud__*',
        '*console__*',
    ],

    /*
    * Time in ms when queries will be considered slow (>=). A slow query will
    * be highlighted with red color. Value of 0 means no color change.
    */
    'slow_time' => 1000,

    /*
     * Outputs results class.
     */
    'output_to' => [
        // outputs results into current visited page.
        Sarfraznawaz2005\Indexer\Outputs\Web::class,
    ],

    /*
     * Font size (including unit) in case of Web output class
     */
    'font_size' => '12px',
];
