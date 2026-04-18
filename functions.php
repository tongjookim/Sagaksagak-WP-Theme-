<?php
/**
 * 사각사각 테마 필수 기능 및 설정
 * (커스터마이저, SEO 설정, 스크립트 로드, 채팅 UI 콜백 등 전체 포함)
 */

function sagaksagak_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );

    add_theme_support( 'custom-header', array(
        'width'       => 300,
        'height'      => 140,
        'flex-width'  => true,
        'flex-height' => true,
    ) );

    register_nav_menus( array(
        'primary'   => __( '2번: 우측 상단 메인 메뉴 (내 블로그 등)', 'sagaksagak' ),
        'secondary' => __( '1번: 로고 옆 서브 메뉴 (일상생각 등)', 'sagaksagak' ),
    ) );

    add_theme_support( 'block-templates' );
    add_theme_support( 'core-block-patterns' );

    // 구텐베르크 에디터 콘텐츠 영역에 profile.css 주입
    add_theme_support( 'editor-styles' );
    add_editor_style( 'pattern/profile.css' );
}
add_action( 'after_setup_theme', 'sagaksagak_setup' );

function sagaksagak_enqueue_scripts() {
    $theme_version   = filemtime( get_stylesheet_directory() . '/style.css' );
    $profile_css_ver = filemtime( get_stylesheet_directory() . '/pattern/profile.css' );

    wp_enqueue_style( 'sagaksagak-style',   get_stylesheet_uri(), array(), $theme_version );
    wp_enqueue_style( 'sagaksagak-profile', get_stylesheet_directory_uri() . '/pattern/profile.css', array(), $profile_css_ver );
}
add_action( 'wp_enqueue_scripts', 'sagaksagak_enqueue_scripts' );

/* =========================================================
   테마 커스터마이저 (SNS, API, 메타태그 등 설정)
========================================================= */
function sagaksagak_customize_register( $wp_customize ) {
    
    // 1. 프로필 및 위젯 배너 섹션
    $wp_customize->add_section( 'sagaksagak_profile_section', array(
        'title'       => __( '프로필 및 위젯 배너', 'sagaksagak' ),
        'priority'    => 20,
    ) );

    $wp_customize->add_setting( 'sns_id', array('default' => '') );
    $wp_customize->add_control( 'sns_id', array(
        'label'   => '노출할 SNS 아이디 (예: @tongjoo.kim)',
        'section' => 'sagaksagak_profile_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'profile_link_text', array('default' => '프로필') );
    $wp_customize->add_control( 'profile_link_text', array(
        'label'   => '프로필 링크 텍스트 (예: 프로필, 포트폴리오 등)',
        'section' => 'sagaksagak_profile_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'profile_link_page_id', array('default' => '') );
    $wp_customize->add_control( 'profile_link_page_id', array(
        'label'       => '프로필 페이지 ID (숫자 입력 시 EDIT 버튼 활성화)',
        'description' => '워드프레스 관리자 → 페이지 → 해당 페이지 ID 숫자를 입력하세요.',
        'section'     => 'sagaksagak_profile_section',
        'type'        => 'number',
        'input_attrs' => array( 'min' => 1, 'step' => 1, 'placeholder' => '예: 42' ),
    ) );

    $wp_customize->add_setting( 'banner1_name', array('default' => '톡톡하기') );
    $wp_customize->add_control( 'banner1_name', array(
        'label'   => '배너 1 이름',
        'section' => 'sagaksagak_profile_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'banner1_url', array('default' => '#') );
    $wp_customize->add_control( 'banner1_url', array(
        'label'   => '배너 1 링크 URL',
        'section' => 'sagaksagak_profile_section',
        'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'banner2_name', array('default' => '이웃추가') );
    $wp_customize->add_control( 'banner2_name', array(
        'label'   => '배너 2 이름',
        'section' => 'sagaksagak_profile_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'banner2_url', array('default' => '#') );
    $wp_customize->add_control( 'banner2_url', array(
        'label'   => '배너 2 링크 URL',
        'section' => 'sagaksagak_profile_section',
        'type'    => 'url',
    ) );

    // 2. 모바일 대화 배너 (톡톡하기 바)
    $wp_customize->add_section( 'sagaksagak_mobile_cta_section', array(
        'title'       => __( '모바일 대화 배너 (톡톡하기)', 'sagaksagak' ),
        'priority'    => 21,
        'description' => __( '모바일 화면 프로필 하단에 표시되는 대화 유도 배너를 설정합니다.', 'sagaksagak' ),
    ) );

    // 배너 배경색 (컬러 피커)
    $wp_customize->add_setting( 'mobile_cta_bg_color', array(
        'default'           => '#00c73c',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mobile_cta_bg_color', array(
        'label'   => __( '배너 배경색', 'sagaksagak' ),
        'section' => 'sagaksagak_mobile_cta_section',
    ) ) );

    // 안내 문구
    $wp_customize->add_setting( 'mobile_cta_text', array(
        'default'           => '블로그 주인과 바로 대화해보세요',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'mobile_cta_text', array(
        'label'       => __( '배너 안내 문구', 'sagaksagak' ),
        'section'     => 'sagaksagak_mobile_cta_section',
        'type'        => 'text',
    ) );

    // 버튼 텍스트
    $wp_customize->add_setting( 'mobile_cta_btn_text', array(
        'default'           => '톡톡하기',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'mobile_cta_btn_text', array(
        'label'   => __( '버튼 텍스트', 'sagaksagak' ),
        'section' => 'sagaksagak_mobile_cta_section',
        'type'    => 'text',
    ) );

    // 버튼 URL
    $wp_customize->add_setting( 'mobile_cta_btn_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'mobile_cta_btn_url', array(
        'label'   => __( '버튼 링크 URL', 'sagaksagak' ),
        'section' => 'sagaksagak_mobile_cta_section',
        'type'    => 'url',
    ) );

    // 3. SNS 및 외부 링크
    $wp_customize->add_section( 'sagaksagak_social_section', array(
        'title'       => __( 'SNS 및 외부 링크', 'sagaksagak' ),
        'priority'    => 25,
    ) );

    $social_networks = array(
        'homepage' => '홈페이지',
        'facebook' => '페이스북',
        'twitter'  => 'X (트위터)',
        'instagram'=> '인스타그램',
        'threads'  => '쓰레드(Threads)',
        'pinterest'=> '핀터레스트',
        'rss'      => 'RSS 피드'
    );

    foreach($social_networks as $id => $label) {
        $wp_customize->add_setting( "social_link_{$id}", array('default' => '') );
        $wp_customize->add_control( "social_link_{$id}", array(
            'label'   => "{$label} URL",
            'section' => 'sagaksagak_social_section',
            'type'    => 'url',
        ) );
    }

    // 3. 공유버튼 API 설정 섹션 (카카오 등)
    $wp_customize->add_section( 'sagaksagak_share_api_section', array(
        'title'       => __( '공유버튼 API 설정', 'sagaksagak' ),
        'priority'    => 26,
    ) );

    $wp_customize->add_setting( 'kakao_js_key', array('default' => '') );
    $wp_customize->add_control( 'kakao_js_key', array(
        'label'   => '카카오 JavaScript 앱 키',
        'section' => 'sagaksagak_share_api_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'naver_client_id', array('default' => '') );
    $wp_customize->add_control( 'naver_client_id', array(
        'label'   => '네이버 Client ID (선택사항)',
        'section' => 'sagaksagak_share_api_section',
        'type'    => 'text',
    ) );

    // 4. SEO 및 통계 스크립트
    $wp_customize->add_section( 'sagaksagak_seo_section', array(
        'title'       => __( 'SEO 및 통계 스크립트', 'sagaksagak' ),
        'priority'    => 30,
    ) );

    $wp_customize->add_setting( 'naver_site_verification' );
    $wp_customize->add_control( 'naver_site_verification', array(
        'label'   => '네이버 웹마스터 (content 속성값만 입력)',
        'section' => 'sagaksagak_seo_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'google_site_verification' );
    $wp_customize->add_control( 'google_site_verification', array(
        'label'   => '구글 서치콘솔 (content 속성값만 입력)',
        'section' => 'sagaksagak_seo_section',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'header_scripts' );
    $wp_customize->add_control( 'header_scripts', array(
        'label'   => '<head> 내부 삽입 코드',
        'section' => 'sagaksagak_seo_section',
        'type'    => 'textarea',
    ) );
    
    $wp_customize->add_setting( 'footer_scripts' );
    $wp_customize->add_control( 'footer_scripts', array(
        'label'   => '</body> 닫기 전 삽입 코드',
        'section' => 'sagaksagak_seo_section',
        'type'    => 'textarea',
    ) );
}
add_action( 'customize_register', 'sagaksagak_customize_register' );


// 커스터마이저 스크립트 및 메타 태그 출력
function sagaksagak_insert_header_codes() {
    $naver_meta = get_theme_mod( 'naver_site_verification' );
    if ( ! empty( $naver_meta ) ) echo '<meta name="naver-site-verification" content="' . esc_attr( $naver_meta ) . '" />' . "\n";
    $google_meta = get_theme_mod( 'google_site_verification' );
    if ( ! empty( $google_meta ) ) echo '<meta name="google-site-verification" content="' . esc_attr( $google_meta ) . '" />' . "\n";
    $header_scripts = get_theme_mod( 'header_scripts' );
    if ( ! empty( $header_scripts ) ) echo $header_scripts . "\n";
}
add_action( 'wp_head', 'sagaksagak_insert_header_codes' );

function sagaksagak_insert_footer_codes() {
    $footer_scripts = get_theme_mod( 'footer_scripts' );
    if ( ! empty( $footer_scripts ) ) echo $footer_scripts . "\n";
}
add_action( 'wp_footer', 'sagaksagak_insert_footer_codes' );


/* =========================================================
   [추가] 메신저(채팅) 스타일 댓글 UI 콜백 함수
========================================================= */
function sagaksagak_chat_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    
    // 현재 로그인한 사용자의 ID 확인
    $current_user_id = get_current_user_id();
    
    // 이 댓글을 작성한 사람과 현재 화면을 보고 있는 사람의 ID가 일치하면 '우측(내 댓글)', 아니면 '좌측(상대방)'
    $is_my_comment = ( $current_user_id && $comment->user_id == $current_user_id );
    $chat_class = $is_my_comment ? 'chat-right' : 'chat-left';
    ?>
    <li <?php comment_class( 'chat-item ' . $chat_class ); ?> id="comment-<?php comment_ID(); ?>">
        <div class="chat-wrap">
            <div class="chat-avatar">
                <?php echo get_avatar( $comment, 44 ); ?>
            </div>
            
            <div class="chat-content-wrap">
                <div class="chat-meta">
                    <span class="chat-name"><?php echo get_comment_author(); ?></span>
                    <span class="chat-date"><?php echo get_comment_date('Y. m. d. H:i'); ?></span>
                </div>
                
                <div class="chat-bubble">
                    <?php comment_text(); ?>
                </div>
                
                <div class="chat-reply">
                    <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                </div>
            </div>
        </div>
    <?php
    // 워드프레스 코어에서 </li> 태그를 자동으로 닫아주기 때문에 생략합니다.
}

/* =========================================================
   구텐베르크 블록 패턴 등록 — 프로필 페이지
========================================================= */
function sagaksagak_register_block_patterns() {
    if ( ! function_exists( 'register_block_pattern' ) ) return;

    register_block_pattern_category( 'sagaksagak', [
        'label' => '사각사각 테마',
    ] );

    ob_start();
    get_template_part( 'pattern/profile' );
    $html = ob_get_clean();

    register_block_pattern( 'sagaksagak/profile-page', [
        'title'       => '프로필 페이지',
        'description' => '히어로 카드 · Core Values · 경력/학력 · My Story · CTA 배너 포함 전체 프로필 레이아웃',
        'categories'  => [ 'sagaksagak' ],
        'keywords'    => [ '프로필', '소개', 'profile', 'about' ],
        'content'     => '<!-- wp:html -->' . "\n" . trim( $html ) . "\n" . '<!-- /wp:html -->',
    ] );
}
add_action( 'init', 'sagaksagak_register_block_patterns' );
