<?php
namespace Parishop\Tests;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Parishop\Document
     */
    protected $document;

    public function setUp()
    {
        $this->document = new \Parishop\Document();
    }

    public function testAliases()
    {
        $this->assertEquals([], $this->document->aliases());
    }

    public function testLinks()
    {
        $this->document->addLess('default.less');
        $this->document->addStylesheet('default.css');
        $links = [
            '<link href="default.less" rel="stylesheet/less" type="text/css"/>',
            '<link href="default.css" rel="stylesheet" type="text/css"/>',
        ];
        $this->assertEquals($links, $this->document->getLinks());
    }

    public function testMeta()
    {
        $this->document->title('Title');
        $this->document->setMetaDescription('description');
        $this->document->setMetaKeywords('keywords');
        $this->document->addMetaName('charset', 'UTF-8');
        $this->document->setMetaOgTitle('Title');
        $this->document->setMetaOgDescription('Description');
        $this->document->setMetaOgImage('image.png');
        $this->document->addMetaHttp('Content-Type', 'text/html; charset=utf-8');
        $meta = [
            '<meta name="description" content="description" />',
            '<meta name="keywords" content="keywords" />',
            '<meta name="charset" content="UTF-8" />',
            '<meta property="og:title" content="Title" />',
            '<meta property="og:description" content="Description" />',
            '<meta property="og:image" content="image.png" />',
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />',
        ];
        $this->assertEquals($meta, $this->document->getMeta());
    }

    public function testMethods()
    {
        $methods = [
            'title'      => 'title',
            'getMeta'    => 'getMeta',
            'getLinks'   => 'getLinks',
            'getScripts' => 'getScripts',
        ];
        $this->assertEquals($methods, $this->document->methods());
    }

    public function testName()
    {
        $this->assertEquals('document', $this->document->name());
    }

    public function testScripts()
    {
        $this->document->addScript('default.js');
        $scripts = ['<script type="text/javascript" src="default.js"></script>'];
        $this->assertEquals($scripts, $this->document->getScripts());
    }

}

