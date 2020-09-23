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

namespace phpDocumentor\Descriptor\Filter;

use phpDocumentor\Descriptor\DescriptorAbstract;
use phpDocumentor\Descriptor\ProjectDescriptor\Settings;
use phpDocumentor\Descriptor\ProjectDescriptorBuilder;
use function preg_replace;

/**
 * Filters a Descriptor when the @internal inline tag, or normal tag, is used.
 *
 * When a Descriptor's description contains the inline tag @internal then the description of that tag should be
 * included only when the visibility allows INTERNAL information. Otherwise it needs to be removed.
 *
 * Similarly, whenever the normal @internal tag is used should this filter return null if the visibility does not allow
 * INTERNAL information. This will remove this descriptor from the project.
 *
 * @link https://docs.phpdoc.org/latest/references/phpdoc/tags/internal.html
 */
class StripInternal implements FilterInterface
{
    /** @var ProjectDescriptorBuilder $builder */
    protected $builder;

    /**
     * Initializes this filter with an instance of the builder to retrieve the latest ProjectDescriptor from.
     */
    public function __construct(ProjectDescriptorBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * If the ProjectDescriptor's settings allow internal tags then return the Descriptor, otherwise null to filter it.
     */
    public function __invoke(?DescriptorAbstract $value) : ?DescriptorAbstract
    {
        $isInternalAllowed = $this->builder->getProjectDescriptor()->isVisibilityAllowed(Settings::VISIBILITY_INTERNAL);
//        if ($isInternalAllowed) {
//            $value->setDescription(preg_replace('/\{@internal\s(.+?)\}\}/', '$1', $value->getDescription()));
//            $value->setDescription(preg_replace('/\{@internal\s(.+?)\}/', '$1', $value->getDescription()));
//
//            return $value;
//        }
//
//        // remove inline @internal tags
//        $value->setDescription(preg_replace('/\{@internal\s(.+?)\}\}/', '', $value->getDescription()));
//        $value->setDescription(preg_replace('/\{@internal\s(.+?)\}/', '', $value->getDescription()));

        // if internal elements are not allowed; filter this element
        if ($value->getTags()->fetch('internal')) {
            return null;
        }

        return $value;
    }
}
