<?php
/**
 * Shared helper functions.
 *
 * @package Kibworks_Student_Dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'kbw_sd_get_smart_resume_url' ) ) {
	/**
	 * URL of the first lesson or topic the user has not completed yet.
	 *
	 * @param int $course_id Course ID.
	 * @param int $user_id   User ID.
	 * @return string
	 */
	function kbw_sd_get_smart_resume_url( $course_id, $user_id ) {
		$course_link = get_permalink( $course_id );

		if ( ! function_exists( 'learndash_get_course_lessons_list' ) ) {
			return $course_link;
		}

		$lessons = learndash_get_course_lessons_list( $course_id, $user_id, array( 'num' => 0 ) );

		if ( empty( $lessons ) ) {
			return $course_link;
		}

		foreach ( $lessons as $lesson ) {
			if ( 'completed' === $lesson['status'] ) {
				continue;
			}

			$lesson_id = $lesson['post']->ID;

			// The lesson is incomplete, so look for an incomplete topic inside it first.
			if ( function_exists( 'learndash_get_topic_list' ) && function_exists( 'learndash_is_topic_complete' ) ) {
				$topics = learndash_get_topic_list( $lesson_id, $course_id );

				if ( ! empty( $topics ) ) {
					foreach ( $topics as $topic ) {
						if ( ! learndash_is_topic_complete( $user_id, $topic->ID, $course_id ) ) {
							return get_permalink( $topic->ID );
						}
					}
				}
			}

			// No topics, or every topic is done: the lesson itself is the resume point.
			return $lesson['permalink'];
		}

		return $course_link;
	}
}

if ( ! function_exists( 'kbw_sd_sanitize_css_color' ) ) {
	/**
	 * Sanitize a colour for use in a CSS custom property.
	 *
	 * sanitize_hex_color() alone is not enough here: the shipped defaults include
	 * `transparent` and `rgba(0, 0, 0, 0.5)`, and it returns null for both, which
	 * would emit an invalid `--var: ;` declaration.
	 *
	 * @param string $value Raw stored colour.
	 * @return string Safe CSS colour, or '' when the value cannot be trusted.
	 */
	function kbw_sd_sanitize_css_color( $value ) {
		if ( ! is_scalar( $value ) ) {
			return '';
		}

		$value = trim( (string) $value );

		if ( '' === $value ) {
			return '';
		}

		$hex = sanitize_hex_color( $value );
		if ( ! empty( $hex ) ) {
			return $hex;
		}

		// CSS-wide and transparent keywords.
		if ( in_array( strtolower( $value ), array( 'transparent', 'inherit', 'initial', 'unset', 'currentcolor' ), true ) ) {
			return strtolower( $value );
		}

		// rgb() / rgba() with numeric components only.
		if ( preg_match( '/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/i', $value ) ) {
			return $value;
		}

		// hsl() / hsla() with numeric components only.
		if ( preg_match( '/^hsla?\(\s*\d{1,3}\s*,\s*\d{1,3}%\s*,\s*\d{1,3}%\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/i', $value ) ) {
			return $value;
		}

		return '';
	}
}

if ( ! function_exists( 'kbw_sd_sanitize_font_family' ) ) {
	/**
	 * Sanitize a font family name before it is interpolated into inline CSS.
	 *
	 * @param string $value    Stored font name.
	 * @param string $fallback Used when the stored value is unusable.
	 * @return string
	 */
	function kbw_sd_sanitize_font_family( $value, $fallback = 'Inter' ) {
		$value = is_scalar( $value ) ? trim( (string) $value ) : '';

		// Font names are plain words; anything else is not a font name.
		$value = preg_replace( '/[^A-Za-z0-9 _-]/', '', $value );
		$value = trim( (string) $value );

		return '' === $value ? $fallback : $value;
	}
}

if ( ! function_exists( 'kbw_sd_get_placeholder_image_url' ) ) {
	/**
	 * Fallback course image.
	 *
	 * Shipped with the plugin rather than fetched from a remote placeholder
	 * service, so the dashboard never depends on a third-party host.
	 *
	 * @return string
	 */
	function kbw_sd_get_placeholder_image_url() {
		$settings = get_option( 'kbw_sd_settings', array() );

		if ( ! empty( $settings['logo_url'] ) ) {
			return $settings['logo_url'];
		}

		return KBW_SD_PLUGIN_URL . 'assets/placeholder-course.svg';
	}
}
