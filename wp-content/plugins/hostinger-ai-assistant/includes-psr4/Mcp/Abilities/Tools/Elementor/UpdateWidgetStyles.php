<?php

namespace Hostinger\AiAssistant\Mcp\Abilities\Tools\Elementor;

if ( ! defined( 'ABSPATH' ) ) {
    die;
}

class UpdateWidgetStyles extends BaseElementorTool {
    public function register(): void {
        $this->register_ability(
            'hostinger-ai/elementor-update-widget-styles',
            array(
                'label'               => __( 'Update Elementor Widget Styles', 'hostinger-ai-assistant' ),
                'description'         => 'Update the visual styles of an Elementor widget: colors, typography, spacing (padding/margin), borders and alignment, as direct or responsive values. Use when asked to restyle a single widget; to link a widget to a brand color use `hostinger-ai/elementor-assign-global-color`. Needs `post_id` from `hostinger-ai/elementor-list-pages` and `widget_id` from `hostinger-ai/elementor-get-page-structure`.',
                'category'            => $this->category,
                'input_schema'        => array(
                    'type'       => 'object',
                    'properties' => array(
                        'post_id'   => array(
                            'type'        => 'integer',
                            'description' => 'The ID of the post/page containing the widget',
                        ),
                        'widget_id' => array(
                            'type'        => 'string',
                            'description' => 'The unique ID of the widget to update',
                        ),
                        'styles'    => array(
                            'type'        => 'object',
                            'description' => 'Style properties to update. All properties are optional. Only provide the properties you want to change.',
                            'properties'  => array(
                                'colors'     => array(
                                    'type'        => 'object',
                                    'description' => 'Color properties. Values should be valid CSS colors (hex, rgb, rgba). Example: "#FF0000" or "rgb(255,0,0)"',
                                    'properties'  => array(
                                        'text_color'              => array(
                                            'type'        => 'string',
                                            'description' => 'Text color. Used for text elements.',
                                        ),
                                        'title_color'             => array(
                                            'type'        => 'string',
                                            'description' => 'Title/heading color. Used for headings and titles.',
                                        ),
                                        'background_color'        => array(
                                            'type'        => 'string',
                                            'description' => 'Background color',
                                        ),
                                        'border_color'            => array(
                                            'type'        => 'string',
                                            'description' => 'Border color',
                                        ),
                                        'button_text_color'       => array(
                                            'type'        => 'string',
                                            'description' => 'Button text color',
                                        ),
                                        'button_background_color' => array(
                                            'type'        => 'string',
                                            'description' => 'Button background color',
                                        ),
                                        'hover_color'             => array(
                                            'type'        => 'string',
                                            'description' => 'Hover state color',
                                        ),
                                        'icon_color'              => array(
                                            'type'        => 'string',
                                            'description' => 'Icon color',
                                        ),
                                    ),
                                ),
                                'typography' => array(
                                    'type'        => 'object',
                                    'description' => 'Typography settings for text appearance',
                                    'properties'  => array(
                                        'font_family'     => array(
                                            'type'        => 'string',
                                            'description' => 'Font family name. Example: "Arial", "Roboto"',
                                        ),
                                        'font_size'       => array(
                                            'type'        => 'object',
                                            'description' => 'Font size with unit',
                                            'properties'  => array(
                                                'size' => array(
                                                    'type'        => 'number',
                                                    'description' => 'Size value',
                                                ),
                                                'unit' => array(
                                                    'type'        => 'string',
                                                    'description' => 'Unit: px, em, rem, %',
                                                    'enum'        => array( 'px', 'em', 'rem', '%', 'vw' ),
                                                    'default'     => 'px',
                                                ),
                                            ),
                                        ),
                                        'font_weight'     => array(
                                            'type'        => 'string',
                                            'description' => 'Font weight. Example: "400", "700", "bold", "normal"',
                                            'enum'        => array(
                                                '100',
                                                '200',
                                                '300',
                                                '400',
                                                '500',
                                                '600',
                                                '700',
                                                '800',
                                                '900',
                                                'normal',
                                                'bold',
                                            ),
                                        ),
                                        'line_height'     => array(
                                            'type'        => 'object',
                                            'description' => 'Line height with unit',
                                            'properties'  => array(
                                                'size' => array(
                                                    'type'        => 'number',
                                                    'description' => 'Size value',
                                                ),
                                                'unit' => array(
                                                    'type'        => 'string',
                                                    'description' => 'Unit: px, em, rem, or empty for unitless',
                                                    'enum'        => array( 'px', 'em', 'rem', '' ),
                                                    'default'     => '',
                                                ),
                                            ),
                                        ),
                                        'letter_spacing'  => array(
                                            'type'        => 'object',
                                            'description' => 'Letter spacing with unit',
                                            'properties'  => array(
                                                'size' => array(
                                                    'type'        => 'number',
                                                    'description' => 'Size value',
                                                ),
                                                'unit' => array(
                                                    'type'        => 'string',
                                                    'description' => 'Unit: px, em',
                                                    'enum'        => array( 'px', 'em' ),
                                                    'default'     => 'px',
                                                ),
                                            ),
                                        ),
                                        'text_transform'  => array(
                                            'type'        => 'string',
                                            'description' => 'Text transformation',
                                            'enum'        => array( 'none', 'uppercase', 'lowercase', 'capitalize' ),
                                        ),
                                        'font_style'      => array(
                                            'type'        => 'string',
                                            'description' => 'Font style',
                                            'enum'        => array( 'normal', 'italic', 'oblique' ),
                                        ),
                                        'text_decoration' => array(
                                            'type'        => 'string',
                                            'description' => 'Text decoration',
                                            'enum'        => array( 'none', 'underline', 'overline', 'line-through' ),
                                        ),
                                    ),
                                ),
                                'padding'    => array(
                                    'type'        => 'object',
                                    'description' => 'Padding spacing. Set "reset" to true to remove padding, or provide dimension values.',
                                    'properties'  => array(
                                        'reset'    => array(
                                            'type'        => 'boolean',
                                            'description' => 'Set to true to remove padding entirely',
                                            'default'     => false,
                                        ),
                                        'top'      => array(
                                            'type'        => 'string',
                                            'description' => 'Top padding value (as string)',
                                        ),
                                        'right'    => array(
                                            'type'        => 'string',
                                            'description' => 'Right padding value (as string)',
                                        ),
                                        'bottom'   => array(
                                            'type'        => 'string',
                                            'description' => 'Bottom padding value (as string)',
                                        ),
                                        'left'     => array(
                                            'type'        => 'string',
                                            'description' => 'Left padding value (as string)',
                                        ),
                                        'unit'     => array(
                                            'type'        => 'string',
                                            'description' => 'Unit for all padding values',
                                            'enum'        => array( 'px', 'em', 'rem', '%', 'vw', 'vh' ),
                                            'default'     => 'px',
                                        ),
                                        'isLinked' => array(
                                            'type'        => 'boolean',
                                            'description' => 'Whether all sides are linked together',
                                            'default'     => true,
                                        ),
                                    ),
                                ),
                                'margin'     => array(
                                    'type'        => 'object',
                                    'description' => 'Margin spacing. Set "reset" to true to remove margin, or provide dimension values.',
                                    'properties'  => array(
                                        'reset'    => array(
                                            'type'        => 'boolean',
                                            'description' => 'Set to true to remove margin entirely',
                                            'default'     => false,
                                        ),
                                        'top'      => array(
                                            'type'        => 'string',
                                            'description' => 'Top margin value (as string)',
                                        ),
                                        'right'    => array(
                                            'type'        => 'string',
                                            'description' => 'Right margin value (as string)',
                                        ),
                                        'bottom'   => array(
                                            'type'        => 'string',
                                            'description' => 'Bottom margin value (as string)',
                                        ),
                                        'left'     => array(
                                            'type'        => 'string',
                                            'description' => 'Left margin value (as string)',
                                        ),
                                        'unit'     => array(
                                            'type'        => 'string',
                                            'description' => 'Unit for all margin values',
                                            'enum'        => array( 'px', 'em', 'rem', '%', 'vw', 'vh' ),
                                            'default'     => 'px',
                                        ),
                                        'isLinked' => array(
                                            'type'        => 'boolean',
                                            'description' => 'Whether all sides are linked together',
                                            'default'     => true,
                                        ),
                                    ),
                                ),
                                'border'     => array(
                                    'type'        => 'object',
                                    'description' => 'Border styling',
                                    'properties'  => array(
                                        'type'   => array(
                                            'type'        => 'string',
                                            'description' => 'Border style type',
                                            'enum'        => array(
                                                'none',
                                                'solid',
                                                'double',
                                                'dotted',
                                                'dashed',
                                                'groove',
                                            ),
                                        ),
                                        'width'  => array(
                                            'type'        => 'object',
                                            'description' => 'Border width for each side',
                                            'properties'  => array(
                                                'top'      => array(
                                                    'type'        => 'string',
                                                    'description' => 'Top border width (as string)',
                                                ),
                                                'right'    => array(
                                                    'type'        => 'string',
                                                    'description' => 'Right border width (as string)',
                                                ),
                                                'bottom'   => array(
                                                    'type'        => 'string',
                                                    'description' => 'Bottom border width (as string)',
                                                ),
                                                'left'     => array(
                                                    'type'        => 'string',
                                                    'description' => 'Left border width (as string)',
                                                ),
                                                'unit'     => array(
                                                    'type'        => 'string',
                                                    'description' => 'Unit for border width',
                                                    'enum'        => array( 'px', 'em', 'rem' ),
                                                    'default'     => 'px',
                                                ),
                                                'isLinked' => array(
                                                    'type'        => 'boolean',
                                                    'description' => 'Whether all sides are linked',
                                                    'default'     => true,
                                                ),
                                            ),
                                        ),
                                        'color'  => array(
                                            'type'        => 'string',
                                            'description' => 'Border color (hex, rgb, rgba)',
                                        ),
                                        'radius' => array(
                                            'type'        => 'object',
                                            'description' => 'Border radius for corners',
                                            'properties'  => array(
                                                'top'      => array(
                                                    'type'        => 'string',
                                                    'description' => 'Top-left border radius (as string)',
                                                ),
                                                'right'    => array(
                                                    'type'        => 'string',
                                                    'description' => 'Top-right border radius (as string)',
                                                ),
                                                'bottom'   => array(
                                                    'type'        => 'string',
                                                    'description' => 'Bottom-right border radius (as string)',
                                                ),
                                                'left'     => array(
                                                    'type'        => 'string',
                                                    'description' => 'Bottom-left border radius (as string)',
                                                ),
                                                'unit'     => array(
                                                    'type'        => 'string',
                                                    'description' => 'Unit for border radius',
                                                    'enum'        => array( 'px', 'em', 'rem', '%' ),
                                                    'default'     => 'px',
                                                ),
                                                'isLinked' => array(
                                                    'type'        => 'boolean',
                                                    'description' => 'Whether all corners are linked',
                                                    'default'     => true,
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                                'alignment'  => array(
                                    'type'        => 'object',
                                    'description' => 'Element alignment settings',
                                    'properties'  => array(
                                        'align'      => array(
                                            'type'        => 'string',
                                            'description' => 'Horizontal alignment',
                                            'enum'        => array( 'left', 'center', 'right', 'justify' ),
                                        ),
                                        'text_align' => array(
                                            'type'        => 'string',
                                            'description' => 'Text alignment',
                                            'enum'        => array( 'left', 'center', 'right', 'justify' ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'required'   => array( 'post_id', 'widget_id', 'styles' ),
                ),
                'permission_callback' => function () {
                    return current_user_can( 'manage_options' );
                },
                'meta'                => array(
                    'show_in_rest' => true,
                    'mcp'          => array(
                        'public' => true,
                        'type'   => $this->type,
                    ),
                    'annotations'  => array(
                        'readOnlyHint'    => false,
                        'destructiveHint' => false,
                        'idempotentHint'  => true,
                        'openWorldHint'   => false,
                    ),
                ),
            )
        );
    }

    public function execute( array $input ): array {
        $post_id   = $input['post_id'];
        $widget_id = $input['widget_id'];
        $styles    = $input['styles'] ?? array();

        if ( empty( $styles ) ) {
            return array(
                'success'    => false,
                'error_code' => 'NO_STYLES_PROVIDED',
                'message'    => 'No style properties provided to update',
            );
        }

        $result = $this->get_elementor_data( $post_id );
        if ( isset( $result['error_code'] ) ) {
            return $result;
        }

        $elementor_data = $result['data'];

        $updated_styles  = array();
        $previous_values = array();

        $updated = $this->set_styles( $elementor_data, $widget_id, $styles, $updated_styles, $previous_values );

        if ( ! $updated ) {
            return array(
                'success'    => false,
                'error_code' => 'WIDGET_NOT_FOUND',
                /* translators: %s: widget id  */
                'message'    => sprintf( __( "Widget with ID '%s' not found", 'hostinger-ai-assistant' ), $widget_id ),
            );
        }

        $save_result = $this->save_elementor_data( $post_id, $elementor_data );
        if ( ! $save_result['success'] ) {
            return $save_result;
        }

        return array(
            'success'         => true,
            'widget_id'       => $widget_id,
            'post_id'         => $post_id,
            'updated_styles'  => $updated_styles,
            'previous_values' => $previous_values,
        );
    }

    private function set_styles( array &$elementor_data, string $widget_id, array $styles, array &$updated_styles, array &$previous_values ): bool {
        return $this->update_widget_in_tree(
            $elementor_data,
            $widget_id,
            function ( &$widget ) use ( $styles, &$updated_styles, &$previous_values ) {
                if ( ! isset( $widget['settings'] ) ) {
                    $widget['settings'] = array();
                }

                $this->set_color_styles( $widget, $styles, $updated_styles, $previous_values );
                $this->set_typography_styles( $widget, $styles, $updated_styles, $previous_values );
                $this->set_padding_style( $widget, $styles, $updated_styles, $previous_values );
                $this->set_margin_style( $widget, $styles, $updated_styles, $previous_values );
                $this->set_border_style( $widget, $styles, $updated_styles, $previous_values );
                $this->set_alignment_style( $widget, $styles, $updated_styles, $previous_values );

                return null;
            }
        );
    }

    private function set_color_styles( array &$widget, array $styles, array &$updated_styles, array &$previous_values ): void {
        if ( isset( $styles['colors'] ) && is_array( $styles['colors'] ) ) {
            foreach ( $styles['colors'] as $color_prop => $color_value ) {
                if ( ! empty( $color_value ) ) {
                    $previous_values[ $color_prop ]          = $widget['settings'][ $color_prop ] ?? null;
                    $widget['settings'][ $color_prop ]       = $color_value;
                    $updated_styles['colors'][ $color_prop ] = $color_value;

                    if ( isset( $widget['settings']['__globals__'][ $color_prop ] ) ) {
                        unset( $widget['settings']['__globals__'][ $color_prop ] );
                    }
                }
            }
        }
    }

    private function set_typography_styles( array &$widget, array $styles, array &$updated_styles, array &$previous_values ): void {
        if ( isset( $styles['typography'] ) && is_array( $styles['typography'] ) ) {
            $typography_settings = $styles['typography'];

            $has_typography_changes = false;

            foreach ( $typography_settings as $typo_prop => $typo_value ) {
                $setting_key = "typography_{$typo_prop}";

                $previous_values[ $setting_key ] = $widget['settings'][ $setting_key ] ?? null;

                if ( $typo_prop === 'font_family' ) {
                    $widget['settings'][ $setting_key ]         = $typo_value;
                    $updated_styles['typography'][ $typo_prop ] = $typo_value;
                    $has_typography_changes                     = true;
                } elseif ( in_array(
                    $typo_prop,
                    array(
                        'font_weight',
                        'text_transform',
                        'font_style',
                        'text_decoration',
                    ),
                    true
                ) ) {
                    $widget['settings'][ $setting_key ]         = $typo_value;
                    $updated_styles['typography'][ $typo_prop ] = $typo_value;
                    $has_typography_changes                     = true;
                } elseif ( in_array( $typo_prop, array( 'font_size', 'line_height', 'letter_spacing' ), true ) && is_array( $typo_value ) ) {
                    $widget['settings'][ $setting_key ]         = $typo_value;
                    $updated_styles['typography'][ $typo_prop ] = $typo_value;
                    $has_typography_changes                     = true;
                }
            }

            if ( $has_typography_changes && isset( $widget['settings']['__globals__']['typography_typography'] ) ) {
                unset( $widget['settings']['__globals__']['typography_typography'] );
            }
        }
    }

    private function set_padding_style( array &$widget, array $styles, array &$updated_styles, array &$previous_values ): void {
        if ( isset( $styles['padding'] ) && is_array( $styles['padding'] ) ) {
            $padding_settings = $styles['padding'];

            if ( isset( $padding_settings['reset'] ) && $padding_settings['reset'] === true ) {
                $previous_values['_padding'] = $widget['settings']['_padding'] ?? null;
                unset( $widget['settings']['_padding'] );
                $updated_styles['padding'] = 'reset';
            } else {
                $padding_data = array();

                foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
                    if ( isset( $padding_settings[ $side ] ) ) {
                        $padding_data[ $side ] = $padding_settings[ $side ];
                    }
                }

                if ( ! empty( $padding_data ) ) {
                    if ( isset( $padding_settings['unit'] ) ) {
                        $padding_data['unit'] = $padding_settings['unit'];
                    }
                    if ( isset( $padding_settings['isLinked'] ) ) {
                        $padding_data['isLinked'] = $padding_settings['isLinked'];
                    }

                    $previous_values['_padding']    = $widget['settings']['_padding'] ?? null;
                    $widget['settings']['_padding'] = $padding_data;
                    $updated_styles['padding']      = $padding_data;
                }
            }
        }
    }

    private function set_margin_style( array &$widget, array $styles, array &$updated_styles, array &$previous_values ): void {
        if ( isset( $styles['margin'] ) && is_array( $styles['margin'] ) ) {
            $margin_settings = $styles['margin'];

            if ( isset( $margin_settings['reset'] ) && $margin_settings['reset'] === true ) {
                $previous_values['_margin'] = $widget['settings']['_margin'] ?? null;
                unset( $widget['settings']['_margin'] );
                $updated_styles['margin'] = 'reset';
            } else {
                $margin_data = array();

                foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
                    if ( isset( $margin_settings[ $side ] ) ) {
                        $margin_data[ $side ] = $margin_settings[ $side ];
                    }
                }

                if ( ! empty( $margin_data ) ) {
                    if ( isset( $margin_settings['unit'] ) ) {
                        $margin_data['unit'] = $margin_settings['unit'];
                    }
                    if ( isset( $margin_settings['isLinked'] ) ) {
                        $margin_data['isLinked'] = $margin_settings['isLinked'];
                    }

                    $previous_values['_margin']    = $widget['settings']['_margin'] ?? null;
                    $widget['settings']['_margin'] = $margin_data;
                    $updated_styles['margin']      = $margin_data;
                }
            }
        }
    }

    private function set_border_style( array &$widget, array $styles, array &$updated_styles, array &$previous_values ): void {
        if ( isset( $styles['border'] ) && is_array( $styles['border'] ) ) {
            $border_settings = $styles['border'];

            if ( isset( $border_settings['type'] ) ) {
                $previous_values['_border_border']    = $widget['settings']['_border_border'] ?? null;
                $widget['settings']['_border_border'] = $border_settings['type'];
                $updated_styles['border']['type']     = $border_settings['type'];
            }

            if ( isset( $border_settings['width'] ) && is_array( $border_settings['width'] ) ) {
                $width_data = array();
                foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
                    if ( isset( $border_settings['width'][ $side ] ) ) {
                        $width_data[ $side ] = $border_settings['width'][ $side ];
                    }
                }

                if ( ! empty( $width_data ) ) {
                    if ( isset( $border_settings['width']['unit'] ) ) {
                        $width_data['unit'] = $border_settings['width']['unit'];
                    }
                    if ( isset( $border_settings['width']['isLinked'] ) ) {
                        $width_data['isLinked'] = $border_settings['width']['isLinked'];
                    }

                    $previous_values['_border_width']    = $widget['settings']['_border_width'] ?? null;
                    $widget['settings']['_border_width'] = $width_data;
                    $updated_styles['border']['width']   = $width_data;
                }
            }

            if ( isset( $border_settings['color'] ) ) {
                $previous_values['_border_color']    = $widget['settings']['_border_color'] ?? null;
                $widget['settings']['_border_color'] = $border_settings['color'];
                $updated_styles['border']['color']   = $border_settings['color'];
            }

            if ( isset( $border_settings['radius'] ) && is_array( $border_settings['radius'] ) ) {
                $radius_data = array();
                foreach ( array( 'top', 'right', 'bottom', 'left' ) as $corner ) {
                    if ( isset( $border_settings['radius'][ $corner ] ) ) {
                        $radius_data[ $corner ] = $border_settings['radius'][ $corner ];
                    }
                }

                if ( ! empty( $radius_data ) ) {
                    if ( isset( $border_settings['radius']['unit'] ) ) {
                        $radius_data['unit'] = $border_settings['radius']['unit'];
                    }
                    if ( isset( $border_settings['radius']['isLinked'] ) ) {
                        $radius_data['isLinked'] = $border_settings['radius']['isLinked'];
                    }

                    $previous_values['_border_radius']    = $widget['settings']['_border_radius'] ?? null;
                    $widget['settings']['_border_radius'] = $radius_data;
                    $updated_styles['border']['radius']   = $radius_data;
                }
            }
        }
    }

    private function set_alignment_style( array &$widget, array $styles, array &$updated_styles, array &$previous_values ): void {
        if ( isset( $styles['alignment'] ) && is_array( $styles['alignment'] ) ) {
            if ( isset( $styles['alignment']['align'] ) ) {
                $previous_values['align']             = $widget['settings']['align'] ?? null;
                $widget['settings']['align']          = $styles['alignment']['align'];
                $updated_styles['alignment']['align'] = $styles['alignment']['align'];
            }

            if ( isset( $styles['alignment']['text_align'] ) ) {
                $previous_values['text_align']             = $widget['settings']['text_align'] ?? null;
                $widget['settings']['text_align']          = $styles['alignment']['text_align'];
                $updated_styles['alignment']['text_align'] = $styles['alignment']['text_align'];
            }
        }
    }
}
