<?php
/**
 * Bibliothèque de pictogrammes SVG inline.
 * stroke = currentColor → recolorables en CSS.
 */

defined('ABSPATH') || exit;

function konoba_icon($name, $class = 'konoba-picto')
{
    $paths = array(
        'cards'    => '<rect x="3" y="6" width="11" height="15" rx="2"/><path d="M8 6V4.5A1.5 1.5 0 0 1 9.5 3h9A1.5 1.5 0 0 1 20 4.5V16"/><path d="M8.5 13.5h1"/>',
        'star'     => '<path d="M12 3l2.6 5.6 6 .8-4.4 4.2 1.1 6-5.3-2.9-5.3 2.9 1.1-6L3.4 9.4l6-.8z"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
        'box'      => '<path d="M3 7l9-4 9 4-9 4-9-4z"/><path d="M3 7v10l9 4 9-4V7"/><path d="M12 11v10"/>',
        'user'     => '<circle cx="12" cy="8" r="3.6"/><path d="M5 20a7 7 0 0 1 14 0"/>',
        'globe'    => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/>',
        'lock'     => '<rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>',
        'cart'     => '<circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/><path d="M3 4h2l2.2 11h11l1.8-8H6"/>',
        'tag'      => '<path d="M3 12l8-8h8v8l-8 8-8-8z"/><circle cx="15.5" cy="8.5" r="1.3"/>',
        'gift'     => '<rect x="3" y="9" width="18" height="4" rx="1"/><path d="M5 13v7a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-7M12 9v12"/><path d="M12 9S10.5 3 8 3a2 2 0 0 0 0 4M12 9s1.5-6 4-6a2 2 0 0 1 0 4"/>',
        'mail'     => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
        'headset'  => '<path d="M4 13v-1a8 8 0 0 1 16 0v1"/><rect x="3" y="13" width="4" height="6" rx="1.2"/><rect x="17" y="13" width="4" height="6" rx="1.2"/><path d="M20 19a4 4 0 0 1-4 3h-2"/>',
        'info'     => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/>',
        'check'    => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'shop'     => '<path d="M4 9l1-4h14l1 4"/><path d="M4 9a2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0"/><path d="M5 11v9h14v-9"/><path d="M9 20v-5h4v5"/>',
    );

    if (empty($paths[$name])) {
        return '';
    }

    return sprintf(
        '<svg class="%s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
        esc_attr($class),
        $paths[$name]
    );
}
