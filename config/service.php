<?php
/**
 *
 */

use Mvc5\Plugin\Args;
use Mvc5\Plugin\Link;
use Mvc5\Plugin\Param;
use Mvc5\Plugin\Plugin;
use View5\Compiler;
use View5\Compiler\Footer;
use View5\Compiler\Token;
use View5\Compiler\Verbatim;
use View5\Container;
use View5\Engine;
use View5\Render;
use View5\Token\Comments;
use View5\Token\Echos;
use View5\Token\Expression;
use View5\CompilerTemplate;

return [
    'template\render' => [
        Render::class, new Link, new Plugin('template\engine'),
        new Args(['directory' => new Param('view'), 'paths' => new Param('templates')])
    ],
    'template\container' => [Container::class, new Param('cache')],
    'template\engine' => [Engine::class, new Plugin('template\container'), new Plugin('view5\compiler')],
    'view5\compiler' => [Compiler::class, new Plugin('view5\template'), [new Footer, new Verbatim, new Token]],
    'view5\template' => [CompilerTemplate::class, ['token' => [new Expression, new Comments, new Echos]]]
];
