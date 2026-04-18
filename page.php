<?php get_header(); ?>

<div class="container site-content">

    <main class="main-area">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="post-header">
                    <h1 class="post-title"><?php the_title(); ?></h1>
                </header>

                <div class="post-content">
                    <?php the_content(); ?>
                </div>

            </article>
        <?php endwhile; endif; ?>
    </main>

    <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
