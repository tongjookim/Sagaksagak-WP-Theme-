<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Font Awesome CDN 최신 버전 업데이트 (X 로고 완벽 지원) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- 모바일 전체화면 카테고리 메뉴 (오버레이) -->
<div id="mobileMenuOverlay" class="mobile-overlay-menu">
    <div class="mobile-menu-header">
        <span class="mobile-menu-close" onclick="toggleMobileMenu()">✕</span>
        <h2>카테고리</h2>
    </div>
    <div class="mobile-menu-content">
        <ul class="mobile-category-list">
            <li>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <span class="cat-name">전체글</span>
                    <span class="count"><?php echo intval( wp_count_posts()->publish ); ?></span>
                </a>
            </li>
            <?php
            $categories = get_categories( array( 'hide_empty' => 0 ) );
            foreach ( $categories as $category ) :
            ?>
            <li>
                <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>">
                    <span class="cat-name"><?php echo esc_html( $category->name ); ?></span>
                    <span class="count"><?php echo intval( $category->count ); ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<header class="site-header">
    <div class="container header-inner">
        <div class="header-left">
            <h1 class="header-logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <?php bloginfo( 'name' ); ?>
                </a>
            </h1>
            <div class="header-sub-menu desktop-only">
                <span class="divider">|</span>
                <?php
                if ( has_nav_menu( 'secondary' ) ) {
                    wp_nav_menu( array( 'theme_location' => 'secondary', 'container' => false, 'depth' => 1, 'menu_class' => 'sub-menu-list', 'fallback_cb' => false ) );
                } else {
                    echo '<ul class="sub-menu-list"><li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">메뉴를 추가해 주세요</a></li></ul>';
                }
                ?>
            </div>
        </div>
        <div class="mobile-menu-toggle" onclick="toggleMobileMenu()"><i class="fa-solid fa-bars"></i></div>
        <nav class="header-right header-nav desktop-only">
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'primary-menu-list', 'fallback_cb' => false ) );
            } else {
                echo '<ul class="primary-menu-list"><li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">메뉴를 추가해 주세요</a></li></ul>';
            }
            ?>
        </nav>
    </div>
</header>

<script>
function toggleMobileMenu() {
    var menu = document.getElementById('mobileMenuOverlay');
    menu.classList.toggle('active');
    if(menu.classList.contains('active')) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
}
</script>
