import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { content } = attributes;
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<RichText
				tagName="p"
				value={ content }
				onChange={ ( content ) => setAttributes( { content } ) }
				placeholder={ __( 'Write something…', '__NAMESPACE__' ) }
			/>
		</div>
	);
}
