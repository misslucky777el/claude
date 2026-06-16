---
name: gutenberg-block-authoring
description: >-
  Author, scaffold, and debug custom WordPress Gutenberg blocks. Use when the
  user wants to create a block (static or dynamic), edit block.json metadata,
  write edit/save components, add block supports/attributes, build with
  @wordpress/scripts, register blocks in PHP, or troubleshoot block validation
  ("block contains unexpected or invalid content") and deprecation issues.
---

# Gutenberg Block Authoring

A practical workflow for building modern WordPress Gutenberg blocks using the
block API v3 and `@wordpress/scripts`. Prefer the **block.json-first** approach:
metadata lives in `block.json`, JS/CSS is compiled by `wp-scripts`, and PHP only
registers the block from its build directory.

## When to use this skill

Reach for this skill when the task involves any of:

- Creating a new block (static save-based, or dynamic PHP-rendered).
- Defining or editing `block.json` (attributes, supports, `viewScript`, styles).
- Writing or fixing the `edit` and `save` components.
- Adding block supports (color, typography, spacing, border, layout).
- Setting up or fixing the `@wordpress/scripts` build.
- Registering blocks from a plugin or theme in PHP.
- Debugging block validation / invalid-content errors and `deprecated` versions.

## Decision: static vs. dynamic block

Choose first — it determines whether you write a `save` function or a `render`.

| Use a **static** block when… | Use a **dynamic** block when… |
| --- | --- |
| Output is fixed markup serialized into post content | Output depends on live data (queries, current user, dates, options) |
| No server logic needed at render time | Content must reflect changes after the post was saved |
| Implement `save()` in JS | Implement `render.php` (or `render_callback`) and usually `save: () => null` |

If unsure, prefer **dynamic** for anything data-driven; it avoids validation
errors when markup changes between plugin versions.

## Recommended workflow

1. **Scaffold.** Fastest path for a brand-new plugin:
   ```bash
   npx @wordpress/create-block@latest my-block --variant dynamic
   ```
   To add a block inside an existing project without the full plugin wrapper,
   use the templates in `templates/` (see `scripts/scaffold-block.sh`).

2. **Define metadata** in `block.json` first. This is the single source of
   truth — `name`, `title`, `category`, `attributes`, `supports`, and the
   `editorScript`/`script`/`viewScript`/`style` handles (`file:` paths resolved
   against the build dir). See `references/block-json.md`.

3. **Write `edit`.** A React component rendering the editor UI. Use
   `useBlockProps()` on the wrapper, `InspectorControls` for the sidebar, and
   `RichText`/`InnerBlocks` for content. See `references/block-api.md`.

4. **Write `save` (static) or `render.php` (dynamic).**
   - Static `save` must use `useBlockProps.save()` and produce markup that
     **exactly matches** what the saved attributes imply — mismatches cause
     "block contains unexpected or invalid content".
   - Dynamic blocks set `"render": "file:./render.php"` in `block.json` and
     return `null` (or omit markup) from `save`.

5. **Build & register.**
   ```bash
   npm install
   npm run build      # or: npm start  (watch mode)
   ```
   Register from the **build** directory in PHP:
   ```php
   add_action( 'init', function () {
       register_block_type( __DIR__ . '/build/my-block' );
   } );
   ```

6. **Verify.** Insert the block in the editor, save, reload, and confirm no
   validation warning. For dynamic blocks, view the front end to confirm
   `render.php` output.

## Critical rules (read before editing save logic)

- **Never change a published block's `save` output without a `deprecated`
  entry.** Any change to serialized markup invalidates existing content. Add the
  old version to the `deprecated` array. See `references/deprecation.md`.
- **`useBlockProps` is mandatory** in both `edit` and static `save` for block
  supports (color/spacing/etc.) to apply correctly.
- **Attributes sourced from markup** (`"source": "html"`, `"attribute"`, etc.)
  must be read identically in `save` and the editor, or content won't round-trip.
- **Block name must be namespaced**: `namespace/block-name`, lowercase, hyphens.
- Bump `"apiVersion": 3` for current behavior (iframe-aware editor canvas).

## Reference files

Load these as needed — don't read them all up front:

- `references/block-json.md` — full `block.json` field reference and examples.
- `references/block-api.md` — `edit`/`save`, `useBlockProps`, common components.
- `references/dynamic-blocks.md` — `render.php`, `$attributes`/`$content`, server data.
- `references/supports.md` — block supports (color, typography, spacing, border).
- `references/deprecation.md` — handling `save` changes and `deprecated` versions.
- `references/troubleshooting.md` — validation errors, build issues, common fixes.

## Templates

`templates/` holds copy-ready starter files: `block.json`, `index.js`,
`edit.js`, `save.js`, `editor.scss`, `style.scss`, and `render.php`. Run
`scripts/scaffold-block.sh <namespace> <block-name> [static|dynamic]` to drop a
ready-to-build block into `src/<block-name>/`.
