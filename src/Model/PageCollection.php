<?php

declare(strict_types = 1);

namespace App\Model;

use PSX\Schema\Attribute\Description;
/**
 * @extends Collection<Page>
 */
#[Description('A collection of all pages')]
class PageCollection extends Collection
{
}

