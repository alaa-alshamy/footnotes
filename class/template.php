<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
/**
 * Includes the Template Engine to load and handle all Template files of the Plugin.
 *
 * @filesource
 * @package footnotes
 * @since 1.5.0
 *
 * @since 2.2.6  Adding: Templates: support for custom templates in sibling folder, thanks to @misfist issue report.
 * @since 2.5.0  Adding: Templates: Enable template location stack, thanks to @misfist issue report and code contribution.
 */

/**
 * Handles each Template file for the Plugin Frontend (e.g. Settings Dashboard, Public pages, ...).
 * Loads a template file, replaces all Placeholders and returns the replaced file content.
 *
 * @since 1.5.0
 */
class Footnotes_Template {

	/**
	 * Directory name for dashboard templates.
	 *
	 * @since 1.5.0
	 * @var string
	 */
	const DASHBOARD = 'dashboard';

	/**
	 * Directory name for public templates.
	 *
	 * @since 1.5.0
	 * @var string
	 */
	const PUBLIC = 'public';

	/**
	 * Contains the content of the template after initialize.
	 *
	 * @since  1.5.0
	 * @var string
	 */
	private $original_content = '';

	/**
	 * Contains the content of the template after initialize with replaced place holders.
	 *
	 * @since  1.5.0
	 * @var string
	 */
	private $replaced_content = '';

	/**
	 * Plugin Directory
	 *
	 * @since 2.4.0d3
	 *
	 * @var string
	 */
	public $plugin_directory;

	/**
	 * Class Constructor. Reads and loads the template file without replace any placeholder.
	 *
	 * @since 1.5.0
	 * @param string $file_type Template file type (take a look on the Class constants).
	 * @param string $file_name Template file name inside the Template directory without the file extension.
	 * @param string $extension Optional Template file extension (default: html).
	 *
	 * - Adding: Templates: support for custom templates in sibling folder, thanks to @misfist issue report.
	 *
	 * @since 2.2.6
	 *
	 * @reporter @misfist
	 * @link https://wordpress.org/support/topic/template-override-filter/
	 */
	public function __construct( $file_type, $file_name, $extension = 'html' ) {
		// No template file type and/or file name set.
		if ( empty( $file_type ) || empty( $file_name ) ) {
			return;
		}

		/**
		 * Define plugin root path.
		 *
		 * @since 2.4.0d3
		 */
		$this->plugin_directory = plugin_dir_path( dirname( __FILE__ ) );

		/**
		 * Modularize functions.
		 *
		 * @since 2.4.0d3
		 */
		$template = $this->get_template( $file_type, $file_name, $extension );
		if ( $template ) {
			$this->process_template( $template );
		} else {
			return;
		}

	}

	/**
	 * Replace all placeholders specified in array.
	 *
	 * @since  1.5.0
	 * @param array $placeholders Placeholders (key = placeholder, value = value).
	 * @return bool True on Success, False if Placeholders invalid.
	 */
	public function replace( $placeholders ) {
		// No placeholders set.
		if ( empty( $placeholders ) ) {
			return false;
		}
		// Template content is empty.
		if ( empty( $this->replaced_content ) ) {
			return false;
		}
		// Iterate through each placeholder and replace it with its value.
		foreach ( $placeholders as $placeholder => $value ) {
			$this->replaced_content = str_replace( '[[' . $placeholder . ']]', $value, $this->replaced_content );
		}
		// Success.
		return true;
	}

	/**
	 * Reloads the original content of the template file.
	 *
	 * @since  1.5.0
	 */
	public function reload() {
		$this->replaced_content = $this->original_content;
	}

	/**
	 * Returns the content of the template file with replaced placeholders.
	 *
	 * @since  1.5.0
	 * @return string Template content with replaced placeholders.
	 */
	public function get_content() {
		return $this->replaced_content;
	}

	/**
	 * Process template file.
	 *
	 * @since 2.4.0d3
	 *
	 * @param string $template The template to be processed.
	 * @return void
	 *
	 * @since 2.0.3  Replace tab with a space.
	 * @since 2.0.3  Replace 2 spaces with 1.
	 * @since 2.0.4  Collapse multiple spaces.
	 * @since 2.2.6  Delete a space before a closing pointy bracket.
	 * @since 2.5.4  Collapse HTML comments and PHP/JS docblocks (only).
	 */
	public function process_template( $template ) {
		// phpcs:disable WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$this->original_content = preg_replace( '#<!--.+?-->#s', '', file_get_contents( $template ) );
		// phpcs:enable
		$this->original_content = preg_replace( '#/\*\*.+?\*/#s', '', $this->original_content );
		$this->original_content = str_replace( "\n", '', $this->original_content );
		$this->original_content = str_replace( "\r", '', $this->original_content );
		$this->original_content = str_replace( "\t", ' ', $this->original_content );
		$this->original_content = preg_replace( '# +#', ' ', $this->original_content );
		$this->original_content = str_replace( ' >', '>', $this->original_content );
		$this->reload();
	}

	/**
	 * Get the template.
	 *
	 * - Adding: Templates: Enable template location stack, thanks to @misfist issue report and code contribution.
	 *
	 * @since 2.4.0d3 Contribution.
	 * @since 2.5.0   Release.
	 *
	 * @contributor @misfist
	 * @link https://wordpress.org/support/topic/template-override-filter/#post-13864301
	 *
	 * @param string $file_type The file type of the template.
	 * @param string $file_name The file name of the template.
	 * @param string $extension The file extension of the template.
	 * @return mixed false | template path
	 */
	public function get_template( $file_type, $file_name, $extension = 'html' ) {
		$located = false;

		/**
		 * The directory can be changed.
		 *
		 * @usage to change location of templates to 'template_parts/footnotes/':
		 * add_filter( 'mci_footnotes_template_directory', function( $directory ) {
		 *  return 'template_parts/footnotes/';
		 * } );
		 */
		$template_directory = apply_filters( 'mci_footnotes_template_directory', 'footnotes/templates/' );
		$custom_directory   = apply_filters( 'mci_footnotes_custom_template_directory', 'footnotes-custom/' );
		$template_name      = $file_type . '/' . $file_name . '.' . $extension;

		/**
		 * Look in active theme.
		 */
		if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $template_directory . $template_name ) ) {
			$located = trailingslashit( get_stylesheet_directory() ) . $template_directory . $template_name;

			/**
			 * Look in parent theme in case active is child.
			 */
		} elseif ( file_exists( trailingslashit( get_template_directory() ) . $template_directory . $template_name ) ) {
			$located = trailingslashit( get_template_directory() ) . $template_directory . $template_name;

			/**
			 * Look in custom plugin directory.
			 */
		} elseif ( file_exists( trailingslashit( WP_PLUGIN_DIR ) . $custom_directory . 'templates/' . $template_name ) ) {
			$located = trailingslashit( WP_PLUGIN_DIR ) . $custom_directory . 'templates/' . $template_name;

			/**
			 * Fall back to the templates shipped with the plugin.
			 */
		} elseif ( file_exists( $this->plugin_directory . 'templates/' . $template_name ) ) {
			$located = $this->plugin_directory . 'templates/' . $template_name;
		}

		return $located;
	}

}
