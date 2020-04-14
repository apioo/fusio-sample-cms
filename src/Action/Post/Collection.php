<?php

namespace App\Action\Post;

use Fusio\Adapter\Sql\Action\SqlBuilderAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

/**
 * Action which returns a collection response of all posts. It shows how to
 * build complex nested JSON structures based on SQL queries
 */
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

        $sql = 'SELECT post.id,
                       post.page_id,
                       post.title,
                       post.summary,
                       post.insert_date
                  FROM app_post post
                 WHERE post.status = 1
                   AND ' . $condition->getExpression($connection->getDatabasePlatform()) . '
              ORDER BY post.insert_date DESC';

        $parameters = array_merge($condition->getValues(), ['startIndex' => $startIndex]);
        $definition = [
            'totalResults' => $builder->doValue('SELECT COUNT(*) AS cnt FROM app_post WHERE status = 1', [], $builder->fieldInteger('cnt')),
            'startIndex' => $startIndex,
            'entries' => $builder->doCollection($sql, $parameters, [
                'id' => $builder->fieldInteger('id'),
                'status' => $builder->fieldInteger('status'),
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

    private function getCondition(RequestInterface $request)
    {
        $parameters = $request->getParameters();
        $condition  = new Condition();

        foreach ($parameters as $name => $value) {
            switch ($name) {
                case 'page':
                    $condition->equals('post.page_id', (int) $value);
                    break;
                case 'title':
                    $condition->like('post.title', '%' . $value . '%');
                    break;
                case 'content':
                    $condition->like('post.content', '%' . $value . '%');
                    break;
            }
        }

        return $condition;
    }
}
