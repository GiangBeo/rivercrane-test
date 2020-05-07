<?php

namespace App\Services;

use DOMDocument;

class LoadHtml
{
    public function loadHtml(): array
    {
        $html = view('html');
        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $name = $this->getTextBetweenTags($doc, 'a');
        $information = $this->getTableContent($doc);
        $price = $this->getTextOfClassName($doc, 'price')->item(0)->firstChild->nodeValue;
        $phone = $this->getTextBetweenTags($doc, 'span');
        $sellerInfo = $this->getTextOfClassName($doc, 'name')->item(1);

        return [
            'image' => $this->getImage($doc),
            'href' => $this->getLink($doc),
            'name' => trim(reset($name)),
            'model' => $information[0],
            'year' => $information[1],
            'displacement' => $information[2],
            'price' => preg_replace('/[^0-9]/', '', $price),
            'tel' => preg_replace('/[^0-9]/', '', reset($phone)),
            'seller_name' => $sellerInfo->firstChild->data
        ];
    }

    /**
     * @param DOMDocument $document
     * @return mixed
     */
    private function getImage(DOMDocument $document)
    {
        $xpath = new \DOMXPath($document);
        return $xpath->evaluate("string(//img/@src)");
    }

    /**
     * @param DOMDocument $document
     * @return mixed
     */
    private function getLink(DOMDocument $document)
    {
        $xpath = new \DOMXPath($document);
        return $xpath->evaluate("string(//a/@href)");
    }

    /**
     * @param DOMDocument $dom
     * @param $tag
     * @return array
     */
    function getTextBetweenTags(DOMDocument $dom, $tag)
    {
        /*** discard white space ***/
        $dom->preserveWhiteSpace = false;

        /*** the tag by its tag name ***/
        $content = $dom->getElementsByTagname($tag);

        /*** the array to return ***/
        $out = array();
        foreach ($content as $item) {
            /*** add node value to the out array ***/
            $out[] = $item->nodeValue;
        }
        /*** return the results ***/
        return $out;
    }

    /**
     * @param DOMDocument $document
     * @return array
     */
    public function getTableContent(DOMDocument $document)
    {
        $tables = $document->getElementsByTagName('table');
        $rows = $tables->item(0)->getElementsByTagName('tr');

        $outs = [];
        foreach ($rows as $row) {
            $listTd = $row->getElementsByTagName('td');
            $outs[] = trim($listTd[$listTd->length - 1]->lastChild->nodeValue);
        }

        return $outs;
    }

    /**
     * @param DOMDocument $document
     * @param string $className
     * @return \DOMNodeList|false
     */
    public function getTextOfClassName(DOMDocument $document, string $className)
    {
        $finder = new \DomXPath($document);
        return $finder->query("//*[contains(@class, '" . $className . "')]");
    }
}
