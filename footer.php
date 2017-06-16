<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package sovenco
 */
?>
    <footer id="colophon" class="site-footer" role="contentinfo">
        <?php
        $sovenco_btt_disable = get_theme_mod('sovenco_btt_disable');
        $sovenco_social_footer_title = get_theme_mod('sovenco_social_footer_title', esc_html__('Keep Updated', 'sovenco'));

        $sovenco_newsletter_disable = get_theme_mod('sovenco_newsletter_disable', '1');
        $sovenco_social_disable = get_theme_mod('sovenco_social_disable', '1');
        $sovenco_newsletter_title = get_theme_mod('sovenco_newsletter_title', esc_html__('Join our Newsletter', 'sovenco'));
        $sovenco_newsletter_mailchimp = get_theme_mod('sovenco_newsletter_mailchimp');

        if ( $sovenco_newsletter_disable != '1' || $sovenco_social_disable != '1' ) : ?>
            <div class="footer-connect">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-2"></div>
                        <?php if ($sovenco_newsletter_disable != '1') : ?>
                            <div class="col-sm-4">
                                <div class="footer-subscribe">
                                    <?php if ($sovenco_newsletter_title != '') echo '<h5 class="follow-heading">' . $sovenco_newsletter_title . '</h5>'; ?>
                                    <form novalidate="" target="_blank" class="" name="mc-embedded-subscribe-form" id="mc-embedded-subscribe-form" method="post"
                                          action="<?php if ($sovenco_newsletter_mailchimp != '') echo $sovenco_newsletter_mailchimp; ?>">
                                        <input type="text" placeholder="<?php esc_attr_e('Enter your e-mail address', 'sovenco'); ?>" id="mce-EMAIL" class="subs_input" name="EMAIL" value="">
                                        <input type="submit" class="subs-button" value="<?php esc_attr_e('Subscribe', 'sovenco'); ?>" name="subscribe">
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="<?php if ( $sovenco_newsletter_disable == '1' ) {
                            echo 'col-sm-8';
                        } else {
                            echo 'col-sm-4';
                        } ?>">
                            <?php
                            if ($sovenco_social_disable != '1') {
                                ?>
                                <div class="footer-social">
                                    <?php
                                    if ($sovenco_social_footer_title != '') echo '<h5 class="follow-heading">' . $sovenco_social_footer_title . '</h5>';

                                    $socials = sovenco_get_social_profiles();
                                    /**
                                     * New Socials profiles
                                     *
                                     * @since 1.1.4
                                     * @change 1.2.1
                                     */
                                    echo '<div class="footer-social-icons">';
                                    if ( $socials ) {
                                        echo $socials;
                                    } else {
                                        /**
                                         * Deprecated
                                         * @since 1.1.4
                                         */
                                        $twitter = get_theme_mod('sovenco_social_twitter');
                                        $facebook = get_theme_mod('sovenco_social_facebook');
                                        $google = get_theme_mod('sovenco_social_google');
                                        $instagram = get_theme_mod('sovenco_social_instagram');
                                        $rss = get_theme_mod('sovenco_social_rss');

                                        if ($twitter != '') echo '<a target="_blank" href="' . $twitter . '" title="Twitter"><i class="fa fa-twitter"></i></a>';
                                        if ($facebook != '') echo '<a target="_blank" href="' . $facebook . '" title="Facebook"><i class="fa fa-facebook"></i></a>';
                                        if ($google != '') echo '<a target="_blank" href="' . $google . '" title="Google Plus"><i class="fa fa-google-plus"></i></a>';
                                        if ($instagram != '') echo '<a target="_blank" href="' . $instagram . '" title="Instagram"><i class="fa fa-instagram"></i></a>';
                                        if ($rss != '') echo '<a target="_blank" href="' . $rss . '"><i class="fa fa-rss"></i></a>';
                                    }
                                    echo '</div>';
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-sm-2"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="container">
                <?php if ($sovenco_btt_disable != '1') : ?>
                    <div class="btt">
                        <a class="back-top-top" href="#page" title="<?php echo esc_html__('Back To Top', 'sovenco') ?>"><i class="fa fa-angle-double-up wow flash" data-wow-duration="2s"></i></a>
                    </div>
                <?php endif; ?>
                <?php
                /**
                 * hooked sovenco_footer_site_info
                 * @see sovenco_footer_site_info
                 */
                do_action('sovenco_footer_site_info');
                ?>
            </div>
        </div>
        <!-- .site-info -->

    </footer><!-- #colophon -->
<?php
/**
 * Hooked: sovenco_site_footer
 *
 * @see sovenco_site_footer
 */
do_action( 'sovenco_site_end' );
?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
