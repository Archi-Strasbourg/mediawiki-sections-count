<?php

namespace SectionsCount;

class SectionsCount
{
    public static function sectionscount(\Parser $parser, $pagename = null)
    {
        global $wgTitle, $wgRequest, $wgUser, $wgParser;
        if (empty($pagename)) {
            $title = $wgTitle;
        } else {
            $title = \Title::newFromText($pagename);
        }
        $revision = \Revision::newFromId($title->getLatestRevID());
        if (isset($revision)) {
            //Prevent recursive parsing
            $otherParser = $wgParser->getFreshParser();
            $nbSections = 0;
            for ($i = 1; $otherParser->getSection($revision->getText(), $i); $i++) {
                $nbSections++;
            }
            return $nbSections;
        }
    }

    public static function onParserSetup(\Parser &$parser)
    {
        $parser->setFunctionHook('sectionscount', ['SectionsCount\SectionsCount', 'sectionscount']);
    }
}
