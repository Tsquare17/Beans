<?php
/**
 * @package API\Fields\Types
 */

beans_add_smart_action( 'beans_field_enqueue_scripts_image', 'beans_field_image_assets' );

/**
 * Enqueued assets required by the beans image field.
 *
 * @since 1.0.0
 */
function beans_field_image_assets() {

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'beans-field-media', BEANS_API_COMPONENTS_URL . 'fields/assets/js/media' . BEANS_MIN_CSS . '.js', array( 'jquery' ), BEANS_VERSION );

}


beans_add_smart_action( 'beans_field_image', 'beans_field_image' );

/**
 * Echo image field type.
 *
 * @since 1.0.0
 *
 * @param array $field {
 *      For best practices, pass the array of data obtained using {@see beans_get_fields()}.
 *
 *      @type mixed  $value      The field value.
 *      @type string $name       The field name value.
 *      @type array  $attributes An array of attributes to add to the field. The array key defines the
 *            					 attribute name and the array value defines the attribute value. Default array.
 *      @type mixed  $default    The default value. Default false.
 *      @type string $multiple   Set to true to enable mutliple images (gallery). Default false.
 * }
 */
function beans_field_image( $field ) {

	// Set the images variable and add placeholder to the array.
	$images = array_merge( (array) $field['value'], array( 'placeholder' ) );

	// Is multiple set.
	$multiple = beans_get( 'multiple', $field );

	// Hide beans if it is a single image and an image already exists
	$hide = !$multiple && is_numeric( $field['value'] ) ? 'style="display: none"' : '';

	echo '<a href="#" class="bs-add-image button button-small" ' . $hide . '>';
		echo _n( 'Add Image', 'Add Images', ( $multiple ? 2 : 1 ), 'tm-beans' );
	echo '</a>';

	echo '<input type="hidden" name="' . $field['name'] . '" value="">';

	echo '<div class="bs-images-wrap" data-multiple="' . $multiple . '">';

		foreach ( $images as $id ) {

			// Stop here if the id is false.
			if ( !$id )
				continue;

			$class = '';
			$img = wp_get_attachment_image_src( $id, 'thumbnail' );

			$attributes = array_merge( array(
				'class' => 'image-id',
				'type' => 'hidden',
				'name' => $multiple ? $field['name'] . '[]' : $field['name'], // Return single value if not multiple.
				'value' => $id
			), $field['attributes'] );

			// Set placeholder.
			if ( $id == 'placeholder' ) {

				$class = 'bs-image-template';
				$attributes = array_merge( $attributes, array( 'disabled' => 'disabled', 'value' => false ) );

			}

			echo '<div class="bs-image-wrap ' . $class . '">';

				echo '<input ' . beans_sanatize_attributes( $attributes ) . ' />';

				echo '<img src="' . beans_get( 0, $img ) . '">';

				echo '<div class="bs-toolbar">';

					if ( $multiple )
						echo '<a href="#" class="dashicons dashicons-menu"></a>';

					echo '<a href="#" class="dashicons dashicons-edit"></a>';
					echo '<a href="#" class="dashicons dashicons-post-trash"></a>';

				echo '</div>';

			echo '</div>';

		}

	echo '</div>';

}