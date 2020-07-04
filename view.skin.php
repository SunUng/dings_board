<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_javascript('<script src="'.$board_skin_url.'/js/jquery.cookie.js"></script>', 0);
add_stylesheet('<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600&family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">', 0);
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<article class="dings-board dings-board-wrapper">
    <div class="board" id="boardView">
        <div class="board-view-head">
            <div class="board-view-head-top">
                <div class="board-view-name">
                    <?php //print_r2($bo_subject) ?>
                    <?php if ($view['is_notice']) { ?>
                        <span class="board-view-isNotice">공지사항</span>
                    <?php } else { ?>
                    <?php echo $view['name'] ?>
                    <?php if ($is_ip_view && $ip !== '::1') { ?>
                        <span class="board-view-ip">
                            <span class="moreinfo-item-title"><span>Author </span>IP</span>
                            <span class="moreinfo-item-content"><?php echo $ip ?></span>
                        </span>
                    <?php } ?>
                    <?php } ?>
                </div>

                <div class="board-view-etc">
                    <div class="board-view-moreinfo">
                        <span class="moreinfo-item">
                            <span class="moreinfo-item-title">Views</span>
                            <span class="moreinfo-item-content"><?php echo number_format($view['wr_hit']) ?></span>
                        </span>
                        <span class="moreinfo-item">
                            <span class="moreinfo-item-title">Comments</span>
                            <span class="moreinfo-item-content"><?php echo number_format($view['wr_comment']) ?></span>
                        </span>
                        <span class="moreinfo-item">
                            <span class="moreinfo-item-title">Date</span>
                            <?php echo date("Y.m.d H:i", strtotime($view['wr_datetime'])) ?>
                        </span>
                    </div>
                    <!-- <div class="board-view-date">
                        <?php //echo date("y-m-d H:i", strtotime($view['wr_datetime'])) ?>
                    </div> -->
                </div>


            </div>
            <div class="board-view-head-bottom">
                <h3>
                    <?php if ($category_name) { ?>
                        <span class="board-view-category"><?php echo $view['ca_name'] ?></span>
                    <?php } ?>
                    <?php echo cut_str(get_text($view['wr_subject']), 70); ?>
                </h3>
            </div>
        </div>
        <div class="board-view-body">
            <?php 
            $v_img_count = count($view['file']); 
            if($v_img_count > 1 && !empty(get_file_thumbnail($view['file'][0]))) {                            
            ?>
            <div class="board-view-thumbs">
            <?php for ($i=0; $i<=count($view['file']); $i++) { ?>
                <div class="borad-view-thumbs-item">
                    <?php echo get_file_thumbnail($view['file'][$i]) ?>
                </div>
            <?php } ?>
            </div>
            <?php } ?>
            <div class="board-view-content">
                <?php echo get_view_thumbnail($view['content']); ?>
            </div>
            <?php if ($is_signature && !empty($signature)) { ?>
            <div class="board-view-signature">
                <?php echo $signature ?>
            </div>
            <?php } ?>

            <?php
            $cnt = 0;
                if ($view['file']['count']) {
                for ($i=0; $i<count($view['file']); $i++) {
                    if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                        $cnt++;
                }
            }
            ?>
            <?php if($cnt) { ?>
            <div class="board-view-files">
                <div class="board-view-files-inner">
                    <div class="board-view-files-label">
                        첨부파일
                    </div>
                    <div class="board-view-files-content">
                        <ul class="board-view-list-file">
                            <?php
                            for ($i=0; $i<count($view['file']); $i++) {
                                if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
                            ?>
                            <li>
                                <div class="board-view-list-files-inner">
                                    <i class="fa fa-paperclip"></i>
                                    <a href="<?php echo $view['file'][$i]['href']; ?>" class="btn-download view_file_download">
                                        <strong><?php echo $view['file'][$i]['source'] ?></strong>
                                        <span class="board-view-file-size">
                                            (<?php echo $view['file'][$i]['size'] ?>)
                                        </span>
                                        <?php if (isset($view['file'][$i]['content']) && !empty($view['file'][$i]['content'])) { ?>
                                        <span class="board-view-file-text">
                                            <?php echo $view['file'][$i]['content'] ?>
                                        </span>
                                        <?php } ?>
                                    </a>
                                    <span class="board-view-file-info">
                                        <span class="board-view-file-download"><?php echo ((int)$view['file'][$i]['download'] > 1) ? $view['file'][$i]['download'].' DOWNLOADS' : $view['file'][$i]['download'].' DOWNLOAD' ?></span> / 
                                        <span class="board-view-file-date"><?php echo $view['file'][$i]['datetime'] ?></span>
                                    </span>
                                </div>
                            </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>
            
            <?php if(isset($view['link'][1]) && $view['link'][1]) { ?>
            <div class="board-view-links">
                <div class="board-view-files-inner">
                    <div class="board-view-files-label">
                        연관링크
                    </div>
                    <div class="board-view-files-content">
                        <ul class="board-view-list-file">
                            <?php
                            // 링크
                            $cnt = 0;
                            for ($i=1; $i<=count($view['link']); $i++) {
                                if ($view['link'][$i]) {
                                    $cnt++;
                                    $link = cut_str($view['link'][$i], 70);
                            ?>
                            <li>
                                <div class="board-view-list-files-inner">
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    <a href="<?php echo $view['link_href'][$i] ?>" target="_blank" class="btn-link">
                                        <strong><?php echo $link ?></strong>
                                    </a>
                                    <span class="board-view-file-info">
                                        <span class="board-view-file-download"><?php echo ((int)$view['link_hit'][$i] > 1) ? $view['link_hit'][$i].' CLICKS' : $view['link_hit'][$i].' CLICK' ?></span>
                                    </span>
                                </div>
                            </li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ( $good_href || $nogood_href) { ?>
            <div class="board-view-action link">
                <div class="board-view-action-inner">
                    <?php if ($good_href) { ?>
                    <div class="board-view-action-btn good btns-rounded">
                        <a href="<?php echo $good_href.'&amp;'.$qstr ?>" id="good_button" class="bo_v_good">
                            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                            <span class="sound_only">추천</span>
                            <strong><?php echo number_format($view['wr_good']) ?></strong>
                        </a>
                        <b id="bo_v_act_good" class="board-view-action-message"></b>
                    </div>
                    <?php } ?>
                    <?php if ($nogood_href) { ?>
                    <div class="board-view-action-btn bad btns-rounded">
                        <a href="<?php echo $nogood_href.'&amp;'.$qstr ?>" id="nogood_button" class="bo_v_nogood">
                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i><span class="sound_only">비추천</span>
                            <strong><?php echo number_format($view['wr_nogood']) ?></strong>
                        </a>
                        <b id="bo_v_act_nogood" class="board-view-action-message"></b>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } else {
                if($board['bo_use_good'] || $board['bo_use_nogood']) {
            ?>
            <div class="board-view-action nolink">
                <div class="board-view-action-inner">
                    <?php if($board['bo_use_good']) { ?>
                    <div class="board-view-action-btn good btns-rounded">
                        <span class="btn-action bo_v_good">
                            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
                            <span class="sound_only">추천</span>
                            <strong><?php echo number_format($view['wr_good']) ?></strong>
                        </span>
                    </div>
                    <?php } ?>
                    <?php if($board['bo_use_nogood']) { ?>
                    <div class="board-view-action-btn bad btns-rounded">
                        <span class="btn-action bo_v_nogood">
                            <i class="fa fa-thumbs-o-down" aria-hidden="true"></i>
                            <span class="sound_only">추천</span>
                            <strong><?php echo number_format($view['wr_nogood']) ?></strong>
                        </span>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php
                }
            }
            ?>

            <div class="board-view-sns btns-rounded">
                <?php include_once(G5_SNS_PATH."/view.sns.skin.php"); ?>
            </div>

        </div>
        <div class="board-view-foot">
            <?php if($copy_href || $move_href || $search_href) { ?>
            <div class="board-view-btns btns-rounded admin">
                <?php if ($copy_href) { ?>
                <div class="btns-item btn-copy">
                    <a href="<?php echo $copy_href ?>">
                        복사
                    </a>
                </div>
                <?php } ?>
                <?php if ($move_href) { ?>
                <div class="btns-item btn-move">
                    <a href="<?php echo $move_href ?>">
                        이동
                    </a>
                </div>
                <?php } ?>
                <?php if ($search_href) { ?>
                <!-- <div class="btns-item btn-view-search">
                    <a href="<?php echo $search_href ?>">
                        검색
                    </a>
                </div> -->
                <?php } ?>
            </div>
            <?php } ?>
            
            <div class="board-view-btns btns-rounded user">
                <?php if ($delete_href) { ?>
                <div class="btns-item btn-remove">
                    <a href="<?php echo $delete_href ?>">
                        삭제
                    </a>
                </div>
                <?php } ?>
                <?php if ($update_href) { ?>
                <div class="btns-item btn-modify">
                    <a href="<?php echo $update_href ?>">
                        수정
                    </a>
                </div>
                <?php } ?>
                <?php if ($reply_href) { ?>
                <div class="btns-item btn-reply">
                    <a href="<?php echo $reply_href ?>">
                        답변
                    </a>
                </div>
                <?php } ?>
                <?php if ($write_href) { ?>
                <div class="btns-item btn-write">
                    <a href="<?php echo $write_href ?>">
                        글쓰기
                    </a>
                </div>
                <?php } ?>
                <div class="btns-item btn-list">
                    <a href="<?php echo $list_href ?>" class="btn-go-list">
                        목록
                    </a>
                </div>
            </div>
        </div>
    
        <?php if ($prev_href || $next_href) { ?>
        <div class="board-others-article<?php if (!$prev_href || !$next_href) echo ' other-odd' ?>">
            <ul>
                <?php if ($prev_href) { ?>
                <li class="others-prev">
                    <div class="others-inner">
                        <a href="<?php echo $prev_href ?>">
                            <i class="fa fa-chevron-left"></i>
                            <div class="others-title">
                                <?php echo $prev_wr_subject;?>
                            </div>
                            <div class="others-date">
                                <?php echo str_replace('-', '.', substr($prev_wr_date, '2', '8')); ?>
                            </div>
                        </a>
                    </div>
                </li>
                <?php } ?>
                <?php if ($next_href) { ?>
                <li class="others-next">
                    <div class="others-inner">
                        <a href="<?php echo $next_href ?>">
                            <i class="fa fa-chevron-right"></i>
                            <div class="others-title">
                                <?php echo $next_wr_subject;?>
                            </div>
                            <div class="others-date">
                                <?php echo str_replace('-', '.', substr($next_wr_date, '2', '8')); ?>
                            </div>
                        </a>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
        <?php } ?>
        <?php include_once(G5_BBS_PATH.'/view_comment.php'); ?>


    </div>
    <div class="mobile-last-btns">
        <div class="mobile-last-btns-inner">
            <a href="#articleAnchor" class="mobile-go-article">
                <i class="fa fa-chevron-up"></i> 본문으로
            </a>
        </div>
        <div class="mobile-last-btns-inner">
            <a href="<?php echo $list_href ?>" class="mobile-go-list">
                <i class="fa fa-chevron-left"></i> 목록
            </a>
        </div>
    </div>
</article>

<script>
var commentWrapper = $('#boardView');
if ($.cookie('comments_toggle')) commentWrapper.addClass('opened');
$('.comments-head').click(function(){
    var isOpened = commentWrapper.hasClass('opened');
    if (isOpened) { //닫기
        commentWrapper.removeClass('opened');
        $.removeCookie('comments_toggle');
    } else { //열기
        commentWrapper.addClass('opened');
        $.cookie('comments_toggle', 'opened');
    }
});

    function excute_good(href, $el, $tx)
    {
        $.post(
            href,
            { js: "on" },
            function(data) {
                if(data.error) {
                    alert(data.error);
                    return false;
                }

                if(data.count) {
                    $el.find("strong").text(number_format(String(data.count)));
                    if($tx.attr("id").search("nogood") > -1) {
                        $tx.text("이 글을 비추천하셨습니다.");
                        $tx.fadeIn(200).delay(2500).fadeOut(200);
                    } else {
                        $tx.text("이 글을 추천하셨습니다.");
                        $tx.fadeIn(200).delay(2500).fadeOut(200);
                    }
                }
            }, "json"
        );
    }
    function board_move(href) {
        window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
    }
    $('.btn-move a, .btn-copy a').click(function(){
        board_move(this.href);
        return false;
    });
    $('.btn-remove, .btn-remove a').click(function(e){
        if (!confirm('해당 글을 삭제하시겠습니까? 삭제하면 복구할 수 없습니다.')) {
            e.preventDefault();
            return false;
        }
    });
    <?php if ($board['bo_download_point'] < 0) { ?>
    $(function() {
        $("a.view_file_download").click(function() {
            if(!g5_is_member) {
                alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
                return false;
            }

            var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

            if(confirm(msg)) {
                var href = $(this).attr("href")+"&js=on";
                $(this).attr("href", href);

                return true;
            } else {
                return false;
            }
        });
    });
    <?php } ?>
    $(function() {
        $("a.view_image").click(function() {
            window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
            return false;
        });

        // 추천, 비추천
        $("#good_button, #nogood_button").click(function() {
            var $tx;
            if(this.id == "good_button")
                $tx = $("#bo_v_act_good");
            else
                $tx = $("#bo_v_act_nogood");

            excute_good(this.href, $(this), $tx);
            return false;
        });

        // 이미지 리사이즈
        $("#bo_v_atc").viewimageresize();
    });
</script>