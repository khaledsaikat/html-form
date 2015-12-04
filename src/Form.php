<?php

namespace UserMeta\Html;

/*
 * Class for html form builder.
 *
 * @author Khaled Hossain
 */
class Form extends OptionsElement
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
     * Creating input.
     *
     * @return string html
     */
    private function _createInput()
    {
        $html = $this->addLabel();

        $html .= '<input'.$this->attributes().'/>';

        return $html;
    }

    /**
     * Adding label to element.
     *
     * @return string html
     */
    protected function addLabel()
    {
        if (isset($this->attributes['label'])) {
            $for = '';

            if (isset($this->attributes['id']) && !in_array($this->type, ['radio', 'checkboxList'])) {
                $for = " for=\"{$this->attributes['id']}\"";
            }

            return "<label$for>{$this->attributes['label']}</label>";
        }

        return '';
    }

    /**
     * Set class properties.
     *
     * @param string $type:      Input type attribute
     * @param string $default:   Default value attribute
     * @param array  $attributes
     * @param array  $options
     */
    protected function setProperties($type, $default, array $attributes, array $options = [])
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
     * @return string: Attributes string
     */
    protected function attributes()
    {
        $attributes = $this->_getRefinedAttributes();

        return $this->toString($attributes);
    }

    /**
     * Apply refinement to $this->attributes and get refined $attributes.
     *
     * @return array: $attributes
     */
    protected function _getRefinedAttributes()
    {
        $attributes = $this->attributes;
        $attributes = $this->onlyNonEmpty($attributes);
        $attributes = $this->onlyString($attributes);
        $attributes = $this->removeKeys($attributes, ['label', 'option_before', 'option_after']);

        if (!empty($attributes['value'])) {
            $attributes['value'] = $this->filter($attributes['value']);
        }

        return $attributes;
    }

    /**
     * Convert associative array to string.
     *
     * @param array: $attributes
     *
     * @return string: Attributes string
     */
    protected function toString(array $attributes)
    {
        $string = '';

        foreach ($attributes as $key => $val) {
            if ($this->isString($val)) {
                $string .= " $key=\"$val\"";
            }
        }

        return $string;
    }

    /**
     * Apply esc_attr/htmlspecialchars to both input string and array.
     *
     * @param array: $attributes
     *
     * @return mixed: htmlspecialchars filtered data
     */
    protected function filter($data)
    {
        if (is_array($data)) {
            return array_map('\\esc_attr', $data);
        } elseif (is_string($data)) {
            return \esc_attr($data);
        }

        return $data;
    }

    /**
     * Add elements to array for given keys.
     *
     * @todo
     *
     * @param array $data: Given array
     * @param array $keys: Given keys to remove
     *
     * @return array
     */
    protected function addKeys(array $data, array $keys, $default = '')
    {
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $data[$key] = $default;
            }
        }

        return $data;
    }

    /**
     * Remove elements from array for given keys.
     *
     * @param array $data: Given array
     * @param array $keys: Given keys to remove
     *
     * @return array
     */
    protected function removeKeys(array $data, array $keys)
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
    protected function onlyString(array $data)
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
    protected function onlyNonEmpty(array $data)
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
    protected function isString($data)
    {
        if (in_array(gettype($data), ['array', 'object'])) {
            return false;
        }

        return true;
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

        try {
            return call_user_func_array([$instance, $method], $args);
        } catch (Exception $e) {
            echo 'Exception: ',  $e->getMessage(), "\n";
        }
    }
}
