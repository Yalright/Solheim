<?php
add_filter('allowed_block_types_all', 'rt_allowed_block_types', 25, 2);

function rt_allowed_block_types($allowed_blocks, $editor_context)
{
    $page_blocks = array(
        'acf/accordion',
        'acf/accommodation',
        'acf/cards-overlayed',
        'acf/cards-standard',
        'acf/contact-form',
        'acf/cta-ticker',
        'acf/faqs',
        'acf/hero',
        'acf/hero-cta',
        'acf/hero-team',
        'acf/hero-testimonial',
        'acf/hero-video',
        'acf/how-to-qualify',
        'acf/image-content',
        'acf/image-slider',
        'acf/latest-news',
        'acf/links-cards',
        'acf/logo-bar',
        'acf/meet-the-team',
        'acf/newsletter',
        'acf/promo-text-card',
        'acf/promo-text-content',
        'acf/promotional-image-text',
        'acf/rolex-banner',
        'acf/team-promo',
        'acf/travel-map',
        'acf/vertical-cards',
        'acf/wysiwyg',
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/image',
    );

    $post_blocks = array(
        'acf/article-image-content',
        'acf/article-wysiwyg',
        'acf/wysiwyg',
        'acf/latest-news',
        'core/paragraph',
        'core/heading',
        'core/list',
        'core/image',
    );

    if (! empty($editor_context->post)) {
        if ($editor_context->post->post_type === 'page') {
            return $page_blocks;
        }
        if ($editor_context->post->post_type === 'post') {
            return $post_blocks;
        }
    }

    return $allowed_blocks;
}
