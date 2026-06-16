# Troubleshooting

## "Block contains unexpected or invalid content"

Current `save` output doesn't match the stored markup. Causes & fixes:

- **You changed `save`** → add a `deprecated` entry (see `deprecation.md`).
- **Missing `useBlockProps.save()`** → the wrapper class/id differs. Add it.
- **Non-deterministic `save`** (dates, `Math.random`, indexes from external
  state) → make `save` a pure function of attributes.
- **Attribute `source` mismatch** between `edit` and `save` → the selector/
  attribute must read back the same value it wrote.
- **Whitespace/self-closing differences** → match tags exactly.

Use the browser console: the editor logs a diff (`Expected` vs `Actual`)
when validation fails — compare them character by character.

## Block doesn't appear in the inserter

- `register_block_type` not called on `init`, or pointed at the wrong dir
  (must contain `block.json`).
- Build not run / `build/` missing → run `npm run build`.
- `editorScript` handle failed to load → check the browser network/console.
- `parent`/`ancestor`/`allowedBlocks` restricting insertion.

## Styles not applying

- Forgot to spread `useBlockProps()` (editor) or
  `get_block_wrapper_attributes()` (PHP render).
- Wrong style field: `style` (front+editor) vs `editorStyle` (editor only).
- CSS not imported in `index.js` (`import './style.scss'`) so wp-scripts never
  compiled it.

## Build issues (`@wordpress/scripts`)

- `npm run build` outputs to `build/` by default; register from there, not `src`.
- Custom entry points: configure `webpack.config.js` extending
  `@wordpress/scripts/config/webpack.config`.
- JSX without the automatic runtime → ensure you're on a current wp-scripts;
  no manual `import React` needed.
- Multiple blocks: `wp-scripts build` auto-detects `src/*/block.json`.

## Dynamic block renders nothing

- `render.php` returned early on empty data — expected; handle gracefully.
- Output not escaped or attributes not read from `$attributes` array (it's an
  associative array, not an object).
- `save` returns markup instead of `null`, causing stale serialized content to
  show alongside server output.

## Quick diagnostics

```bash
npm run build               # rebuild
npx wp-scripts lint-js src  # lint
```

Then hard-reload the editor (scripts are cached aggressively).
