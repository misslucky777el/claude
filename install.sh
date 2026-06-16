#!/usr/bin/env bash
#
# Install the Gutenberg Block Authoring skill into your Claude skills directory.
#
# Usage:
#   ./install.sh            # install to ~/.claude/skills (or $CLAUDE_SKILLS_DIR)
#   ./install.sh --uninstall
#
set -euo pipefail

SKILL_NAME="gutenberg-block-authoring"
SRC_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SKILLS_ROOT="${CLAUDE_SKILLS_DIR:-$HOME/.claude/skills}"
DEST_DIR="$SKILLS_ROOT/$SKILL_NAME"

info()  { printf '\033[0;34m==>\033[0m %s\n' "$*"; }
ok()    { printf '\033[0;32m✓\033[0m %s\n' "$*"; }
die()   { printf '\033[0;31mError:\033[0m %s\n' "$*" >&2; exit 1; }

if [[ "${1:-}" == "--uninstall" ]]; then
  if [[ -d "$DEST_DIR" ]]; then
    rm -rf "$DEST_DIR"
    ok "Removed $DEST_DIR"
  else
    info "Nothing to uninstall at $DEST_DIR"
  fi
  exit 0
fi

[[ -f "$SRC_DIR/SKILL.md" ]] || die "SKILL.md not found in $SRC_DIR"

info "Installing '$SKILL_NAME' to $DEST_DIR"
mkdir -p "$DEST_DIR"

# Copy the skill payload (SKILL.md + supporting directories), excluding repo
# scaffolding that the skill runtime does not need.
cp "$SRC_DIR/SKILL.md" "$DEST_DIR/"
for dir in references templates scripts; do
  if [[ -d "$SRC_DIR/$dir" ]]; then
    rm -rf "${DEST_DIR:?}/$dir"
    cp -R "$SRC_DIR/$dir" "$DEST_DIR/$dir"
  fi
done

# Keep helper scripts executable.
if [[ -d "$DEST_DIR/scripts" ]]; then
  find "$DEST_DIR/scripts" -name '*.sh' -exec chmod +x {} +
fi

ok "Installed. Restart Claude Code or reload skills to pick it up."
info "Skill location: $DEST_DIR"
