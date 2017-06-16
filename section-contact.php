<?php
$sovenco_contact_id            = get_theme_mod( 'sovenco_contact_id', esc_html__('contact', 'sovenco') );
$sovenco_contact_disable       = get_theme_mod( 'sovenco_contact_disable' ) == 1 ?  true : false;
$sovenco_contact_title         = get_theme_mod( 'sovenco_contact_title', esc_html__('Get in touch', 'sovenco' ));
$sovenco_contact_subtitle      = get_theme_mod( 'sovenco_contact_subtitle', esc_html__('Section subtitle', 'sovenco' ));
$sovenco_contact_cf7           = get_theme_mod( 'sovenco_contact_cf7' );
$sovenco_contact_cf7_disable   = get_theme_mod( 'sovenco_contact_cf7_disable' );
$sovenco_contact_text          = get_theme_mod( 'sovenco_contact_text' );
$sovenco_contact_address_title = get_theme_mod( 'sovenco_contact_address_title' );
$sovenco_contact_address       = get_theme_mod( 'sovenco_contact_address' );
$sovenco_contact_phone         = get_theme_mod( 'sovenco_contact_phone' );
$sovenco_contact_email         = get_theme_mod( 'sovenco_contact_email' );
$sovenco_contact_fax           = get_theme_mod( 'sovenco_contact_fax' );

if ( sovenco_is_selective_refresh() ) {
    $sovenco_contact_disable = false;
}

if ( $sovenco_contact_cf7 || $sovenco_contact_text || $sovenco_contact_address_title || $sovenco_contact_phone || $sovenco_contact_email || $sovenco_contact_fax ) {
    $desc = get_theme_mod( 'sovenco_contact_desc' );
    ?>
    <?php if (!$sovenco_contact_disable) : ?>
        <?php if ( ! sovenco_is_selective_refresh() ){ ?>
        <section id="<?php if ($sovenco_contact_id != '') echo $sovenco_contact_id; ?>" <?php do_action('sovenco_section_atts', 'counter'); ?>
                 class="<?php echo esc_attr(apply_filters('sovenco_section_class', 'section-contact section-padding  section-meta onepage-section', 'contact')); ?>">
        <?php } ?>
            <?php do_action('sovenco_section_before_inner', 'contact'); ?>
            <div class="container">
                <?php if ( $sovenco_contact_title || $sovenco_contact_subtitle || $desc ){ ?>
                <div class="section-title-area">
                    <?php if ($sovenco_contact_subtitle != '') echo '<h5 class="section-subtitle">' . esc_html($sovenco_contact_subtitle) . '</h5>'; ?>
                    <?php if ($sovenco_contact_title != '') echo '<h2 class="section-title">' . esc_html($sovenco_contact_title) . '</h2>'; ?>
                    <?php if ( $desc ) {
                        echo '<div class="section-desc">' . apply_filters( 'sovenco_the_content', wp_kses_post( $desc ) ) . '</div>';
                    } ?>
                </div>
                <?php } ?>
                <div class="row">
                    <?php if ($sovenco_contact_cf7_disable != '1') : ?>
                        <?php if (isset($sovenco_contact_cf7) && $sovenco_contact_cf7 != '') { ?>
                            <div class="contact-form col-sm-6 wow slideInUp">
                                <?php echo do_shortcode(wp_kses_post($sovenco_contact_cf7)); ?>
                            </div>
                        <?php } else { ?>
                            <div class="contact-form col-sm-6 wow slideInUp">
                                <br>
                                <small>
                                    <i><?php printf(esc_html__('You can install %1$s plugin and go to Customizer &rarr; Section: Contact &rarr; Section Content to show a working contact form here.', 'sovenco'), '<a href="' . esc_url('https://wordpress.org/plugins/contact-form-7/', 'sovenco') . '" target="_blank">Contact Form 7</a>'); ?></i>
                                </small>
                            </div>
                        <?php } ?>
                    <?php endif; ?>

                    <div class="col-sm-6 wow slideInUp">
                        <br>
                        <?php
                        if ($sovenco_contact_text != '') {
                            echo apply_filters( 'sovenco_the_content', wp_kses_post( $sovenco_contact_text ) );
                        }
                        ?>
                        <br><br>
                        <div class="address-box">

                            <h3><?php if ($sovenco_contact_address_title != '') echo wp_kses_post($sovenco_contact_address_title); ?></h3>

                            <?php if ($sovenco_contact_address != ''): ?>
                                <div class="address-contact">
                                    <span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-map-marker fa-stack-1x fa-inverse"></i></span>

                                    <div class="address-content"><?php echo wp_kses_post($sovenco_contact_address); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($sovenco_contact_phone != ''): ?>
                                <div class="address-contact">
                                    <span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-phone fa-stack-1x fa-inverse"></i></span>
                                    <div class="address-content"><?php echo wp_kses_post($sovenco_contact_phone); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($sovenco_contact_email != ''): ?>
                                <div class="address-contact">
                                    <span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-envelope-o fa-stack-1x fa-inverse"></i></span>
                                    <div class="address-content"><a href="mailto:<?php echo antispambot($sovenco_contact_email); ?>"><?php echo antispambot($sovenco_contact_email); ?></a></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($sovenco_contact_fax != ''): ?>
                                <div class="address-contact">
                                    <span class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-fax fa-stack-1x fa-inverse"></i></span>

                                    <div class="address-content"><?php echo wp_kses_post($sovenco_contact_fax); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php do_action('sovenco_section_after_inner', 'contact'); ?>
        <?php if ( ! sovenco_is_selective_refresh() ){ ?>
        </section>
        <?php } ?>
    <?php endif;
}
