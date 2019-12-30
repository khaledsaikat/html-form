<?php
namespace UserMeta\Html;

class EscapeTest extends \TestCase
{

    public function testValueEscape()
    {
        $data = Html::text('hello & world');
        $this->assertRegExp('/value="hello &amp; world"/', $data);

        $data = Html::text('"hello" & "world" <br>');
        $this->assertRegExp('/value="&quot;hello&quot; &amp; &quot;world&quot; &lt;br&gt;"/', $data);
    }

    // @todo Find example of multiple value and test it.
    public function testMultipleValueEscape()
    {
        $this->assertTrue(true);
    }

    public function testHtmlIdEscape()
    {
        $data = Html::text(null, [
            'id' => 'hello&world'
        ]);
        $this->assertRegExp('/id="hello&amp;world"/', $data);
    }

    public function testHtmlClassEscape()
    {
        $data = Html::text(null, [
            'class' => 'hello&world'
        ]);
        $this->assertRegExp('/class="hello&amp;world"/', $data);
    }

    public function testTextEscape()
    {
        $data = Html::text('hello & world');
        $this->assertRegExp('/value="hello &amp; world"/', $data);

        $data = Html::text(null, [
            'class' => 'hello&world'
        ]);
        $this->assertRegExp('/class="hello&amp;world"/', $data);
    }

    public function testPasswordEscape()
    {
        $data = Html::password('hello & world');
        $this->assertRegExp('/value="hello &amp; world"/', $data);

        $data = Html::password(null, [
            'class' => 'hello&world'
        ]);
        $this->assertRegExp('/class="hello&amp;world"/', $data);
    }

    public function testTextareaEscape()
    {
        $data = Html::textarea('hello & world');
        $this->assertEquals('<textarea>hello &amp; world</textarea>', $data);

        $data = Html::textarea(null, [
            'class' => 'hello&world'
        ]);
        $this->assertRegExp('/class="hello&amp;world"/', $data);
    }

    public function testCheckboxEscape()
    {
        $data = Html::checkbox(null, [
            'value' => 'hello & world',
            'class' => 'hello&world'
        ]);
        $this->assertRegExp('/value="hello &amp; world"/', $data);
        $this->assertRegExp('/class="hello&amp;world"/', $data);
    }

    public function testMultipleCheckboxEscape()
    {
        $data = Html::checkbox(null, [
            'class' => 'hello'
        ], [
            'dog' => 'Dog',
            'cat' => 'Cat'
        ]);
        $this->assertRegExp('/value="dog"/', $data);
        $this->assertRegExp('/value="cat"/', $data);
        $this->assertRegExp('/class="hello"/', $data);
    }
}
