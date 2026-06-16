# Edit / Save and the block API

A block module registers itself by calling `registerBlockType` with the
`block.json` metadata and the runtime functions (`edit`, `save`).

## `index.js` (registration entry)

```js
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import Edit from './edit';
import save from './save';
import './style.scss';   // front end + editor
import './editor.scss';  // editor only

registerBlockType( metadata.name, { edit: Edit, save } );
```

## `edit.js`

```js
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
    const { content, level } = attributes;
    const blockProps = useBlockProps( { className: `is-${ level }` } );

    return (
        <>
            <InspectorControls>
                <PanelBody title={ __( 'Settings', 'acme' ) }>
                    <SelectControl
                        label={ __( 'Level', 'acme' ) }
                        value={ level }
                        options={ [
                            { label: 'Info', value: 'info' },
                            { label: 'Warning', value: 'warning' },
                        ] }
                        onChange={ ( level ) => setAttributes( { level } ) }
                    />
                </PanelBody>
            </InspectorControls>

            <div { ...blockProps }>
                <RichText
                    tagName="p"
                    value={ content }
                    onChange={ ( content ) => setAttributes( { content } ) }
                    placeholder={ __( 'Write a notice…', 'acme' ) }
                />
            </div>
        </>
    );
}
```

## `save.js` (static blocks)

```js
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
    const { content, level } = attributes;
    const blockProps = useBlockProps.save( { className: `is-${ level }` } );
    return (
        <div { ...blockProps }>
            <RichText.Content tagName="p" value={ content } />
        </div>
    );
}
```

`save` runs at serialization time only — it has **no state, no hooks, no event
handlers**. Output must be a pure function of `attributes`.

## Essential APIs

- `useBlockProps()` / `useBlockProps.save()` — applies the block wrapper class,
  id, and styles from supports. Always spread onto the outermost element.
- `RichText` / `RichText.Content` — editable text; pair the editor and save
  components so HTML round-trips.
- `InnerBlocks` / `useInnerBlocksProps` — nest child blocks; in `save` use
  `<InnerBlocks.Content />`.
- `InspectorControls` — sidebar panel. `BlockControls` — the floating toolbar.
- `@wordpress/components` — `PanelBody`, `ToggleControl`, `RangeControl`,
  `ColorPalette`, `Button`, etc.
- `@wordpress/data` (`useSelect`, `useDispatch`) — read/write editor stores.

## Common pitfalls

- Forgetting `useBlockProps` → supports styles/classes don't render and
  validation can fail.
- Adding non-deterministic content to `save` (dates, random ids) → invalid block.
- Spreading `blockProps` on an inner element instead of the wrapper.
