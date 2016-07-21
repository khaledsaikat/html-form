<?php
namespace UserMeta\Html;

class UtilsTest extends \TestCase
{
    private $dummyArray = ['id' => 'ID', 'class' => 'Class'];

    public function setUp()
    {
        $this->form = new Form();
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

        $data = $this->invokeMethod($this->form, 'isString', [new \stdClass()]);
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

    public function testAddKeys()
    {
        $data = $this->invokeMethod($this->form, 'addKeys', [$this->dummyArray, ['test']]);
        $this->assertArrayHasKey('test', $data);
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

        $first = $data[0];
        $this->assertFalse(empty($first['value']));
        $this->assertFalse(empty($first['label']));
    }

    public function optionsArray()
    {
        return [
            [['a', 's', 'd']],
            [['a' => 'A', 's' => 'S']],
        ];
    }

    public function testSetOptionsWithDirectPass()
    {
        $options = [['value' => 'value1', 'label' => 'Label1'], ['value' => 'value2', 'label' => 'Label2']];
        $this->invokeMethod($this->form, 'setOptions', [$options]);
        $data = $this->getProperty($this->form, 'options');

        $this->assertEquals($options, $data);
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
    
    function testIsIntegerKeys()
    {
        $data = $this->invokeMethod($this->form, '_isIntegerKeys', [['a']]);
        $this->assertTrue($data);
        $data = $this->invokeMethod($this->form, '_isIntegerKeys', [['a','b'=>'B']]);
        $this->assertFalse($data);
    }
}
