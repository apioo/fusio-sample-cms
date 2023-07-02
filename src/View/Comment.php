<?php

namespace App\View;

use App\Table;
use Fusio\Impl\Table\Generated\UserTable;
use PSX\Nested\Builder;
use PSX\Nested\Reference;
use PSX\Sql\Condition;
use PSX\Sql\ViewAbstract;

class Comment extends ViewAbstract
{
    public function getCollection(int $refId, int $startIndex, int $count, ?string $search = null): mixed
    {
        if (empty($startIndex) || $startIndex < 0) {
            $startIndex = 0;
        }

        if (empty($count) || $count < 1 || $count > 1024) {
            $count = 16;
        }

        $condition = Condition::withAnd();
        $condition->like(Table\Generated\CommentTable::COLUMN_REF_ID, $refId);

        if (!empty($search)) {
            $condition->like(Table\Generated\CommentTable::COLUMN_CONTENT, '%' . $search . '%');
        }

        $builder = new Builder($this->connection);

        $definition = [
            'totalResults' => $this->getTable(Table\Comment::class)->getCount($condition),
            'startIndex' => $startIndex,
            'itemsPerPage' => $count,
            'entry' => $builder->doCollection([$this->getTable(Table\Comment::class), 'findAll'], [$condition, $startIndex, $count], [
                'id' => $builder->fieldInteger(Table\Generated\CommentTable::COLUMN_ID),
                'user' => $builder->doEntity([$this->getTable(UserTable::class), 'find'], [new Reference(Table\Generated\CommentTable::COLUMN_USER_ID)], [
                    'id' => $builder->fieldInteger(UserTable::COLUMN_ID),
                    'name' => UserTable::COLUMN_NAME,
                ]),
                'content' => Table\Generated\CommentTable::COLUMN_CONTENT,
                'insertDate' => $builder->fieldDateTime(Table\Generated\CommentTable::COLUMN_INSERT_DATE),
                'links' => [
                    'self' => $builder->fieldFormat('id', '/comment/%s'),
                ]
            ]),
        ];

        return $builder->build($definition);
    }

    public function getEntity(int $id): mixed
    {
        $builder = new Builder($this->connection);

        $definition = $builder->doEntity([$this->getTable(Table\Comment::class), 'find'], [$id], [
            'id' => $builder->fieldInteger(Table\Generated\CommentTable::COLUMN_ID),
            'user' => $builder->doEntity([$this->getTable(UserTable::class), 'find'], [new Reference(Table\Generated\CommentTable::COLUMN_USER_ID)], [
                'id' => $builder->fieldInteger(UserTable::COLUMN_ID),
                'name' => UserTable::COLUMN_NAME,
            ]),
            'content' => Table\Generated\CommentTable::COLUMN_CONTENT,
            'insertDate' => $builder->fieldDateTime(Table\Generated\CommentTable::COLUMN_INSERT_DATE),
            'links' => [
                'self' => $builder->fieldFormat('id', '/comment/%s'),
            ]
        ]);

        return $builder->build($definition);
    }
}
