<?php
/**
 * Template Name: 프로필 페이지
 */
get_header(); ?>

<div class="container site-content">

    <main class="main-area">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="post-content">
                    <?php the_content(); ?>
                </div>

            </article>
        <?php endwhile; endif; ?>
    </main>

    <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
