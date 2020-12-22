<?php

namespace App\Action\Comment;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

/**
 * Action which returns a collection response of all comments. It shows how to build complex nested JSON structures
 * based on SQL queries
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

        $sql = 'SELECT comment.id,
                       comment.ref_id,
                       comment.content,
                       comment.insert_date
                  FROM app_comment comment
                 WHERE ' . $condition->getExpression($connection->getDatabasePlatform()) . '
              ORDER BY comment.insert_date DESC';

        $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
        $definition = [
            'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_comment', [], $builder->fieldInteger('cnt')),
            'startIndex' => $startIndex,
            'entries' => $builder->doCollection($sql, $parameters, [
                'id' => $builder->fieldInteger('id'),
                'refId' => $builder->fieldInteger('ref_id'),
                'content' => 'content',
                'insertDate' => $builder->fieldDateTime('insert_date'),
                'links' => [
                    'self' => $builder->fieldReplace('/comment/{id}'),
                ]
            ])
        ];

        return $this->response->build(200, [], $builder->build($definition));
    }

    private function getCondition(RequestInterface $request): Condition
    {
        $condition = new Condition();

        $ref = $request->get('refId');
        if (!empty($ref)) {
            $condition->equals('comment.ref_id', (int) $ref);
        }

        $content = $request->get('content');
        if (!empty($content)) {
            $condition->like('comment.content', '%' . $content . '%');
        }

        return $condition;
    }
}
