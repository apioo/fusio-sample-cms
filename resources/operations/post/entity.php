<?php

use App\Action;
use App\Model;
use Fusio\Cli\Builder\Operation;
use Fusio\Cli\Builder\Operation\HttpMethod;
use Fusio\Cli\Builder\Operation\Stability;
use PSX\Schema\Type\Factory\PropertyTypeFactory;

return function (Operation $operation) {
    $operation->setScopes(["post"]);
    $operation->setStability(Stability::EXPERIMENTAL);
    $operation->setPublic(true);
    $operation->setDescription('Returns a single post');
    $operation->setHttpMethod(HttpMethod::GET);
    $operation->setHttpPath('/post/:id');
    $operation->setHttpCode(200);
    $operation->setOutgoing(Model\Post::class);
    $operation->addThrow(500, Model\Post::class);
    $operation->setAction(Action\Post\Get::class);
};
