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
    $operation->setDescription('Returns a single page');
    $operation->setHttpMethod(HttpMethod::GET);
    $operation->setHttpPath('/page/:id');
    $operation->setHttpCode(200);
    $operation->setOutgoing(Model\Page::class);
    $operation->addThrow(500, Model\Page::class);
    $operation->setAction(Action\Page\Get::class);
};
