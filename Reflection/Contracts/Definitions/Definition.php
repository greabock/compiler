<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Definitions;

use Railt\Compiler\Reflection\Contracts\Behavior\Nameable;
use Railt\Compiler\Reflection\Contracts\Behavior\Deprecatable;
use Railt\Compiler\Reflection\Contracts\Document;

/**
 * Interface TypeDefinition
 */
interface Definition extends Nameable, Deprecatable
{
    /**
     * @return string
     */
    public function getTypeName(): string;

    /**
     * @return Document
     */
    public function getDocument(): Document;

    /**
     * A unique identifier for the type is needed to identify the entity
     * in cases of type names conflicts.
     *
     * @return string Type identifier
     */
    public function getUniqueId(): string;
}

