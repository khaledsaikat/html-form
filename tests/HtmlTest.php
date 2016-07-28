<?php
namespace UserMeta\Html;

class HtmlTest extends \TestCase
{

    private $dummyArray = [
        'id' => 'ID',
        'class' => 'Class'
    ];

    public function setUp()
    {
        $this->form = new Form();
    }

    public function testInput()
    {
        $data = Form::input('text');
        $this->assertEquals('<input type="text"/>', $data);
        
        $data = Form::input('email', 'noreply@email.com');
        $this->assertEquals('<input type="email" value="noreply@email.com"/>', $data);
        
        $data = Form::input('email', 'noreply@email.com', [
            'id' => 'ID',
            'class' => 'Class'
        ]);
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
        
        $data = Form::text('noreply@email.com', [
            'id' => 'ID',
            'class' => 'Class'
        ]);
        $this->assertEquals('<input type="text" value="noreply@email.com" id="ID" class="Class"/>', $data);
    }

    public function testLabel()
    {
        $data = Form::label();
        $this->assertEquals('<label></label>', $data);
        
        $data = Form::label('Some text');
        $this->assertEquals('<label>Some text</label>', $data);
        
        $data = Form::label('Some text', [
            'id' => 'ID',
            'class' => 'Class',
            'for' => 'for'
        ]);
        $this->assertEquals('<label id="ID" class="Class" for="for">Some text</label>', $data);
    }

    public function testCheckbox()
    {
        $data = Form::checkbox();
        $this->assertEquals('<input type="checkbox" value="1"/>', $data);
        
        $data = Form::checkbox(true);
        $this->assertEquals('<input type="checkbox" value="1" checked="checked"/>', $data);
        
        $data = Form::checkbox('Some default value');
        $this->assertEquals('<input type="checkbox" value="1"/>', $data);
        
        $data = Form::checkbox(0, [
            'id' => 'ID',
            'class' => 'Class'
        ]);
        $this->assertEquals('<input type="checkbox" value="1" id="ID" class="Class"/>', $data);
        
        $data = Form::checkbox(1, [
            'id' => 'ID',
            'class' => 'Class'
        ]);
        $this->assertEquals('<input type="checkbox" value="1" id="ID" class="Class" checked="checked"/>', $data);
    }

    public function testCheckboxList()
    {
        $data = Form::checkboxList();
        $this->assertEquals('', $data);
        
        $data = Form::checkboxList(null, [], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="checkbox" value="a"/> a</label><label><input type="checkbox" value="b"/> b</label>', $data);
        
        $data = Form::checkboxList('b', [], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="checkbox" value="a"/> a</label><label><input type="checkbox" value="b" checked="checked"/> b</label>', $data);
        
        $data = Form::checkboxList([
            'a',
            'b'
        ], [], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="checkbox" value="a" checked="checked"/> a</label><label><input type="checkbox" value="b" checked="checked"/> b</label>', $data);
        
        $data = Form::checkboxList([
            'a'
        ], [
            'class' => 'Class'
        ], [
            'a' => 'A',
            'b' => 'B'
        ]);
        $this->assertEquals('<label><input type="checkbox" value="a" class="Class" checked="checked"/> A</label><label><input type="checkbox" value="b" class="Class"/> B</label>', $data);
    }

    public function testSelect()
    {
        // $this->markTestSkipped();
        $data = Form::select();
        $this->assertEquals('<select></select>', $data);
        
        $data = Form::select(null, [
            'id' => 'ID',
            'class' => 'Class'
        ]);
        $this->assertEquals('<select id="ID" class="Class"></select>', $data);
        
        $data = Form::select(null, [], [
            'a',
            'b'
        ]);
        $this->assertEquals('<select><option value="a">a</option><option value="b">b</option></select>', $data);
        
        $data = Form::select('b', [], [
            'a',
            'b'
        ]);
        $this->assertEquals('<select><option value="a">a</option><option value="b" selected="selected">b</option></select>', $data);
        
        $data = Form::select('b', [
            'id' => 'ID',
            'class' => 'Class'
        ], [
            'a' => 'A',
            'b' => 'B'
        ]);
        $this->assertEquals('<select id="ID" class="Class"><option value="a">A</option><option value="b" selected="selected">B</option></select>', $data);
    }

    /**
     * @depends testSelect
     */
    public function testDropdown()
    {
        $data = Form::dropdown('b', [
            'id' => 'ID',
            'class' => 'Class'
        ], [
            'a',
            'b'
        ]);
        $this->assertEquals('<select id="ID" class="Class"><option value="a">a</option><option value="b" selected="selected">b</option></select>', $data);
    }

    /**
     * @depends testSelect
     */
    public function testMultiselect()
    {
        $data = Form::multiselect();
        $this->assertEquals('<select multiple="multiple"></select>', $data);
        
        $data = Form::multiselect(null, [
            'id' => 'ID',
            'class' => 'Class'
        ]);
        $this->assertEquals('<select id="ID" class="Class" multiple="multiple"></select>', $data);
        
        $data = Form::multiselect(null, [], [
            'a',
            'b'
        ]);
        $this->assertEquals('<select multiple="multiple"><option value="a">a</option><option value="b">b</option></select>', $data);
        
        $data = Form::multiselect([
            'a',
            'c'
        ], [], [
            'a',
            'b',
            'c'
        ]);
        $this->assertEquals('<select multiple="multiple"><option value="a" selected="selected">a</option><option value="b">b</option><option value="c" selected="selected">c</option></select>', $data);
        
        $data = Form::multiselect('b', [
            'id' => 'ID',
            'class' => 'Class'
        ], [
            'a' => 'A',
            'b' => 'B'
        ]);
        $this->assertEquals('<select id="ID" class="Class" multiple="multiple"><option value="a">A</option><option value="b" selected="selected">B</option></select>', $data);
    }

    public function testRadio()
    {
        $data = Form::radio();
        $this->assertEquals('<input type="radio" value="1"/>', $data);
        
        $data = Form::radio(null, [], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="radio" value="a"/> a</label><label><input type="radio" value="b"/> b</label>', $data);
        
        $data = Form::radio('b', [], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="radio" value="a"/> a</label><label><input type="radio" value="b" checked="checked"/> b</label>', $data);
        
        $data = Form::radio('b', [
            'class' => 'Class'
        ], [
            'a' => 'A',
            'b' => 'B'
        ]);
        $this->assertEquals('<label><input type="radio" value="a" class="Class"/> A</label><label><input type="radio" value="b" class="Class" checked="checked"/> B</label>', $data);
    }

    public function testOptionsElementWithGroup()
    {
        $options = [
            [
                'type' => 'optgroup',
                'label' => 'Group1'
            ],
            [
                'value' => 'a',
                'label' => 'A'
            ],
            [
                'value' => 'b',
                'label' => 'B'
            ],
            [
                'type' => 'optgroup',
                'label' => 'Group2'
            ],
            [
                'value' => 'p',
                'label' => 'P'
            ]
        ];
        
        $data = Form::select(null, [], $options);
        $this->assertEquals('<select><optgroup label="Group1"><option value="a">A</option><option value="b">B</option></optgroup><optgroup label="Group2"><option value="p">P</option></optgroup></select>', $data);
        
        $data = Form::radio(null, [], $options);
        $this->assertEquals('<div><label>Group1</label><br /><label><input type="radio" value="a"/> A</label><label><input type="radio" value="b"/> B</label></div><div><label>Group2</label><br /><label><input type="radio" value="p"/> P</label></div>', $data);
        
        $data = Form::checkboxList(null, [], $options);
        $this->assertEquals('<div><label>Group1</label><br /><label><input type="checkbox" value="a"/> A</label><label><input type="checkbox" value="b"/> B</label></div><div><label>Group2</label><br /><label><input type="checkbox" value="p"/> P</label></div>', $data);
    }

    public function testOptionsElementWithAttributes()
    {
        $options = [
            [
                'value' => 'a',
                'label' => 'A',
                'class' => 'ClassA',
                'data' => 'DataA'
            ],
            [
                'value' => 'b',
                'label' => 'B',
                'class' => 'ClassB',
                'data' => 'DataB'
            ]
        ];
        
        $html = Form::select(null, [
            'class' => 'Class',
            'test' => 'test'
        ], $options);
        $this->assertEquals('<select class="Class" test="test"><option value="a" class="ClassA" data="DataA">A</option><option value="b" class="ClassB" data="DataB">B</option></select>', $html);
        
        $html = Form::radio(null, [
            'class' => 'Class',
            'test' => 'test'
        ], $options);
        $this->assertEquals('<label><input type="radio" value="a" class="ClassA" test="test" data="DataA"/> A</label><label><input type="radio" value="b" class="ClassB" test="test" data="DataB"/> B</label>', $html);
        
        $html = Form::checkboxList(null, [
            'class' => 'Class',
            'test' => 'test'
        ], $options);
        $this->assertEquals('<label><input type="checkbox" value="a" class="ClassA" test="test" data="DataA"/> A</label><label><input type="checkbox" value="b" class="ClassB" test="test" data="DataB"/> B</label>', $html);
    }

    public function testOptionsElementWithOptionGroup()
    {
        $options = [
            [
                'type' => 'optgroup',
                'label' => 'A',
                'class' => 'GroupClass'
            ]
        ];
        
        $html = Form::select(null, [
            'class' => 'Class'
        ], $options);
        $this->assertEquals('<select class="Class"><optgroup label="A" class="GroupClass"></optgroup></select>', $html);
        
        $html = Form::radio(null, [
            'class' => 'Class'
        ], $options);
        $this->assertEquals('<div><label class="GroupClass">A</label><br /></div>', $html);
        
        $html = Form::checkboxList(null, [
            'class' => 'Class'
        ], $options);
        $this->assertEquals('<div><label class="GroupClass">A</label><br /></div>', $html);
    }

    public function testNameAttribute()
    {
        $html = Form::text(null, [
            'name' => 'Name'
        ]);
        $this->assertEquals('<input type="text" name="Name"/>', $html);
        
        $html = Form::checkbox(null, [
            'name' => 'Name'
        ]);
        $this->assertEquals('<input type="checkbox" name="Name" value="1"/>', $html);
        
        $html = Form::select(null, [
            'name' => 'Name'
        ], [
            'a',
            'b'
        ]);
        $this->assertEquals('<select name="Name"><option value="a">a</option><option value="b">b</option></select>', $html);
        
        $html = Form::radio(null, [
            'name' => 'Name'
        ], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="radio" value="a" name="Name"/> a</label><label><input type="radio" value="b" name="Name"/> b</label>', $html);
        
        $html = Form::checkboxList(null, [
            'name' => 'Name'
        ], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="checkbox" value="a" name="Name"/> a</label><label><input type="checkbox" value="b" name="Name"/> b</label>', $html);
    }

    public function testIdAttribute()
    {
        $html = Form::text(null, [
            'id' => 'ID'
        ]);
        $this->assertEquals('<input type="text" id="ID"/>', $html);
        
        $html = Form::checkbox(null, [
            'id' => 'ID'
        ]);
        $this->assertEquals('<input type="checkbox" value="1" id="ID"/>', $html);
        
        $html = Form::select(null, [
            'id' => 'ID'
        ], [
            'a',
            'b'
        ]);
        $this->assertEquals('<select id="ID"><option value="a">a</option><option value="b">b</option></select>', $html);
        
        $html = Form::radio(null, [
            'id' => 'ID'
        ], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="radio" value="a" id="ID_1"/> a</label><label><input type="radio" value="b" id="ID_2"/> b</label>', $html);
        
        $html = Form::checkboxList(null, [
            'id' => 'ID'
        ], [
            'a',
            'b'
        ]);
        $this->assertEquals('<label><input type="checkbox" value="a" id="ID_1"/> a</label><label><input type="checkbox" value="b" id="ID_2"/> b</label>', $html);
    }

    public function testOptionsElementBeforeAfter()
    {
        $html = Form::radio(null, [
            '_option_before' => 'B',
            '_option_after' => 'A'
        ], [
            'a'
        ]);
        $this->assertEquals('B<label><input type="radio" value="a"/> a</label>A', $html);
        
        $html = Form::checkboxList(null, [
            '_option_before' => 'B',
            '_option_after' => 'A'
        ], [
            'a'
        ]);
        $this->assertEquals('B<label><input type="checkbox" value="a"/> a</label>A', $html);
        
        $html = Form::radio(null, [], [
            [
                '_option_before' => 'B',
                'value' => 'Value',
                'label' => 'Label'
            ]
        ]);
        $this->assertEquals('B<label><input type="radio" value="Value"/> Label</label>', $html);
        
        $html = Form::checkboxList(null, [], [
            [
                '_option_after' => 'A',
                'value' => 'Value',
                'label' => 'Label'
            ]
        ]);
        $this->assertEquals('<label><input type="checkbox" value="Value"/> Label</label>A', $html);
    }

    public function testElementWithLabel()
    {
        $html = Form::text(null, [
            'label' => 'Label'
        ]);
        $this->assertEquals('<label>Label</label><input type="text"/>', $html);
        
        $html = Form::checkbox(null, [
            'label' => 'Label'
        ]);
        $this->assertEquals('<label>Label</label><input type="checkbox" value="1"/>', $html);
        
        $html = Form::select(null, [
            'label' => 'Label'
        ]);
        $this->assertEquals('<label>Label</label><select></select>', $html);
        
        $html = Form::radio(null, [
            'label' => 'Label'
        ], [
            'a'
        ]);
        $this->assertEquals('<label>Label</label><label><input type="radio" value="a"/> a</label>', $html);
        
        $html = Form::checkboxList(null, [
            'label' => 'Label'
        ], [
            'a'
        ]);
        $this->assertEquals('<label>Label</label><label><input type="checkbox" value="a"/> a</label>', $html);
    }

    public function testElementWithLabelAndID()
    {
        $html = Form::text(null, [
            'id' => 'ID',
            'label' => 'Label'
        ]);
        $this->assertEquals('<label for="ID">Label</label><input type="text" id="ID"/>', $html);
        
        $html = Form::checkbox(null, [
            'id' => 'ID',
            'label' => 'Label'
        ]);
        $this->assertEquals('<label for="ID">Label</label><input type="checkbox" value="1" id="ID"/>', $html);
        
        $html = Form::select(null, [
            'id' => 'ID',
            'label' => 'Label'
        ]);
        $this->assertEquals('<label for="ID">Label</label><select id="ID"></select>', $html);
        
        $html = Form::radio(null, [
            'id' => 'ID',
            'label' => 'Label'
        ], [
            'a'
        ]);
        $this->assertEquals('<label>Label</label><label><input type="radio" value="a" id="ID_1"/> a</label>', $html);
        
        $html = Form::checkboxList(null, [
            'id' => 'ID',
            'label' => 'Label'
        ], [
            'a'
        ]);
        $this->assertEquals('<label>Label</label><label><input type="checkbox" value="a" id="ID_1"/> a</label>', $html);
    }

    public function testLabelInCollection()
    {
        $div = new Html('div');
        $div->text('example', [
            'label' => 'Label',
            'name' => 'Name'
        ]);
        $html = $div->render();
        $this->assertEquals('<div><label>Label</label><input type="text" name="Name" value="example"/></div>', $html);
    }

    public function testLabelAttributesInCollection()
    {
        $div = new Html('div');
        $div->text('example', [
            'label' => [
                'Label',
                'class' => 'Class'
            ],
            'name' => 'Name'
        ]);
        $html = $div->render();
        $this->assertEquals('<div><label class="Class">Label</label><input type="text" name="Name" value="example"/></div>', $html);
    }
}
