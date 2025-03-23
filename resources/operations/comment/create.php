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
    $operation->setPublic(false);
    $operation->setDescription('Creates a new comment');
    $operation->setHttpMethod(HttpMethod::POST);
    $operation->setHttpPath('/comment');
    $operation->setHttpCode(201);
    $operation->setIncoming(Model\Comment::class);
    $operation->setOutgoing(Model\CommentCollection::class);
    $operation->addThrow(500, Model\CommentCollection::class);
    $operation->setAction(Action\Comment\Create::class);
};
