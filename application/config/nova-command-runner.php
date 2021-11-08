<?php

return [
    'commands' => [
        'Partnerverträge'     => [
            'run' => 'contract:pdf',
            'type' => 'danger',
            'group' => 'PDF Generation',
        ],
        'Partnervertrag Nachtrag'     => [
            'run' => 'propvest:pdf',
            'type' => 'danger',
            'group' => 'PDF Generation',
        ],

        'Route clear'     => ['run' => 'route:clear', 'type' => 'warning', 'group' => 'Administration'],
        'Config clear'    => ['run' => 'config:clear', 'type' => 'warning', 'group' => 'Administration'],
        'View clear'      => ['run' => 'view:clear', 'type' => 'warning', 'group' => 'Administration'],

        'Route cache'      => ['run' => 'route:cache', 'type' => 'info', 'group' => 'Cache'],
        'Config cache'     => ['run' => 'config:cache', 'type' => 'info', 'group' => 'Cache'],
        'View cache'       => ['run' => 'view:cache', 'type' => 'info', 'group' => 'Cache'],

    ],
    'history'  => 10,
];
