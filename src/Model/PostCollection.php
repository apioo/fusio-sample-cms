<?php

declare(strict_types = 1);

namespace App\Model;

use PSX\Schema\Attribute\Description;
/**
 * @extends Collection<Post>
 */
#[Description('A collection of posts')]
class PostCollection extends Collection
{
}

