import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { content } = attributes;
	const blockProps = useBlockProps.save();

	return (
		<div { ...blockProps }>
			<RichText.Content tagName="p" value={ content } />
		</div>
	);
}
