<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$thisMode = '';
// 'bbs', 'card', 'download', 'gallery 중 택1, 설정안하면 bbs모드로 실행됨.
// 'bbs' : 일반게시판
// 'card' : 카드타입 디자인, 위쪽 썸네일, 아래쪽 정보 텍스트, 목록에서 첨부파일 1번 다운로드 지원(게시판 다운로드 권한 설정, 포인트 설정 적용됨). !!단, 게시판 설정에서 '목록에서 파일 사용' 체크 해야함.
// 'download' : 카드타입 디자인에서 썸네일 제거됨, 목록에서 첨부파일 1번 다운로드 지원(게시판 다운로드 권한 설정, 포인트 설정 적용됨). !!단, 게시판 설정에서 '목록에서 파일 사용' 체크 해야함.
// 'gallery' : 핀터레스트 스타일 그리드. 썸네일에 mouseover(hover) 상태에서 정보 레이어(div) 오버레이됨. 모바일에서는 카드형 레이아웃 형태로 썸네일 CROP하지 않고 수직 비율 유지되어 나타남(css background-size:cover 형태 아님)

include_once($board_skin_path.'/dings.lib.php');
$searchOptions = dings_selected_options($sfl);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_stylesheet('<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600&family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">', 0);
?>

<article class="dings-board dings-board-wrapper">
    <div class="board board-list-card" id="boardList">
            
        <div class="board-head">

            <!-- 게시판 카테고리 -->
            <?php if ($is_category) { ?>
            <nav class="nav-category">
                <h2 class="sound_only"><?php echo $board['bo_subject'] ?> 카테고리</h2>
                <ul class="board-list-category">
                    <?php echo $category_option ?>
                </ul>
            </nav>
            <?php } ?>
            <!-- / 게시판 카테고리 -->
        
            <!-- 검색 필드 -->
            <fieldset>
                <form name="fsearch" method="get">
                    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
                    <input type="hidden" name="sca" value="<?php echo $sca ?>">
                    <input type="hidden" name="sop" value="and">
                    <div class="board-search">
                        <div class="search-select noselect">
                            <select name="sfl" id="sfl" class="real-select slt-search">
                                <?php echo $searchOptions[0]; ?>
                            </select>

                            <span class="selected-value">
                                <div class="selected-value-inner">
                                    <span class="selected-text">
                                        전체
                                    </span>
                                    <span class="select-arrows">
                                        <i class="fa fa-chevron-up close"></i>
                                        <i class="fa fa-chevron-down open"></i>
                                    </span>
                                </div>
                            </span>
                            <ul class="fake-select" tabindex="0">
                                <?php echo $searchOptions[1]; ?>
                            </ul>
                        </div>
                        <div class="search-input">
                            <label for="iptSearch">
                                <input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" class="ipt-search" id="iptSearch" size="25" maxlength="20" placeholder="검색어를 입력하세요.">
                            </label>
                            <button class="btn-search">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </fieldset>
            <!-- / 검색 필드 -->

            <!-- 페이지 정보 -->
            <div class="board-info">
                총 <?php echo number_format($total_count) ?>개, <?php echo $page ?> 쪽
            </div>
            <!-- / 페이지 정보 -->
        </div>

        <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
            <input type="hidden" name="sw" value="">

            <div class="board-body">

                <?php if ($gallery) { ?>
                <!-- 갤러리 스킨 -->
                <ul class="board-card-list gallery js-grid">
                    <?php if (count($list) < 1) { ?>
                    <li class="nothing">
                        <div class="board-card-inner">
                            게시물이 없습니다.
                        </div>
                    </li>
                    <?php } else { ?>
                    <?php for ($i=0; $i<count($list); $i++) { ?>
                    <li class="js-grid-item">
                        <div class="board-card-inner">
                            <div class="board-card-thumb">
                                <?php
                                include_once(G5_LIB_PATH.'/thumbnail.lib.php');
                                $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], '395', '0');
                                if ($thumb['src'] && (!isset($list[$i]['icon_secret']) || empty($list[$i]['icon_secret']))) {
                                ?>
                                <a href="<?php echo $list[$i]['href'] ?>" style="background-image:url('<?php echo $thumb['src'] ?>');">
                                    <img src="<?php echo $thumb['src'] ?>" alt="">
                                </a>
                                <?php } else { ?>
                                    <div class="board-card-noimage">
                                        <img src="<?php echo $board_skin_url ?>/img/noimage.png" alt="">
                                        <div class="board-card-noimage-comment">
                                            <?php if (isset($list[$i]['icon_secret']) && !empty($list[$i]['icon_secret'])) { ?>
                                                이미지를 볼 수 없습니다.
                                            <?php } else { ?>
                                                이미지가 없습니다.
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="board-card-text">
                                <div class="board-card-text-inner">
                                    <div class="board-card-text-inner-inner">
                                        <?php if ($is_category && $list[$i]['ca_name']) { ?>
                                        <div class="board-card-category ellipsis">
                                            <a href="<?php echo $list[$i]['ca_name_href'] ?>">
                                                <?php echo $list[$i]['ca_name'] ?>
                                            </a>
                                        </div>
                                        <?php } ?>
                                        <div class="board-card-title ellipsis">
                                            <?php for ($re = 0; $re < strlen($list[$i]['wr_reply']); $re++) { ?>
                                                <span class="board-card-icon-reply">Re:</span>
                                            <?php } ?>
                                            <a href="<?php echo $list[$i]['href'] ?>">
                                                <?php if (isset($list[$i]['icon_secret']) && !empty($list[$i]['icon_secret'])) { ?><span class="board-card-icon-secret"><i class="fa fa-key"></i></span><?php } ?>
                                                <?php echo trim(str_replace('Re:', '', $list[$i]['subject'])) ?>
                                                <?php if ($list[$i]['comment_cnt']) echo '<span class="sound_only">댓글</span><span class="board-text-count-comments ">'.$list[$i]['wr_comment'].'</span><span class="sound_only">개</span>'; ?></a>
                                        </div>

                                        <div class="board-card-name ellipsis">
                                            <?php echo $list[$i]['name'] ?>
                                        </div>
                                        <div class="board-card-moreinfo ellipsis">
                                            <span class="moreinfo-item board-card-number">
                                                <?php
                                                if ($list[$i]['is_notice']) echo '<span class="board-text-notice">공지</span>';
                                                else if ($wr_id == $list[$i]['wr_id']) echo '<span class="board-text-current">열람중</span>';
                                                else echo '<span class="board-text-number">NO. '.$list[$i]['num'].'</span>';
                                                ?>                                                    
                                            </span>
                                            <span class="moreinfo-item board-card-hit">H <?php echo $list[$i]['wr_hit'] ?></span>
                                            <?php if ($is_good) { ?><span class="moreinfo-item board-card-good">G <?php echo $list[$i]['wr_good'] ?></span><?php } ?>
                                            <?php if ($is_nogood) { ?><span class="moreinfo-item board-card-bad">B <?php echo $list[$i]['wr_nogood'] ?></span><?php } ?>
                                        </div>
                                        <div class="board-card-info ellipsis">
                                            <div class="board-card-info-date">
                                                <?php echo date("Y.d.m", strtotime($list[$i]['datetime'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($is_checkbox) { ?>
                                <div class="board-card-check">
                                    <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="hidden-checkbox js-checkbox">
                                    <label class="checkbox-fake" for="chk_wr_id_<?php echo $i ?>">
                                        <div class="checkbox-fake-icon"></div>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
                <?php add_javascript('<script src="'.$board_skin_url.'/js/masonry.pkgd.min.js"></script>', 0); ?>
                <script>
                    $(window).load(function(){
                        $('.js-grid').masonry({
                            itemSelector: '.js-grid-item'
                        });
                    });
                </script>
                <!--/ 갤러리 스킨 -->
                <?php } ?>

                <?php if ($card) { ?>
                <!-- 카드형 스킨 -->
                <ul class="board-card-list equal-height">
                    <?php if (count($list) < 1) { ?>
                    <li class="nothing">
                        <div class="board-card-inner">
                            게시물이 없습니다.
                        </div>
                    </li>
                    <?php } else { ?>
                    <?php for ($i=0; $i<count($list); $i++) { ?>
                    <li>
                        <div class="board-card-inner">
                            <div class="board-card-thumb">
                                <?php
                                include_once(G5_LIB_PATH.'/thumbnail.lib.php');
                                $thumb = get_list_thumbnail($bo_table, $list[$i]['wr_id'], '395', '260', false, true);
                                if ($thumb['src'] && (!isset($list[$i]['icon_secret']) || empty($list[$i]['icon_secret']))) {
                                ?>
                                <a href="<?php echo $list[$i]['href'] ?>">
                                    <img src="<?php echo $thumb['src'] ?>" alt="">
                                </a>
                                <?php } else { ?>
                                    <div class="board-card-noimage">
                                        <img src="<?php echo $board_skin_url ?>/img/noimage.png" alt="">
                                        <div class="board-card-noimage-comment">
                                            <?php if (isset($list[$i]['icon_secret']) && !empty($list[$i]['icon_secret'])) { ?>
                                                이미지를 볼 수 없습니다.
                                            <?php } else { ?>
                                                이미지가 없습니다.
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="board-card-text">
                                <?php if ($is_category && $list[$i]['ca_name']) { ?>
                                <div class="board-card-category ellipsis">
                                    <a href="<?php echo $list[$i]['ca_name_href'] ?>">
                                        <?php echo $list[$i]['ca_name'] ?>
                                    </a>
                                </div>
                                <?php } ?>
                                <div class="board-card-title ellipsis">
                                    <?php for ($re = 0; $re < strlen($list[$i]['wr_reply']); $re++) { ?>
                                        <span class="board-card-icon-reply">Re:</span>
                                    <?php } ?>
                                    <a href="<?php echo $list[$i]['href'] ?>">
                                        <?php if (isset($list[$i]['icon_secret']) && !empty($list[$i]['icon_secret'])) { ?><span class="board-card-icon-secret"><i class="fa fa-key"></i></span><?php } ?>
                                        <?php echo trim(str_replace('Re:', '', $list[$i]['subject'])) ?>
                                        <?php if ($list[$i]['comment_cnt']) echo '<span class="sound_only">댓글</span><span class="board-text-count-comments ">'.$list[$i]['wr_comment'].'</span><span class="sound_only">개</span>'; ?></a>
                                </div>
                                <div class="board-card-content">
                                    <a href="<?php echo $list[$i]['href'] ?>">
                                        <?php if (isset($list[$i]['icon_secret']) && !empty($list[$i]['icon_secret'])) { ?>
                                            비밀번호 보호중
                                        <?php } else { ?>
                                        <?php echo cut_str(str_replace('> ', '', $list[$i]['wr_content']), 50, '...') ?>
                                        <?php } ?>
                                    </a>
                                </div>
                                <div class="board-card-name ellipsis">
                                    <?php echo $list[$i]['name'] ?>
                                </div>
                                <div class="board-card-moreinfo ellipsis">
                                    <span class="moreinfo-item board-card-number">
                                        <?php
                                        if ($list[$i]['is_notice']) echo '<span class="board-text-notice">공지</span>';
                                        else if ($wr_id == $list[$i]['wr_id']) echo '<span class="board-text-current">열람중</span>';
                                        else echo '<span class="board-text-number">NO. '.$list[$i]['num'].'</span>';
                                        ?>                                                    
                                    </span>
                                    <span class="moreinfo-item board-card-hit">H <?php echo $list[$i]['wr_hit'] ?></span>
                                    <?php if ($is_good) { ?><span class="moreinfo-item board-card-good">G <?php echo $list[$i]['wr_good'] ?></span><?php } ?>
                                    <?php if ($is_nogood) { ?><span class="moreinfo-item board-card-bad">B <?php echo $list[$i]['wr_nogood'] ?></span><?php } ?>
                                </div>
                                <div class="board-card-info ellipsis">
                                    <div class="board-card-info-date">
                                        <?php echo date("Y.d.m", strtotime($list[$i]['datetime'])); ?>
                                    </div>
                                    <?php
                                    if($board['bo_use_list_file']){
                                        if ($list[$i]['file']['count'] > 0) {
                                            $ss_name = 'ss_view_'.$bo_table.'_'.$list[$i]['wr_id'];
                                            if (!get_session($ss_name)) set_session($ss_name, TRUE);
                                    ?>
                                    <div class="board-card-info-button">
                                        <a href="<?php echo $list[$i]['file'][0]['href'] ?>">Download</a>
                                    </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php if ($is_checkbox) { ?>
                                <div class="board-card-check">
                                    <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="hidden-checkbox js-checkbox">
                                    <label class="checkbox-fake" for="chk_wr_id_<?php echo $i ?>">
                                        <div class="checkbox-fake-icon"></div>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
                <!--/ 카드형 스킨 -->
                <?php }  ?>

                <?php if ($download) { ?>
                <!-- 다운로드형 스킨 -->
                <ul class="board-card-list equal-height">
                    <?php if (count($list) < 1) { ?>
                    <li class="nothing">
                        <div class="board-card-inner">
                            게시물이 없습니다.
                        </div>
                    </li>
                    <?php } else { ?>
                    <?php for ($i=0; $i<count($list); $i++) { ?>
                    <li>
                        <div class="board-card-inner">
                            <div class="board-card-text">
                                <?php if ($is_category && $list[$i]['ca_name']) { ?>
                                <div class="board-card-category ellipsis">
                                    <a href="<?php echo $list[$i]['ca_name_href'] ?>">
                                        <?php echo $list[$i]['ca_name'] ?>
                                    </a>
                                </div>
                                <?php } ?>
                                <div class="board-card-title ellipsis">
                                    <?php for ($re = 0; $re < strlen($list[$i]['wr_reply']); $re++) { ?>
                                        <span class="board-card-icon-reply">Re:</span>
                                    <?php } ?>
                                    <a href="<?php echo $list[$i]['href'] ?>">
                                        <?php if (isset($list[$i]['icon_secret']) && !empty($list[$i]['icon_secret'])) { ?><span class="board-card-icon-secret"><i class="fa fa-key"></i></span><?php } ?>
                                        <?php echo trim(str_replace('Re:', '', $list[$i]['subject'])) ?>
                                        <?php if ($list[$i]['comment_cnt']) echo '<span class="sound_only">댓글</span><span class="board-text-count-comments ">'.$list[$i]['wr_comment'].'</span><span class="sound_only">개</span>'; ?></a>
                                </div>
                                <div class="board-card-content">
                                    <a href="<?php echo $list[$i]['href'] ?>">
                                        <?php if (isset($list[$i]['icon_secret']) && !empty($list[$i]['icon_secret'])) { ?>
                                            비밀번호 보호중
                                        <?php } else { ?>
                                        <?php echo cut_str(str_replace('> ', '', $list[$i]['wr_content']), 50, '...') ?>
                                        <?php } ?>
                                    </a>
                                </div>
                                <div class="board-card-name ellipsis">
                                    <?php echo $list[$i]['name'] ?>
                                </div>
                                <div class="board-card-moreinfo ellipsis">
                                    <span class="moreinfo-item board-card-number">
                                        <?php
                                        if ($list[$i]['is_notice']) echo '<span class="board-text-notice">공지</span>';
                                        else if ($wr_id == $list[$i]['wr_id']) echo '<span class="board-text-current">열람중</span>';
                                        else echo '<span class="board-text-number">NO. '.$list[$i]['num'].'</span>';
                                        ?>                                                    
                                    </span>
                                    <span class="moreinfo-item board-card-hit">H <?php echo $list[$i]['wr_hit'] ?></span>
                                    <?php if ($is_good) { ?><span class="moreinfo-item board-card-good">G <?php echo $list[$i]['wr_good'] ?></span><?php } ?>
                                    <?php if ($is_nogood) { ?><span class="moreinfo-item board-card-bad">B <?php echo $list[$i]['wr_nogood'] ?></span><?php } ?>
                                </div>
                                <div class="board-card-info ellipsis">
                                    <div class="board-card-info-date">
                                        <?php echo date("Y.d.m", strtotime($list[$i]['datetime'])); ?>
                                    </div>
                                    <?php
                                    if($board['bo_use_list_file']){
                                        if ($list[$i]['file']['count'] > 0) {
                                            $ss_name = 'ss_view_'.$bo_table.'_'.$list[$i]['wr_id'];
                                            if (!get_session($ss_name)) set_session($ss_name, TRUE);
                                    ?>
                                    <div class="board-card-info-button">
                                        <a href="<?php echo $list[$i]['file'][0]['href'] ?>">Download</a>
                                    </div>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php if ($is_checkbox) { ?>
                                <div class="board-card-check">
                                    <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="hidden-checkbox js-checkbox">
                                    <label class="checkbox-fake" for="chk_wr_id_<?php echo $i ?>">
                                        <div class="checkbox-fake-icon"></div>
                                    </label>
                                </div>
                            <?php } ?>
                        </div>
                    </li>
                    <?php } ?>
                    <?php } ?>
                </ul>
                <!-- /다운로드형 스킨 -->
                <?php } ?>

                <?php if ($bbs) { ?>
                <!-- 게시판형 스킨 -->
                <table class="board-normal-list<?php if ($is_checkbox) echo ' is_checkbox' ?>">
                    <colgroup>
                        <?php if ($is_checkbox) { ?><col class="col1"><?php } ?>
                        <col class="col2">
                        <col class="col3">
                        <col class="col4">
                        <col class="col5">
                        <?php if ($is_good) { ?><col class="col6"><?php } ?>
                        <?php if ($is_nogood) { ?><col class="col7"><?php } ?>
                        <col class="col8">
                    </colgroup>
                    <tbody>
                        <?php if (count($list) < 1) { ?>
                            <tr class="nothing"><td colspan="<?php echo $colspan ?>">게시물이 없습니다.</td></tr> 
                        <?php } ?>
                        <?php for ($i=0; $i<count($list); $i++) { ?>
                        <tr<?php if ($list[$i]['is_notice']) echo ' class="notice"'; ?>>
                            <?php if ($is_checkbox) { ?>
                            <td class="col1 clickable-wrapper">
                                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>" class="hidden-checkbox js-checkbox">
                                <label class="checkbox-fake" for="chk_wr_id_<?php echo $i ?>">
                                    <div class="checkbox-fake-icon"></div>
                                </label>
                            </td>
                            <?php } ?>
                            <td class="col2<?php if ($list[$i]['is_notice']) echo ' class=" col-notice"'; ?><?php if ($list[$i]['is_notice']) echo ' class=" col-visited"'; ?>">
                                <?php
                                if ($list[$i]['is_notice']) echo '<span class="board-text-notice">공지</span>';
                                else if ($wr_id == $list[$i]['wr_id']) echo '<span class="board-text-current">열람중</span>';
                                else echo '<span class="board-text-number">'.$list[$i]['num'].'</span>';
                                ?>
                            </td>
                            <td class="col3" >
                                <div class="board-subject-wrapper">
                                    <?php if (isset($list[$i]['reply']) && !empty($list[$i]['reply']) && (int)$list[$i]['reply'] > 0) { ?>
                                    <?php for ($re = 0; $re < strlen($list[$i]['wr_reply']); $re++) { ?>
                                        <span class="board-text-icon reply">Re:</span>
                                    <?php } ?>
                                    <?php } ?>
                                    <?php if ($is_category && $list[$i]['ca_name']) { ?>
                                    <a href="<?php echo $list[$i]['ca_name_href'] ?>" class="board-text-category"><?php echo $list[$i]['ca_name'] ?></a>
                                    <?php } ?>
                                    <a href="<?php echo $list[$i]['href'] ?>" class="board-text-title">
                                        <?php if (isset($list[$i]['icon_secret']) && !empty($list[$i]['icon_secret'])) echo '<span class="board-text-icon secret"><i class="fa fa-key"></i></span>' ?>
                                        <?php if ($list[$i]['comment_cnt']) echo '<span class="sound_only">댓글</span><span class="board-text-count-comments ">'.$list[$i]['wr_comment'].'</span><span class="sound_only">개</span>'; ?>
                                        <?php echo trim(str_replace('Re:', '', $list[$i]['subject'])) ?>
                                    </a>
                                    <?php
                                    if (isset($list[$i]['icon_new']) && !empty($list[$i]['icon_new'])) echo '<span class="board-text-icon new">N</span>';
                                    if (isset($list[$i]['icon_hot']) && !empty($list[$i]['icon_hot'])) echo '<span class="board-text-icon hot">H</span>';
                                    if (isset($list[$i]['icon_file']) && !empty($list[$i]['icon_file'])) echo '<span class="board-text-icon download"><i class="fa fa-download"></i></span>';
                                    ?>
                                </div>
                            </td>
                            <td class="col4"><?php echo $list[$i]['name'] ?></td>
                            <td class="col5">H <?php echo $list[$i]['wr_hit'] ?></td>
                            <?php if ($is_good) { ?><td class="col6">G <?php echo $list[$i]['wr_good'] ?></td><?php } ?>
                            <?php if ($is_nogood) { ?><td class="col7">B <?php echo $list[$i]['wr_nogood'] ?></td><?php } ?>
                            <td class="col8"><?php echo date("Y.d.m", strtotime($list[$i]['datetime'])); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <?php if ($is_admin == 'super') {  ?>
                    <tfoot>
                        <tr class="row-admin">
                            <?php if ($is_checkbox) { ?>
                            <td class="col1">
                                <input type="checkbox" id="checkboxToggle" class="hidden-checkbox">
                                <label class="checkbox-fake admin" for="checkboxToggle">
                                    <div class="checkbox-fake-icon"></div>
                                </label>
                            </td>
                            <?php }  ?>
                            <!-- <td class="col2">관리자</td> -->
                            <td colspan="<?php echo ($colspan - 1) ?>" class="col-etc">
                                <ul class="board-list-admin">
                                    <?php if ($is_checkbox) { ?>
                                    <li><button value="선택삭제" name="btn_submit" onclick="document.pressed=this.value">선택삭제</button></li>
                                    <li><button value="선택복사" name="btn_submit" onclick="document.pressed=this.value">선택복사</button></li>
                                    <li><button value="선택이동" name="btn_submit" onclick="document.pressed=this.value">선택이동</button></li>
                                    <?php }  ?>
                                    <li><a href="<?php echo $admin_href ?>" target="_blank" title="관리자">관리자</a></li>
                                </ul>
                            </td>
                        </tr>
                    </tfoot>
                    <?php }  ?>
                </table>
                <!-- /게시판형 스킨 -->
                <?php } ?>

                <?php if (!$bbs) {
                    // BBS 스킨이 아닐 때에만 나타남. BBS 스킨은 table tfoot 부분에 이미 관리자용 인터페이스(DOM)을 포함하고 있음
                ?>
                <?php if ($is_admin == 'super') {  ?>
                <!-- 관리자용 인터페이스 -->
                <div class="card-admin-scroller">
                    <ul class="board-list-admin">
                        <?php if ($is_checkbox) { ?>
                        <li>
                            <input type="checkbox" id="checkboxToggle" class="hidden-checkbox">
                            <label class="checkbox-fake admin" for="checkboxToggle">
                                <div class="checkbox-fake-icon"></div>
                            </label>
                        </li>
                        <li><button value="선택삭제" name="btn_submit" onclick="document.pressed=this.value">선택삭제</button></li>
                        <li><button value="선택복사" name="btn_submit" onclick="document.pressed=this.value">선택복사</button></li>
                        <li><button value="선택이동" name="btn_submit" onclick="document.pressed=this.value">선택이동</button></li>
                        <?php }  ?>
                        <li><a href="<?php echo $admin_href ?>" target="_blank" title="관리자">관리자</a></li>
                    </ul>
                </div>
                <!-- / 관리자용 인터페이스 -->
                <?php }  ?>
                <?php } ?>

                <!-- 사용자용 인터페이스 -->
                <?php if ($list_href || $is_checkbox || $write_href) { ?>
                <div class="board-interface">
                    <?php if ($list_href || $write_href) { ?>
                    <div class="board-interface-user">
                        <?php if ($rss_href) { ?><a href="<?php echo $rss_href ?>" target="_blank" class="btn-board write">RSS</a><?php } ?>
                        <?php if ($write_href) { ?><a href="<?php echo $write_href ?>" class="btn-board write">글쓰기</a><?php } ?>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
                <!-- / 사용자용 인터페이스 -->
            </div>

            <?php if (!empty($write_pages)) { ?>
            <div class="board-foot">
                <?php echo $write_pages; ?>
            </div>
            <?php } ?>
        </form>
    </div>
</article>

<script>
    var formSelectWrapper = '.search-select';
    var formSelect = '.real-select';
    var formSelectTrigger = '.selected-value';
    var formSelectResult = '.selected-text';
    var formFake = '.fake-select';
    var formFakeOption = '.fake-select li';

    function selectToggle(){
        $(formSelectWrapper).toggleClass('focus');
    }

    function changeValue($this){
        var thisIndex = $this.prop('selectedIndex');
        var selected = $(formFake).find('li').eq(thisIndex);
        var selectedTxt = selected.text();
        selected.addClass('selected').siblings().removeClass();
        $(formSelectResult).text(selectedTxt);
    }
    $(document).mouseup(function(e) {
        var container = $(formSelectWrapper);
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $(formSelectWrapper).removeClass('focus');
        }
    });
    $(document).on('click', formSelectTrigger, selectToggle)
    .on('click', formFakeOption, function(){
        selectToggle();
        var $this = $(this);
        if ($this.hasClass('selected')) return;
        var thisIndex = $(formFakeOption).index($this);
        $(formSelect).find('option').eq(thisIndex).prop('selected', true).trigger('change');
    }).on('load change', formSelect, function(){
        changeValue($(this));
    });
    $(document).ready(function(){
        changeValue($(formSelect));
    });

    $(formFake).focus(function(){
        selectToggle();
    }).blur(function(){
        selectToggle();
    });

    var checkboxEle = $('.js-checkbox');
    var checkboxCtl = $('#checkboxToggle');
    checkboxCtl.change(function(){
        var $this = $(this);
        var thisVal = $(this).prop('checked');
        if (thisVal) checkboxEle.prop('checked', true);
        else checkboxEle.prop('checked', false);
    });
    $('.js-checkbox').change(function(){
        var $this = $(this);
        var runCheck = true;
        checkboxEle.each(function(){
            if (!$(this).prop('checked')) runCheck = false;
        });
        if (runCheck) checkboxCtl.prop('checked', true);
        else checkboxCtl.prop('checked', false);
    });

    <?php if ($is_checkbox) { ?>

    function fboardlist_submit(f) {
        var chk_count = 0;

        for (var i=0; i<f.length; i++) {
            if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
                chk_count++;
        }

        if (!chk_count) {
            alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
            return false;
        }

        if(document.pressed == "선택복사") {
            select_copy("copy");
            return;
        }

        if(document.pressed == "선택이동") {
            select_copy("move");
            return;
        }

        if(document.pressed == "선택삭제") {
            if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
                return false;

            f.removeAttribute("target");
            f.action = g5_bbs_url+"/board_list_update.php";
        }

        return true;
    }
    function select_copy(sw) {
        var f = document.fboardlist;

        if (sw == "copy")
            str = "복사";
        else
            str = "이동";

        var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

        f.sw.value = sw;
        f.target = "move";
        f.action = g5_bbs_url+"/move.php";
        f.submit();
    }
    <?php } ?>
    
</script>
            

<!-- } 게시판 목록 끝 -->
