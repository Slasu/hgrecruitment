<?php
get_header();

?>

<section class="section--home__welcome">
    <h1 class="page--title"><?php echo get_field('pageTitle');?></h1>
    <p class="page--desc"><?php echo get_field('pageDesc');?></p>
</section>

<section class="section--home__quiz">
    <div class="home--quiz__holder">
        <?php
        $quizId = get_field('pageQuiz');
        echo do_shortcode('[hgquiz id="'.$quizId.'"]');
        ?>
    </div>
</section>

<?php
get_footer();
?>