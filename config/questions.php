<?php

use Lazzier\Contracts\YamlKey;

return [
    'add_task' => [
        'question' => 'Do you want to add a task to section "%s"?',
        'default' => true,
    ],
    'enter_command_line' => [
        'question' => '%sEnter the command',
        'default' => 'echo "Hello World!"',
    ],
    'enter_source' => [
        'question' => '%sEnter the source path',
        'default' => null,
    ],
    'enter_target' => [
        'question' => '%sEnter the target path',
        'default' => null,
    ],
    'enter_param' => [
        'question' => '[%1$s] Enter param %2$s',
    ],
    YamlKey::ROOT_DIR => [
        'question' => 'Enter the path to your root directory',
        'default' => '/var/www',
    ],
    YamlKey::RELEASES_DIR => [
        'question' => 'Enter the path to your releases directory',
        'default' => '/var/www/releases',
    ],
    YamlKey::RELEASE_LINK => [
        'question' => 'Enter the path to your active release link',
        'default' => '/var/www/current',
    ],
];
