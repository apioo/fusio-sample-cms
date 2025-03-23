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
    $operation->setDescription('Updates a single page');
    $operation->setHttpMethod(HttpMethod::PUT);
    $operation->setHttpPath('/page/:id');
    $operation->setHttpCode(200);
    $operation->setIncoming(Model\Page::class);
    $operation->setOutgoing(Model\Message::class);
    $operation->addThrow(500, Model\Message::class);
    $operation->setAction(Action\Page\Update::class);
};
