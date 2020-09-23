<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 */

namespace phpDocumentor\Descriptor\Builder\Reflector;

use phpDocumentor\Descriptor\Builder\AssemblerAbstract as BaseAssembler;
use phpDocumentor\Descriptor\Collection;
use phpDocumentor\Descriptor\DescriptorAbstract;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Compound;
use function array_values;
use function count;
use function reset;
use function stripcslashes;
use function trim;

abstract class AssemblerAbstract extends BaseAssembler
{
    /**
     * Assemble DocBlock.
     */
    protected function assembleDocBlock(?DocBlock $docBlock, DescriptorAbstract $target) : void
    {
        if (!$docBlock) {
            return;
        }

        $target->setSummary($docBlock->getSummary());
        $target->setDescription($docBlock->getDescription());

        foreach ($docBlock->getTags() as $tag) {
            $tagDescriptor = $this->builder->buildDescriptor($tag);

            // allow filtering of tags
            if (!$tagDescriptor) {
                continue;
            }

            $target->getTags()
                ->fetch($tag->getName(), new Collection())
                ->add($tagDescriptor);
        }
    }

    /**
     * Extracts the package from the DocBlock.
     */
    protected function extractPackageFromDocBlock(?DocBlock $docBlock) : ?string
    {
        $packageTags = $docBlock ? $docBlock->getTagsByName('package') : [];
        if (count($packageTags) === 0) {
            return null;
        }

        /** @var DocBlock\Tags\Generic $tag */
        $tag = reset($packageTags);

        return trim((string) $tag->getDescription());
    }

    /**
     * @deprecated the functionality in this method has been moved to the Compound type in the latest unreleased
     * version of the TypeResolver library
     */
    public static function deduplicateTypes(?Type $type) : ?Type
    {
        if ($type instanceof Compound) {
            $normalizedTypes = [];

            foreach ($type as $typePart) {
                $normalizedTypes[(string) $typePart] = $typePart;
            }

            return new Compound(array_values($normalizedTypes));
        }

        return $type;
    }

    protected function pretifyValue(?string $value) : ?string
    {
        if ($value === null) {
            return null;
        }

        return stripcslashes($value);
    }
}
