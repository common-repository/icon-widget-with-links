<?php
/**
 * This file is used to markup the administration form of the widget.
 *
 * @package Icon_widget
 */

?>

<div class="wrapper">

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
			<?php esc_html_e( 'Title:', 'icon-widget' ); ?>
        </label>
        <br/>
        <input type="text" class='widefat'
               id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
               name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
               value="<?php echo esc_attr( $title ); ?>">
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'link_to' ) ); ?>">
			<?php esc_html_e( 'Link to:', 'icon-widget' ); ?>
        </label>
        <br/>
        <input type="text" class='widefat'
               id="<?php echo esc_attr( $this->get_field_id( 'link_to' ) ); ?>"
               name="<?php echo esc_attr( $this->get_field_name( 'link_to' ) ); ?>"
               value="<?php echo esc_attr( $link_to ); ?>"
               placeholder="http://">
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>">
			<?php esc_html_e( 'Content:', 'icon-widget' ); ?>
        </label>
        <br/>
        <textarea class='widefat' rows="4"
                  id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>"
                  value="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>"><?php echo esc_textarea( $content ); ?></textarea>
    </p>

	<?php

	$settings = get_option( 'icon_widget_settings' );
	$font     = $settings['font'];

	// Load the array of icon glyphs.
	include( plugin_dir_path( __DIR__ ) . 'includes/' . $font . '.php' );

	?>

    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#widgets-right .select-picker').selectpicker({
                iconBase: 'fa',
                dropupAuto: false
            });
        });
    </script>
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>">
			<?php esc_html_e( 'Icon:', 'icon-widget' ); ?>
        </label>
        <br/>
        <select class='select-picker widefat'
                id="<?php echo esc_attr( $this->get_field_id( 'icon' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'icon' ) ); ?>"
                data-live-search="true">

			<?php foreach ( $icons as $icon ) : ?>

                <option data-icon='<?php echo esc_attr( $icon ); ?>'
                        value="<?php echo esc_attr( $icon ); ?>" <?php echo ( $instance['icon'] === $icon ) ? 'selected' : ''; ?>><?php echo esc_html( str_replace( array(
						'-',
						'fa ',
						'ion '
					), array( ' ', '', '' ), $icon ) ); ?></option>

			<?php endforeach; ?>

        </select>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>">
			<?php esc_html_e( 'Size:', 'icon-widget' ); ?>
        </label>
        <br/>
        <select class='widefat'
                id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>"
                type="text">

            <option value='lg' <?php echo ( 'lg' === $size ) ? 'selected' : ''; ?>>
                lg
            </option>
            <option value='2x' <?php echo ( '2x' === $size ) ? 'selected' : ''; ?>>
                2x
            </option>
            <option value='3x' <?php echo ( '3x' === $size ) ? 'selected' : ''; ?>>
                3x
            </option>
            <option value='4x' <?php echo ( '4x' === $size ) ? 'selected' : ''; ?>>
                4x
            </option>
            <option value='5x' <?php echo ( '5x' === $size ) ? 'selected' : ''; ?>>
                5x
            </option>

        </select>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">
			<?php esc_html_e( 'Align:', 'icon-widget' ); ?>
        </label>
        <br/>
        <select class='widefat'
                id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>"
                type="text">

            <option value='left' <?php echo ( 'left' === $align ) ? 'selected' : ''; ?>>
                Left
            </option>
            <option value='center' <?php echo ( 'center' === $align ) ? 'selected' : ''; ?>>
                Center
            </option>
            <option value='right' <?php echo ( 'right' === $align ) ? 'selected' : ''; ?>>
                Right
            </option>

        </select>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'padding' ) ); ?>">
			<?php esc_html_e( 'Padding (px):', 'icon-widget' ); ?>
        </label>
        <br/>
        <input type="number" class='widefat'
               id="<?php echo esc_attr( $this->get_field_id( 'padding' ) ); ?>"
               name="<?php echo esc_attr( $this->get_field_name( 'padding' ) ); ?>"
               value="<?php echo esc_attr( $padding ); ?>">
    </p>
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'radius' ) ); ?>">
			<?php esc_html_e( 'Border Radius (px):', 'icon-widget' ); ?>
        </label>
        <br/>
        <input type="number" class='widefat'
               id="<?php echo esc_attr( $this->get_field_id( 'radius' ) ); ?>"
               name="<?php echo esc_attr( $this->get_field_name( 'radius' ) ); ?>"
               value="<?php echo esc_attr( $radius ); ?>">
    </p>

    <script type="text/javascript">
        (function ($) {
            function initColorPicker(widget) {
                widget.find('.color-picker').wpColorPicker({
                    change: _.throttle(function () { // For Customizer
                        $(this).trigger('change');
                    }, 3000)
                });
            }

            function onFormUpdate(event, widget) {
                initColorPicker(widget);
            }

            $(document).on('widget-added widget-updated', onFormUpdate);

            $(document).ready(function () {
                $('#widgets-right .widget:has(.color-picker)').each(function () {
                    initColorPicker($(this));
                });
            });
        }(jQuery));
    </script>
    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>">
			<?php esc_html_e( 'Icon Color:', 'icon-widget' ); ?>
        </label>
        <br/>
        <input class="color-picker" type="text"
               id="<?php echo esc_attr( $this->get_field_id( 'color' ) ); ?>"
               name="<?php echo esc_attr( $this->get_field_name( 'color' ) ); ?>"
               value="<?php echo esc_attr( $color ); ?>"/>
    </p>

    <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'bg' ) ); ?>">
			<?php esc_html_e( 'Background Color:', 'icon-widget' ); ?>
        </label>
        <br/>
        <input class="color-picker" type="text"
               id="<?php echo esc_attr( $this->get_field_id( 'bg' ) ); ?>"
               name="<?php echo esc_attr( $this->get_field_name( 'bg' ) ); ?>"
               value="<?php echo esc_attr( $bg ); ?>"/>
    </p>

</div>

