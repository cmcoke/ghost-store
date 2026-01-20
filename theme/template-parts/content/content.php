<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package ghost
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <header class="entry-header">
    <?php
    if (is_sticky() && is_home() && ! is_paged()) {
      printf('<span">%s</span>', esc_html_x('Featured', 'post', 'ghost'));
    }
    if (is_singular()) :
      the_title('<h1 class="entry-title">', '</h1>');
    else :
      the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
    endif;
    ?>
  </header><!-- .entry-header -->

  <?php ghost_post_thumbnail(); ?>

  <div <?php ghost_content_class('entry-content'); ?>>
    <?php
    the_content();

    wp_link_pages(
      array(
        'before' => '<div>' . __('Pages:', 'ghost'),
        'after'  => '</div>',
      )
    );
    ?>
  </div><!-- .entry-content -->

  <footer class="entry-footer">
    <?php ghost_entry_footer(); ?>
  </footer><!-- .entry-footer -->

</article><!-- #post-${ID} -->