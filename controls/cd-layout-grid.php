<?php
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Main Ele Layout Grid Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class CD_Ele_Layout_Grid {


	/**
	 * Initialize 
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public static function init() {

		// Document Settings :: 
		add_action( 'elementor/element/wp-post/document_settings/before_section_end', [ __CLASS__, 'layout_grid_controls' ] ); 
		add_action( 'elementor/element/wp-page/document_settings/before_section_end', [ __CLASS__, 'layout_grid_controls' ] ); 
		add_action( 'elementor/preview/enqueue_styles', [ __CLASS__, 'enqueue_style' ] );

	}
	 /* enqueue style */
	  public static function enqueue_style() {
		wp_register_style( 'cd-layout-grid-style', plugins_url( 'ele-layout-grid/assets/css/main.css') ,'',null,'all');
		wp_enqueue_style( 'cd-layout-grid-style' );
	 }

    

    public static function layout_grid_controls( \Elementor\Core\DocumentTypes\PageBase $page ) {
		
		// LAYOUT GRID CONTROLS
		$page->add_control(
			'_ele_layout_grid_use_layout_grid',
			[
				'label' => __( 'Layout Grid', 'ele-layout-grid' ),
				'description' => __( 'Layout grid helps you accurately design your layouts consistently', 'ele-layout-grid' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ele-layout-grid' ),
				'label_off' => __( 'No', 'ele-layout-grid' ),
				'return_value' => 'yes',
				'default' => 'no',
				'label_block' => false,
				'separator' => 'before', 
			]
		);
        $page->add_control(
            '_ele_layout_grid_layout_grid_style',
            [
                'label' => __( 'Grid style', 'ele-layout-grid' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'both', 
                'options' => [
                    'both' => __( 'Squares', 'ele-layout-grid' ),
                    'column' => __( 'Columns', 'ele-layout-grid' ),
                    'baseline' => __( 'Baseline', 'ele-layout-grid' ),
				],
				'condition' => [
                    '_ele_layout_grid_use_layout_grid' => 'yes', 
                ],
            ]
        );
		$page->add_control(
			'_ele_layout_grid_layout_grid_color',
			[
				'label' => __( 'Grid color', 'ele-layout-grid' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgb(255,0,0,.1)', 
				'condition' => [
                    '_ele_layout_grid_use_layout_grid' => 'yes', 
                ],
			]
		);
		$page->add_control(
			'_ele_layout_grid_use_fullwidth',
			[
				'label' => __( 'Full Width Grid', 'ele-layout-grid' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'ele-layout-grid' ),
				'label_off' => __( 'No', 'ele-layout-grid' ),
				'return_value' => 'yes',
				'default' => 'no',
				'selectors' => [
					'html.elementor-html::before' => 'width: {{SIZE}}{{UNIT}};', 
				], 
				'condition' => [
                    '_ele_layout_grid_use_layout_grid' => 'yes', 
					'_ele_layout_grid_layout_grid_style' => [ 'column' ], 
                ],
			]
		);
		$page->add_control(
			'_ele_layout_grid_layout_grid_content_width',
			[
				'label' => __( 'Content Width (px)', 'ele-layout-grid' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 2000,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1140,
				],
				'selectors' => [
					'html.elementor-html::before' => 'width: {{SIZE}}{{UNIT}};', 
				], 
				'condition' => [
                    '_ele_layout_grid_use_layout_grid' => 'yes', 
					'_ele_layout_grid_layout_grid_style' => [ 'column' ], 
					'_ele_layout_grid_use_fullwidth!' => 'yes',
                ],
			]
		);
		$page->add_control(
			'_ele_layout_grid_layout_grid_size',
			[
				'label' => __( 'Grid size (px)', 'ele-layout-grid' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'html.elementor-html::before' => 'background-image: repeating-linear-gradient(to right, {{_ele_layout_grid_layout_grid_color.VALUE}}, {{_ele_layout_grid_layout_grid_color.VALUE}} 1px, transparent 1px, transparent), repeating-linear-gradient(to bottom, {{_ele_layout_grid_layout_grid_color.VALUE}}, {{_ele_layout_grid_layout_grid_color.VALUE}} 1px, transparent 1px, transparent);background-size: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};', 
				],
				'condition' => [
					'_ele_layout_grid_use_layout_grid' => 'yes', 
					'_ele_layout_grid_layout_grid_style' => [ 'both' ], 
                ],
			]
		);
		$page->add_control(
			'_ele_layout_grid_layout_grid_size_col',
			[
				'label' => __( 'Column Spacing (px)', 'ele-layout-grid' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'selectors' => [
					'html.elementor-html::before' => 'background-image: repeating-linear-gradient(90deg, transparent 0px,transparent {{SIZE}}{{UNIT}}, {{_ele_layout_grid_layout_grid_color.VALUE}} {{SIZE}}{{UNIT}},{{_ele_layout_grid_layout_grid_color.VALUE}} calc({{SIZE}}{{UNIT}}*2));',
				],
				'condition' => [
					'_ele_layout_grid_use_layout_grid' => 'yes', 
					'_ele_layout_grid_layout_grid_style' => [ 'column' ], 
                ],
			]
		);
		$page->add_control(
			'_ele_layout_grid_layout_grid_size_base',
			[
				'label' => __( 'Baseline Spacing (px)', 'ele-layout-grid' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'html.elementor-html::before' => 'background-image: repeating-linear-gradient(0deg, transparent 0px,transparent {{SIZE}}{{UNIT}}, {{_ele_layout_grid_layout_grid_color.VALUE}} {{SIZE}}{{UNIT}},{{_ele_layout_grid_layout_grid_color.VALUE}} calc({{SIZE}}{{UNIT}}*2));',
				],
				'condition' => [
					'_ele_layout_grid_use_layout_grid' => 'yes', 
					'_ele_layout_grid_layout_grid_style' => [ 'baseline' ], 
                ],
			]
		);

	}

}
CD_Ele_Layout_Grid::init();