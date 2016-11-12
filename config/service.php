<?php
/**
 *
 */

use Mvc5\Plugin\Args;
use Mvc5\Plugin\Link;
use Mvc5\Plugin\Param;
use Mvc5\Plugin\Plugin;
use Mvc5\Plugin\Service;
use Mvc5\Plugin\Shared;

return [
    'template\render' => new Shared('view\factory'),
    'view\compiler'   => new Plugin(View5\Compiler\Blade::class, [new Args(['cache_dir' => new Param('cache')])]),
    'view\engines'    => new Plugin(View5\Engine\Resolver::class, [new Args([
        'blade' => new Plugin(View5\Engine\CompilerEngine::class, [new Plugin('view\compiler')]),
        'php'   => new Plugin(View5\Engine\PhpEngine::class)
    ])]),
    'view\factory'  => new Service(View5\Factory::class, [new Plugin('view\engines'), new Plugin('view\finder'), new Link]),
    'view\finder'   => new Plugin(View5\Finder\File::class, [new Args([new Param('view')]), new Param('templates')]),
    'view\renderer' => [View5\Renderer::class, new Shared('view\factory')]
];
