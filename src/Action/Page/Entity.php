<?php

namespace App\Action\Page;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Reference;

class Entity extends SqlBuilderAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');
        $builder    = new Builder($connection);

        $sql = 'SELECT page.id,
                       page.status,
                       page.title,
                       page.data,
                       page.insert_date
                  FROM app_page page
                 WHERE page.id = :id';

        $parameters = ['id' => (int) $request->getUriFragment('page_id')];
        $definition = $builder->doEntity($sql, $parameters, [
            'id' => $builder->fieldInteger('id'),
            'status' => $builder->fieldInteger('status'),
            'title' => 'title',
            'blocks' => $builder->fieldJson('data'),
            'insertDate' => $builder->fieldDateTime('insert_date'),
            'children' => $builder->doCollection('SELECT id, parent_id, status, title, insert_date FROM app_page WHERE parent_id = :parent', ['parent' =>  new Reference('id')], [
                'id' => $builder->fieldInteger('id'),
                'status' => $builder->fieldInteger('status'),
                'title' => 'title',
                'insertDate' => $builder->fieldDateTime('insert_date'),
                'links' => [
                    'self' => $builder->fieldReplace('/page/{id}'),
                    'parent' => $builder->fieldReplace('/page/{parent_id}'),
                ]
            ]),
            'links' => [
                'self' => $builder->fieldReplace('/page/{id}'),
            ]
        ]);

        return $this->response->build(200, [], $builder->build($definition));
    }
}
