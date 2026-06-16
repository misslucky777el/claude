# `block.json` reference

`block.json` is the canonical metadata for a block. WordPress reads it on both
the server (`register_block_type`) and the client. Paths prefixed with `file:`
are resolved relative to the `block.json` location (typically the build dir).

## Minimal example (static)

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "acme/notice",
  "version": "1.0.0",
  "title": "Notice",
  "category": "widgets",
  "icon": "info",
  "description": "A styled notice box.",
  "keywords": ["alert", "message"],
  "textdomain": "acme",
  "attributes": {
    "content": { "type": "string", "source": "html", "selector": "p" },
    "level":   { "type": "string", "default": "info" }
  },
  "supports": {
    "html": false,
    "color": { "background": true, "text": true },
    "spacing": { "padding": true }
  },
  "editorScript": "file:./index.js",
  "editorStyle": "file:./index.css",
  "style": "file:./style-index.css"
}
```

## Dynamic block additions

```json
{
  "name": "acme/latest-posts",
  "render": "file:./render.php",
  "viewScript": "file:./view.js"
}
```

For dynamic blocks the front-end markup comes from `render.php`; `save` usually
returns `null`.

## Key fields

| Field | Purpose |
| --- | --- |
| `apiVersion` | Use `3`. Enables the iframed editor canvas. |
| `name` | `namespace/slug`, lowercase + hyphens. Globally unique. |
| `category` | `text`, `media`, `design`, `widgets`, `theme`, `embed`, or a custom one. |
| `attributes` | Typed data model. See sourcing below. |
| `supports` | Opt into editor features (see `supports.md`). |
| `editorScript` | JS loaded only in the editor (registers the block). |
| `script` | JS loaded in editor **and** front end. |
| `viewScript` | JS loaded only on the front end (interactivity). |
| `style` / `editorStyle` | CSS for front end / editor. |
| `render` | `file:` path to the PHP render template (dynamic blocks). |
| `parent` / `ancestor` | Restrict where the block can be inserted. |
| `providesContext` / `usesContext` | Pass data to inner blocks. |

## Attribute sourcing

Attributes can be stored as serialized comment data (default) or parsed from
markup. The `save`/`edit` markup must match the source declaration.

```json
"attributes": {
  "url":     { "type": "string", "source": "attribute", "selector": "img", "attribute": "src" },
  "alt":     { "type": "string", "source": "attribute", "selector": "img", "attribute": "alt", "default": "" },
  "caption": { "type": "string", "source": "html", "selector": "figcaption" },
  "items":   { "type": "array",  "source": "query", "selector": "li",
               "query": { "text": { "type": "string", "source": "html" } } }
}
```

- **No `source`** → stored in the block's HTML comment delimiter (most robust;
  survives markup changes).
- **`source: "html"|"attribute"|"text"|"query"`** → parsed from saved markup;
  must round-trip exactly or the block becomes invalid.

## Registration

```php
add_action( 'init', function () {
    register_block_type( __DIR__ . '/build/notice' ); // dir containing block.json
} );
```

Passing the directory lets WordPress auto-enqueue the scripts/styles named in
`block.json`. Do not manually enqueue those handles.
