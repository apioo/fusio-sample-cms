<?php

namespace App\Action\Post;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

/**
 * Action which returns a collection response of all posts. It shows how to build complex nested JSON structures based
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

        $sql = 'SELECT post.id,
                       post.ref_id,
                       post.title,
                       post.summary,
                       post.insert_date
                  FROM app_post post
                 WHERE ' . $condition->getExpression($connection->getDatabasePlatform()) . '
              ORDER BY post.insert_date DESC';

        $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
        $definition = [
            'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_post', [], $builder->fieldInteger('cnt')),
            'startIndex' => $startIndex,
            'entries' => $builder->doCollection($sql, $parameters, [
                'id' => $builder->fieldInteger('id'),
                'refId' => $builder->fieldInteger('ref_id'),
                'title' => 'title',
                'summary' => 'summary',
                'insertDate' => $builder->fieldDateTime('insert_date'),
                'links' => [
                    'self' => $builder->fieldReplace('/post/{id}'),
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
            $condition->equals('post.ref_id', (int) $ref);
        }

        $title = $request->get('title');
        if (!empty($title)) {
            $condition->like('post.title', '%' . $title . '%');
        }

        $summary = $request->get('summary');
        if (!empty($summary)) {
            $condition->like('post.summary', '%' . $summary . '%');
        }

        $content = $request->get('content');
        if (!empty($content)) {
            $condition->like('post.content', '%' . $content . '%');
        }

        return $condition;
    }
}
