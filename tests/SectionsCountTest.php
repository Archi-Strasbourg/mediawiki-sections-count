<?php

namespace SectionsCount\Test;

use Mockery;
use SectionsCount\SectionsCount;

class SectionsCountTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Mockery::mock('overload:ParserOutput')
            ->shouldReceive('getSections')
            ->andReturn(['foo', 'bar']);
        Mockery::mock('overload:Parser')
            ->shouldReceive('setFunctionHook')
            ->shouldReceive('getSection')
            ->andReturn('foo', 'bar', null, 'foo', 'bar', null)
            ->shouldReceive('getFreshParser')
            ->andReturn(new \Parser());
        Mockery::mock('overload:ParserOptions');
        Mockery::mock('overload:Revision')
            ->shouldReceive('getText')
            ->shouldReceive('newFromId')
            ->andReturn(new \Revision(), new \Revision(), null);
        Mockery::mock('overload:Title')
            ->shouldReceive('getLatestRevID')
            ->shouldReceive('newFromText')
            ->andReturn(new \Title());
    }

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testSectionscount()
    {
        global $wgTitle, $wgParser;
        $wgParser = new \Parser();

        $this->assertEquals(2, SectionsCount::sectionscount(new \Parser(), 'Foo'));
        $wgTitle = new \Title();
        $this->assertEquals(2, SectionsCount::sectionscount(new \Parser()));
        //And now with Revision::newFromId returning null
        SectionsCount::sectionscount(new \Parser());
    }

    public function testOnParserSetup()
    {
        $parser = new \Parser();
        SectionsCount::onParserSetup($parser);
    }
}
