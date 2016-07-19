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

    public function testCollection()
    {
        $html = new Html('form');
        $html->text();
        $data = $html->render();
        $this->assertEquals('<form><input type="text"/></form>', $data);
    }
}
