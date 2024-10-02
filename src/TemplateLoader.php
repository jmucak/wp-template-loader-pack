<?php

namespace jmucak\wpTemplateLoaderPack;

class TemplateLoader {
	protected static ?TemplateLoader $instance = null;

	private function __construct() {
	}

	final protected function __clone() {
	}

	public static function get_instance(): ?TemplateLoader {
		if ( null === self::$instance ) {
			self::$instance = new TemplateLoader();
		}

		return self::$instance;
	}

	// @codingStandardsIgnoreStart
	public function render( string $_view_file_, array $_data_ = array() ): void {
		// we use special variable names here to avoid conflict when extracting data
		if ( ! empty( $_data_ ) ) {
			extract( $_data_, EXTR_OVERWRITE );
		}

		require $_view_file_;
	}

	public function get_html( $_view_file_, array $_data_ = array() ): bool|string {
		// we use special variable names here to avoid conflict when extracting data
		if ( ! empty( $_data_ ) ) {
			extract( $_data_, EXTR_OVERWRITE );
		}

		ob_start();
		ob_implicit_flush( 0 );
		require $_view_file_;

		return ob_get_clean();
	}

	// @codingStandardsIgnoreEnd

	/**
	 * First check if file path exists and return file path
	 *
	 * @param string $file_path
	 * @return string
	 */
	private function get_file_path( string $file_path ): string {
		if ( file_exists( $file_path ) ) {
			return $file_path;
		}

		if ( file_exists( '_' . $file_path ) ) {
			return '_' . $file_path;
		}

		return '';
	}

	/**
	 *
	 * Get partial template
	 *
	 * @param string $path
	 * @param array $data
	 * @param bool $html
	 * @return bool|string|void
	 */
	public function get_partial( string $path, array $data = array(), bool $html = false ) {
		$file_path = $this->get_file_path( $path );

		if ( empty( $file_path ) ) {
			return '';
		}

		if ( $html ) {
			return $this->get_html( $path, $data );
		}

		$this->render( $path, $data );
	}
}