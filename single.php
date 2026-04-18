<?php get_header(); ?>

<div class="container site-content">
    
    <main class="main-area">
        <?php 
        // 카카오 API 설정 불러오기 및 SDK 삽입
        $kakao_key = get_theme_mod('kakao_js_key'); 
        if(!empty($kakao_key)): 
        ?>
        <script src="https://t1.kakaocdn.net/kakao_js_sdk/2.7.2/kakao.min.js" crossorigin="anonymous"></script>
        <script>
            if (!Kakao.isInitialized()) {
                Kakao.init('<?php echo esc_js($kakao_key); ?>');
            }
        </script>
        <?php endif; ?>

        <?php
        // 현재 포스트의 ID를 가져와서 목록에서 현재 글을 하이라이트할 때 사용합니다.
        $current_post_id = get_queried_object_id();
        ?>

        <!-- 상단: 전체글 목록 (메인 페이지와 동일한 디자인) -->
        <div class="post-list-wrap">
            <div class="post-list-header">
                <h2 onclick="handleTitleClick(event)">
                    전체글 <span style="font-size:12px; font-weight:normal;">∨</span>
                </h2>
                
                <div class="list-options desktop-only">
                    <span id="post-list-toggle-btn" style="cursor:pointer;" onclick="togglePostList()">목록닫기 &#9650;</span>
                </div>
                
                <div class="list-options mobile-only" style="font-size:18px; color:#ccc;">
                    ⊞ 𝄘 ⊟
                </div>
            </div>
            
            <div id="post-list-content">
                <?php
                // 포스트 페이지 내에서 페이징을 위해 'page' 또는 'paged' 파라미터 체크
                $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );
                $setting_posts_per_page = get_option( 'posts_per_page' );
                
                $list_args = array( 
                    'posts_per_page' => $setting_posts_per_page, 
                    'paged' => $paged 
                );
                
                $list_query = new WP_Query( $list_args );
                if ( $list_query->have_posts() ) :
                    while ( $list_query->have_posts() ) : $list_query->the_post();
                ?>
                    <div class="post-list-item" <?php if(get_the_ID() == $current_post_id) echo 'style="background-color:#fafafa;"'; ?>>
                        <div class="post-list-item-main">
                            <a href="<?php the_permalink(); ?>" <?php if(get_the_ID() == $current_post_id) echo 'style="font-weight:800; color:#000;"'; ?>>
                                <?php the_title(); ?>
                            </a>
                            <div class="post-list-excerpt mobile-only">
                                <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                            </div>
                        </div>
                        <span class="post-list-date desktop-only"><?php echo get_the_date('Y. m. d.'); ?></span>
                        <div class="post-list-meta-mobile mobile-only">
                            <span><?php echo get_the_date('Y. m. d.'); ?></span>
                        </div>
                    </div>
                <?php
                    endwhile;
                    echo '<div class="post-list-pagination">';
                    // 개별 포스트 화면에서의 페이징 URL 처리
                    echo paginate_links( array(
                        'total' => $list_query->max_num_pages, 
                        'current' => $paged, 
                        'prev_text' => '이전', 
                        'next_text' => '다음',
                        'base' => @add_query_arg('page','%#%')
                    ) );
                    echo '</div>';
                    wp_reset_postdata();
                else:
                    echo '<div class="post-list-item" style="justify-content:center;">등록된 글이 없습니다.</div>';
                endif;
                ?>
            </div>
        </div>

        <script>
        function handleTitleClick(e) {
            if (window.innerWidth <= 768) {
                if (typeof toggleMobileMenu === 'function') {
                    toggleMobileMenu();
                }
            } else {
                togglePostList();
            }
        }

        function togglePostList() {
            var content = document.getElementById('post-list-content');
            var btn = document.getElementById('post-list-toggle-btn');
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                if(btn) btn.innerHTML = '목록닫기 &#9650;';
            } else {
                content.style.display = 'none';
                if(btn) btn.innerHTML = '목록열기 &#9660;';
            }
        }
        </script>


        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="post-header">
                    <span class="post-category">
                        <?php 
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) echo esc_html( $categories[0]->name );
                        ?>
                    </span>
                    <h1 class="post-title"><?php the_title(); ?></h1>
                    
                    <div class="post-meta">
                        <?php echo get_avatar( get_the_author_meta('ID'), 36 ); ?>
                        <span class="author-name"><?php the_author(); ?></span>
                        <span class="post-date"><?php echo get_the_date('Y. m. d. H:i'); ?></span>
                        
                        <div class="post-meta-right post-utilities-top">
                            <span id="copy-msg" style="display:none; font-size:11px; color:#2db400; margin-right:5px;">복사완료!</span>
                            
                            <!-- 프린트 버튼 -->
                            <button onclick="window.print()" class="util-btn" title="인쇄하기"><i class="fa-solid fa-print"></i></button>

                            <!-- URL 복사 -->
                            <button onclick="copyToClipboard('<?php the_permalink(); ?>', 'copy-msg')" class="util-btn" title="URL 복사"><i class="fa-solid fa-link"></i></button>
                            
                            <!-- 페이스북 -->
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-fb" title="페이스북 공유"><i class="fa-brands fa-facebook-f"></i></a>
                            
                            <!-- X (트위터) -->
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-tw" title="X 공유"><i class="fa-brands fa-x-twitter"></i></a>
                            
                            <!-- 인스타그램 -->
                            <button onclick="alert('인스타그램은 웹 공유를 지원하지 않습니다. 링크가 복사되었습니다.'); copyToClipboard('<?php the_permalink(); ?>', 'copy-msg');" class="util-btn share-ig" title="인스타그램 공유"><i class="fa-brands fa-instagram"></i></button>
                            
                            <!-- 핀터레스트 -->
                            <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-pin" title="핀터레스트 공유"><i class="fa-brands fa-pinterest-p"></i></a>
                            
                            <!-- 네이버 카페 -->
                            <a href="https://cafe.naver.com/cafe-shared/info?url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-nv" title="네이버 카페 공유"><b style="font-family:sans-serif; font-weight:900;">N</b></a>
                            
                            <!-- 카카오톡 -->
                            <?php if(!empty($kakao_key)): ?>
                            <button onclick="shareKakao()" class="util-btn share-ka" title="카카오톡 공유"><i class="fa-solid fa-comment"></i></button>
                            <script>
                            function shareKakao() {
                                Kakao.Share.sendDefault({
                                    objectType: 'text',
                                    text: '<?php echo esc_js(get_the_title()); ?>',
                                    link: {
                                        mobileWebUrl: '<?php echo esc_js(get_permalink()); ?>',
                                        webUrl: '<?php echo esc_js(get_permalink()); ?>',
                                    },
                                });
                            }
                            </script>
                            <?php else: ?>
                            <button onclick="alert('사용자 정의하기 > 공유버튼 API 설정에서 카카오 JS 앱 키를 등록해주세요.');" class="util-btn share-ka" title="카카오톡 공유"><i class="fa-solid fa-comment"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>

                <div class="post-content">
                    <?php the_content(); ?>
                </div>

                <script>
                function copyToClipboard(url, msgId) {
                    var dummy = document.createElement('input');
                    document.body.appendChild(dummy);
                    dummy.value = url;
                    dummy.select();
                    document.execCommand('copy');
                    document.body.removeChild(dummy);
                    var msg = document.getElementById(msgId || 'copy-msg');
                    if(msg) {
                        msg.style.display = 'inline-block';
                        setTimeout(function(){ msg.style.display = 'none'; }, 2000);
                    }
                }
                </script>

                <footer class="post-tags">
                    <?php 
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach($tags as $tag) {
                            echo '<a href="' . get_tag_link($tag->term_id) . '">#' . $tag->name . '</a>';
                        }
                    }
                    ?>
                </footer>

                <!-- 하단: 현재 카테고리의 다른 글 목록 (최근 5개) -->
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    $cat_id = $categories[0]->term_id;
                    $cat_name = $categories[0]->name;
                    
                    $related_query = new WP_Query( array(
                        'cat' => $cat_id,
                        'posts_per_page' => 5, // 하단 카테고리 글은 깔끔하게 5개로 고정
                    ) );
                    
                    if ( $related_query->have_posts() ) :
                ?>
                    <div class="post-list-wrap" style="margin-top: 50px; margin-bottom: 30px; border-top: 2px solid #333;">
                        <div class="post-list-header" style="border-bottom: 1px solid #eee; padding: 12px 5px;">
                            <h2 style="font-size: 15px; color: #333; font-weight: 700;">'<?php echo esc_html($cat_name); ?>' 카테고리의 다른 글</h2>
                        </div>
                        <div id="related-list-content">
                            <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                                <div class="post-list-item" style="padding: 12px 5px; <?php if(get_the_ID() == $current_post_id) echo 'background-color:#fafafa;'; ?>">
                                    <div class="post-list-item-main">
                                        <a href="<?php the_permalink(); ?>" <?php if(get_the_ID() == $current_post_id) echo 'style="font-weight:800; color:#000;"'; ?>>
                                            <?php the_title(); ?>
                                        </a>
                                    </div>
                                    <span class="post-list-date desktop-only"><?php echo get_the_date('Y. m. d.'); ?></span>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php 
                    wp_reset_postdata();
                    endif;
                }
                ?>

                <!-- 댓글 영역 (comments.php 로 위임) -->
                <?php
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>
                
            </article>
        <?php endwhile; endif; ?>

    </main>

    <!-- 우측 사이드바 불러오기 (sidebar.php) -->
    <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
