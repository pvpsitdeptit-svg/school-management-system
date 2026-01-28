<?php

return [
    'exports' => [
        /*
        |--------------------------------------------------------------------------
        | Chunk size
        |--------------------------------------------------------------------------
        |
        | When using FromQuery, the query is automatically chunked.
        | Here you can specify how big the chunk should be.
        |
        */
        'chunk_size' => 1000,

        /*
        |--------------------------------------------------------------------------
        | CSV settings
        |--------------------------------------------------------------------------
        |
        | These are the default settings for CSV exports. Each setting can be
        | overridden at export time.
        |
        */
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => false,
            'include_separator_line' => false,
            'separator_line' => 'SEP=',
            'excel_compatibility' => false,
        ],

        /*
        |--------------------------------------------------------------------------
        | Worksheet settings
        |--------------------------------------------------------------------------
        |
        | These are the default worksheet settings for Excel exports. Each setting
        | can be overridden at export time.
        |
        */
        'worksheets' => [
            'auto_size' => true,
            'freeze_pane' => [
                'column' => 'A1',
                'row' => 2,
            ],
        ],
    ],

    'imports' => [
        /*
        |--------------------------------------------------------------------------
        | Read only
        |--------------------------------------------------------------------------
        |
        | When dealing with import files, you might want to ignore the actual
        | cell values and only read the cell values. This can be achieved by
        | setting read_only to true.
        |
        */
        'read_only' => true,

        /*
        |--------------------------------------------------------------------------
        | Heading row formatter
        |--------------------------------------------------------------------------
        |
        | When dealing with imports, you might want to have a heading row
        | formatter. This can be achieved by setting a heading formatter.
        |
        */
        'heading_formatter' => 'slug',

        /*
        |--------------------------------------------------------------------------
        | CSV settings
        |--------------------------------------------------------------------------
        |
        | These are the default settings for CSV imports. Each setting can be
        | overridden at import time.
        |
        */
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape_character' => '\\',
            'contiguous' => false,
            'input_encoding' => 'UTF-8',
        ],

        /*
        |--------------------------------------------------------------------------
        | Cell cache
        |--------------------------------------------------------------------------
        |
        | By default, cells are cached in memory. When dealing with large files,
        | you might want to cache them to disk or redis.
        |
        */
        'cache' => [
            'driver' => 'memory',
        ],

        /*
        |--------------------------------------------------------------------------
        | Chunk size
        |--------------------------------------------------------------------------
        |
        | When using FromQuery, the query is automatically chunked.
        | Here you can specify how big the chunk should be.
        |
        */
        'chunk_size' => 1000,

        /*
        |--------------------------------------------------------------------------
        | Batch size
        |--------------------------------------------------------------------------
        |
        | When using FromQuery, the query is automatically batched.
        | Here you can specify how big the batch should be.
        |
        */
        'batch_size' => 1000,

        /*
        |--------------------------------------------------------------------------
        | Pre-calculate formulas
        |--------------------------------------------------------------------------
        |
        | When dealing with imports, you might want to have a heading row
        | formatter. This can be achieved by setting a heading formatter.
        |
        */
        'pre_calculate_formulas' => false,

        /*
        |--------------------------------------------------------------------------
        | Ignore empty
        |--------------------------------------------------------------------------
        |
        | When dealing with imports, you might want to ignore empty rows.
        | This can be achieved by setting ignore_empty to true.
        |
        */
        'ignore_empty' => false,
    ],

    'temporary_files' => [
        /*
        |--------------------------------------------------------------------------
        | Local Temporary Path
        |--------------------------------------------------------------------------
        |
        | When importing files, we use a temporary file to store the file.
        | Here you can specify the temporary path.
        |
        */
        'local_path' => storage_path('framework/laravel-excel'),

        /*
        |--------------------------------------------------------------------------
        | Remote Temporary Disk
        |--------------------------------------------------------------------------
        |
        | When importing files, we use a temporary file to store the file.
        | Here you can specify the temporary disk.
        |
        */
        'remote_disk' => null,

        /*
        |--------------------------------------------------------------------------
        | Force Resync
        |--------------------------------------------------------------------------
        |
        | When dealing with remote disks, we can force a resync of the
        | temporary file. This can be useful when dealing with
        | remote disks that have a latency.
        |
        */
        'force_resync_remote' => false,
    ],
];
