<?php
/**
 * Plugin Name: Add Cron Job
 * Description: This plugin allows for real-time notifications when a post is published.
 * Version: 1.0
 * Author: Sadekur Rahman
 */


/**
 *
 * @param array $schedules
 * @return void
 */
function custom_every_minute_schedule( $schedules ) {
    // add a 'everyfivesecond' schedule to the existing set
    $schedules['everyfivesecond'] = array(
        'interval' => 5,
        'display'  => __( 'Custom Five Second', 'gca-core' ),
    );
    return $schedules;
}
add_filter( 'cron_schedules', 'custom_every_minute_schedule' );

/**
 * Scedule cron job.
 *
 * @return void
 */
function custom_core_activate() {
    if ( ! wp_next_scheduled( 'custom_every_minute_event' ) ) {
        wp_schedule_event( time(), 'everyfivesecond', 'custom_every_minute_event' );
    }
}
register_activation_hook( __FILE__, 'custom_core_activate' );

add_action( 'custom_every_minute_event', 'custom_every_minute_cronjob' );

/**
 * Do whatever you want to do in the cron job.
 */
// function custom_every_minute_cronjob() {
// 	$user_id = get_current_user_id();
// 	$args = array(
// 		'post_type' 	=> 'post',
// 		'order'         => 'ASC',
//         'author'        => $user_id,
// 		'posts_per_page'=> -1,
// 	);

// 	$query = new WP_Query( $args );
// 	if ( $query->have_posts() ) {
//         while ( $query->have_posts() ) {
//             $query->the_post();
//             wp_delete_post( get_the_ID(), false );
//         }
//         wp_reset_postdata(); // Reset post data
//     }
// 	// error_log( date( 'Y-m-d H:i:s', time() ) );
// 	// add_option( 'custom_crone_run_at', date( 'Y-m-d H:i:s', time() ) );
// }

function custom_every_minute_cronjob() {
    $new_post = array(
        'post_title'    => 'New Post',
        'post_content'  => 'This is a new post created by the cron job.',
        'post_status'   => 'publish',
        'post_author'   => 1, // Change the author ID if needed
        'post_type'     => 'post',
    );

    // Insert the post into the database
    wp_insert_post( $new_post );
}

/**
 * Clear cron scedular.
 *
 * @return void
 */
function custom_deactivation() {
    wp_clear_scheduled_hook( 'custom_every_minute_event' );
}
register_deactivation_hook( __FILE__, 'custom_deactivation' );