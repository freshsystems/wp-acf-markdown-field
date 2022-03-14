<?php

namespace Fresh\ACFMarkdownField;

use Fresh\ACFMarkdownField\Helpers;

use acf_field;

/**
 * # ACF MarkdownField
 * 
 * @see https://github.com/AdvancedCustomFields/acf-field-type-template
 * 
 */
class MarkdownField extends acf_field 
{
    /** @var \Fresh\ACFMarkdownField\Helpers $helpers */
    protected $helpers;

    /**
     *  This function will setup the field type data
     *
     * @since ACF 5.0.0
     * @param array $settings
     * @param \Fresh\ACFMarkdownField\Helpers $helpers
     */
	function __construct( array $settings, Helpers $helpers ) 
    {	
        $this->helpers = $helpers;

		/* name (string) Single word, no spaces. Underscores allowed */
		$this->name = 'markdown';
		/* label (string) Multiple words, can include spaces, visible when selecting a field type */
		$this->label = __('Markdown', 'wp-acf-markdown-field');
		/* category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME */
		$this->category = 'content';
		/* defaults (array) Array of default settings which are merged into the field object. These are used later in settings */
		$this->defaults = [];
		/* l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		var message = acf._e('markdown', 'error'); */
		$this->l10n = [
			// 'error'	=> __('Error!', 'wp-acf-markdown-field'),
        ];
	    /* settings (array) Store plugin settings (url, path, version) as a reference for later use with assets */
		$this->settings = $settings;

        /* Call parent constructor: */
    	parent::__construct();
	}	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	function render_field( $field ) 
    {
        echo '<textarea rows="8" class="fresh-acf-markdown-input" name="' . esc_attr($field['name']) . '">'. esc_textarea($field['value']).'</textarea>';
	}
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/	
	function input_admin_enqueue_scripts() 
    {	
        wp_enqueue_media();
		// Register & include vendor and field script/styles:
        // Scripts:
        wp_register_script( 'wp-acf-markdown-field-manifest', $this->helpers->getAssetsUrl('/dist/scripts/manifest.js'), [], null );
		wp_register_script( 'wp-acf-markdown-field-vendor', $this->helpers->getAssetsUrl('/dist/scripts/vendor.js'), ['wp-acf-markdown-field-manifest'], null );
		wp_register_script( 'wp-acf-markdown-field', $this->helpers->getAssetsUrl('/dist/scripts/field.js'), ['acf-input', 'wp-acf-markdown-field-manifest', 'wp-acf-markdown-field-vendor' ], null );
		wp_enqueue_script( 'wp-acf-markdown-field-manifest');
		wp_enqueue_script( 'wp-acf-markdown-field-vendor');
		wp_enqueue_script( 'wp-acf-markdown-field');
        // Styles
        wp_register_style( 'wp-acf-markdown-field', $this->helpers->getAssetsUrl('/dist/styles/field.css'), ['acf-input'], null );
		wp_enqueue_style( 'wp-acf-markdown-field' );
	}
	
}
