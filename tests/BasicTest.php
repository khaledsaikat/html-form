<?php
namespace UserMeta\Html;

class BasicTest extends \TestCase
{

    /**
     * Test non-existing button method as input
     */
    public function testButton()
    {
        $data = Html::button();
        $this->assertEquals('<input type="button"/>', $data);
    }

    /**
     * Test non-existing div method as tag
     */
    public function testDiv()
    {
        $data = Html::div();
        $this->assertEquals('<div></div>', $data);
    }

    public function testMeta()
    {
        $data = Html::meta([
            'name' => 'keywords',
            'content' => 'Example'
        ]);
        $this->assertEquals('<meta name="keywords" content="Example" />', $data);
    }

    public function testImg()
    {
        $data = Html::meta([
            'src' => 'image.png'
        ]);
        $this->assertEquals('<meta src="image.png" />', $data);
    }

    public function testLink()
    {
        $data = Html::meta([
            'rel' => 'stylesheet',
            'type' => 'text/css',
            'href' => 'style.css'
        ]);
        $this->assertEquals('<meta rel="stylesheet" type="text/css" href="style.css" />', $data);
    }

    public function testCollection()
    {
        $html = new Html('form');
        $html->text();
        $data = $html->render();
        $this->assertEquals('<form><input type="text"/></form>', $data);
    }

    public function testNestedCollection()
    {
        $html = new Html('html');
        $head = new Html('head');
        $head->title('Example');
        $html->add($head);
        $data = $html->render();
        $this->assertEquals('<html><head><title>Example</title></head></html>', $data);
    }
}
