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

namespace ApacheSolrForTypo3\Solr\Domain\Search\ResultSet\Facets\OptionBased;

use ApacheSolrForTypo3\Solr\Domain\Search\ResultSet\Facets\AbstractFacetItemCollection;
use ApacheSolrForTypo3\Solr\Domain\Search\ResultSet\Facets\OptionBased\Options\Option;
use ApacheSolrForTypo3\Solr\System\Data\AbstractCollection;

/**
 * Collection for facet options.
 */
class OptionCollection extends AbstractFacetItemCollection
{
    /**
     * Returns an array of prefixes from the option labels.
     *
     * Red, Blue, Green => r, g, b
     *
     * Can be used in combination with getByPrefix() to group facet options by prefix (e.g. alphabetical).
     */
    public function getLowercaseLabelPrefixes(int $length = 1): array
    {
        $prefixes = $this->getLabelPrefixes($length);
        return array_map('mb_strtolower', $prefixes);
    }

    /**
     * Returns {@link AbstractFacetItemCollection} or {@link OptionCollection} for filtered prefix
     */
    public function getByLowercaseLabelPrefix(string $filteredPrefix): AbstractCollection|AbstractFacetItemCollection|OptionCollection
    {
        return $this->getFilteredCopy(function (Option $option) use ($filteredPrefix) {
            $filteredPrefixLength = mb_strlen($filteredPrefix);
            $currentPrefix = mb_substr(mb_strtolower($option->getLabel()), 0, $filteredPrefixLength);

            return $currentPrefix === $filteredPrefix;
        });
    }

    /**
     * Returns the labels prefixes
     */
    protected function getLabelPrefixes(int $length = 1): array
    {
        $prefixes = [];
        foreach ($this->data as $option) {
            $prefix = mb_substr($option->getLabel(), 0, $length);
            $prefixes[$prefix] = $prefix;
        }

        return array_values($prefixes);
    }
}
