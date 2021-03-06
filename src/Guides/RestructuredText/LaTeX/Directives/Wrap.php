<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\LaTeX\Directives;

use phpDocumentor\Guides\RestructuredText\Directives\SubDirective;
use phpDocumentor\Guides\RestructuredText\Nodes\Node;
use phpDocumentor\Guides\RestructuredText\Parser;

/**
 * Wraps a sub document in a div with a given class
 */
class Wrap extends SubDirective
{
    /** @var string */
    protected $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function getName() : string
    {
        return $this->class;
    }

    /**
     * @param string[] $options
     */
    public function processSub(
        Parser $parser,
        ?Node $document,
        string $variable,
        string $data,
        array $options
    ) : ?Node {
        return $document;
    }
}
