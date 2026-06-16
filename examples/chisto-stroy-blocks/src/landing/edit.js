import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	TextareaControl,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';
import metadata from './block.json';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	const field = ( key, label, help ) => (
		<TextControl
			__nextHasNoMarginBottom
			label={ label }
			help={ help }
			value={ attributes[ key ] }
			onChange={ ( value ) => setAttributes( { [ key ]: value } ) }
		/>
	);

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Контакты', 'chisto-stroy' ) }>
					{ field( 'phoneDisplay', __( 'Телефон (как показать)', 'chisto-stroy' ) ) }
					{ field( 'phoneHref', __( 'Телефон для ссылки tel:', 'chisto-stroy' ) ) }
					{ field( 'email', __( 'E-mail', 'chisto-stroy' ) ) }
					{ field( 'hours', __( 'Часы работы', 'chisto-stroy' ) ) }
					{ field( 'region', __( 'Регион', 'chisto-stroy' ) ) }
				</PanelBody>
				<PanelBody title={ __( 'Hero и цена', 'chisto-stroy' ) } initialOpen={ false }>
					<TextareaControl
						__nextHasNoMarginBottom
						label={ __( 'Заголовок hero', 'chisto-stroy' ) }
						value={ attributes.heroTitle }
						onChange={ ( heroTitle ) => setAttributes( { heroTitle } ) }
					/>
					<TextareaControl
						__nextHasNoMarginBottom
						label={ __( 'Подзаголовок hero', 'chisto-stroy' ) }
						value={ attributes.heroLead }
						onChange={ ( heroLead ) => setAttributes( { heroLead } ) }
					/>
					{ field(
						'pricePerM2',
						__( 'Цена от, ₽/м²', 'chisto-stroy' ),
						__( 'Используется в hero и тарифе «Грубая уборка».', 'chisto-stroy' )
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block={ metadata.name }
					attributes={ attributes }
				/>
			</div>
		</>
	);
}
