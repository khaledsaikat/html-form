<?php
namespace UserMeta\Html;

/*
 * Default config for the package.
 *
 * @author Khaled Hossain
 * @since 1.2
 */
trait Config
{

    protected $attributesConfig = [
        'value' => [
            '_escape_function' => 'esc_attr'
        ],
        'id' => [
            '_escape_function' => 'esc_attr'
        ],
        'class' => [
            '_escape_function' => 'esc_attr'
        ],
        'src' => [
            '_escape_function' => 'esc_url'
        ],
        'href' => [
            '_escape_function' => 'esc_url'
        ]
    ];
}