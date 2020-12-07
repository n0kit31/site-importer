<?php

namespace SitemapImporter;

class SitemapImporter
{
    /**
     * Import XML file/URL and return formatted array.
     *
     * @param string $source
     *
     * @return array
     */
    public function import($source)
    {
        $content = $this->readContent($source);
        if (empty($content)) {
            return [];
        }
        $xml = new \SimpleXMLElement($content);

        return $this->prepareResultArray($xml);
    }

    /**
     * Read content from given source.
     *
     * @param string $source URL or file path
     *
     * @return string|false
     */
    private function readContent($source)
    {
        return file_get_contents($source);
    }

    /**
     * Prepare array to import from XML file.
     *
     * Array format:
     * [
     *     website1 => [page1, page2],
     *     website1 => [page3, page4, page5]
     * ];
     */
    private function prepareResultArray($xml)
    {
        $urls = [];

        foreach ($xml->sitemap as $element) {
            $parseUrl = parse_url((string)$element->loc);
            $urls[$parseUrl['host']][] = ltrim($parseUrl['path'], '/');
        }

        return $urls;
    }
}
