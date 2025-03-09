<?php

namespace App\Table\Generated;

enum PostColumn : string implements \PSX\Sql\ColumnInterface
{
    case ID = \App\Table\Generated\PostTable::COLUMN_ID;
    case REF_ID = \App\Table\Generated\PostTable::COLUMN_REF_ID;
    case USER_ID = \App\Table\Generated\PostTable::COLUMN_USER_ID;
    case TITLE = \App\Table\Generated\PostTable::COLUMN_TITLE;
    case SUMMARY = \App\Table\Generated\PostTable::COLUMN_SUMMARY;
    case CONTENT = \App\Table\Generated\PostTable::COLUMN_CONTENT;
    case INSERT_DATE = \App\Table\Generated\PostTable::COLUMN_INSERT_DATE;
}