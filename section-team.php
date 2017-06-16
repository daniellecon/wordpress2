<?php
$sovenco_team_id       = get_theme_mod( 'sovenco_team_id', esc_html__('team', 'sovenco') );
$sovenco_team_disable  = get_theme_mod( 'sovenco_team_disable' ) ==  1 ? true : false;
$sovenco_team_title    = get_theme_mod( 'sovenco_team_title', esc_html__('Our Team', 'sovenco' ));
$sovenco_team_subtitle = get_theme_mod( 'sovenco_team_subtitle', esc_html__('Section subtitle', 'sovenco' ));
$layout = intval( get_theme_mod( 'sovenco_team_layout', 3 ) );
if ( $layout <= 0 ){
    $layout = 3;
}
$user_ids = sovenco_get_section_team_data();
if ( sovenco_is_selective_refresh() ) {
    $sovenco_team_disable = false;
}
if ( ! empty( $user_ids ) ) {
    $desc = get_theme_mod( 'sovenco_team_desc' );
    ?>
    <?php if ( ! $sovenco_team_disable ) : ?>
        <?php if ( ! sovenco_is_selective_refresh() ){ ?>
        <section id="<?php if ($sovenco_team_id != '') echo $sovenco_team_id; ?>" <?php do_action('sovenco_section_atts', 'team'); ?>
                 class="<?php echo esc_attr(apply_filters('sovenco_section_class', 'section-team section-padding section-meta onepage-section', 'team')); ?>">
        <?php } ?>
            <?php do_action('sovenco_section_before_inner', 'team'); ?>
            <div class="container">
                <?php if ( $sovenco_team_title || $sovenco_team_subtitle || $desc ){ ?>
                <div class="section-title-area">
                    <?php if ($sovenco_team_subtitle != '') echo '<h5 class="section-subtitle">' . esc_html($sovenco_team_subtitle) . '</h5>'; ?>
                    <?php if ($sovenco_team_title != '') echo '<h2 class="section-title">' . esc_html($sovenco_team_title) . '</h2>'; ?>
                    <?php if ( $desc ) {
                        echo '<div class="section-desc">' . apply_filters( 'sovenco_the_content', wp_kses_post( $desc ) ) . '</div>';
                    } ?>
                </div>
                <?php } ?>
                <div class="team-members row team-layout-<?php echo intval( 12 / $layout  ); ?>">
                    <?php
                    if ( ! empty( $user_ids ) ) {
                        $n = 0;

                        foreach ( $user_ids as $member ) {
                            $member = wp_parse_args( $member, array(
                                'user_id'  =>array(),
                            ));

                            $link = isset( $member['link'] ) ?  $member['link'] : '';
                            $user_id = wp_parse_args( $member['user_id'],array(
                                'id' => '',
                             ) );

                            $image_attributes = wp_get_attachment_image_src( $user_id['id'], 'sovenco-small' );
                            $image_alt = get_post_meta( $user_id['id'], '_wp_attachment_image_alt', true);

                            if ( $image_attributes ) {
                                $image = $image_attributes[0];
                                $data = get_post( $user_id['id'] );
                                $n ++ ;
                                ?>
                                <div class="team-member wow slideInUp">
                                    <div class="member-thumb">
                                        <?php if ( $link ) { ?>
                                            <a href="<?php echo esc_url( $link ); ?>">
                                        <?php } ?>
                                        <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo $image_alt; ?>">
                                        <?php if ( $link ) { ?>
                                            </a>
                                        <?php } ?>
                                        <?php do_action( 'sovenco_section_team_member_media', $member ); ?>
                                    </div>
                                    <div class="member-info">
                                        <h5 class="member-name"><?php if ( $link ) { ?><a href="<?php echo esc_url( $link ); ?>"><?php } ?><?php echo esc_html( $data->post_title ); ?><?php if ( $link ) { ?></a><?php } ?></h5>
                                        <span class="member-position"><?php echo esc_html( $data->post_content ); ?></span>
                                    </div>
                                </div>
                                <?php
                            }

                        } // end foreach
                    }

                    ?>
                </div>
            </div>
            <?php do_action('sovenco_section_after_inner', 'team'); ?>
        <?php if ( ! sovenco_is_selective_refresh() ){ ?>
        </section>
        <?php } ?>
    <?php endif;
}
