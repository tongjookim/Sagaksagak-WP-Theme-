<?php get_header(); ?>


<div class="container site-content">
    
    <!-- 좌측 메인 컨텐츠 영역 -->
    <main class="main-area">
        
        <!-- 네이버 스타일: 상단 글 목록 -->
        <div class="post-list-wrap">
            <div class="post-list-header">
                <!-- 제목 클릭 시 동작: 모바일에서는 메뉴 열기, PC에서는 목록 접기 -->
                <h2 onclick="handleTitleClick(event)">
                    <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    
                    // 워드프레스 [설정] > [읽기]의 '페이지당 보여줄 글 수' 가져오기
                    $setting_posts_per_page = get_option( 'posts_per_page' );
                    
                    $args = array( 
                        'posts_per_page' => $setting_posts_per_page, 
                        'paged' => $paged 
                    );
                    
                    if ( is_category() ) {
                        $cat_id = get_query_var('cat');
                        $args['cat'] = $cat_id;
                        $cat_info = get_category($cat_id);
                        echo esc_html($cat_info->name) . ' <span style="font-size:12px; font-weight:normal;">∨</span>';
                    } 
                    else {
                        echo '전체글 <span style="font-size:12px; font-weight:normal;">∨</span>';
                    }
                    ?>
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
                $list_query = new WP_Query( $args );
                if ( $list_query->have_posts() ) :
                    while ( $list_query->have_posts() ) : $list_query->the_post();
                ?>
                    <div class="post-list-item">
                        <div class="post-list-item-main">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            <div class="post-list-excerpt mobile-only">
                                <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
                            </div>
                        </div>
                        <span class="post-list-date desktop-only"><?php echo get_the_date('Y. m. d.'); ?></span>
                        <div class="post-list-meta-mobile mobile-only">
                            <span><?php echo get_the_date('Y. m. d.'); ?></span>
                            <span>조회수 표시생략</span>
                        </div>
                    </div>
                <?php
                    endwhile;
                    echo '<div class="post-list-pagination">';
                    echo paginate_links( array('total' => $list_query->max_num_pages, 'current' => $paged, 'prev_text' => '이전', 'next_text' => '다음' ) );
                    echo '</div>';
                    wp_reset_postdata();
                else:
                    echo '<div class="post-list-item" style="justify-content:center;">등록된 글이 없습니다.</div>';
                endif;
                ?>
            </div>
        </div>

        <script>
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

        <!-- 하단: 워드프레스 설정 값에 지정된 개수만큼 본문 출력 -->
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

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="margin-bottom: 80px;">
                <header class="post-header">
                    <span class="post-category">
                        <?php 
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) echo esc_html( $categories[0]->name );
                        ?>
                    </span>
                    <h1 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                    <div class="post-meta">
                        <?php echo get_avatar( get_the_author_meta('ID'), 36 ); ?>
                        <span class="author-name"><?php the_author(); ?></span>
                        <span class="post-date"><?php echo get_the_date('Y. m. d. H:i'); ?></span>
                        
                        <div class="post-meta-right post-utilities-top" style="position: relative;">
                            <span id="copy-msg-<?php the_ID(); ?>" style="display:none; font-size:11px; color:#2db400; margin-right:5px;">복사완료!</span>
                            
                            <!-- 항상 노출: URL 복사 -->
                            <button onclick="copyToClipboard('<?php the_permalink(); ?>', 'copy-msg-<?php the_ID(); ?>')" class="util-btn" title="URL 복사"><i class="fa-solid fa-link"></i></button>
                            
                            <!-- 항상 노출: 카카오톡 (가장 많이 쓰이므로 밖으로 배치) -->
                            <?php if(!empty($kakao_key)): ?>
                            <button onclick="shareKakao<?php the_ID(); ?>()" class="util-btn share-ka" title="카카오톡 공유"><i class="fa-solid fa-comment"></i></button>
                            <script>
                            function shareKakao<?php the_ID(); ?>() {
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

                            <!-- 더보기 버튼 (버튼 간소화) -->
                            <button onclick="toggleExtraSNS('sns-extra-<?php the_ID(); ?>')" class="util-btn" title="더보기"><i class="fa-solid fa-ellipsis"></i></button>

                            <!-- 숨겨진 추가 SNS 버튼 레이어 -->
                            <div id="sns-extra-<?php the_ID(); ?>" class="sns-extra-layer">
                                <!-- 페이스북 -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-fb" title="페이스북 공유"><i class="fa-brands fa-facebook-f"></i></a>
                                
                                <!-- X (트위터) -->
                                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-tw" title="X 공유"><i class="fa-brands fa-x-twitter"></i></a>
                                
                                <!-- 인스타그램 -->
                                <button onclick="alert('인스타그램은 웹 공유를 지원하지 않습니다. 링크가 복사되었습니다.'); copyToClipboard('<?php the_permalink(); ?>', 'copy-msg-<?php the_ID(); ?>');" class="util-btn share-ig" title="인스타그램 공유"><i class="fa-brands fa-instagram"></i></button>
                                
                                <!-- 핀터레스트 -->
                                <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink()); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-pin" title="핀터레스트 공유"><i class="fa-brands fa-pinterest-p"></i></a>
                                
                                <!-- 네이버 카페 -->
                                <a href="https://cafe.naver.com/cafe-shared/info?url=<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-nv" title="네이버 카페 공유"><b style="font-family:sans-serif; font-weight:900;">N</b></a>
                            </div>
                        </div>
                    </div>
                </header>
                <div class="post-content"><?php the_content(); ?></div>
                <footer class="post-tags">
                    <?php 
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach($tags as $tag) { echo '<a href="' . get_tag_link($tag->term_id) . '">#' . $tag->name . '</a>'; }
                    }
                    ?>
                </footer>
            </article>
        <?php endwhile; endif; ?>

        <script>
        function copyToClipboard(url, msgId) {
            var dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = url;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);
            if(msgId && document.getElementById(msgId)){
                var msg = document.getElementById(msgId);
                msg.style.display = 'inline-block';
                setTimeout(function(){ msg.style.display = 'none'; }, 2000);
            }
        }

        // 추가 SNS 공유 버튼 토글 로직
        function toggleExtraSNS(layerId) {
            var layer = document.getElementById(layerId);
            if(layer.classList.contains('show')) {
                layer.classList.remove('show');
            } else {
                // 다른 열려있는 레이어 닫기
                var allLayers = document.querySelectorAll('.sns-extra-layer');
                allLayers.forEach(function(el) { el.classList.remove('show'); });
                layer.classList.add('show');
            }
        }

        // 바탕 클릭 시 공유 레이어 자동으로 닫기
        document.addEventListener('click', function(event) {
            var isClickInside = event.target.closest('.post-utilities-top');
            if (!isClickInside) {
                var allLayers = document.querySelectorAll('.sns-extra-layer');
                allLayers.forEach(function(el) { el.classList.remove('show'); });
            }
        });
        </script>
    </main>

    <?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>
