<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 * @author Ryan Weaver <ryan@symfonycasts.com> on the original DocBuilder.
 * @author Mike van Riel <me@mikevanriel.com> for adapting this to phpDocumentor.
 */

namespace phpDocumentor\Guides;

use phpDocumentor\Guides\RestructuredText\Formats\Format;
use phpDocumentor\Guides\RestructuredText\HTML\Renderers\DocumentNodeRenderer;
use phpDocumentor\Guides\RestructuredText\Nodes\CodeNode;
use phpDocumentor\Guides\RestructuredText\Nodes\DocumentNode;
use phpDocumentor\Guides\RestructuredText\Nodes\SpanNode;
use phpDocumentor\Guides\RestructuredText\Renderers\CallableNodeRendererFactory;
use phpDocumentor\Guides\RestructuredText\Renderers\NodeRendererFactory;
use phpDocumentor\Guides\RestructuredText\Templates\TemplateRenderer;

final class HtmlFormat implements Format
{
    private $templateRenderer;
    private $htmlFormat;
    private $globalTemplatesPath;
    private $subFolder;

    public function __construct(
        TemplateRenderer $templateRenderer,
        Format $format,
        string $globalTemplatesPath,
        string $subFolder
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->htmlFormat = $format;
        $this->globalTemplatesPath = $globalTemplatesPath;
        $this->subFolder = $subFolder;
    }

    public function getFileExtension() : string
    {
        return Format::HTML;
    }

    public function getDirectives() : array
    {
        return $this->htmlFormat->getDirectives();
    }

    /**
     * @return NodeRendererFactory[]
     */
    public function getNodeRendererFactories() : array
    {
        $nodeRendererFactories = $this->htmlFormat->getNodeRendererFactories();

        $nodeRendererFactories[DocumentNode::class] = new CallableNodeRendererFactory(
            function (DocumentNode $node) {
                return new DocumentNodeRenderer(
                    $node,
                    $this->templateRenderer,
                    $this->subFolder
                );
            }
        );

        $nodeRendererFactories[CodeNode::class] = new CallableNodeRendererFactory(
            function (CodeNode $node) {
                return new RestructuredText\Renderers\CodeNodeRenderer(
                    $node,
                    $this->templateRenderer,
                    $this->globalTemplatesPath
                );
            }
        );

//        $nodeRendererFactories[SpanNode::class] = new CallableNodeRendererFactory(
//            function (SpanNode $node) {
//                return new RestructuredText\Renderers\SpanNodeRenderer(
//                    $node->getEnvironment(),
//                    $node,
//                    $this->templateRenderer
//                );
//            }
//        );

        return $nodeRendererFactories;
    }
}
