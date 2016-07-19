<?php

namespace UserMeta\Html;

/*
 * trait for html elements
 *
 * @author Khaled Hossain
 */
trait Element
{
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
        return $this->element('label', $default, $attributes);
    }
    
    /**
     * Generate div.
     *
     * @param string $default:    Text for div
     * @param array  $attributes: (optional)
     *
     * @return string : html div
     */
    protected function div($default = null, array $attributes = [])
    {
        return $this->element('div', $default, $attributes);
    }
    
    /**
     * Generate paragraph.
     *
     * @param string $default:    Text for p
     * @param array  $attributes: (optional)
     *
     * @return string : html for p tag
     */
    protected function p($default = null, array $attributes = [])
    {
        return $this->element('p', $default, $attributes);
    }
    
    /**
     * Generate html element.
     *
     * @param string $type:       Element type, e.g. p, div, label
     * @param string $default:    Text for element
     * @param array  $attributes: (optional)
     *
     * @return string : html
     */
    protected function element($type, $default = null, array $attributes = [])
    {
        $this->setProperties($type, $default, $attributes);
    
        return "<{$type}{$this->attributes()}>$default</$type>";
    }
    
}