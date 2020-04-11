<?php

namespace App\Action\Page;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

class Collection extends SqlBuilderAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');
        $builder    = new Builder($connection);

        $startIndex = (int) $request->getParameter('startIndex');
        $startIndex = $startIndex <= 0 ? 0 : $startIndex;
        $condition  = $this->getCondition($request);

        $sql = 'SELECT page.id,
                       page.parent_id,
                       page.status,
                       page.title,
                       page.insert_date
                  FROM app_page page
                 WHERE page.status = 1
                   AND ' . $condition->getExpression($connection->getDatabasePlatform()) . '
              ORDER BY page.sort ASC';

        $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
        $definition = [
            'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_page WHERE status = 1', [], $builder->fieldInteger('cnt')),
            'startIndex' => $startIndex,
            'entries' => $builder->doCollection($sql, $parameters, [
                'id' => $builder->fieldInteger('id'),
                'parentId' => $builder->fieldInteger('parent_id'),
                'status' => $builder->fieldInteger('status'),
                'title' => 'title',
                'insertDate' => $builder->fieldDateTime('insert_date'),
                'links' => [
                    'self' => $builder->fieldReplace('/page/{id}'),
                ]
            ])
        ];

        return $this->response->build(200, [], $builder->build($definition));
    }

    private function getCondition(RequestInterface $request)
    {
        $parameters = $request->getParameters();
        $condition  = new Condition();

        foreach ($parameters as $name => $value) {
            switch ($name) {
                case 'parent':
                    $condition->equals('page.parent_id', (int) $value);
                    break;
                case 'title':
                    $condition->like('page.title', '%' . $value . '%');
                    break;
                case 'content':
                    $condition->like('page.content', '%' . $value . '%');
                    break;
            }
        }

        if (!isset($parameters['parent'])) {
            // in case we have no parent only get the pages without parent
            $condition->nil('page.parent_id');
        }

        return $condition;
    }
}
