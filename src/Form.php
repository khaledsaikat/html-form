<?php

namespace UserMeta\Html;

class Form
{
    public $type;

    public $default;

    public $attributes = [];

    public $options = [];

    protected function label($default = null, array $attributes = [])
    {
        return "<label{$this->attributes()}>$default</label>";
    }

    protected function dropdown($default = null, array $attributes = [], array $options = [])
    {
        return $this->select($default, $attributes, $options);
    }

    protected function select($default = null, array $attributes = [], array $options = [])
    {
        $this->setProperties('select', $default, $attributes, $options);

        $html = "<select{$this->attributes()}>";
        foreach ($this->options as $option) {
            $html .= "<option value=\"{$option['value']}\"{$this->_optionsAttributes($option)}>{$option['label']}</option>";
        }
        $html .= '</select>';

        return $html;
    }

    private function _optionsAttributes(array $option)
    {
        return $this->_selectedAttribute($option);
    }

    /**
     * Get selected/checked attribute.
     *
     * @param array $option: single option contains key 'value'
     *
     * @return string: <space>selected="selected" | <space>checked="checked" | ""
     */
    private function _selectedAttribute(array $option)
    {
        $selected = '';

        if (empty($option['value']) || empty($this->default)) {
            return $selected;
        }

        switch ($this->type) {
            case 'select':
                $text = ' selected="selected"';
            break;

            case 'checkbox':
                $text = ' checked="checked"';
            break;
        }

        if (is_array($this->default)) {
            $selected = in_array($option['value'], $this->default) ? $text : '';
        } else {
            $selected = $this->default == $option['value'] ? $text : '';
        }

        return $selected;
    }

    protected function radio($default = null, array $attributes = [], array $options = [])
    {

        /*$this->setProperties('radio', $default, $attributes, $options);

        $html = "<select{$this->attributes()}>";
        foreach ($this->options as $option) {
            $html .= "<option value=\"{$option['value']}\"{$this->_optionsAttributes($option)}>{$option['label']}</option>";
        }
        $html .= '</select>';

        return $html;*/

        $this->setProperties('radio', $default, $attributes, $options);

        $html = '';
        foreach ($this->options as $option) {
            $radio = $this->_singleCheckboxRadio('radio', $default, $attributes);

            //$html .= "<option value=\"{$option['value']}\"{$this->_optionsAttributes($option)}>{$option['label']}</option>";

            $html .= "<label>$radio {$option['label']}</label>";
        }

        return $html;
    }

    protected function checkboxList($default = null, array $attributes = [], array $options = [])
    {
        $this->setOptions($options);

        $html = '';
        foreach ($this->options as $option) {
            $checkbox = $this->checkbox($default, $attributes);

            $html .= "<label>$checkbox {$option['label']}</label>";
        }

        return $html;
    }

    /**
     * Set $this->options.
     *
     * @param array $options: indexed, associative [value => label]
     */
    private function setOptions(array $options)
    {
        if (empty($options)) {
            return;
        }

        /*
         * Determine if $options is indexed or associative array
         */
        $isIndexed = isset($options[0]) && isset($options[count($options) - 1]) ? true : false;

        if (!is_array(reset($options))) {
            $opt = array();
            foreach ($options as $key => $val) {
                $opt[] = [
                    'value' => $isIndexed ? $val : $key,
                    'label' => $val,
                ];
            }

            $options = $opt;
        }

        $this->options = $options;
    }

    private function buildOptions()
    {
        $html = '';
        foreach ($this->options as $option) {
            if (!empty($option['type']) && $option['type'] == 'optgroup') {
                if (isset($option['label']) && $option['label'] == '__end__') {
                    $html .= $this->groupEnd();
                    $inGroup = false;
                } else {
                    if (!empty($inGroup)) {
                        $html .= $this->groupEnd();
                    }

                    $html .= $this->groupStart($option);
                    $inGroup = true;
                }
            } else {
                $html .= $this->buildSingleOption($option);
            }
        }

        if (!empty($inGroup)) {
            $html .= $this->groupEnd();
        }

        return $html;
    }

    private function buildSingleOption($option)
    {
        $html = '';

        $value = isset($option['value']) ? $option['value'] : '';
        $label = isset($option['label']) ? $option['label'] : '';

        $attributes = $this->buildOptionAttributes($option);

        if (is_array($this->storedValue)) {
            $attributes .= in_array($value, $this->storedValue) ? $this->selected : '';
        } else {
            $attributes .= ($this->storedValue == $value) ? $this->selected : '';
        }

        //if (!empty($this->config['option_before'])) {
        //    $html .= $this->config['option_before'];
        //}

        $args = compact('attributes', 'value', 'label');
        $html .= $this->getSingleOption($args, $option);

        //if (!empty($this->config['option_after'])) {
        //    $html .= $this->config['option_after'];
        //}

        return $html;
    }

    protected function checkbox($default = false, array $attributes = [])
    {
        return $this->_singleCheckboxRadio('checkbox', $default, $attributes);
    }

    private function _singleCheckboxRadio($type, $default, array $attributes)
    {
        $this->setProperties($type, $default, $attributes);
        $this->_refineInputAttributes();

        if ($type == 'checkbox') {
            $this->attributes['value'] = !empty($attributes['value']) ? $attributes['value'] : '1';
        }

        if ($default) {
            $this->attributes['checked'] = 'checked';
        }

        return $this->_createInput();
    }

    protected function text($default = null, array $attributes = [])
    {
        return $this->input('text', $default, $attributes);
    }

    /**
     * Generate checkbox.
     *
     * @param bool  $default:   true for checked else false
     * @param array $attributes
     *
     * @return string : html
     */
    protected function input($type, $default = null, array $attributes = [])
    {
        $this->setProperties($type, $default, $attributes);
        $this->_refineInputAttributes();

        return $this->_createInput();
    }

    private function _createInput()
    {
        return '<input'.$this->attributes().'/>';
    }

    private function setProperties($type, $default, array $attributes, array $options = [])
    {
        $this->type = $type ?: 'text';
        $this->default = $default;
        $this->attributes = $attributes;

        $this->setOptions($options);
    }

    private function _refineInputAttributes()
    {
        $this->attributes = array_merge([
            'type' => $this->type,
            'value' => $this->default,
        ], $this->attributes);
    }

    /**
     * Build attributes string from $this->attributes property.
     *
     * @return string
     */
    private function attributes()
    {
        $html = '';

        $attributes = $this->attributes;
        $attributes = $this->_onlyNonEmpty($attributes);
        $attributes = $this->_onlyString($attributes);
        $attributes = $this->_removeKeys($attributes, ['label']);

        if (empty($attributes)) {
            return $html;
        }

        foreach ($attributes as $key => $val) {
            $html .= " $key=\"$val\"";
        }

        return $html;
    }

    /**
     * Remove elements from array for given keys.
     *
     * @param array $data: Given array
     * @param array $keys: Given keys to remove
     *
     * @return array
     */
    private function _removeKeys(array $data, array $keys)
    {
        foreach ($data as $key => $itm) {
            if (in_array($key, $keys)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Filter all non string value from given array.
     *
     * @param array $data
     *
     * @return array
     */
    private function _onlyString(array $data)
    {
        foreach ($data as $key => $itm) {
            if (!$this->_isString($itm)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Filter all empty value from given array.
     *
     * @param array $data
     *
     * @return array
     */
    private function _onlyNonEmpty(array $data)
    {
        foreach ($data as $key => $itm) {
            if (empty($itm)) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * Check if given argument is string.
     *
     * @param mixed $date
     *
     * @return bool
     */
    private function _isString($data)
    {
        if (in_array(gettype($data), ['array', 'object'])) {
            return false;
        }

        return true;
    }

    protected function test()
    {
        return 'test';
    }

    public static function registerForm()
    {
        return ['form' => new self()];
    }

    public static function __callStatic($method, $args)
    {
        $instance = new self();

        return call_user_func_array([$instance, $method], $args);
    }
}
