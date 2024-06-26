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

namespace ApacheSolrForTypo3\Solr\ViewHelpers\Uri\Result;

use ApacheSolrForTypo3\Solr\Domain\Search\Highlight\SiteHighlighterUrlModifier;
use ApacheSolrForTypo3\Solr\ViewHelpers\AbstractSolrFrontendViewHelper;
use Closure;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Class AddSearchWordListViewHelper
 */
class AddSearchWordListViewHelper extends AbstractSolrFrontendViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @inheritdoc
     */
    public function initializeArguments(): void
    {
        parent::initializeArguments();
        $this->registerArgument('url', 'string', 'The context searchResultSet', true);
        $this->registerArgument('searchWords', 'string', 'The document to highlight', true, '');
        $this->registerArgument('addNoCache', 'boolean', 'Should no_cache=1 be added or not', false, true);
        $this->registerArgument('keepCHash', 'boolean', 'Should cHash be kept or not', false, false);
    }

    /**
     * Renders URI for adding the search word list.
     */
    public static function renderStatic(
        array $arguments,
        Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $url = $arguments['url'];

        $resultSet = self::getUsedSearchResultSetFromRenderingContext($renderingContext);
        if (!$resultSet->getUsedSearchRequest()->getContextTypoScriptConfiguration()->getSearchResultsSiteHighlighting()) {
            return $url;
        }

        $searchWords = $arguments['searchWords'] ?? '';
        $addNoCache = $arguments['addNoCache'];
        $keepCHash = $arguments['keepCHash'];

        /** @var SiteHighlighterUrlModifier $siteHighlighterUrlModifier */
        $siteHighlighterUrlModifier = GeneralUtility::makeInstance(SiteHighlighterUrlModifier::class);

        return $siteHighlighterUrlModifier->modify($url, $searchWords, $addNoCache, $keepCHash);
    }
}
