<?php

return [
    /*
    | Available card templates (folder: resources/views/cards/templates/).
    | Add new template key => label when you add a new .blade.php file.
    */
    'templates' => [
        'birthday-classic' => 'Birthday Classic',
        // 'birthday-elegant' => 'Birthday Elegant',
        // 'birthday-minimal' => 'Birthday Minimal',
    ],

    'template_defaults' => [
        'birthday-classic' => [
            'greeting' => 'Happy Birthday',
            'name_placeholder' => 'Your Name',
        ],
    ],
];
