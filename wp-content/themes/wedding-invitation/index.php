<?php get_header(); ?>

<main class="min-h-screen bg-gradient-to-br from-rose-50 to-pink-100">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('container mx-auto px-4 py-8'); ?>>
                <div class="max-w-4xl mx-auto">
                    <header class="text-center mb-8" data-aos="fade-up">
                        <h1 class="text-4xl md:text-6xl font-serif text-rose-600 mb-4"><?php the_title(); ?></h1>
                    </header>
                    
                    <div class="content prose prose-lg mx-auto" data-aos="fade-up" data-aos-delay="200">
                        <?php the_content(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="container mx-auto px-4 py-8 text-center">
            <h1 class="text-4xl font-serif text-rose-600 mb-4">Welcome to Our Wedding</h1>
            <p class="text-lg text-gray-600">Content coming soon...</p>
        </div>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
