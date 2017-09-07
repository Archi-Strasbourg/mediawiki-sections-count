<?php

namespace SectionsCount;

class SectionsCount
{
    public static function sectionscount(\Parser $parser, $pagename = null)
    {
        global $wgTitle, $wgRequest, $wgUser, $wgParser, $wgSectionsCountIgnoreSections;
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
            for ($i = 1; $section = $otherParser->getSection($revision->getText(), $i); $i++) {
                if (isset($wgSectionsCountIgnoreSections)) {
                    foreach ($wgSectionsCountIgnoreSections as $ignoreSection) {
                        if (preg_match('/=+\s*'.$ignoreSection.'\s*=+/', $section) == 1) {
                            //If this is an ignored section, we don't count it
                            break 2;
                        }
                    }
                }
                $nbSections++;
            }

            return $nbSections;
        }

        return 0;
    }

    public static function onParserSetup(\Parser &$parser)
    {
        $parser->setFunctionHook('sectionscount', ['SectionsCount\SectionsCount', 'sectionscount']);
    }
}
