<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package hwcpa
 */

use function BopDesign\hwcpa\get_main_classes;
use function BopDesign\hwcpa\print_numeric_pagination;

get_header();

$allsearch = new WP_Query("s=$s&showposts=0");  ?>

	<main id="main" class="search-page <?php echo esc_attr( get_main_classes( [] ) ); ?>" role="main">
		<div class="container">
			<div class="breadcrumbs">
				<div class="breadcrump">
					<ul class="breadcrump-inner">
						<li>Search</li>
					</ul>
				</div>
			</div>
			<?php if ( have_posts() ) : ?>
				<header class="page-header text-center">
					<h1 class="page-title">
						<?php esc_html_e( 'Search Result For', THEME_TEXT_DOMAIN ); ?>
					</h1>
					<!-- <p class="search-results-title"><?php esc_html_e( get_search_query() ); ?></p> -->
					<label class="result-found"><?php echo $allsearch ->found_posts.' search results found.'; ?></label>
				</header><!-- .page-header -->

				<?php
				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'search' );
				endwhile;

				print_numeric_pagination();
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif;
			?>
		</div>
	</main><!-- #main -->

<?php
get_footer();
