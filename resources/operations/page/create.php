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
    $operation->setPublic(false);
    $operation->setDescription('Creates a new page');
    $operation->setHttpMethod(HttpMethod::POST);
    $operation->setHttpPath('/page');
    $operation->setHttpCode(201);
    $operation->setIncoming(Model\Page::class);
    $operation->setOutgoing(Model\PageCollection::class);
    $operation->addThrow(500, Model\PageCollection::class);
    $operation->setAction(Action\Page\Create::class);
};
