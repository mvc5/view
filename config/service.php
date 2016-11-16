<?php
/**
 *
 */

use Mvc5\Plugin\Args;
use Mvc5\Plugin\Link;
use Mvc5\Plugin\Param;
use Mvc5\Plugin\Plugin;
use Mvc5\Plugin\Service;
use View5\Compiler\Engine;
use View5\Compiler\Footer;
use View5\Compiler\Parser;
use View5\Compiler\Verbatim;
use View5\Compiler\ViewTemplate;
use View5\Engine\CompilerEngine;
use View5\Engine\EngineResolver;
use View5\Engine\PhpEngine;

return [
    'template\render' => new Service(View5\Render::class,[
        new Plugin('view\resolver'),
        new Plugin('template\provider'),
        new Args(['directory' => new Param('view'), 'paths' => new Param('templates')])
    ]),
    'template\provider' => new Link,
    'view\compiler' => [Engine::class, new Plugin(ViewTemplate::class),
        new Args([new Plugin(Footer::class), new Plugin(Verbatim::class), new Plugin(Parser::class)])
    ],
    'view\resolver' => [EngineResolver::class, new Args([
        'blade' => new Plugin(CompilerEngine::class, [new Plugin('view\compiler'), new Args(['directory' => new Param('cache')])]),
        'php' => new Plugin(PhpEngine::class)
    ])]
];
