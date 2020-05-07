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

        return [
            'image' => $this->getImage($doc),
            'href' => $this->getLink($doc),
            'name' => trim(reset($name)),
        ];
    }

    private function getImage(DOMDocument $document)
    {
        $xpath = new \DOMXPath($document);
        return $xpath->evaluate("string(//img/@src)");
    }

    private function getLink(DOMDocument $document)
    {
        $xpath = new \DOMXPath($document);
        return $xpath->evaluate("string(//a/@href)");
    }

    function getTextBetweenTags(DOMDocument $dom, $tag)
    {
        /*** discard white space ***/
        $dom->preserveWhiteSpace = false;

        /*** the tag by its tag name ***/
        $content = $dom->getElementsByTagname($tag);

        /*** the array to return ***/
        $out = array();
        foreach ($content as $item)
        {
            /*** add node value to the out array ***/
            $out[] = $item->nodeValue;
        }
        /*** return the results ***/
        return $out;
    }
}
