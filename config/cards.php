<?php

return [
    /*
    | Category = folder name under resources/views/cards/templates/.
    | Display name on frontend: ucwords(str_replace('-', ' ', $slug)) e.g. "Birthday Cards"
    */
    'categories' => [
        'birthday-cards' => 'Birthday Cards',
        // 'wedding' => 'Wedding',
        // 'baby-kids' => 'Baby & Kids',
        // 'party' => 'Party',
    ],

    /*
    | Templates per category (folder: resources/views/cards/templates/{category}/).
    */
    'templates' => [
        'birthday-cards' => [
            'birthday-classic' => 'Birthday Classic',
        ],
        // 'wedding' => [
        //     'wedding-elegant' => 'Wedding Elegant',
        // ],
    ],

    'template_defaults' => [
        'birthday-classic' => [
            'greeting' => 'Happy Birthday',
            'name_placeholder' => 'Your Name',
        ],
    ],
];
