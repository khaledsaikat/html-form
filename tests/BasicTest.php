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
        $this->assertEquals('<meta name="keywords" content="Example"/>', $data);
    }

    public function testImg()
    {
        $data = Html::meta([
            'src' => 'image.png'
        ]);
        $this->assertEquals('<meta src="image.png"/>', $data);
    }

    public function testLink()
    {
        $data = Html::meta([
            'rel' => 'stylesheet',
            'type' => 'text/css',
            'href' => 'style.css'
        ]);
        $this->assertEquals('<meta rel="stylesheet" type="text/css" href="style.css"/>', $data);
    }

    public function testRequired()
    {
        $data = Html::email('', [
            'required'
        ]);
        $this->assertEquals('<input type="email" required="required"/>', $data);
    }

    public function testBeforeAfterEnclose()
    {
        $data = Html::email('', [
            '_before' => 'Before',
            '_after' => 'After',
            '_enclose' => [
                'div',
                'class' => 'Class'
            ]
        ]);
        $this->assertEquals('<div class="Class">Before<input type="email"/>After</div>', $data);
    }

    public function testLabelArray()
    {
        $data = Html::email('', [
            'id' => 'email',
            'label' => [
                'Label',
                'id' => 'label'
            ]
        ]);
        $this->assertEquals('<label id="label" for="email">Label</label><input type="email" id="email"/>', $data);
    }

    public function testBasicOptions()
    {
        $data = Html::select(null, [], [
            'a',
            'b' => 'B',
            'c' => [
                'C',
                'data' => 'DataC'
            ],
            [
                'value' => 'd',
                'label' => 'D',
                'data' => 'DataD'
            ]
        ]);
        $this->assertEquals('<select><option value="a">a</option><option value="b">B</option><option value="c" data="DataC">C</option><option value="d" data="DataD">D</option></select>', $data);
    }

    public function testCollection()
    {
        $html = new Html('form');
        $html->text();
        $html->add('Hello');
        $data = $html->render();
        $this->assertEquals('<form><input type="text"/>Hello</form>', $data);
    }

    public function testNestedCollection()
    {
        $html = new Html('html');
        $head = $html->import('head');
        $head->title('Example');
        $data = $html->render();
        $this->assertEquals('<html><head><title>Example</title></head></html>', $data);
    }
}
