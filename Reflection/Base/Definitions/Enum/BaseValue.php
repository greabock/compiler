<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Base\Definitions\Enum;

use Railt\Compiler\Reflection\Base\Dependent\BaseDependent;
use Railt\Compiler\Reflection\Contracts\Definitions\Enum\ValueDefinition;
use Railt\Compiler\Reflection\Contracts\Definitions\EnumDefinition;

/**
 * Class BaseValue
 */
abstract class BaseValue extends BaseDependent implements ValueDefinition
{
    /**
     * Enum value type name
     */
    protected const TYPE_NAME = 'EnumValue';

    /**
     * @return string
     */
    public function getValue(): string
    {
        return (string)$this->resolve()->name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->getValue();
    }

    /**
     * @return EnumDefinition
     */
    public function getParent(): EnumDefinition
    {
        return $this->resolve()->parent;
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        return \array_merge(parent::__sleep(), [
        ]);
    }
}
