<?php

namespace App\Action\Page;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Reference;

/**
 * Action which returns all details for a single page
 */
class Get extends SqlBuilderAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');
        $builder    = new Builder($connection);

        $sql = 'SELECT page.id,
                       page.ref_id,
                       page.title,
                       page.content,
                       page.insert_date
                  FROM app_page page
                 WHERE page.id = :id';

        $parameters = ['id' => (int) $request->get('page_id')];
        $definition = $builder->doEntity($sql, $parameters, [
            'id' => $builder->fieldInteger('id'),
            'refId' => $builder->fieldInteger('ref_id'),
            'title' => 'title',
            'content' => 'content',
            'insertDate' => $builder->fieldDateTime('insert_date'),
            'links' => [
                'self' => $builder->fieldReplace('/page/{id}'),
            ]
        ]);

        return $this->response->build(200, [], $builder->build($definition));
    }
}
