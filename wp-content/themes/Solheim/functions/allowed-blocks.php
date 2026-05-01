<?php
add_filter('allowed_block_types_all', 'rt_allowed_block_types', 25, 2);

function rt_allowed_block_types($allowed_blocks, $editor_context)
{
    // Array for pages
    $page_blocks = array(
        'acf/image-content-selector',
        'acf/socials',
        'acf/timeline',
        'acf/quote-slider',
        'acf/map',
        'acf/contact-row',
        'acf/cta',
        'acf/overview-text',
        'acf/text-columns',
        'acf/image',
        'acf/related-pages',
        'acf/accordion',
        'acf/tabs',
        'acf/content-grid',
        'acf/latest-news',
        'acf/card-slider',
        'acf/newsletter',
        'acf/quote-block',
        'acf/quote-image-block',
        'acf/small-text-frame',
        'acf/image-content',
        'acf/title',
        'acf/text-overlay-banner',
        'acf/hero',
        'acf/offset-content',
        'acf/offset-media-content',
        'acf/magazine-coming-soon',
        'acf/directory-slider',
        'acf/shop-coming-soon',
        'acf/events-coming-soon',
        'acf/content-bar',
        'acf/image-testimonial-slider',
        'acf/hero-cta',
        'acf/hero-featured-post',
        'acf/image-featured',
        'acf/quote-content',
        'acf/statistics',
        'acf/image-overlay-content',
        'acf/image-slider',
        'acf/content-socials',
        'acf/wysiwyg',
        'core/paragraph',
        'acf/vacancies',
        'acf/gallery'
    );

    // Array for posts (currently same as pages - ready for customization)
    $post_blocks = array(
        'acf/post-wysiwyg',
        'acf/post-image',
        'acf/post-image-wysiwyg',
        'acf/magazine-hero',
        'acf/hero-featured-post'
    );

    // Return appropriate blocks based on post type
    if ($editor_context->post->post_type === 'page') {
        return $page_blocks;
    } elseif ($editor_context->post->post_type === 'post') {
        return $post_blocks;
    }

    return $allowed_blocks;
}
