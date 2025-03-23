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
    $operation->setPublic(false);
    $operation->setDescription('Creates a new post');
    $operation->setHttpMethod(HttpMethod::POST);
    $operation->setHttpPath('/post');
    $operation->setHttpCode(201);
    $operation->setIncoming(Model\Post::class);
    $operation->setOutgoing(Model\PostCollection::class);
    $operation->addThrow(500, Model\PostCollection::class);
    $operation->setAction(Action\Post\Create::class);
};
