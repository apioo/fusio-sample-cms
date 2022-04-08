<?php

namespace App\Action\Post;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;

/**
 * Action which returns all details for a single post
 */
class Get extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');
        $builder    = new Builder($connection);

        $sql = 'SELECT post.id,
                       post.ref_id,
                       post.title,
                       post.summary,
                       post.content,
                       post.insert_date
                  FROM app_post post
                 WHERE post.id = :id';

        $parameters = ['id' => (int) $request->get('post_id')];
        $definition = $builder->doEntity($sql, $parameters, [
            'id' => $builder->fieldInteger('id'),
            'refId' => $builder->fieldInteger('ref_id'),
            'title' => 'title',
            'summary' => 'summary',
            'content' => 'content',
            'insertDate' => $builder->fieldDateTime('insert_date'),
            'links' => [
                'self' => $builder->fieldFormat('id', '/post/%s'),
            ]
        ]);

        return $this->response->build(200, [], $builder->build($definition));
    }
}
