# 사각사각(SagakSagak) 테마 - CLI 작업 명세서 및 전체 소스 코드

이 문서는 워드프레스 '사각사각(SagakSagak)' 테마의 현재까지 진행된 모든 작업 내역과 100% 완전한 소스 코드를 포함하고 있습니다. CLI 환경이나 AI 코딩 어시스턴트에서 컨텍스트를 파악하고 프로젝트를 이어서 작업하기 위한 기준 문서(Master Document)로 사용됩니다.

## 📌 주요 구현 기능 (현재 상태)

- **모바일 5열 그리드 버튼**: 모바일 환경에서 프로필 하단에 5개의 액션 버튼(홈편집, 메뉴, 톡톡, 이웃, 통계)이 Glassmorphism 스타일로 겹침 없이 한 줄로 고정 출력됨.
- **채팅형 댓글 UI (Messenger Style)**: comments.php와 커스텀 콜백 함수(sagaksagak_chat_comment)를 통해 내 댓글은 우측(초록색), 상대방 댓글은 좌측(회색) 말풍선으로 렌더링.
- **SNS 공유 더보기 레이어**: index.php, single.php에 URL 복사와 카카오톡만 꺼내두고, 나머지 공유 버튼(페이스북, X, 핀터레스트, 네이버카페, 인스타그램)은 '더보기(⋯)' 버튼 클릭 시 말풍선 레이어로 나타나도록 최적화. (Font Awesome 6.5.2 적용하여 X 로고 정상 출력)
- **프로필 커스터마이저 연동**: 관리자 [외모 > 사용자 정의하기]에서 프로필 링크, 배너, SNS URL, 카카오/네이버 API 키를 직접 설정 가능.
- **관리자(Administrator) 정보 자동 매핑**: 사용자 ID가 1이 아니더라도, 관리자 권한을 가진 유저의 '공개 이름'과 '약력(자기소개)'을 sidebar.php에 자동 출력.
- **동적 포스트 출력 및 페이징**: 워드프레스 [설정 > 읽기]의 '페이지당 보여줄 글 수' 설정값을 반영하여 목록과 본문을 출력. PC 환경에서는 사각형 형태의 번호 페이징 적용.
- **싱글 포스트 컨텍스트**: 개별 글 보기(single.php) 화면 상단에는 전체 글 목록을, 하단에는 현재 카테고리의 관련 글 5개를 출력.

## 📁 디렉토리 구조

wp-content/themes/sagaksagak/
├── style.css           # 메인 스타일시트 (코어 리셋, 반응형, 채팅 UI, 그리드 등)
├── functions.php       # 테마 기능 (커스터마이저, SEO, 스크립트, 채팅 콜백 함수)
├── sidebar.php         # 사이드바 (프로필 히어로, 모바일 5열 버튼, 위젯)
├── index.php           # 메인 템플릿 (목록 + 본문 반복, 공유 기능)
├── single.php          # 개별 글 템플릿 (상단 목록, 본문, 하단 관련글, 공유, 댓글)
├── comments.php        # 채팅 스타일 댓글 래퍼 템플릿
├── header.php          # 상단 영역 및 모바일 오버레이 메뉴
├── footer.php          # 하단 영역
└── theme.json          # FSE 및 블록 설정

## 💻 전체 소스 코드 (100% 완전본, 생략 없음)

### 1. style.css

/*
Theme Name: 사각사각 테마
Theme URI: #
Author: Gemini
Description: 워드프레스 코어 필수 스타일 전체 복구 + 모바일 5열 그리드, 채팅형 댓글, SNS 더보기 완벽 통합 버전. (압축 해제본)
Version: 3.1 Final
Text Domain: sagaksagak
*/

@import url('[https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css](https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/static/pretendard.css)');

/* ==========================================================================
   1. 기본 초기화 (Reset & Normalize)
========================================================================== */
*, 
*::before, 
*::after {
    box-sizing: border-box;
}

html {
    line-height: 1.15;
    -webkit-text-size-adjust: 100%;
}

body {
    margin: 0;
    padding: 0;
    font-family: 'Pretendard', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    font-size: 15px;
    line-height: 1.6;
    color: #333;
    background-color: #fff;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    word-wrap: break-word;
    word-break: keep-all;
}

main { display: block; }
h1, h2, h3, h4, h5, h6 { margin-top: 0; margin-bottom: 0.5rem; font-weight: 700; color: #111; line-height: 1.3; }
p { margin-top: 0; margin-bottom: 1rem; }
ul, ol { margin-top: 0; margin-bottom: 1rem; padding-left: 2rem; }
a { color: inherit; text-decoration: none; transition: all 0.2s ease-in-out; background-color: transparent; }
a:hover { text-decoration: none; }
img { border-style: none; max-width: 100%; height: auto; display: block; }
button, input, optgroup, select, textarea { font-family: inherit; font-size: 100%; line-height: 1.15; margin: 0; }
button, input { overflow: visible; }
button, select { text-transform: none; }
button, [type="button"], [type="reset"], [type="submit"] { -webkit-appearance: button; cursor: pointer; }
textarea { overflow: auto; resize: vertical; }
figure { margin: 0 0 1rem; }

.container { max-width: 1080px; margin: 0 auto; padding: 0 20px; width: 100%; }

.mobile-only { display: none !important; }
.desktop-only { display: block; }

/* ==========================================================================
   2. 워드프레스 코어 필수 클래스
========================================================================== */
.alignnone { margin: 5px 20px 20px 0; }
.aligncenter, div.aligncenter { display: block; margin: 5px auto 5px auto; text-align: center; }
.alignright { float: right; margin: 5px 0 20px 20px; }
.alignleft { float: left; margin: 5px 20px 20px 0; }
a img.alignright { float: right; margin: 5px 0 20px 20px; }
a img.alignnone { margin: 5px 20px 20px 0; }
a img.alignleft { float: left; margin: 5px 20px 20px 0; }
a img.aligncenter { display: block; margin-left: auto; margin-right: auto; }

.wp-caption { background: #fff; border: 1px solid #f0f0f0; max-width: 96%; padding: 5px 3px 10px; text-align: center; margin-bottom: 20px; }
.wp-caption.alignnone { margin: 5px 20px 20px 0; }
.wp-caption.alignleft { margin: 5px 20px 20px 0; }
.wp-caption.alignright { margin: 5px 0 20px 20px; }
.wp-caption img { border: 0 none; height: auto; margin: 0; max-width: 98.5%; padding: 0; width: auto; }
.wp-caption p.wp-caption-text { font-size: 13px; line-height: 1.5; margin: 0; padding: 10px 0 5px; color: #777; }

.screen-reader-text { border: 0; clip: rect(1px, 1px, 1px, 1px); clip-path: inset(50%); height: 1px; margin: -1px; overflow: hidden; padding: 0; position: absolute !important; width: 1px; word-wrap: normal !important; }
.clear:before, .clear:after { content: ""; display: table; table-layout: fixed; }
.clear:after { clear: both; }

.gallery { margin-bottom: 1.5em; display: flex; flex-wrap: wrap; gap: 10px; }
.gallery-item { display: inline-block; text-align: center; width: 100%; }
.gallery-caption { display: block; font-size: 13px; color: #777; margin-top: 5px; }

/* 관리자 바 대응 */
.admin-bar .site-header { top: 32px; }
.admin-bar .mobile-overlay-menu { top: 32px; height: calc(100% - 32px); }
@media screen and (max-width: 782px) {
    .admin-bar .site-header { top: 46px; }
    .admin-bar .mobile-overlay-menu { top: 46px; height: calc(100% - 46px); }
}

/* ==========================================================================
   3. 헤더 영역 (PC 및 모바일 메뉴)
========================================================================== */
.site-header { padding: 15px 0; font-size: 14px; position: relative; z-index: 100; background: #fff; border-bottom: 1px solid #eee; }
.header-inner { display: flex; justify-content: space-between; align-items: center; }
.header-left { display: flex; align-items: center; }
.header-logo { font-weight: 800; font-size: 18px; color: #000; letter-spacing: -0.5px; }
.header-sub-menu { display: flex; align-items: center; margin-left: 15px; color: #888; font-size: 14px; }
.header-sub-menu .divider { margin-right: 10px; color: #ddd; }
.header-sub-menu ul { list-style: none; display: flex; gap: 15px; margin: 0; padding: 0; }
.header-sub-menu ul li a:hover { color: #111; text-decoration: underline; }

.header-nav ul { list-style: none; display: flex; gap: 20px; color: #555; font-size: 14px; font-weight: 500; margin: 0; padding: 0; }
.header-nav ul li a:hover { color: #00c73c; }
.mobile-menu-toggle { display: none; font-size: 24px; cursor: pointer; color: #333; }

/* 모바일 오버레이 메뉴 */
.mobile-overlay-menu { position: fixed; top: 0; right: -100%; width: 100%; height: 100%; background: #fff; z-index: 9999; transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; }
.mobile-overlay-menu.active { right: 0; box-shadow: -5px 0 15px rgba(0,0,0,0.1); }
.mobile-menu-header { padding: 20px; border-bottom: 1px solid #f0f0f0; text-align: center; position: relative; background: #fafafa; }
.mobile-menu-header h2 { font-size: 16px; margin: 0; }
.mobile-menu-close { position: absolute; left: 20px; font-size: 24px; cursor: pointer; color: #333; top: 50%; transform: translateY(-50%); }
.mobile-category-list { list-style: none; overflow-y: auto; padding: 0; margin: 0; }
.mobile-category-list li { border-bottom: 1px solid #f8f9fa; }
.mobile-category-list li a { display: flex; justify-content: space-between; padding: 18px 20px; font-size: 15px; color: #333; width: 100%; }
.mobile-category-list li a:hover { background: #fdfdfd; }
.mobile-category-list .count { font-weight: 700; color: #00c73c; }

/* ==========================================================================
   4. 메인 레이아웃 및 전체 구조
========================================================================== */
.site-content { display: flex; margin-top: 40px; gap: 40px; margin-bottom: 60px; }
.main-area { width: 75%; }
.sidebar-area { width: 25%; }

/* ==========================================================================
   5. 글 목록 (리스트) 및 PC 페이징
========================================================================== */
.post-list-wrap { border-top: 2px solid #222; border-bottom: 1px solid #eee; margin-bottom: 50px; background: #fff; }
.post-list-header { display: flex; justify-content: space-between; padding: 15px 5px; border-bottom: 1px solid #eee; font-size: 14px; color: #555; align-items: center; }
.post-list-header h2 { margin: 0; font-size: 16px; font-weight: 700; color: #111; cursor: pointer; display: flex; align-items: center; gap: 5px; }
.list-options { display: flex; align-items: center; gap: 8px; font-size: 13px; }

.post-list-item { display: flex; justify-content: space-between; padding: 18px 5px; border-bottom: 1px dashed #eee; font-size: 15px; align-items: center; }
.post-list-item:last-child { border-bottom: none; }
.post-list-item-main { flex: 1; }
.post-list-item a { font-weight: 600; color: #222; letter-spacing: -0.3px; }
.post-list-item a:hover { color: #00c73c; text-decoration: underline; }
.post-list-date { color: #999; font-size: 13px; white-space: nowrap; margin-left: 20px; }
.post-list-excerpt { display: none; color: #777; font-size: 14px; margin-top: 8px; line-height: 1.5; }

/* PC 번호 페이징 디자인 */
.post-list-pagination { display: flex; justify-content: center; gap: 6px; margin: 40px 0; }
.post-list-pagination .page-numbers { display: flex; align-items: center; justify-content: center; min-width: 36px; height: 36px; padding: 0 10px; border: 1px solid #e0e0e0; color: #555; background: #fff; font-size: 14px; font-weight: 500; border-radius: 4px; transition: all 0.2s; }
.post-list-pagination a.page-numbers:hover { border-color: #333; color: #333; background: #fafafa; }
.post-list-pagination .page-numbers.current { border-color: #00c73c; background: #00c73c; color: #fff; font-weight: 700; }

/* ==========================================================================
   6. 개별 포스트 본문 디자인
========================================================================== */
.post-category { color: #00c73c; font-size: 14px; font-weight: 600; margin-bottom: 10px; display: inline-block; }
.post-title { font-size: 32px; font-weight: 800; margin: 0 0 20px 0; line-height: 1.35; letter-spacing: -0.5px; color: #111; }
.post-meta { display: flex; align-items: center; gap: 12px; color: #666; font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 25px; margin-bottom: 40px; }
.post-meta img.avatar { border-radius: 50%; width: 40px; height: 40px; object-fit: cover; }
.author-name { font-weight: 700; color: #222; }
.post-date { color: #999; }

.post-content { font-size: 16px; line-height: 1.8; min-height: 300px; color: #333; }
.post-content p { margin-bottom: 1.5em; }
.post-content h2, .post-content h3 { margin-top: 1.5em; margin-bottom: 0.8em; }
.post-content blockquote { border-left: 4px solid #00c73c; padding-left: 15px; margin-left: 0; font-style: italic; color: #555; background: #f9f9f9; padding: 15px; }

.post-tags { margin-top: 50px; padding-top: 20px; display: flex; flex-wrap: wrap; gap: 8px; }
.post-tags a { display: inline-block; background: #f1f3f5; padding: 8px 16px; border-radius: 20px; font-size: 13px; color: #495057; font-weight: 500; }
.post-tags a:hover { background: #e9ecef; color: #212529; }

/* ==========================================================================
   7. SNS 공유 버튼 및 더보기 레이어 (Glassmorphism)
========================================================================== */
.post-utilities-top { display: flex; align-items: center; gap: 6px; position: relative; margin-left: auto; }
.util-btn { display: inline-flex; justify-content: center; align-items: center; width: 36px; height: 36px; background: #fff; border: 1px solid #e0e0e0; border-radius: 50%; color: #555; font-size: 15px; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
.util-btn:hover { background: #f8f9fa; border-color: #ccc; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.05); }

.util-btn.share-fb:hover { background: #1877F2; color: #fff; border-color: #1877F2; }
.util-btn.share-tw:hover { background: #000000; color: #fff; border-color: #000000; }
.util-btn.share-ig:hover { background: #E4405F; color: #fff; border-color: #E4405F; }
.util-btn.share-pin:hover { background: #E60023; color: #fff; border-color: #E60023; }
.util-btn.share-nv:hover { background: #03C75A; color: #fff; border-color: #03C75A; }
.util-btn.share-ka:hover { background: #FEE500; color: #3C1E1E; border-color: #FEE500; }

.sns-extra-layer { 
    display: none !important; 
    position: absolute; 
    top: 45px; 
    right: 0; 
    background: rgba(255, 255, 255, 0.95); 
    padding: 10px 15px; 
    border: 1px solid rgba(0,0,0,0.08); 
    border-radius: 12px; 
    box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
    gap: 8px; 
    z-index: 999; 
    white-space: nowrap; 
    backdrop-filter: blur(10px);
}
.sns-extra-layer.show { display: flex !important; animation: popIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
@keyframes popIn { 
    0% { opacity: 0; transform: translateY(-10px) scale(0.95); } 
    100% { opacity: 1; transform: translateY(0) scale(1); } 
}

/* ==========================================================================
   8. 사이드바 및 프로필 위젯
========================================================================== */
.sidebar-area .widget { border: 1px solid #e5e5e5; padding: 25px 20px; margin-bottom: 30px; background: #fff; border-radius: 8px; }
.widget-title { font-size: 15px; font-weight: 800; margin: 0 0 18px 0; padding-bottom: 12px; border-bottom: 2px solid #333; letter-spacing: -0.3px; color: #111; }
.widget ul { list-style: none; padding: 0; margin: 0; }
.widget li { margin-bottom: 12px; font-size: 14px; color: #555; }
.widget li a:hover { color: #00c73c; text-decoration: underline; }

.profile-wrap { text-align: left; position: relative; background: #fff; border: 1px solid #e5e5e5; border-radius: 8px; overflow: hidden; margin-bottom: 30px; }
.profile-img { width: 100%; height: 160px; background: #f4f4f4; object-fit: cover; }
.profile-img.placeholder { display: flex; flex-direction: column; justify-content: center; align-items: center; background: #f8f9fa; color: #adb5bd; font-size: 13px; font-weight: 600; text-align: center; border-bottom: 1px solid #eee; margin-bottom: 0; min-height: 160px; }

.profile-info-container { padding: 20px; }
.profile-info { display: flex; align-items: center; padding-bottom: 15px; }
.profile-avatar img { border-radius: 20px; width: 64px; height: 64px; object-fit: cover; border: 1px solid #eee; }
.profile-details { margin-left: 15px; }
.profile-name { font-weight: 800; font-size: 18px; color: #111; margin-bottom: 2px; letter-spacing: -0.3px; }
.profile-sns-id { font-size: 13px; color: #888; font-family: Tahoma, sans-serif; }

.profile-bio { font-size: 14px; color: #666; margin-bottom: 20px; line-height: 1.6; word-break: keep-all; }

.profile-links { display: flex; align-items: center; margin-bottom: 20px; }
.edit-badge { background: #b5b5b5; color: #fff; font-size: 10px; padding: 3px 6px; border-radius: 4px; font-weight: 800; margin-right: 6px; letter-spacing: 0.5px; }
.profile-more { color: #888; font-size: 12px; display: flex; align-items: center; font-weight: 500; }
.profile-more .profile-icon { color: #888; margin-left: 5px; font-size: 13px; }
.profile-more:hover, .profile-more:hover .profile-icon { color: #333; }

.profile-banners { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; }
.banner-btn { background: #fff; border: 1px solid #ddd; padding: 12px; font-size: 14px; font-weight: 700; color: #333; border-radius: 6px; cursor: pointer; display: flex; justify-content: center; align-items: center; gap: 8px; transition: all 0.2s; }
.banner-btn:hover { background: #f8f9fa; border-color: #ccc; }

.profile-admin-links { display: flex; gap: 15px; font-size: 13px; margin-top: 15px; color: #777; border-top: 1px solid #eee; padding-top: 15px; }
.profile-admin-links a:hover { color: #111; }

.widget-social .social-icons { display: flex; flex-wrap: wrap; gap: 10px; }
.social-icon { display: inline-flex; justify-content: center; align-items: center; width: 40px; height: 40px; background: #fff; border: 1px solid #eee; border-radius: 50%; font-size: 18px; color: #555; transition: all 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
.social-icon:hover { background: #f8f9fa; border-color: #ddd; transform: translateY(-2px); color: #111; box-shadow: 0 5px 10px rgba(0,0,0,0.05); }

/* ==========================================================================
   9. 채팅형 댓글 시스템 (UI/UX)
========================================================================== */
.comments-area { margin-top: 80px; border-top: 2px solid #222; padding-top: 40px; }
.comments-title { font-size: 20px; font-weight: 800; margin-bottom: 30px; color: #111; }

.chat-style-list { list-style: none; padding: 0; margin: 0 0 50px 0; }
.chat-style-list .children { list-style: none; padding-left: 20px; margin-top: 20px; position: relative; }
.chat-style-list .children::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 2px; background: #f0f0f0; border-radius: 2px; }

.chat-item { margin-bottom: 25px; }
.chat-wrap { display: flex; gap: 12px; align-items: flex-start; }
.chat-avatar img { border-radius: 40%; width: 48px; height: 48px; object-fit: cover; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
.chat-content-wrap { display: flex; flex-direction: column; max-width: 85%; }

.chat-meta { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; }
.chat-name { font-weight: 800; font-size: 14px; color: #222; }
.chat-date { font-size: 12px; color: #999; }

.chat-bubble { padding: 14px 18px; border-radius: 20px; font-size: 15px; line-height: 1.6; display: inline-block; word-break: break-all; box-shadow: 0 2px 6px rgba(0,0,0,0.04); }
.chat-bubble p { margin: 0; padding: 0; }
.chat-bubble p + p { margin-top: 10px; }

.chat-reply { margin-top: 8px; }
.chat-reply a { font-size: 12px; color: #666; font-weight: 600; background: #f8f9fa; border: 1px solid #eee; padding: 5px 10px; border-radius: 12px; transition: all 0.2s; }
.chat-reply a:hover { background: #f1f3f5; color: #333; }

/* 우측 정렬 (내가 쓴 댓글) */
.chat-right .chat-wrap { flex-direction: row-reverse; } 
.chat-right .chat-content-wrap { align-items: flex-end; }
.chat-right .chat-meta { flex-direction: row-reverse; } 
.chat-right .chat-bubble { background: #00c73c; color: #fff; border-top-right-radius: 4px; text-align: right; }
.chat-right .chat-bubble a { color: #fff; text-decoration: underline; }
.chat-right .chat-reply { text-align: right; }

/* 좌측 정렬 (상대방 댓글) */
.chat-left .chat-wrap { flex-direction: row; }
.chat-left .chat-bubble { background: #f1f3f5; color: #333; border-top-left-radius: 4px; text-align: left; }
.chat-left .chat-meta { justify-content: flex-start; }
.chat-left .chat-reply { text-align: left; }

.comment-respond { background: #fafafa; padding: 30px; border-radius: 16px; border: 1px solid #eee; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
#reply-title { font-size: 18px; font-weight: 800; margin-bottom: 20px; color: #111; }
.comment-form-comment textarea { width: 100%; border: 1px solid #ddd; padding: 15px; border-radius: 12px; resize: vertical; font-family: inherit; font-size: 15px; transition: border-color 0.2s; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02); }
.comment-form-comment textarea:focus { outline: none; border-color: #00c73c; }
.form-submit .submit-btn { background: #222; color: #fff; border: none; padding: 14px 30px; border-radius: 8px; cursor: pointer; font-weight: 700; font-size: 15px; width: 100%; transition: background 0.2s; }
.form-submit .submit-btn:hover { background: #000; }

/* ==========================================================================
   10. 모바일 반응형 강제 처리 (5열 버튼 등)
========================================================================== */
@media screen and (max-width: 768px) {
    .mobile-only { display: block !important; }
    .desktop-only { display: none !important; }
    
    .container { padding: 0; }
    .site-content { flex-direction: column; margin-top: 0; gap: 0; margin-bottom: 0; }
    .sidebar-area { width: 100%; order: -1; }
    .main-area { width: 100%; padding: 0 20px; margin-top: 20px; }

    /* 투명 헤더 오버레이 */
    .site-header { position: absolute; top: 0; left: 0; width: 100%; padding: 15px 20px; color: #fff; background: transparent; border-bottom: none; }
    .header-logo, .header-logo a { color: #fff; }
    .mobile-menu-toggle { display: block; color: #fff; font-size: 24px; text-shadow: 0 1px 3px rgba(0,0,0,0.3); }

    /* 모바일 프로필 (히어로 영역) */
    .profile-wrap { padding: 0; border: none; border-radius: 0; margin-bottom: 0; background-color: #a06e50; } 
    .profile-info-container { padding: 90px 20px 30px; } 
    .profile-avatar img { border: 2px solid rgba(255,255,255,0.4); width: 60px; height: 60px; }
    
    .profile-wrap.mobile-hero-bg .profile-name,
    .profile-wrap.mobile-hero-bg .profile-sns-id,
    .profile-wrap.mobile-hero-bg .mobile-blog-title,
    .profile-wrap.mobile-hero-bg .stats-today,
    .profile-wrap.mobile-hero-bg .profile-bio {
        color: #ffffff !important;
        text-shadow: 0 1px 3px rgba(0,0,0,0.3);
    }
    
    .profile-name { font-size: 22px; margin-bottom: 4px; }
    div.profile-share-btn { display: flex !important; margin-left: auto; font-size: 24px; cursor: pointer; color: #fff; text-shadow: 0 1px 3px rgba(0,0,0,0.3); }

    /* 모바일 5열 버튼 (Grid 고정) */
    div.mobile-action-grid { 
        display: grid !important; 
        grid-template-columns: 2.5fr 1fr 1fr 1fr 1fr !important; 
        gap: 6px !important; 
        width: 100% !important; 
        height: 50px !important; 
        margin-top: 25px !important; 
        align-items: stretch !important; 
    }
    .grid-btn { 
        display: flex !important; 
        align-items: center !important; 
        justify-content: center !important; 
        background: rgba(255, 255, 255, 0.25) !important; 
        border: 1px solid rgba(255, 255, 255, 0.15) !important; 
        border-radius: 12px !important; 
        color: #fff !important; 
        backdrop-filter: blur(10px) !important; 
        -webkit-backdrop-filter: blur(10px) !important; 
        cursor: pointer !important; 
        padding: 0 !important; 
        margin: 0 !important; 
        transition: background 0.2s !important; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .grid-btn:active { background: rgba(255, 255, 255, 0.4) !important; }
    .grid-btn i { font-size: 18px !important; }
    .grid-btn.btn-home-edit { font-size: 14px !important; font-weight: 800 !important; gap: 6px !important; }
    .grid-btn.btn-home-edit i { font-size: 15px !important; }

    /* 모바일 톡톡하기 배너 */
    div.mobile-green-banner { 
        display: flex !important; 
        background: #00c73c !important; 
        color: #fff !important; 
        padding: 16px 20px !important; 
        justify-content: space-between !important; 
        align-items: center !important; 
        font-size: 15px !important; 
        font-weight: 700 !important; 
    }
    .mobile-green-banner .btn { border: 1px solid rgba(255,255,255,0.5); padding: 6px 14px; border-radius: 20px; font-size: 13px; background: rgba(0,0,0,0.05); }

    /* 모바일 글 목록 및 본문 최적화 */
    .post-list-wrap { border-top: none; border-bottom: 8px solid #f5f5f5; margin: 0 -20px 30px; padding: 0 20px 25px; }
    .post-list-header { border-bottom: none; padding: 20px 0 10px; }
    .post-list-header h2 { font-size: 18px; }
    .post-list-item { flex-direction: column; padding: 20px 0; border-bottom: 1px solid #f0f0f0; align-items: flex-start; }
    .post-list-item a { font-size: 17px; line-height: 1.4; }
    
    .post-list-excerpt { display: -webkit-box !important; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 12px; color: #666; font-size: 14px; }
    .post-list-meta-mobile { display: flex !important; justify-content: space-between; font-size: 13px; color: #999; margin-top: auto; width: 100%; }
    
    .post-title { font-size: 24px; }
    .post-meta { flex-wrap: wrap; }
    .post-utilities-top { margin-left: 0; margin-top: 10px; width: 100%; justify-content: flex-end; }
    
    .chat-content-wrap { max-width: 88%; }
    .chat-bubble { font-size: 14px; padding: 12px 15px; }
}

### 2. functions.php

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
}
add_action( 'after_setup_theme', 'sagaksagak_setup' );

function sagaksagak_enqueue_scripts() {
    $theme_version = filemtime( get_stylesheet_directory() . '/style.css' );
    wp_enqueue_style( 'sagaksagak-style', get_stylesheet_uri(), array(), $theme_version );
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

    $wp_customize->add_setting( 'profile_link_url', array('default' => '#') );
    $wp_customize->add_control( 'profile_link_url', array(
        'label'   => '프로필 링크 URL',
        'section' => 'sagaksagak_profile_section',
        'type'    => 'url',
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

    // 2. SNS 및 외부 링크
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
    $current_user_id = get_current_user_id();
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
}

### 3. sidebar.php

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

        <div class="profile-info-container">
            <!-- 프로필 사진, 관리자 이름, SNS 아이디 -->
            <div class="profile-info">
                <div class="profile-avatar">
                    <?php echo get_avatar( $admin_id, 64 ); ?>
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
            <div class="profile-links desktop-only">
                <?php if(current_user_can('edit_posts')): ?>
                    <span class="edit-badge">EDIT</span>
                <?php endif; ?>
                <a href="<?php echo esc_url(get_theme_mod('profile_link_url', '#')); ?>" class="profile-more">
                    <?php echo esc_html(get_theme_mod('profile_link_text', '프로필')); ?> <i class="fa-solid fa-square-caret-right profile-icon"></i>
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
    </div>

    <!-- 모바일 전용 하단 녹색 배너 -->
    <div class="mobile-only mobile-green-banner" onclick="window.open('<?php echo esc_url(get_theme_mod('banner1_url', '#')); ?>', '_blank')">
        <span class="text">블로그 주인과 바로 대화해보세요</span>
        <span class="btn"><i class="fa-regular fa-comment-dots"></i> 톡톡하기</span>
    </div>

    <div style="height:20px;" class="desktop-only"></div>

    <!-- 카테고리 위젯 -->
    <div class="widget desktop-only">
        <h3 class="widget-title">카테고리</h3>
        <ul><?php wp_list_categories( array('title_li' => '', 'show_count' => 1, 'hide_empty' => 0 ) ); ?></ul>
    </div>

    <!-- 활동정보 위젯 -->
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

### 4. index.php

<?php get_header(); ?>

<div class="container site-content">
    <main class="main-area">
        
        <!-- 네이버 스타일: 상단 글 목록 -->
        <div class="post-list-wrap">
            <div class="post-list-header">
                <h2 onclick="handleTitleClick(event)">
                    <?php
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $setting_posts_per_page = get_option( 'posts_per_page' );
                    $args = array( 'posts_per_page' => $setting_posts_per_page, 'paged' => $paged );
                    
                    if ( is_category() ) {
                        $cat_id = get_query_var('cat');
                        $args['cat'] = $cat_id;
                        $cat_info = get_category($cat_id);
                        echo esc_html($cat_info->name) . ' <span style="font-size:12px; font-weight:normal;">∨</span>';
                    } else {
                        echo '전체글 <span style="font-size:12px; font-weight:normal;">∨</span>';
                    }
                    ?>
                </h2>
                
                <div class="list-options desktop-only">
                    <span id="post-list-toggle-btn" style="cursor:pointer;" onclick="togglePostList()">목록닫기 ▲</span>
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
        function handleTitleClick(e) {
            if (window.innerWidth <= 768) {
                if (typeof toggleMobileMenu === 'function') toggleMobileMenu();
            } else {
                togglePostList();
            }
        }
        function togglePostList() {
            var content = document.getElementById('post-list-content');
            var btn = document.getElementById('post-list-toggle-btn');
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                if(btn) btn.innerHTML = '목록닫기 ▲';
            } else {
                content.style.display = 'none';
                if(btn) btn.innerHTML = '목록열기 ▼';
            }
        }
        </script>

        <style>
        .post-utilities-top { display: flex; align-items: center; gap: 5px; position: relative; }
        .sns-extra-layer { display: none !important; position: absolute; top: 40px; right: 0; background: #ffffff; padding: 8px 12px; border: 1px solid #e5e5e5; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); gap: 6px; z-index: 999; white-space: nowrap; }
        .sns-extra-layer.show { display: flex !important; animation: fadeInLayer 0.2s ease-out; }
        @keyframes fadeInLayer { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        </style>

        <?php 
        $kakao_key = get_theme_mod('kakao_js_key'); 
        if(!empty($kakao_key)): 
        ?>
        <script src="[https://t1.kakaocdn.net/kakao_js_sdk/2.7.2/kakao.min.js](https://t1.kakaocdn.net/kakao_js_sdk/2.7.2/kakao.min.js)" crossorigin="anonymous"></script>
        <script>
            if (!Kakao.isInitialized()) Kakao.init('<?php echo esc_js($kakao_key); ?>');
        </script>
        <?php endif; ?>

        <!-- 본문 리스트 (설정 개수만큼 반복) -->
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
                        
                        <div class="post-meta-right post-utilities-top">
                            <span id="copy-msg-<?php the_ID(); ?>" style="display:none; font-size:11px; color:#2db400; margin-right:5px;">복사완료!</span>
                            
                            <button onclick="copyToClipboard('<?php the_permalink(); ?>', 'copy-msg-<?php the_ID(); ?>')" class="util-btn" title="URL 복사"><i class="fa-solid fa-link"></i></button>
                            
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

                            <button onclick="toggleExtraSNS('sns-extra-<?php the_ID(); ?>')" class="util-btn" title="더보기"><i class="fa-solid fa-ellipsis"></i></button>

                            <div id="sns-extra-<?php the_ID(); ?>" class="sns-extra-layer">
                                <a href="[https://www.facebook.com/sharer/sharer.php?u=](https://www.facebook.com/sharer/sharer.php?u=)<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-fb" title="페이스북 공유"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="[https://twitter.com/intent/tweet?url=](https://twitter.com/intent/tweet?url=)<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-tw" title="X 공유"><i class="fa-brands fa-x-twitter"></i></a>
                                <button onclick="alert('인스타그램은 웹 공유를 지원하지 않습니다. 링크가 복사되었습니다.'); copyToClipboard('<?php the_permalink(); ?>', 'copy-msg-<?php the_ID(); ?>');" class="util-btn share-ig" title="인스타그램 공유"><i class="fa-brands fa-instagram"></i></button>
                                <a href="[https://pinterest.com/pin/create/button/?url=](https://pinterest.com/pin/create/button/?url=)<?php echo urlencode(get_permalink()); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-pin" title="핀터레스트 공유"><i class="fa-brands fa-pinterest-p"></i></a>
                                <a href="[https://cafe.naver.com/cafe-shared/info?url=](https://cafe.naver.com/cafe-shared/info?url=)<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-nv" title="네이버 카페 공유"><b style="font-family:sans-serif; font-weight:900;">N</b></a>
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
        function toggleExtraSNS(layerId) {
            var layer = document.getElementById(layerId);
            if(layer.classList.contains('show')) {
                layer.classList.remove('show');
            } else {
                var allLayers = document.querySelectorAll('.sns-extra-layer');
                allLayers.forEach(function(el) { el.classList.remove('show'); });
                layer.classList.add('show');
            }
        }
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

### 5. single.php

<?php get_header(); ?>

<div class="container site-content">
    <main class="main-area">
        <?php 
        $kakao_key = get_theme_mod('kakao_js_key'); 
        if(!empty($kakao_key)): 
        ?>
        <script src="[https://t1.kakaocdn.net/kakao_js_sdk/2.7.2/kakao.min.js](https://t1.kakaocdn.net/kakao_js_sdk/2.7.2/kakao.min.js)" crossorigin="anonymous"></script>
        <script>
            if (!Kakao.isInitialized()) Kakao.init('<?php echo esc_js($kakao_key); ?>');
        </script>
        <?php endif; ?>

        <?php $current_post_id = get_queried_object_id(); ?>

        <!-- 상단 전체글 목록 -->
        <div class="post-list-wrap">
            <div class="post-list-header">
                <h2 onclick="handleTitleClick(event)">
                    전체글 <span style="font-size:12px; font-weight:normal;">∨</span>
                </h2>
                <div class="list-options desktop-only">
                    <span id="post-list-toggle-btn" style="cursor:pointer;" onclick="togglePostList()">목록닫기 ▲</span>
                </div>
                <div class="list-options mobile-only" style="font-size:18px; color:#ccc;">⊞ 𝄘 ⊟</div>
            </div>
            
            <div id="post-list-content">
                <?php
                $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : ( get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1 );
                $setting_posts_per_page = get_option( 'posts_per_page' );
                $list_args = array( 'posts_per_page' => $setting_posts_per_page, 'paged' => $paged );
                
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
                    echo paginate_links( array('total' => $list_query->max_num_pages, 'current' => $paged, 'prev_text' => '이전', 'next_text' => '다음', 'base' => @add_query_arg('page','%#%') ) );
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
                if (typeof toggleMobileMenu === 'function') toggleMobileMenu();
            } else {
                togglePostList();
            }
        }
        function togglePostList() {
            var content = document.getElementById('post-list-content');
            var btn = document.getElementById('post-list-toggle-btn');
            if (content.style.display === 'none' || content.style.display === '') {
                content.style.display = 'block';
                if(btn) btn.innerHTML = '목록닫기 ▲';
            } else {
                content.style.display = 'none';
                if(btn) btn.innerHTML = '목록열기 ▼';
            }
        }
        </script>

        <style>
        .post-utilities-top { display: flex; align-items: center; gap: 5px; position: relative; }
        .sns-extra-layer { display: none !important; position: absolute; top: 40px; right: 0; background: #ffffff; padding: 8px 12px; border: 1px solid #e5e5e5; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); gap: 6px; z-index: 999; white-space: nowrap; }
        .sns-extra-layer.show { display: flex !important; animation: fadeInLayer 0.2s ease-out; }
        @keyframes fadeInLayer { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        </style>

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
                            
                            <button onclick="window.print()" class="util-btn" title="인쇄하기"><i class="fa-solid fa-print"></i></button>
                            <button onclick="copyToClipboard('<?php the_permalink(); ?>', 'copy-msg')" class="util-btn" title="URL 복사"><i class="fa-solid fa-link"></i></button>
                            
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

                            <button onclick="toggleExtraSNS('sns-extra-single')" class="util-btn" title="더보기"><i class="fa-solid fa-ellipsis"></i></button>

                            <div id="sns-extra-single" class="sns-extra-layer">
                                <a href="[https://www.facebook.com/sharer/sharer.php?u=](https://www.facebook.com/sharer/sharer.php?u=)<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-fb" title="페이스북 공유"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="[https://twitter.com/intent/tweet?url=](https://twitter.com/intent/tweet?url=)<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-tw" title="X 공유"><i class="fa-brands fa-x-twitter"></i></a>
                                <button onclick="alert('인스타그램은 웹 공유를 지원하지 않습니다. 링크가 복사되었습니다.'); copyToClipboard('<?php the_permalink(); ?>', 'copy-msg');" class="util-btn share-ig" title="인스타그램 공유"><i class="fa-brands fa-instagram"></i></button>
                                <a href="[https://pinterest.com/pin/create/button/?url=](https://pinterest.com/pin/create/button/?url=)<?php echo urlencode(get_permalink()); ?>&description=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-pin" title="핀터레스트 공유"><i class="fa-brands fa-pinterest-p"></i></a>
                                <a href="[https://cafe.naver.com/cafe-shared/info?url=](https://cafe.naver.com/cafe-shared/info?url=)<?php echo urlencode(get_permalink()); ?>&title=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener noreferrer" class="util-btn share-nv" title="네이버 카페 공유"><b style="font-family:sans-serif; font-weight:900;">N</b></a>
                            </div>
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
                function toggleExtraSNS(layerId) {
                    var layer = document.getElementById(layerId);
                    if(layer.classList.contains('show')) layer.classList.remove('show');
                    else {
                        var allLayers = document.querySelectorAll('.sns-extra-layer');
                        allLayers.forEach(function(el) { el.classList.remove('show'); });
                        layer.classList.add('show');
                    }
                }
                document.addEventListener('click', function(event) {
                    var isClickInside = event.target.closest('.post-utilities-top');
                    if (!isClickInside) {
                        var allLayers = document.querySelectorAll('.sns-extra-layer');
                        allLayers.forEach(function(el) { el.classList.remove('show'); });
                    }
                });
                </script>

                <footer class="post-tags">
                    <?php 
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach($tags as $tag) { echo '<a href="' . get_tag_link($tag->term_id) . '">#' . $tag->name . '</a>'; }
                    }
                    ?>
                </footer>

                <!-- 하단 관련 카테고리 글 추천 -->
                <?php
                $categories = get_the_category();
                if ( ! empty( $categories ) ) {
                    $cat_id = $categories[0]->term_id;
                    $cat_name = $categories[0]->name;
                    
                    $related_query = new WP_Query( array('cat' => $cat_id, 'posts_per_page' => 5) );
                    
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

                <!-- 댓글 영역 -->
                <div class="comments-area">
                    <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>
                </div>
                
            </article>
        <?php endwhile; endif; ?>

    </main>
    <?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>

### 6. comments.php

<?php
/**
 * 채팅 스타일 댓글 출력을 담당하는 템플릿 파일
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            댓글 <?php echo get_comments_number(); ?>
        </h2>

        <ul class="comment-list chat-style-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ul',
                'short_ping' => true,
                'callback'   => 'sagaksagak_chat_comment',
            ) );
            ?>
        </ul>

        <?php the_comments_navigation(); ?>
    <?php endif; ?>

    <?php
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments" style="text-align:center; color:#999; margin-bottom:20px;">댓글이 닫혀있습니다.</p>
    <?php endif; ?>

    <?php
    comment_form( array(
        'title_reply' => '댓글 남기기',
        'class_submit' => 'submit-btn',
        'comment_field' => '<div class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="4" placeholder="따뜻한 댓글을 남겨보세요..." required="required"></textarea></div>',
    ) );
    ?>
</div>

### 7. header.php

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- Font Awesome 6.5.2 (X 로고 완벽 지원) -->
    <link rel="stylesheet" href="[https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css](https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css)">
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
                    <span>전체글</span>
                    <span class="count"><?php echo wp_count_posts()->publish; ?></span>
                </a>
            </li>
            <?php 
            $categories = get_categories(array('hide_empty' => 0));
            foreach($categories as $category) {
                echo '<li class="cat-item">';
                echo '<a href="' . get_category_link($category->term_id) . '">' . esc_html($category->name) . '</a>';
                echo '<span class="count">' . $category->count . '</span>';
                echo '</li>';
            }
            ?>
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

### 8. footer.php

<footer class="site-footer" style="text-align: center; padding: 40px 0; margin-top: 80px; border-top: 1px solid #eee; color: #888; font-size: 13px;">
    <div class="container">
        <p>© <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All Rights Reserved.</p>
        <p>워드프레스 사각사각 테마</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>

### 9. theme.json

{
    "version": 2,
    "settings": {
        "appearanceTools": true,
        "layout": {
            "contentSize": "800px",
            "wideSize": "1080px"
        },
        "spacing": {
            "margin": true,
            "padding": true,
            "units": [ "px", "em", "rem", "vh", "vw", "%" ]
        }
    }
}
