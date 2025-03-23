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
    $operation->setDescription('Returns all available posts');
    $operation->setHttpMethod(HttpMethod::GET);
    $operation->setHttpPath('/post');
    $operation->setHttpCode(200);
    $operation->addParameter('refId', PropertyTypeFactory::getInteger());
    $operation->addParameter('startIndex', PropertyTypeFactory::getInteger());
    $operation->addParameter('count', PropertyTypeFactory::getInteger());
    $operation->addParameter('search', PropertyTypeFactory::getString());
    $operation->setOutgoing(Model\PostCollection::class);
    $operation->addThrow(500, Model\PostCollection::class);
    $operation->setAction(Action\Post\GetAll::class);
};
