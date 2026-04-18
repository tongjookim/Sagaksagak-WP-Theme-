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

        <!-- 채팅 스타일 댓글 목록 -->
        <ul class="comment-list chat-style-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ul',
                'short_ping' => true,
                'callback'   => 'sagaksagak_chat_comment', // functions.php에 정의된 채팅 UI 함수 호출
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
