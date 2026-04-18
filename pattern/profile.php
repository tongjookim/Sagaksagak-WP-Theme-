<?php
$admins    = get_users( [ 'role' => 'administrator', 'number' => 1 ] );
$admin_id  = ! empty( $admins ) ? $admins[0]->ID : 1;
$avatar_url = get_avatar_url( $admin_id, [ 'size' => 160 ] );
?>
<div class="pf-wrap">

    <!-- 1. Hero -->
    <div class="pf-box pf-p10">
        <div class="pf-hero">
            <div class="pf-avatar-wrap">
                <div class="pf-avatar">
                    <img src="<?php echo esc_url( $avatar_url ); ?>" alt="Profile">
                </div>
                <div class="pf-avatar-badge">
                    <i class="fa-solid fa-check" style="color:#fff;font-size:11px;"></i>
                </div>
            </div>
            <div class="pf-info">
                <div class="pf-name-row">
                    <span class="pf-name">마루밑다락방</span>
                    <span class="pf-handle">@tongjoo.kim</span>
                </div>
                <p class="pf-tagline">
                    "철학적 사유로 세상을 읽고, 청년의 목소리를 디자인합니다."<br>
                    <span class="pf-tagline-sub">수완뉴스 Founder, Publisher | 국립강릉원주대학교 철학과</span>
                </p>
                <div class="pf-tags">
                    <span class="pf-tag">#언론인</span>
                    <span class="pf-tag">#철학도</span>
                    <span class="pf-tag">#자수성가형_리더</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. About Me -->
    <div class="pf-box pf-p8">
        <h2 class="pf-section-title">About Me</h2>
        <div class="pf-prose">
            <p>2015년부터 <strong>수완뉴스</strong>를 설립하여 초심을 잃지 않고 운영하고 있습니다.</p>
            <p>10년 간의 비즈니스 노하우를 통해 디지털 콘텐츠 기획, 브랜딩, 현장 인터뷰 등 전문적인 미디어 서비스를 제공합니다. 유복한 환경에 안주하지 않고, 스스로의 힘으로 자립하여 가치를 증명하는 자수성가형 리더로서 매일 공부하고 정진합니다.</p>
        </div>
    </div>

    <!-- 3. Core Values -->
    <div class="pf-grid-3">
        <div class="pf-box pf-value-card">
            <div class="pf-value-icon"><i class="fa-solid fa-magnifying-glass" style="color:#00C73C;font-size:17px;"></i></div>
            <div class="pf-value-title">본질 탐구</div>
            <div class="pf-value-desc">철학적 사유로 사물의 근원을 묻는 태도</div>
        </div>
        <div class="pf-box pf-value-card">
            <div class="pf-value-icon"><i class="fa-solid fa-hand-fist" style="color:#00C73C;font-size:17px;"></i></div>
            <div class="pf-value-title">청년의 주체성</div>
            <div class="pf-value-desc">스스로 결정하고 책임지는 삶의 방식</div>
        </div>
        <div class="pf-box pf-value-card">
            <div class="pf-value-icon"><i class="fa-solid fa-newspaper" style="color:#00C73C;font-size:17px;"></i></div>
            <div class="pf-value-title">현장의 가치</div>
            <div class="pf-value-desc">발로 뛰며 기록하는 생생한 저널리즘</div>
        </div>
    </div>

    <!-- 4. Numbers -->
    <div class="pf-box pf-p8" style="background:#fafafa;">
        <h2 class="pf-section-title">Numbers</h2>
        <div class="pf-num-grid">
            <div>
                <div class="pf-num-value" style="color:#00C73C;">10+</div>
                <div class="pf-num-label">수완뉴스 운영 연수</div>
            </div>
            <div>
                <div class="pf-num-value">500+</div>
                <div class="pf-num-label">누적 발행 기사</div>
            </div>
            <div>
                <div class="pf-num-value">3</div>
                <div class="pf-num-label">현직 단체·기관</div>
            </div>
        </div>
    </div>

    <!-- 5. Experience + Education -->
    <div class="pf-grid-2">
        <div class="pf-box pf-p6">
            <h2 class="pf-section-title">Experience</h2>
            <ul class="pf-exp-list">
                <li class="pf-exp-row">
                    <span class="pf-exp-badge" style="color:#00C73C;">현)</span>
                    <div>
                        <div class="pf-exp-title">수완뉴스 Founder, Publisher</div>
                        <div class="pf-exp-sub">청년·청소년 언론 플랫폼 운영 및 발행</div>
                    </div>
                </li>
                <li class="pf-exp-row">
                    <span class="pf-exp-badge" style="color:#00C73C;">현)</span>
                    <div>
                        <div class="pf-exp-title">청소년 단체 유니엄 공동 대표</div>
                        <div class="pf-exp-sub">사회적 가치 실천 및 청소년 활동 지원</div>
                    </div>
                </li>
                <li class="pf-exp-row">
                    <span class="pf-exp-badge" style="color:#999;">전)</span>
                    <div>
                        <div class="pf-exp-title">국립강릉원주대 신문사</div>
                        <div class="pf-exp-sub">언론원 정기자 (전 사회부장)</div>
                    </div>
                </li>
                <li class="pf-exp-row">
                    <span class="pf-exp-badge" style="color:#999;">전)</span>
                    <div>
                        <div class="pf-exp-title">옹기종기 사회적협동조합</div>
                        <div class="pf-exp-sub">이사</div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="pf-box pf-p6">
            <h2 class="pf-section-title">Education</h2>
            <div class="pf-timeline">
                <div class="pf-timeline-item">
                    <div class="pf-timeline-title">국립강릉원주대학교</div>
                    <div class="pf-timeline-sub">철학과 전공 (재학)</div>
                </div>
                <div class="pf-timeline-item">
                    <div class="pf-timeline-title">현천고등학교 졸업</div>
                    <div class="pf-timeline-sub">대안학교에서의 주도적 성장</div>
                </div>
                <div class="pf-timeline-item">
                    <div class="pf-timeline-title">중졸 검정고시 합격</div>
                    <div class="pf-timeline-sub">스스로 선택한 새로운 시작 (2014)</div>
                </div>
                <div class="pf-timeline-item">
                    <div class="pf-timeline-title">강릉노암초등학교 졸업</div>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. Academic Thesis -->
    <div class="pf-box pf-p8" style="background:#fafafa;">
        <h2 class="pf-section-title">Academic Thesis</h2>
        <div class="pf-thesis-inner">
            <div class="pf-thesis-badge">Bachelor's Thesis</div>
            <div class="pf-thesis-title">"데카르트 신 존재 증명에 대한 고찰"</div>
            <div class="pf-thesis-sub">- 제3 성찰 텍스트를 중심으로</div>
        </div>
    </div>

    <!-- 7. My Story -->
    <div class="pf-box pf-p8 pf-story-wrap">
        <i class="fa-solid fa-leaf pf-story-deco"></i>
        <h2 class="pf-section-title">My Story</h2>
        <div class="pf-prose">
            <p>제 유년 시절은 강릉의 맑은 공기 속에서 자랐습니다. 중학교 자퇴라는 쉽지 않은 선택을 거쳐 19세에 <strong>현천고등학교</strong>에 입학하기까지, 제 삶은 주도적인 선택의 연속이었습니다. 대안학교라는 낯선 환경은 오히려 스스로 커리큘럼을 설계하고, 세상과 직접 부딪히는 법을 가르쳐 주었습니다. 그 과정에서 얻은 철학적 성찰은 지금의 저를 지탱하는 가장 큰 힘입니다.</p>
            <p>고등학교를 마친 뒤, 저는 언론과 철학이라는 두 키워드가 맞닿는 지점을 찾아 <strong>국립강릉원주대학교 철학과</strong>에 진학했습니다. 입학은 단순한 학업의 연장이 아니라, 10년간 현장에서 쌓아온 질문들에 학문적 언어를 부여하는 여정의 시작이었습니다. 강의실 안팎에서 데카르트와 하이데거를 읽으면서, 뉴스를 만드는 일과 철학하는 일이 결국 같은 뿌리에서 출발한다는 것을 확인하고 있습니다.</p>
            <p>유복한 환경은 저에게 큰 축복이었지만, 그 너머 '스스로의 이름'으로 성공한 리더가 되기 위해 끊임없이 고민합니다. 수완뉴스를 통해 청년들의 목소리를 세상에 전하고, 플랫폼으로서 그들을 하나로 묶는 것, 그것이 제가 10년째 이어오고 있는 소명입니다.</p>
        </div>
    </div>

    <!-- 8. CTA -->
    <div class="pf-cta">
        <div>
            <div class="pf-cta-title">미디어 전략 &amp; 인터뷰 협업 제안</div>
            <div class="pf-cta-desc">콘텐츠 기획, 브랜딩 인터뷰, 청년 미디어 협력까지<br>함께할 수 있는 모든 협업 제안을 환영합니다.</div>
        </div>
        <a href="mailto:tongjoogim@gmail.com" class="pf-cta-btn">협업 제안하기 →</a>
    </div>

</div>
