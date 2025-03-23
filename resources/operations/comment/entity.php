<?php

use App\Action;
use App\Model;
use Fusio\Cli\Builder\Operation;
use Fusio\Cli\Builder\Operation\HttpMethod;
use Fusio\Cli\Builder\Operation\Stability;
use PSX\Schema\Type\Factory\PropertyTypeFactory;

return function (Operation $operation) {
    $operation->setScopes(["comment"]);
    $operation->setStability(Stability::EXPERIMENTAL);
    $operation->setPublic(true);
    $operation->setDescription('Returns a single comment');
    $operation->setHttpMethod(HttpMethod::GET);
    $operation->setHttpPath('/comment/:id');
    $operation->setHttpCode(200);
    $operation->setOutgoing(Model\Comment::class);
    $operation->addThrow(500, Model\Comment::class);
    $operation->setAction(Action\Comment\Get::class);
};
