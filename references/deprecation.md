# Deprecation: changing a block's `save` output safely

When a block's serialized markup changes, every previously saved instance
becomes "invalid" because the stored HTML no longer matches what the current
`save` produces. The `deprecated` array tells WordPress how to recognize and
migrate old markup.

## Symptom

> This block contains unexpected or invalid content.

Editor offers "Attempt Block Recovery", "Convert to HTML", or "Convert to
Classic". This means current `save` ≠ stored markup.

## Fix: add a deprecation

Each entry describes a previous version. WordPress tries them in order until one
parses the old markup, then migrates to the current shape.

```js
// deprecated.js
import { useBlockProps, RichText } from '@wordpress/block-editor';

const v1 = {
    attributes: {
        content: { type: 'string', source: 'html', selector: 'p' },
        // old shape, e.g. a boolean that's now a string `level`
        isWarning: { type: 'boolean', default: false },
    },
    save( { attributes } ) {
        // EXACT old markup
        return (
            <div className={ attributes.isWarning ? 'is-warning' : 'is-info' }>
                <RichText.Content tagName="p" value={ attributes.content } />
            </div>
        );
    },
    migrate( attributes ) {
        const { isWarning, ...rest } = attributes;
        return { ...rest, level: isWarning ? 'warning' : 'info' };
    },
    // optional: only apply this deprecation when this matches
    isEligible( attributes ) {
        return attributes.isWarning !== undefined;
    },
};

export default [ v1 ];
```

Wire it up in `index.js`:

```js
import deprecated from './deprecated';
registerBlockType( metadata.name, { edit, save, deprecated } );
```

## Rules of thumb

- **Order matters**: newest deprecation first; WP tries each until one validates.
- A deprecation's `save` + `attributes` + `supports` must reproduce the OLD
  serialized markup exactly.
- Use `migrate` to map old attributes/inner blocks to the new shape.
- Use `isEligible` when markup alone is ambiguous.
- Never edit a shipped `save` without a matching deprecation — you'll break
  existing posts.
- Dynamic blocks (markup from PHP) rarely need deprecations, since post content
  stores no markup — another reason to prefer dynamic for evolving output.
