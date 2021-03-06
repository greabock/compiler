<?php
/**
 * This file is part of Railt package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Railt\Compiler\Reflection\Builder\Process;

use Hoa\Compiler\Llk\TreeNode;
use Railt\Compiler\Reflection\Builder\DocumentBuilder;
use Railt\Compiler\Reflection\CompilerInterface;
use Railt\Compiler\Reflection\Contracts\Behavior\Nameable;
use Railt\Compiler\Reflection\Contracts\Definitions\Definition;
use Railt\Compiler\Reflection\Contracts\Document;
use Railt\Compiler\Exceptions\BuildingException;

/**
 * Trait Builder
 */
trait Compiler
{
    use NameBuilder;

    /**
     * @var TreeNode
     */
    protected $ast;

    /**
     * @var bool
     */
    protected $completed = false;

    /**
     * @return Document|DocumentBuilder
     */
    public function getDocument(): Document
    {
        \assert($this->document instanceof Document);

        return $this->document;
    }

    /**
     * @return CompilerInterface
     */
    public function getCompiler(): CompilerInterface
    {
        \assert($this->getDocument()->getCompiler() instanceof CompilerInterface);

        return $this->getDocument()->getCompiler();
    }

    /**
     * @return array
     */
    public function __sleep(): array
    {
        $this->compileIfNotCompiled();

        $data = ['completed'];

        if (\method_exists(parent::class, '__sleep')) {
            return \array_merge(parent::__sleep(), $data);
        }

        return $data;
    }

    /**
     * @return bool
     */
    public function compileIfNotCompiled(): bool
    {
        if ($this->completed === false) {
            $this->completed = true;

            if ($this instanceof Definition) {
                // Initialize identifier
                $this->getUniqueId();
            }

            $siblings = \class_uses_recursive(static::class);

            foreach ($this->getAst()->getChildren() as $child) {
                if ($this->compileSiblings($siblings, $child)) {
                    continue;
                }

                if ($this->compile($child)) {
                    continue;
                }
            }

            $this->verify();

            return true;
        }

        return false;
    }

    /**
     * @return TreeNode
     */
    public function getAst(): TreeNode
    {
        \assert($this->ast instanceof TreeNode);

        return $this->ast;
    }

    /**
     * @param array $siblings
     * @param TreeNode $child
     * @return bool
     */
    private function compileSiblings(array $siblings, TreeNode $child): bool
    {
        foreach ($siblings as $sibling) {
            $method = 'compile' . \class_basename($sibling);

            if (\method_exists($sibling, $method) && $this->$method($child)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return void
     */
    public function verify(): void
    {
        // Postprocess
    }

    /**
     * @param TreeNode $ast
     * @return bool
     */
    public function compile(TreeNode $ast): bool
    {
        return false;
    }

    /**
     * @param TreeNode $ast
     * @param DocumentBuilder $document
     * @return void
     * @throws \Railt\Compiler\Exceptions\TypeConflictException
     */
    protected function bootBuilder(TreeNode $ast, DocumentBuilder $document): void
    {
        $this->ast = $ast;
        $this->document = $document;

        if ($this instanceof Nameable) {
            /** @var $this NameBuilder */
            $this->precompileNameableType($ast);
        }
    }

    /**
     * @return self
     */
    final protected function resolve(): self
    {
        $this->compileIfNotCompiled();

        return $this;
    }

    /**
     * @param TreeNode $ast
     * @return void
     * @throws BuildingException
     */
    protected function throwInvalidAstNodeError(TreeNode $ast): void
    {
        throw new BuildingException(\sprintf('Invalid %s AST Node.', $ast->getId()));
    }
}
