<?php

/**
 * WordPress function for non WP users.
 * @author Khaled Hossain
 * @since 1.0.0
 */
if (! function_exists('esc_attr')) {

    /**
     * Declare esc_attr if the project is used outside WordPress.
     * WordPress uses esc_attr for escaping attributes. The function contains rich set of filters.
     *
     * @param
     *            string : $text
     *            
     * @return string
     */
    function esc_attr($text)
    {
        return htmlspecialchars($text);
    }
}
