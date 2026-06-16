# Gutenberg Block Authoring Skill

A [Claude Code](https://code.claude.com) skill that helps you author, scaffold,
and debug custom **WordPress Gutenberg blocks** — static and dynamic — using the
block API v3 and `@wordpress/scripts`.

## What it does

When installed, Claude gains a focused playbook for block development:

- Deciding between **static** (`save`) and **dynamic** (`render.php`) blocks.
- Writing `block.json`, `edit`/`save`, and PHP registration correctly.
- Adding **block supports** (color, typography, spacing, border).
- Handling the dreaded *"block contains unexpected or invalid content"* error
  with proper `deprecated` versions.
- Copy-ready **templates** and a **scaffold script** for new blocks.

## Install

```bash
git clone https://github.com/misslucky777el/claude.git gutenberg-block-authoring-skill
cd gutenberg-block-authoring-skill
./install.sh
```

This copies the skill into `~/.claude/skills/gutenberg-block-authoring/`
(override with `CLAUDE_SKILLS_DIR`). Restart Claude Code to pick it up.

Uninstall with `./install.sh --uninstall`.

## Scaffold a block

```bash
# static block
scripts/scaffold-block.sh acme notice

# dynamic (server-rendered) block
scripts/scaffold-block.sh acme latest-posts dynamic ./src
```

Then build and register:

```bash
npm install && npm run build
```

```php
add_action( 'init', function () {
    register_block_type( __DIR__ . '/build/notice' );
} );
```

## Layout

```
SKILL.md                  Skill instructions (frontmatter + workflow)
install.sh                Installer / uninstaller
references/               Deep-dive docs loaded on demand
  block-json.md           block.json field reference
  block-api.md            edit/save, useBlockProps, components
  dynamic-blocks.md       render.php and server data
  supports.md             block supports
  deprecation.md          safely changing save output
  troubleshooting.md      validation/build/style fixes
templates/                Copy-ready starter files
scripts/scaffold-block.sh Generate a new block from templates
```

## License

MIT — see [LICENSE](LICENSE).
