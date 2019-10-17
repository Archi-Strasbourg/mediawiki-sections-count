<?php

namespace SectionsCount;

use ContentHandler;
use Parser;
use Revision;
use Title;

class SectionsCount
{
    /**
     * @param Parser $parser
     * @param null $pagename
     * @return int
     * @throws \MWException
     */
    public static function sectionscount(Parser $parser, $pagename = null)
    {
        global $wgTitle, $wgParser, $wgSectionsCountIgnoreSections;
        if (empty($pagename)) {
            $title = $wgTitle;
        } else {
            $title = Title::newFromText($pagename);
        }

        if (isset($title)) {
            $revision = Revision::newFromId($title->getLatestRevID());
            if (isset($revision)) {
                //Prevent recursive parsing
                $otherParser = $wgParser->getFreshParser();
                $nbSections = 0;
                for ($i = 1; $section = $otherParser->getSection(ContentHandler::getContentText($revision->getContent(Revision::RAW)), $i); $i++) {
                    if (isset($wgSectionsCountIgnoreSections)) {
                        foreach ($wgSectionsCountIgnoreSections as $ignoreSection) {
                            if (preg_match('/=+\s*' . $ignoreSection . '\s*=+/', $section) == 1) {
                                //If this is an ignored section, we don't count it
                                break 2;
                            }
                        }
                    }
                    $nbSections++;
                }

                return $nbSections;
            }
        }

        return 0;
    }

    /**
     * @param Parser $parser
     * @throws \MWException
     */
    public static function onParserSetup(Parser &$parser)
    {
        $parser->setFunctionHook('sectionscount', ['SectionsCount\SectionsCount', 'sectionscount']);
    }
}
