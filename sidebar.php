<aside class="sidebar-area">
    <?php $header_image = get_header_image(); ?>
    
    <style>
    @media screen and (max-width: 768px) {
        .mobile-hero-bg {
            <?php if ( ! empty( $header_image ) ) : ?>
            background-image: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(140, 100, 70, 0.95) 100%), url('<?php echo esc_url($header_image); ?>');
            <?php else : ?>
            background-image: linear-gradient(to bottom, rgba(0,0,0,0.1) 0%, rgba(140, 100, 70, 0.95) 100%);
            background-color: #a06e50;
            <?php endif; ?>
            background-size: cover;
            background-position: center;
        }
        .mobile-hero-bg .profile-name,
        .mobile-hero-bg .profile-sns-id,
        .mobile-hero-bg .mobile-blog-title,
        .mobile-hero-bg .stats-today,
        .mobile-hero-bg .profile-bio {
            color: #ffffff !important;
        }
    }
    </style>

    <?php
    // 메인 관리자(Administrator) 계정을 자동으로 찾아 정보 가져오기
    $admin_users = get_users( array( 'role' => 'administrator', 'number' => 1 ) );
    $admin_user = !empty( $admin_users ) ? $admin_users[0] : null;

    $admin_id   = $admin_user ? $admin_user->ID : 1;
    $admin_name = $admin_user ? $admin_user->display_name : get_bloginfo('name');
    $admin_bio  = $admin_user ? get_user_meta( $admin_id, 'description', true ) : '';
    ?>

    <div class="profile-wrap mobile-hero-bg">
        <!-- 데스크탑 전용 커버 이미지 -->
        <?php if ( ! empty( $header_image ) ) : ?>
            <img src="<?php echo esc_url( $header_image ); ?>" class="profile-img desktop-only" alt="프로필 커버">
        <?php else : ?>
            <div class="profile-img placeholder desktop-only">
                <div>
                    <i class="fa-regular fa-image" style="font-size:24px; margin-bottom:8px; display:block;"></i>
                    외모 > 사용자 정의하기 > 헤더 이미지
                </div>
            </div>
        <?php endif; ?>

        <!-- 모바일 전용 상단 정보 -->
        <div class="mobile-only stats-today" style="font-size:12px; margin-bottom:10px; opacity: 0.8;">
            오늘 1 · 전체 <?php $stats = count_users(); echo number_format($stats['total_users'] * 150); ?>
        </div>
        <div class="mobile-only mobile-blog-title"><?php echo get_bloginfo('description'); ?></div>

        <!-- 프로필 사진, 관리자 이름, SNS 아이디 -->
        <div class="profile-info">
            <div class="profile-avatar">
                <?php echo get_avatar( $admin_id, 56 ); ?>
            </div>
            <div class="profile-details">
                <div class="profile-name"><?php echo esc_html( $admin_name ); ?></div>
                <div class="profile-sns-id desktop-only"><?php echo esc_html(get_theme_mod('sns_id', 'sns_id_here')); ?></div>
                <div class="profile-sns-id mobile-only"><?php $user_count = count_users(); echo $user_count['total_users']; ?>명의 이웃</div>
            </div>
            <!-- 모바일 공유 아이콘 -->
            <div class="mobile-only profile-share-btn" onclick="copyToClipboard('<?php echo home_url('/'); ?>', 'dummy')">
                <i class="fa-solid fa-share-nodes"></i>
            </div>
        </div>

        <!-- 자기소개(신상정보) 출력: 사용자 > 프로필 > 약력 정보 -->
        <?php if ( ! empty( $admin_bio ) ) : ?>
            <div class="profile-bio desktop-only">
                <?php echo nl2br( esc_html( $admin_bio ) ); ?>
            </div>
        <?php endif; ?>

        <!-- 모바일 액션 버튼 그리드 (5열 고정) -->
        <div class="mobile-only mobile-action-grid">
            <button class="grid-btn btn-home-edit" onclick="location.href='<?php echo admin_url('customize.php'); ?>'">
                <i class="fa-solid fa-gear"></i> 홈편집
            </button>
            <button class="grid-btn" onclick="toggleMobileMenu()"><i class="fa-solid fa-list-ul"></i></button>
            <button class="grid-btn"><i class="fa-regular fa-comment"></i></button>
            <button class="grid-btn"><i class="fa-solid fa-user-group"></i></button>
            <button class="grid-btn" onclick="location.href='<?php echo admin_url(); ?>'"><i class="fa-solid fa-chart-line"></i></button>
        </div>

        <!-- 커스텀 프로필 링크 및 배너 (데스크탑 전용) -->
        <?php
        $profile_page_id = absint( get_theme_mod( 'profile_link_page_id', 0 ) );
        $profile_url     = $profile_page_id ? get_permalink( $profile_page_id ) : '#';
        ?>
        <div class="profile-links desktop-only">
            <?php if ( $profile_page_id && current_user_can( 'edit_posts' ) ) : ?>
                <a href="<?php echo esc_url( get_edit_post_link( $profile_page_id ) ); ?>" class="edit-badge">EDIT</a>
            <?php endif; ?>
            <a href="<?php echo esc_url( $profile_url ); ?>" class="profile-more">
                <?php echo esc_html( get_theme_mod( 'profile_link_text', '프로필' ) ); ?> <i class="fa-solid fa-square-caret-right profile-icon"></i>
            </a>
        </div>
        <div class="profile-banners desktop-only">
            <button class="banner-btn" onclick="window.open('<?php echo esc_url(get_theme_mod('banner1_url', '#')); ?>', '_blank')">
                <i class="fa-regular fa-comment-dots"></i> <?php echo esc_html(get_theme_mod('banner1_name', '톡톡하기')); ?>
            </button>
            <button class="banner-btn" onclick="window.open('<?php echo esc_url(get_theme_mod('banner2_url', '#')); ?>', '_blank')">
                <i class="fa-solid fa-plus"></i> <?php echo esc_html(get_theme_mod('banner2_name', '이웃추가')); ?>
            </button>
        </div>
        <div class="profile-admin-links desktop-only">
            <a href="<?php echo admin_url('post-new.php'); ?>"><i class="fa-solid fa-pen"></i> 글쓰기</a>
            <a href="<?php echo admin_url(); ?>"><i class="fa-solid fa-gear"></i> 관리·통계</a>
        </div>
    </div>

    <?php
    // 커스터마이저 값 로드 (외모 > 사용자 정의하기 > 모바일 대화 배너)
    $cta_bg       = get_theme_mod( 'mobile_cta_bg_color', '#00c73c' );
    $cta_text     = get_theme_mod( 'mobile_cta_text',     '블로그 주인과 바로 대화해보세요' );
    $cta_btn_text = get_theme_mod( 'mobile_cta_btn_text', '톡톡하기' );
    $cta_btn_url  = get_theme_mod( 'mobile_cta_btn_url',  get_theme_mod( 'banner1_url', '#' ) );
    ?>
    <!-- 모바일 전용 하단 대화 배너 — 색상·텍스트·URL은 사용자 정의하기에서 편집 -->
    <div class="mobile-only mobile-green-banner"
         style="--cta-bg: <?php echo esc_attr( $cta_bg ); ?>;"
         onclick="window.open('<?php echo esc_url( $cta_btn_url ); ?>', '_blank')">
        <span class="text"><?php echo esc_html( $cta_text ); ?></span>
        <span class="btn">
            <i class="fa-regular fa-comment-dots"></i>
            <?php echo esc_html( $cta_btn_text ); ?>
        </span>
    </div>

    <div style="height:20px;" class="desktop-only"></div>

    <!-- 카테고리 위젯 (활동정보 위로 이동됨) -->
    <div class="widget desktop-only">
        <h3 class="widget-title">카테고리</h3>
        <ul><?php wp_list_categories( array('title_li' => '', 'show_count' => 1, 'hide_empty' => 0 ) ); ?></ul>
    </div>

    <!-- 활동정보 위젯 (데스크탑 전용) -->
    <div class="widget desktop-only">
        <h3 class="widget-title">활동정보</h3>
        <ul>
            <?php
            $count_posts = wp_count_posts();
            $count_comments = wp_count_comments();
            $user_count = count_users();
            ?>
            <li>전체 발행 글 <strong style="color:#000;"><?php echo $count_posts->publish; ?></strong> 개</li>
            <li>누적 댓글 수 <strong style="color:#000;"><?php echo $count_comments->approved; ?></strong> 개</li>
            <li>가입 이웃(회원) <strong style="color:#000;"><?php echo $user_count['total_users']; ?></strong> 명</li>
        </ul>
    </div>

    <!-- SNS 위젯 -->
    <?php
    $social_icons = array(
        'homepage' => array('icon' => '<i class="fa-solid fa-house"></i>'),
        'facebook' => array('icon' => '<i class="fa-brands fa-facebook-f"></i>'),
        'twitter'  => array('icon' => '<i class="fa-brands fa-x-twitter"></i>'),
        'instagram'=> array('icon' => '<i class="fa-brands fa-instagram"></i>'),
        'rss'      => array('icon' => '<i class="fa-solid fa-rss"></i>')
    );
    $has_social_link = false;
    foreach($social_icons as $id => $data) { if(get_theme_mod("social_link_{$id}")) { $has_social_link = true; break; } }
    if($has_social_link):
    ?>
    <div class="widget widget-social">
        <h3 class="widget-title desktop-only">외부 채널</h3>
        <div class="social-icons">
            <?php foreach($social_icons as $id => $data): 
                $url = get_theme_mod("social_link_{$id}");
                if($url): echo '<a href="'.esc_url($url).'" target="_blank" class="social-icon">'.$data['icon'].'</a>'; endif;
            endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

</aside>
