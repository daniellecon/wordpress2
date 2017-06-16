<?php
/**
 *Template Name: Frontpage
 *
 * @package sovenco
 */

get_header(); ?>

	<div id="content" class="site-content">
		<main id="main" class="site-main" role="main">
            <?php

            do_action( 'sovenco_frontpage_before_section_parts' );

			if ( ! has_action( 'sovenco_frontpage_section_parts' ) ) {

				$sections = apply_filters( 'sovenco_frontpage_sections_order', array(
                    'hero', 'features', 'about', 'services', 'videolightbox', 'gallery', 'counter', 'team',  'news', 'contact'
                ) );

				foreach ( $sections as $section ){
                    /**
                     * Hook before section
                     */
                    do_action('sovenco_before_section_'.$section );
                    do_action( 'sovenco_before_section_part', $section );

                    /**
                     * Load section template part
                     */
					get_template_part( 'section-parts/section', $section );

                    /**
                     * Hook after section
                     */
                    do_action('sovenco_after_section_part', $section );
                    do_action('sovenco_after_section_'.$section );
				}

			} else {
				do_action( 'sovenco_frontpage_section_parts' );
			}

            do_action( 'sovenco_frontpage_after_section_parts' );

			?>
		</main><!-- #main -->
	</div><!-- #content -->

<?php get_footer(); ?>
