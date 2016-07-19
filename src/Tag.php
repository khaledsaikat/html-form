<?php
namespace UserMeta\Html;

/*
 * trait for html elements
 *
 * @author Khaled Hossain
 * @since 1.0.0
 */
trait Tag
{

    /**
     * Generate html tag.
     * Alies of tag
     */
    protected function element($type, $default = null, array $attributes = [])
    {
        return $this->tag($type, $default, $attributes);
    }

    /**
     * Generate html tag.
     *
     * @param string $type:
     *            tag type, e.g. p, div, label
     * @param string $default:
     *            Text for element
     * @param array $attributes:
     *            (optional)
     *            
     * @return string : html
     */
    protected function tag($type, $default = null, array $attributes = [])
    {
        $this->setProperties($type, $default, $attributes);
        
        return "<{$type}{$this->attributes()}>$default</$type>";
    }
}