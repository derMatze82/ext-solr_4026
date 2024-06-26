<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace ApacheSolrForTypo3\Solr\ViewHelpers;

use Closure;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class PageBrowserRangeViewHelper
 */
class PageBrowserRangeViewHelper extends AbstractSolrFrontendViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Initializes the arguments
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('from', 'string', 'from', false, 'from');
        $this->registerArgument('to', 'string', 'to', false, 'to');
        $this->registerArgument('total', 'string', 'total', false, 'total');
    }

    /**
     * @noinspection PhpMissingReturnTypeInspection
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $from = $arguments['from'];
        $to = $arguments['to'];
        $total = $arguments['total'];

        $resultSet = self::getUsedSearchResultSetFromRenderingContext($renderingContext);
        $search = $resultSet->getUsedSearch();
        $variableProvider = $renderingContext->getVariableProvider();

        $numberOfResultsOnPage = $resultSet->getSearchResults()->getCount();
        $numberOfAllResults = $resultSet->getAllResultCount();

        $resultsFrom = ($search->getResponseBody() ? $search->getResponseBody()->start : 0) + 1;
        $resultsTo = $resultsFrom + $numberOfResultsOnPage - 1;
        $variableProvider->add($from, $resultsFrom);
        $variableProvider->add($to, $resultsTo);
        $variableProvider->add($total, $numberOfAllResults);

        $content = $renderChildrenClosure();

        $variableProvider->remove($from);
        $variableProvider->remove($to);
        $variableProvider->remove($total);

        return $content;
    }
}
