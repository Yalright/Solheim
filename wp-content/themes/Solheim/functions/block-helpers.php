<?php
/**
 * Shared helpers for block rendering (e.g. first block in page content).
 */
if (!defined('ABSPATH')) {
    exit;
}

/**
 * First block name in document order, drilling into common layout wrappers.
 *
 * @param array<int, array<string, mixed>> $blocks Parsed blocks from parse_blocks().
 * @return string|null Block name e.g. acf/faqs, or null if none found.
 */
function solheim_first_meaningful_block_name(array $blocks)
{
    $wrappers = array('core/group', 'core/columns', 'core/column', 'core/block');

    foreach ($blocks as $block) {
        $name = isset($block['blockName']) ? (string) $block['blockName'] : '';

        if ($name !== '' && in_array($name, $wrappers, true)) {
            if (! empty($block['innerBlocks']) && is_array($block['innerBlocks'])) {
                $inner = solheim_first_meaningful_block_name($block['innerBlocks']);
                if ($inner !== null) {
                    return $inner;
                }
            }
            continue;
        }

        if ($name !== '') {
            return $name;
        }

        if (! empty($block['innerBlocks']) && is_array($block['innerBlocks'])) {
            $inner = solheim_first_meaningful_block_name($block['innerBlocks']);
            if ($inner !== null) {
                return $inner;
            }
        }
    }

    return null;
}

/**
 * True when this page’s first meaningful block is the FAQs ACF block (desktop extra top padding).
 */
function solheim_is_acf_faqs_first_block_after_header()
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    if (! is_singular('page')) {
        return $cached = false;
    }

    $post = get_post();
    if (! $post instanceof WP_Post) {
        return $cached = false;
    }

    $blocks = parse_blocks((string) $post->post_content);
    $first  = solheim_first_meaningful_block_name($blocks);

    return $cached = ($first === 'acf/faqs');
}
