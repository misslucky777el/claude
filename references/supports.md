# Block supports

`supports` opts a block into editor UI and generates the corresponding classes
and inline styles automatically — no custom controls needed. Declared in
`block.json` under `"supports"`. For styles to apply you **must** spread
`useBlockProps()` (JS) / `get_block_wrapper_attributes()` (PHP).

## Common supports

```json
"supports": {
  "html": false,
  "anchor": true,
  "align": ["wide", "full"],
  "className": true,
  "color": {
    "background": true,
    "text": true,
    "gradients": true,
    "link": true
  },
  "typography": {
    "fontSize": true,
    "lineHeight": true,
    "__experimentalFontFamily": true
  },
  "spacing": {
    "margin": true,
    "padding": true,
    "blockGap": true
  },
  "border": {
    "color": true,
    "radius": true,
    "style": true,
    "width": true
  },
  "dimensions": {
    "minHeight": true
  },
  "layout": true,
  "shadow": true
}
```

## Notes

- `html: false` disables the "Edit as HTML" option — recommended for most
  blocks to prevent users producing invalid markup.
- `anchor: true` adds an HTML-id field (lets blocks be link targets).
- `align` accepts an array of allowed alignments or `true` for all.
- Color/typography/spacing supports require theme.json opt-in for the picker
  presets, but core controls work regardless.
- Supports values are stored as attributes (e.g. `style`, `backgroundColor`)
  automatically — don't redeclare them in `attributes`.
- Prefixes like `__experimental*` change across WP versions; check the version
  you target.

## Default presets via block.json

Set initial appearance with top-level `"style"` attribute defaults or via
`theme.json` `styles.blocks["acme/notice"]`. Prefer `theme.json` for
theme-level styling so users can override.
