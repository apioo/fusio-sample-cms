<?php

namespace App\Action\Page;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

/**
 * Action which returns a collection response of all pages. It shows how to build complex nested JSON structures based
 * on SQL queries
 */
class GetAll extends SqlBuilderAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');
        $builder    = new Builder($connection);

        $startIndex = (int) $request->get('startIndex');
        $startIndex = $startIndex <= 0 ? 0 : $startIndex;
        $condition  = $this->getCondition($request);

        $sql = 'SELECT page.id,
                       page.ref_id,
                       page.title,
                       page.insert_date
                  FROM app_page page
                 WHERE ' . $condition->getExpression($connection->getDatabasePlatform()) . '
              ORDER BY page.id DESC';

        $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
        $definition = [
            'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_page', [], $builder->fieldInteger('cnt')),
            'startIndex' => $startIndex,
            'entries' => $builder->doCollection($sql, $parameters, [
                'id' => $builder->fieldInteger('id'),
                'refId' => $builder->fieldInteger('ref_id'),
                'title' => 'title',
                'insertDate' => $builder->fieldDateTime('insert_date'),
                'links' => [
                    'self' => $builder->fieldReplace('/page/{id}'),
                ]
            ])
        ];

        return $this->response->build(200, [], $builder->build($definition));
    }

    private function getCondition(RequestInterface $request): Condition
    {
        $condition  = new Condition();

        $ref = $request->get('refId');
        if (!empty($ref)) {
            $condition->equals('comment.ref_id', (int) $ref);
        }

        $title = $request->get('title');
        if (!empty($title)) {
            $condition->like('comment.title', '%' . $title . '%');
        }

        $content = $request->get('content');
        if (!empty($content)) {
            $condition->like('comment.content', '%' . $content . '%');
        }

        return $condition;
    }
}
