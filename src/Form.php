<?php

namespace UserMeta\Html;

/*
 * Class for html form builder.
 *
 * @author Khaled Hossain
 */

if (!class_exists('Form')) {
    class Form
    {
        /**
     * Input type.
     */
    public $type;

    /**
     * Default value.
     */
    public $default;

    /**
     * Input attributes.
     */
    public $attributes = [];

    /**
     * Options array for select | multiselect | radio | checkboxList.
     */
    public $options = [];

    /**
     * Generate text input.
     *
     * @param string $default:    Default value attribute
     * @param array  $attributes: (optional)
     *
     * @return string : html text input
     */
    protected function text($default = null, array $attributes = [])
    {
        return $this->input('text', $default, $attributes);
    }

    /**
     * Generate label.
     *
     * @param string $default:    Text for label
     * @param array  $attributes: (optional)
     *
     * @return string : html label
     */
    protected function label($default = null, array $attributes = [])
    {
        $this->setProperties('label', $default, $attributes);

        return "<label{$this->attributes()}>$default</label>";
    }

    /**
     * Generate single checkbox.
     *
     * @param bool  $default:    true, 1 or any value for checked and false or 0 for unchecked
     * @param array $attributes: (optional)
     *
     * @return string : html checkbox
     */
    protected function checkbox($default = false, array $attributes = [])
    {
        $this->setProperties('checkbox', $default, $attributes);
        $this->_refineInputAttributes();

        $this->attributes['value'] = !empty($attributes['value']) ? $attributes['value'] : '1';

        if ($default) {
            $this->attributes['checked'] = 'checked';
        }

        return $this->_createInput();
    }

    /**
     * Generate html input.
     *
     * @param string $type:      Input type attribute
     * @param string $default:   Default value attribute
     * @param array  $attributes
     *
     * @return string : Generic html input
     */
    protected function input($type, $default = null, array $attributes = [])
    {
        $this->setProperties($type, $default, $attributes);
        $this->_refineInputAttributes();

        return $this->_createInput();
    }

    /**
     * Generate html select.
     *
     * @param string $default:   Default selected value
     * @param array  $attributes
     * @param array  $options:   Dropdown options
     *
     * @return string : html select
     */
    protected function dropdown($default = null, array $attributes = [], array $options = [])
    {
        return $this->select($default, $attributes, $options);
    }

    /**
     * Generate html select.
     *
     * @param string $default:   Default selected value
     * @param array  $attributes
     * @param array  $options:   Dropdown options
     *
     * @return string : html select
     */
    protected function select($default = null, array $attributes = [], array $options = [])
    {
        $this->setProperties('select', $default, $attributes, $options);

        $html = "<select{$this->attributes()}>";
        foreach ($this->options as $option) {
            $html .= "<option value=\"{$option['value']}\"{$this->optionsAttributes($option)}>{$option['label']}</option>";
        }
        $html .= '</select>';

        return $html;
    }

    /**
     * Generate list of radios.
     *
     * @param string $default:   Default checked value
     * @param array  $attributes
     * @param array  $options:   Dropdown options
     *
     * @return string : html radio
     */
    protected function radio($default = null, array $attributes = [], array $options = [])
    {
        $this->setProperties('radio', $default, $attributes, $options);

        $html = '';
        foreach ($this->options as $option) {
            $html .= "<label><input type=\"radio\" value=\"{$option['value']}\"{$this->optionsAttributes($option)}/> {$option['label']}</label>";
        }

        return $html;
    }

    /**
     * Generate list of checkboxes.
     *
     * @param string | array $default:   Default checked value
     * @param array          $attributes
     * @param array          $options:   Dropdown options
     *
     * @return string : html checkboxes
     */
    protected function checkboxList($default = null, array $attributes = [], array $options = [])
    {
        $this->setProperties('checkbox', $default, $attributes, $options);

        $html = '';
        foreach ($this->options as $option) {
            $html .= "<label><input type=\"checkbox\" value=\"{$option['value']}\"{$this->optionsAttributes($option)}/> {$option['label']}</label>";
        }

        return $html;
    }

    /**
     * Building options attributes.
     *
     * @param array $options
     *
     * @return string : html atributes
     */
    private function optionsAttributes(array $option)
    {
        return $this->_selectedAttribute($option);
    }

    /**
     * Get selected/checked attribute.
     *
     * @param array $option: single option contains key 'value'
     *
     * @return string: <space>selected="selected" | <space>checked="checked" | ''
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
            case 'radio':
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

        private function buildOptions_()
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

        private function buildSingleOption_($option)
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

    /**
     * Creating input.
     */
    private function _createInput()
    {
        return '<input'.$this->attributes().'/>';
    }

    /**
     * Set class properties.
     *
     * @param string $type:      Input type attribute
     * @param string $default:   Default value attribute
     * @param array  $attributes
     * @param array  $options
     */
    private function setProperties($type, $default, array $attributes, array $options = [])
    {
        $this->type = $type ?: 'text';
        $this->default = $default;
        $this->attributes = $attributes;

        $this->setOptions($options);
    }

    /**
     * Adding type and value to $this->attributes
     * Useful for making text fields.
     */
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
        $attributes = $this->onlyNonEmpty($attributes);
        $attributes = $this->onlyString($attributes);
        $attributes = $this->removeKeys($attributes, ['label']);

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
    private function removeKeys(array $data, array $keys)
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
    private function onlyString(array $data)
    {
        foreach ($data as $key => $itm) {
            if (!$this->isString($itm)) {
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
    private function onlyNonEmpty(array $data)
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
    private function isString($data)
    {
        if (in_array(gettype($data), ['array', 'object'])) {
            return false;
        }

        return true;
    }

        public static function registerForm()
        {
            return ['form' => new self()];
        }

    /**
     * Call static methods. eg: Form::text('something');.
     *
     * @param string $method: Method name to call
     * @param array  $args:   Arguments array to pass to invocked method call
     *
     * @return method return
     */
    public static function __callStatic($method, $args)
    {
        $instance = new self();

        return call_user_func_array([$instance, $method], $args);
    }
    }
}
