<?php

return [
    'commands' => [
        'Propvest PDF'     => ['run' => 'propvest:pdf', 'type' => 'danger', 'group' => 'Propvest'],

        'Route clear'     => ['run' => 'route:clear', 'type' => 'warning', 'group' => 'Administration'],
        'Config clear'    => ['run' => 'config:clear', 'type' => 'warning', 'group' => 'Administration'],
        'View clear'      => ['run' => 'view:clear', 'type' => 'warning', 'group' => 'Administration'],

        'Route cache'      => ['run' => 'route:cache', 'type' => 'info', 'group' => 'Cache'],
        'Config cache'     => ['run' => 'config:cache', 'type' => 'info', 'group' => 'Cache'],
        'View cache'       => ['run' => 'view:cache', 'type' => 'info', 'group' => 'Cache'],

    ],
    'history'  => 10,
];
