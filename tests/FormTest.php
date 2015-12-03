<?php

use UserMeta\Html\Form;

class FormTest extends TestCase
{

    private $dummyArray = ['id' => 'ID', 'class' => 'Class'];

    public function setUp()
    {
        $this->form = new Form;
    }

    public function testIsString()
    {
        $data = $this->invokeMethod($this->form, 'isString', ['abc']);
        $this->assertTrue($data);

        $data = $this->invokeMethod($this->form, 'isString', ['1']);
        $this->assertTrue($data);

        $data = $this->invokeMethod($this->form, 'isString', ['0']);
        $this->assertTrue($data);

        $data = $this->invokeMethod($this->form, 'isString', ['']);
        $this->assertTrue($data);

        $data = $this->invokeMethod($this->form, 'isString', [null]);
        $this->assertTrue($data);

        $data = $this->invokeMethod($this->form, 'isString', [[]]);
        $this->assertFalse($data);

        $data = $this->invokeMethod($this->form, 'isString', [new stdClass]);
        $this->assertFalse($data);
    }

    public function testOnlyNonEmpty()
    {
        $data = $this->invokeMethod($this->form, 'onlyNonEmpty', [['a' => 'A', 'b' => '', 'c' => 'C']]);
        $this->assertCount(2, $data);
    }

    public function testOnlyString()
    {
        $data = $this->invokeMethod($this->form, 'onlyString', [['a' => 'A', 'b' => [], 'c' => 'C']]);
        $this->assertCount(2, $data);
    }

    public function testRemoveKeys()
    {
        $data = $this->invokeMethod($this->form, 'removeKeys', [$this->dummyArray, ['class']]);
        $this->assertFalse(isset($data['class']));
    }

    /**
     * @depends testOnlyNonEmpty
     * @depends testOnlyString
     * @depends testRemoveKeys
     */
    public function testAttributes()
    {
        $this->setProperty($this->form, 'attributes', [
            'value' => 'Value',
            'id' => 'ID',
            'class' => 'Class',
            'b' => '', // will skip as empty
            'c' => [], // will skip as empty
            'd' => ['asdf'], // will skip as non string
            'label' => 'Label', // will skip
        ]);

        $data = $this->invokeMethod($this->form, 'attributes');
        $this->assertEquals(' value="Value" id="ID" class="Class"', $data);
    }

    /**
     * @dataProvider optionsArray
     */
    public function testSetOptions($options)
    {
        $this->invokeMethod($this->form, 'setOptions', [$options]);
        $data = $this->getProperty($this->form, 'options');

        $this->assertCount(count($options), $data);

        $first = reset($data);
        $this->assertFalse(empty($first['value']));
        $this->assertFalse(empty($first['label']));
    }

    public function optionsArray()
    {
        return [
            [['a', 's', 'd']],
            [['a' => 'A', 's' => 'S', 'd']]
        ];
    }

    /**
     * @depends testSetOptions
     */
    public function testSetProperties()
    {
        $arg = ['email', 'noreply@email.com', ['id' => 'ID', 'class' => 'Class']];
        $this->invokeMethod($this->form, 'setProperties', $arg);

        $this->assertEquals('email', $this->form->type);
        $this->assertEquals('noreply@email.com', $this->form->default);
        $this->assertEquals(['id' => 'ID', 'class' => 'Class'], $this->form->attributes);
    }

    public function testInput()
    {
        $data = Form::input('text');
        $this->assertEquals('<input type="text"/>', $data);

        $data = Form::input('email', 'noreply@email.com');
        $this->assertEquals('<input type="email" value="noreply@email.com"/>', $data);

        $data = Form::input('email', 'noreply@email.com', ['id' => 'ID', 'class' => 'Class']);
        $this->assertEquals('<input type="email" value="noreply@email.com" id="ID" class="Class"/>', $data);
    }

    /**
     * @depends testInput
     */
    public function testText()
    {
        $data = Form::text();
        $this->assertEquals('<input type="text"/>', $data);

        $data = Form::text('Some text');
        $this->assertEquals('<input type="text" value="Some text"/>', $data);

        $data = Form::text('noreply@email.com', ['id' => 'ID', 'class' => 'Class']);
        $this->assertEquals('<input type="text" value="noreply@email.com" id="ID" class="Class"/>', $data);
    }

    public function testCheckbox()
    {
        $data = Form::checkbox();
        $this->assertEquals('<input type="checkbox" value="1"/>', $data);

        $data = Form::checkbox('Some default value');
        $this->assertEquals('<input type="checkbox" value="1" checked="checked"/>', $data);

        $data = Form::checkbox(0, ['id' => 'ID', 'class' => 'Class']);
        $this->assertEquals('<input type="checkbox" value="1" id="ID" class="Class"/>', $data);

        $data = Form::checkbox(1, ['id' => 'ID', 'class' => 'Class']);
        $this->assertEquals('<input type="checkbox" value="1" id="ID" class="Class" checked="checked"/>', $data);
    }

    public function testCheckboxList()
    {
        $data = Form::checkboxList();
        $this->assertEquals('', $data);

        $data = Form::checkboxList(null, [], ['a', 'b']);
        $this->assertEquals('<label><input type="checkbox" value="a"/> a</label><label><input type="checkbox" value="b"/> b</label>', $data);

        $data = Form::checkboxList('b', [], ['a', 'b']);
        $this->assertEquals('<label><input type="checkbox" value="a"/> a</label><label><input type="checkbox" value="b" checked="checked"/> b</label>', $data);

        $data = Form::checkboxList(['a', 'b'], [], ['a', 'b']);
        $this->assertEquals('<label><input type="checkbox" value="a" checked="checked"/> a</label><label><input type="checkbox" value="b" checked="checked"/> b</label>', $data);

        $data = Form::checkboxList(['a'], ['id' => 'ID', 'class' => 'Class'], ['a' => 'A', 'b' => 'B']);
        $this->assertEquals('<label><input type="checkbox" value="a" checked="checked"/> A</label><label><input type="checkbox" value="b"/> B</label>', $data);
    }

    public function testSelect()
    {
        $data = Form::select();
        $this->assertEquals('<select></select>', $data);

        $data = Form::select(null, ['id' => 'ID', 'class' => 'Class']);
        $this->assertEquals('<select id="ID" class="Class"></select>', $data);

        $data = Form::select(null, [], ['a', 'b']);
        $this->assertEquals('<select><option value="a">a</option><option value="b">b</option></select>', $data);

        $data = Form::select('b', [], ['a', 'b']);
        $this->assertEquals('<select><option value="a">a</option><option value="b" selected="selected">b</option></select>', $data);

        $data = Form::select('b', ['id' => 'ID', 'class' => 'Class'], ['a' => 'A', 'b' => 'B']);
        $this->assertEquals('<select id="ID" class="Class"><option value="a">A</option><option value="b" selected="selected">B</option></select>', $data);
    }

    /**
     * @depends testSelect
     */
    public function testDropdown()
    {
        $data = Form::dropdown('b', ['id' => 'ID', 'class' => 'Class'], ['a', 'b']);
        $this->assertEquals('<select id="ID" class="Class"><option value="a">a</option><option value="b" selected="selected">b</option></select>', $data);
    }

    public function testRadio()
    {
        $data = Form::radio();
        $this->assertEquals('', $data);

        $data = Form::radio(null, [], ['a', 'b']);
        $this->assertEquals('<label><input type="radio" value="a"/> a</label><label><input type="radio" value="b"/> b</label>', $data);

        $data = Form::radio('b', [], ['a', 'b']);
        $this->assertEquals('<label><input type="radio" value="a"/> a</label><label><input type="radio" value="b" checked="checked"/> b</label>', $data);

        $data = Form::radio('b', ['id' => 'ID', 'class' => 'Class'], ['a' => 'A', 'b' => 'B']);
        $this->assertEquals('<label><input type="radio" value="a"/> A</label><label><input type="radio" value="b" checked="checked"/> B</label>', $data);
    }

    public function testLabel()
    {
        $data = Form::label();
        $this->assertEquals('<label></label>', $data);

        $data = Form::label('Some text');
        $this->assertEquals('<label>Some text</label>', $data);

        $data = Form::label('Some text', ['id' => 'ID', 'class' => 'Class', 'for' => 'for']);
        $this->assertEquals('<label id="ID" class="Class" for="for">Some text</label>', $data);
    }

}
