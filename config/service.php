<?php
/**
 *
 */

use Mvc5\Plugin\Args;
use Mvc5\Plugin\Link;
use Mvc5\Plugin\Param;
use Mvc5\Plugin\Plugin;
use Mvc5\Plugin\Service;

return [
    'template\render' => new Service(View5\Render::class,[
        new Plugin('view\resolver'),
        new Plugin('template\provider'),
        new Args(['directory' => new Param('view'), 'paths' => new Param('templates')])
    ]),
    'template\provider' => new Link,
    'view\compiler' => View5\Compiler\Blade::class,
    'view\resolver' => [View5\Engine\EngineResolver::class, new Args([
        'blade' => new Plugin(
            View5\Engine\CompilerEngine::class, [new Plugin('view\compiler'), new Args(['directory' => new Param('cache')])]
        ),
        'php' => new Plugin(View5\Engine\PhpEngine::class)
    ])]
];
