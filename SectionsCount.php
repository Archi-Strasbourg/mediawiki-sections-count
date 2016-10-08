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
            $otherParser = new \Parser();
            $output = $otherParser->parse($revision->getText(), $title, new \ParserOptions($wgUser));
            return count($output->getSections());
        }
    }

    public static function onParserSetup(\Parser &$parser)
    {
        $parser->setFunctionHook('sectionscount', ['SectionsCount\SectionsCount', 'sectionscount']);
    }
}
