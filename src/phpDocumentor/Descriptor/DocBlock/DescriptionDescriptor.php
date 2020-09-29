<?php

declare(strict_types=1);

namespace phpDocumentor\Descriptor\DocBlock;

use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\Tags\Formatter\PassthroughFormatter;

final class DescriptionDescriptor
{
    /**
     * @var Description
     */
    private $description;

    /**
     * @var InlineTagDescriptor[]
     */
    private $inlineTags;

    /**
     * @param InlineTagDescriptor[] $inlineTags
     */
    public function __construct(?Description $description, array $inlineTags)
    {
        $this->description = $description ?? new Description('');
        $this->inlineTags = $inlineTags;
    }

    public function getBodyTemplate() : string
    {
        return $this->description->getBodyTemplate();
    }

    public function replaceTag(int $position, ?InlineTagDescriptor $tagDescriptor) : void
    {
        $this->inlineTags[$position] = $tagDescriptor;
    }

    /**
     * Returns the tags for this description
     *
     * @return InlineTagDescriptor[]
     */
    public function getTags() : array
    {
        return $this->inlineTags;
    }

    /**
     * Renders docblock as string.
     *
     * This method is here for legacy purpose. The new v3 template has improved the way we render descriptons
     * which requires more advanced handling of descriptions and just not some string jugling.
     *
     * @deprecated will be removed in v4
     *
     * @return string
     */
    public function __toString()
    {
        $tags = [];
        foreach ($this->getTags() as $tag) {
            if ($tag === null) {
                $tags[] = null;
                continue;
            }

            $tags[] = '{' . trim('@' . $tag->getName() . ' ' . $tag) . '}';
        }

        return vsprintf($this->getBodyTemplate(), $tags);
    }
}
