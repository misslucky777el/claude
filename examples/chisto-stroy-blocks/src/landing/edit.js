import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	Placeholder,
	PanelBody,
	TextControl,
	TextareaControl,
} from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: 'cs-landing-editor-preview',
	} );

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
				<Placeholder
					icon="buildings"
					label={ __( 'ЧИСТО.СТРОЙ — Лендинг', 'chisto-stroy' ) }
					instructions={ __(
						'Готовая секция-лендинг. Полный вид виден на опубликованной странице (предпросмотр). Тексты редактируются на панели справа.',
						'chisto-stroy'
					) }
				>
					<ul className="cs-landing-summary">
						<li>📞 { attributes.phoneDisplay } · { attributes.hours }</li>
						<li>✉️ { attributes.email }</li>
						<li>📍 { attributes.region }</li>
						<li>🏷️ { __( 'Цена от', 'chisto-stroy' ) } { attributes.pricePerM2 } ₽/м²</li>
						<li>🧱 { attributes.heroTitle }</li>
					</ul>
				</Placeholder>
			</div>
		</>
	);
}
