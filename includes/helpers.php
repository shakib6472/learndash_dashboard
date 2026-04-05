<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Global helper functions for the LearnDash Premium Dashboard plugin.
 * These functions can be used across multiple files to avoid code duplication and maintain consistency.
 */

if ( ! function_exists( 'ldp_get_smart_resume_url' ) ) {
    /**
     * Get the exact URL of the first incomplete lesson or topic for a LearnDash course.
     */
    function ldp_get_smart_resume_url( $course_id, $user_id ) {
        $course_link = get_permalink( $course_id );

        if ( ! function_exists( 'learndash_get_course_lessons_list' ) ) {
            return $course_link;
        }

        // 1. Get all lessons for the course
        $lessons = learndash_get_course_lessons_list( $course_id, $user_id, array( 'num' => 0 ) );

        if ( empty( $lessons ) ) {
            return $course_link;
        }

        // 2. Loop through lessons to find the first uncompleted one
        foreach ( $lessons as $lesson ) {
            if ( $lesson['status'] !== 'completed' ) {
                $lesson_id = $lesson['post']->ID;
                
                // 3. If the lesson is incomplete, check if it has topics
                if ( function_exists( 'learndash_get_topic_list' ) && function_exists( 'learndash_is_topic_complete' ) ) {
                    $topics = learndash_get_topic_list( $lesson_id, $course_id );
                    
                    if ( ! empty( $topics ) ) {
                        // Loop topics to find the first uncompleted one
                        foreach ( $topics as $topic ) {
                            if ( ! learndash_is_topic_complete( $user_id, $topic->ID, $course_id ) ) {
                                return get_permalink( $topic->ID ); // Return uncompleted topic URL
                            }
                        }
                    }
                }
                
                // If no topics, or all topics completed, return the lesson URL
                return $lesson['permalink'];
            }
        }

        // If everything is completed, just go to the course page
        return $course_link;
    }
}