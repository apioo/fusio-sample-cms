<?php

namespace App\Table\Generated;

enum CommentColumn : string implements \PSX\Sql\ColumnInterface
{
    case ID = \App\Table\Generated\CommentTable::COLUMN_ID;
    case REF_ID = \App\Table\Generated\CommentTable::COLUMN_REF_ID;
    case USER_ID = \App\Table\Generated\CommentTable::COLUMN_USER_ID;
    case CONTENT = \App\Table\Generated\CommentTable::COLUMN_CONTENT;
    case INSERT_DATE = \App\Table\Generated\CommentTable::COLUMN_INSERT_DATE;
}