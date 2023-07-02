<?php

declare(strict_types = 1);

namespace App\Model;

use PSX\Schema\Attribute\Description;
/**
 * @extends Collection<Comment>
 */
#[Description('A collection of comments')]
class CommentCollection extends Collection
{
}

