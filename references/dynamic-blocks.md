# Dynamic blocks (server-rendered)

Dynamic blocks generate front-end markup in PHP at render time. Use them when
output depends on data that can change after the post is saved.

## block.json

```json
{
  "name": "acme/latest-posts",
  "apiVersion": 3,
  "render": "file:./render.php",
  "attributes": {
    "count": { "type": "number", "default": 5 }
  }
}
```

## save.js

Return `null` so nothing is serialized into post content (markup comes from PHP).
Keep the block delimiter + attributes only.

```js
export default function save() {
    return null;
}
```

If the block has inner blocks, return `<InnerBlocks.Content />` instead of `null`
so children still serialize.

## render.php

The render template receives three variables in scope:

| Variable | Contents |
| --- | --- |
| `$attributes` | Associative array of the block's attributes |
| `$content` | Inner blocks' serialized HTML (string) |
| `$block` | The `WP_Block` instance (context, etc.) |

```php
<?php
$count = isset( $attributes['count'] ) ? (int) $attributes['count'] : 5;

$posts = get_posts( array( 'numberposts' => $count ) );
if ( empty( $posts ) ) {
    return;
}

$wrapper = get_block_wrapper_attributes(); // applies supports classes/styles
?>
<ul <?php echo $wrapper; ?>>
    <?php foreach ( $posts as $post ) : ?>
        <li>
            <a href="<?php echo esc_url( get_permalink( $post ) ); ?>">
                <?php echo esc_html( get_the_title( $post ) ); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
```

## Rules

- Use `get_block_wrapper_attributes()` for the wrapper so block supports
  (color, spacing, etc.) apply — it's the PHP equivalent of `useBlockProps`.
- **Always escape output**: `esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`.
- Returning empty output (or `return;`) renders nothing — handle empty data.
- Alternative to `render` file: pass `render_callback` to `register_block_type`
  in PHP. The `render` field in `block.json` is preferred and self-documenting.
- Add `viewScript` for front-end interactivity, or use the Interactivity API
  (`@wordpress/interactivity`) with `viewScriptModule`.
