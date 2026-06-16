<?php
/**
 * Server render for the __NAMESPACE__/__SLUG__ dynamic block.
 *
 * Available variables:
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks markup.
 * @var WP_Block $block      Block instance.
 */

$wrapper_attributes = get_block_wrapper_attributes();
$text               = isset( $attributes['content'] ) ? $attributes['content'] : '';
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<p><?php echo wp_kses_post( $text ); ?></p>
</div>
