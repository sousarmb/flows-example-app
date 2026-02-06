<?php

return [
    'gate' => [
        'on_branch' => [
            'keep_io' => false,
        ],
    ],
    'stop' => [
        'on_no_event_handler' => false,
        'on_offload_error' => true,
    ],
    'http' => [
        'server' => [
            'command_socket_path' => '/tmp/command.sock',
            'address' => '0.0.0.0:9090',
            'timeout_read_external_process' => 30,
        ],
    ],
    'wait_timeout_for_files' => 1000, // microseconds, low value when running on fast hardware
];
