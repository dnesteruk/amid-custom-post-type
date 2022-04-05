<?php

defined( 'ABSPATH' ) || exit;

/**
 * Let's add additional data before the script, which is in the output queue.
 */
function assets() {

	wp_localize_script( 'amid-cpt-filter', 'amid_cpt', array(
		'nonce'    => wp_create_nonce( 'amid_cpt' ),
		'ajax_url' => admin_url( 'admin-ajax.php' )
	) );
}

add_action( 'wp_enqueue_scripts', 'assets', 100 );

/**
 * AJAX filter posts by taxonomy term
 */
function amid_cpt_filter_processor_callback() {

	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'amid_cpt' ) ) {
		die( __( 'Permission denied', 'amid-custom-post-type' ) );
	}

	/**
	 * Default response
	 */
	$response = [
		'status'  => 500,
		'message' => __( 'Something is wrong, please try again later ...' ),
		'content' => false,
		'found'   => 0
	];

	$all            = false;
	$terms          = $_POST['params']['terms'];
	$page           = intval( $_POST['params']['page'] );
	$posts_per_page = intval( $_POST['params']['qty'] );
	$names          = $_POST['params']['names'];
	$tax_query      = [];
	$message        = '';

	/**
	 * Check if term exists
	 */
	if ( ! is_array( $terms ) ) :
		$response = [
			'status'  => 501,
			'message' => 'Term doesn\'t exist',
			'content' => 0
		];

		die( json_encode( $response ) );
	else :

		foreach ( $terms as $tax => $slugs ) :

			if ( in_array( 'all-terms', $slugs ) ) {
				$all = true;
			}

			$tax_query[] = [
				'taxonomy' => $tax,
				'field'    => 'slug',
				'terms'    => $slugs,
				'names'    => $names
			];
		endforeach;
	endif;

	/**
	 * Setup query
	 */
	$args = [
		'paged'          => $page,
		'post_type'      => 'news',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
	];

	if ( $tax_query && ! $all ) :
		$args['tax_query'] = $tax_query;
	endif;

	$query = new WP_Query( $args );

	ob_start();
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) : $query->the_post(); ?>

            <article id="post-<?= $query->post->ID; ?>" <?php post_class(); ?>>

                <header class="amid-cpt-entry-header">
					<?php
					the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
					?>
                </header><!-- .entry-header -->

                <figure class="post-thumbnail">
                    <a class="post-thumbnail-inner" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
						<?php the_post_thumbnail( 'post-thumbnail' ); ?>
                    </a>
                </figure>

                <div class="amid-cpt-entry-content">
					<?php the_excerpt(); ?>
                </div><!-- .entry-content -->

                <footer class="amid-cpt-entry-footer">
					<?php $cpt_terms = get_the_terms( $query->post->ID, 'topic' );
					if ( $cpt_terms ) {
						$links = [];
						foreach ( $cpt_terms as $cpt_term ) {
							$links[] = '<span class="text-uppercase">' . $cpt_term->name . '</span>';
						}

						echo implode( ', ', $links );
					} else {
						echo '<span class="text-uppercase">' . __( 'Post subject not assigned', 'amid-custom-post-type' ) . '</span>';
					} ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->

		<?php endwhile;

		amid_pagination_ajax_pager( $query, $page );

		error_log( print_r( $names, true ) );

		foreach ( $tax_query as $tax ) :
			$message .= __( 'Topics selected: ', 'amid-custom-post-type' );
			foreach ( $tax['names'] as $name ) :
				$message .= $name . ', ';
			endforeach;

			$message = rtrim( $message, ', ' );
			$message .= __( '. Found news: ', 'amid-custom-post-type' ) . $query->found_posts;
		endforeach;
		$response = [
			'status'  => 200,
			'found'   => $query->found_posts,
			'message' => $message,
			'next'    => $page + 1
		];

	else :

		$response = [
			'status'  => 201,
			'message' => __( 'No posts found', 'amid-custom-post-type' ),
			'next'    => 0
		];

	endif;

	$response['content'] = ob_get_clean();

	die( json_encode( $response ) );
}

add_action( 'wp_ajax_amid_cpt_filter_query_posts', 'amid_cpt_filter_processor_callback' );
add_action( 'wp_ajax_nopriv_amid_cpt_filter_query_posts', 'amid_cpt_filter_processor_callback' );

/**
 * Shortocde for displaying terms filter and results on page
 */
function amid_cpt_filter_content( $atts ) {

	$attributes = shortcode_atts( array(
		'tax'      => 'topic', // Taxonomy
		'terms'    => false, // Get specific taxonomy terms only
		'active'   => false, // Set active term by ID
		'per_page' => 5, // How many posts per page,
	), $atts );

	$result = null;
	$terms  = get_terms( $attributes['tax'] );

	if ( count( $terms ) ) :
		ob_start(); ?>
        <div id="container-async" data-paged="<?= $attributes['per_page']; ?>" class="amid-cpt-container-filter">
            <ul class="wrap-term-filter">
				<?php foreach ( $terms as $term ) : ?>
                    <li<?php if ( $term->term_id == $attributes['active'] ) : ?> class="active"<?php endif; ?>>
                        <a href="<?= get_term_link( $term, $term->taxonomy ); ?>" data-filter="<?= $term->taxonomy; ?>"
                           data-term="<?= $term->slug; ?>" data-page="1">
							<?= $term->name; ?>
                        </a>
                    </li>
				<?php endforeach; ?>
                <li>
                    <a href="#" data-filter="<?= $terms[0]->taxonomy; ?>" data-term="all-terms" data-page="1"
                       class="amid-cpt-all-terms">
						<?= __( ' All news ', 'amid-custom-post-type' ) ?>
                    </a>
                </li>
            </ul>

            <div class="status"></div>
            <div class="content"></div>

        </div>

		<?php $result = ob_get_clean();
	endif;

	return $result;
}

add_shortcode( 'amid_ajax_filter_cpt', 'amid_cpt_filter_content' );


/**
 * Pagination
 */
function amid_pagination_ajax_pager( $query = null, $paged = 1 ) {

	if ( ! $query ) {
		return;
	}

	$paginate = paginate_links( [
		'base'      => '%_%',
		'type'      => 'array',
		'total'     => $query->max_num_pages,
		'format'    => '#page=%#%',
		'current'   => max( 1, $paged ),
		'prev_text' => '&xlarr;',
		'next_text' => '&xrarr;'
	] );

	if ( $query->max_num_pages > 1 ) : ?>
        <div class="wrap-page-numbers">
            <ul class="page-numbers">
				<?php foreach ( $paginate as $page ) :
					?>
                    <li><?php echo $page; ?></li>
				<?php endforeach; ?>
            </ul>
        </div>
	<?php endif;
}
