<?php

namespace App\Action\Comment;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;

/**
 * Action which returns a specific comment
 */
class Get extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');
        $builder    = new Builder($connection);

        $sql = 'SELECT comment.id,
                       comment.ref_id,
                       comment.content,
                       comment.insert_date
                  FROM app_comment comment
                 WHERE comment.id = :id';

        $parameters = ['id' => (int) $request->get('comment_id')];
        $definition = $builder->doEntity($sql, $parameters, [
            'id' => $builder->fieldInteger('id'),
            'refId' => $builder->fieldInteger('ref_id'),
            'content' => 'content',
            'insertDate' => $builder->fieldDateTime('insert_date'),
            'links' => [
                'self' => $builder->fieldFormat('id', '/comment/%s'),
            ]
        ]);

        return $this->response->build(200, [], $builder->build($definition));
    }
}
