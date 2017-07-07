<?php

return [
    'binaries' => [
        'enabled' => env('FFMPEG_BINARIES', false),
        'path'    => [
            'ffmpeg'  => env('FFMPEG_PATH', '/opt/local/ffmpeg/bin/ffmpeg'),
            'ffprobe' => env('FFPROBE_PATH', '/opt/local/ffmpeg/bin/ffprobe'),
            'timeout' => env('FFMPEG_TIMEOUT', 3600), // The timeout for the underlying process
            'threads' => env('FFMPEG_THREADS', 12),   // The number of threads that FFMpeg should use
        ],
    ]
];
