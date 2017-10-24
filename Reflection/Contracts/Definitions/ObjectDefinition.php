<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Contracts\Definitions;

use Railt\Compiler\Reflection\Contracts\Dependent\Field\HasFields;
use Railt\Compiler\Reflection\Contracts\Invocations\Directive\HasDirectives;

/**
 * Interface ObjectDefinition
 */
interface ObjectDefinition extends Definition, HasFields, HasDirectives
{
    /**
     * @return iterable|InterfaceDefinition[]
     */
    public function getInterfaces(): iterable;

    /**
     * @param string $name
     * @return bool
     */
    public function hasInterface(string $name): bool;

    /**
     * @param string $name
     * @return null|InterfaceDefinition
     */
    public function getInterface(string $name): ?InterfaceDefinition;

    /**
     * @return int
     */
    public function getNumberOfInterfaces(): int;
}
