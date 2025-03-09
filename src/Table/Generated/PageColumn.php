<?php

namespace App\Table\Generated;

enum PageColumn : string implements \PSX\Sql\ColumnInterface
{
    case ID = \App\Table\Generated\PageTable::COLUMN_ID;
    case REF_ID = \App\Table\Generated\PageTable::COLUMN_REF_ID;
    case USER_ID = \App\Table\Generated\PageTable::COLUMN_USER_ID;
    case TITLE = \App\Table\Generated\PageTable::COLUMN_TITLE;
    case CONTENT = \App\Table\Generated\PageTable::COLUMN_CONTENT;
    case INSERT_DATE = \App\Table\Generated\PageTable::COLUMN_INSERT_DATE;
}