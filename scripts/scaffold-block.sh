#!/usr/bin/env bash
#
# Scaffold a new Gutenberg block from the skill templates.
#
# Usage:
#   scaffold-block.sh <namespace> <slug> [static|dynamic] [target-src-dir]
#
# Examples:
#   scaffold-block.sh acme notice
#   scaffold-block.sh acme latest-posts dynamic ./src
#
set -euo pipefail

NAMESPACE="${1:-}"
SLUG="${2:-}"
KIND="${3:-static}"
SRC_ROOT="${4:-./src}"

usage() {
  echo "Usage: $0 <namespace> <slug> [static|dynamic] [target-src-dir]" >&2
  exit 1
}

[[ -n "$NAMESPACE" && -n "$SLUG" ]] || usage
[[ "$KIND" == "static" || "$KIND" == "dynamic" ]] || { echo "kind must be 'static' or 'dynamic'" >&2; exit 1; }

# Validate naming (lowercase, digits, hyphens).
for v in "$NAMESPACE" "$SLUG"; do
  [[ "$v" =~ ^[a-z][a-z0-9-]*$ ]] || { echo "Invalid name '$v' (use lowercase, digits, hyphens)." >&2; exit 1; }
done

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
TPL_DIR="$SCRIPT_DIR/../templates"
[[ -d "$TPL_DIR" ]] || { echo "Templates not found at $TPL_DIR" >&2; exit 1; }

DEST="$SRC_ROOT/$SLUG"
[[ -e "$DEST" ]] && { echo "Refusing to overwrite existing $DEST" >&2; exit 1; }
mkdir -p "$DEST"

# Title-case the slug for the human-readable title (notice -> Notice, my-box -> My Box).
TITLE="$(echo "$SLUG" | tr '-' ' ' | awk '{ for (i=1;i<=NF;i++) $i=toupper(substr($i,1,1)) substr($i,2) }1')"

render() { # render <template> <dest>
  sed -e "s/__NAMESPACE__/$NAMESPACE/g" \
      -e "s/__SLUG__/$SLUG/g" \
      -e "s/__TITLE__/$TITLE/g" \
      "$1" > "$2"
}

render "$TPL_DIR/block.json"   "$DEST/block.json"
render "$TPL_DIR/index.js"     "$DEST/index.js"
render "$TPL_DIR/edit.js"      "$DEST/edit.js"
render "$TPL_DIR/editor.scss"  "$DEST/editor.scss"
render "$TPL_DIR/style.scss"   "$DEST/style.scss"

if [[ "$KIND" == "dynamic" ]]; then
  render "$TPL_DIR/render.php" "$DEST/render.php"
  # save returns null for dynamic blocks
  cat > "$DEST/save.js" <<'EOF'
export default function save() {
	return null;
}
EOF
  # Point block.json at render.php
  sed -i.bak 's#"editorScript": "file:./index.js",#"render": "file:./render.php",\n  "editorScript": "file:./index.js",#' "$DEST/block.json"
  rm -f "$DEST/block.json.bak"
else
  render "$TPL_DIR/save.js"    "$DEST/save.js"
fi

echo "Scaffolded $KIND block '$NAMESPACE/$SLUG' at $DEST"
echo "Next: npm install && npm run build, then register_block_type( __DIR__ . '/build/$SLUG' );"
