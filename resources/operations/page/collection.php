<?php

use App\Action;
use App\Model;
use Fusio\Cli\Builder\Operation;
use Fusio\Cli\Builder\Operation\HttpMethod;
use Fusio\Cli\Builder\Operation\Stability;
use PSX\Schema\Type\Factory\PropertyTypeFactory;

return function (Operation $operation) {
    $operation->setScopes(["page"]);
    $operation->setStability(Stability::EXPERIMENTAL);
    $operation->setPublic(true);
    $operation->setDescription('Returns all available pages');
    $operation->setHttpMethod(HttpMethod::GET);
    $operation->setHttpPath('/page');
    $operation->setHttpCode(200);
    $operation->addParameter('refId', PropertyTypeFactory::getInteger());
    $operation->addParameter('startIndex', PropertyTypeFactory::getInteger());
    $operation->addParameter('count', PropertyTypeFactory::getInteger());
    $operation->addParameter('search', PropertyTypeFactory::getString());
    $operation->setOutgoing(Model\PageCollection::class);
    $operation->addThrow(500, Model\PageCollection::class);
    $operation->setAction(Action\Page\GetAll::class);
};
