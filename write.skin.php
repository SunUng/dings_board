<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_stylesheet('<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600&family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">', 0);

$option = '';
$option_hidden = '';
if ($is_notice || $is_html || $is_secret || $is_mail) { 
    $option = '';
    if ($is_notice) {
        $option .= PHP_EOL.'
                <li>
                    <input type="checkbox" name="notice" value="1" id="notice" class="hidden-checkbox selec_chk" '.$notice_checked.'>
                    <label class="checkbox-fake" for="notice">
                        <div class="checkbox-fake-icon"></div>
                        <div class="checkbox-fake-text">공지</div>
                    </label>
                </li>
        ';
    }
    if ($is_html) {
        if ($is_dhtml_editor) {
            $option_hidden .= '<input type="hidden" value="html1" name="html">';
        } else {
            $option .= PHP_EOL.'
                <li>
                    <input type="checkbox" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" id="html" class="hidden-checkbox selec_chk" '.$html_checked.'>
                    <label class="checkbox-fake" for="html">
                        <div class="checkbox-fake-icon"></div>
                        <div class="checkbox-fake-text">HTML</div>
                    </label>
                </li>
            ';
        }
    }
    if ($is_secret) {
        if ($is_admin || $is_secret==1) {
            $option .= PHP_EOL.'
                <li>
                    <input type="checkbox" name="secret" value="secret" id="secret" class="hidden-checkbox selec_chk" '.$secret_checked.'>
                    <label class="checkbox-fake" for="secret">
                        <div class="checkbox-fake-icon"></div>
                        <div class="checkbox-fake-text">비밀글</div>
                    </label>
                </li>
            ';
        } else {
            $option_hidden .= '<input type="hidden" name="secret" value="secret">';
        }
    }
    if ($is_mail) {
        $option .= PHP_EOL.'
                <li>
                    <input type="checkbox" name="mail" value="mail" id="mail" class="hidden-checkbox selec_chk" '.$recv_email_checked.'>
                    <label class="checkbox-fake" for="mail">
                        <div class="checkbox-fake-icon"></div>
                        <div class="checkbox-fake-text">답변메일</div>
                    </label>
                </li>
        ';
    }
}
?>


<article class="dings-board dings-board-wrapper">

    <div class="board-write">
        <h3 class="sound_only">글 작성</h3>

        <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
            <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
            <input type="hidden" name="w" value="<?php echo $w ?>">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">

            <?php if ($is_category) { ?>
            <div class="write-form-item write-form-item-multiple type1 required">
                <div class="write-form-item-inner">
                    <label for="ca_name" class="write-form-label">
                        분류 선택
                    </label>
                    <div class="write-form-content">


                        <div class="category-select noselect">
                            <select name="ca_name" id="ca_name" class="real-select slt-search" required>
                                <option value="">분류를 선택하세요</option>
                                <?php echo $category_option ?>
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
                            <?php
                            $categories = explode("|", $board['bo_category_list'].($is_admin?"|공지":"")); // 구분자가 | 로 되어 있음    
                            $str = "";
                            for ($i=0; $i<count($categories); $i++) {
                                $category = trim($categories[$i]);
                                if (!$category) continue;
                                $str .= '<li';
                                if ($category == $ca_name) {
                                    $str .= ' class="selected"';
                                }
                                $str .= ">$categories[$i]</li>\n";
                            }
                            $category_list = $str;
                            ?>
                            <ul class="fake-select">
                                <li>분류를 선택하세요</li>
                                <?php echo $category_list ?>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ($is_name) { ?>
            <div class="write-form-item write-form-item-multiple type1 required">
                <div class="write-form-item-inner">
                    <label for="wr_name" class="write-form-label">
                        이름
                    </label>
                    <div class="write-form-content">

                        <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="write-form-text" placeholder="이름을 입력해주세요">

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ($is_password) { ?>
            <div class="write-form-item write-form-item-multiple type1 required">
                <div class="write-form-item-inner">
                    <label for="wr_password" class="write-form-label">
                        비밀번호
                    </label>
                    <div class="write-form-content">

                        <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="write-form-text <?php echo $password_required ?>" placeholder="비밀번호를 입력해주세요" autocomplete="off">

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ($is_email) { ?>
            <div class="write-form-item write-form-item-multiple type1">
                <div class="write-form-item-inner">
                    <label for="wr_email" class="write-form-label">
                        이메일
                    </label>
                    <div class="write-form-content">

                        <input type="email" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="write-form-text " placeholder="이메일 주소를 입력해주세요">

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ($is_homepage) { ?>
            <div class="write-form-item write-form-item-multiple type1">
                <div class="write-form-item-inner">
                    <label for="wr_homepage" class="write-form-label">
                        홈페이지
                    </label>
                    <div class="write-form-content">

                        <input type="text" name="wr_homepage" value="<?php echo $homepage ?>" id="wr_homepage" class="write-form-text" size="50" placeholder="홈페이지를 입력해주세요">

                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="write-form-item write-form-item-multiple type1 required">
                <div class="write-form-item-inner">
                    <label for="wr_subject" class="write-form-label">
                        제목
                    </label>
                    <div class="write-form-content">
                        <div class="wirte-form-title-wrapper">
                            <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="write-form-text" size="50" maxlength="255" placeholder="제목을 입력해주세요">
                            <?php if ($is_member) { // 임시 저장된 글 기능 ?>
                            <script src="<?php echo G5_JS_URL; ?>/autosave.js"></script>
                            <?php if($editor_content_js) echo $editor_content_js; ?>
                            <button type="button" id="btn_autosave" class="btn_frmline">임시 저장된 글 (<span id="autosave_count"><?php echo $autosave_count; ?></span>)</button>
                            <div id="autosave_pop">
                                <strong>임시 저장된 글 목록</strong>
                                <ul></ul>
                                <div><button type="button" class="autosave_close">닫기</button></div>
                            </div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>

            <div class="write-form-item write-form-item-multiple type1 required">
                <div class="write-form-item-inner">
                    <label for="wr_content" class="write-form-label">
                        내용
                    </label>
                    <div class="write-form-content">

                        <?php if ($option) { ?>
                            <ul class="list-write-option">
                                <?php echo $option ?>
                            </ul>
                        <?php } ?>
                        <div class="wr_content <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                            <?php if($write_min || $write_max) { ?>
                            <!-- 최소/최대 글자 수 사용 시 -->
                            <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                            <?php } ?>
                            <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                            <?php if($write_min || $write_max) { ?>
                            <!-- 최소/최대 글자 수 사용 시 -->
                            <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>

            <?php //if ($is_link && 0 < G5_LINK_COUN) { ?>
            <div class="write-form-item write-form-item-multiple type1">
                <div class="write-form-item-inner">
                    <span class="write-form-label">
                        링크
                    </span>
                    <div class="write-form-content">
                        
                        <?php for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
                        <div class="write-form-file-row">
                            <input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){ echo $write['wr_link'.$i]; } ?>" id="wr_link<?php echo $i ?>" class="write-form-text" size="50" placeholder="링크  #<?php echo $i ?>">
                        </div>
                        <?php } ?>


                    </div>
                </div>
            </div>
            <?php //} ?>

            <?php if ($is_file && 0 < $file_count) { ?>
            <div class="write-form-item write-form-item-multiple type1">
                <div class="write-form-item-inner">
                    <label for="reg_mb_id" class="write-form-label">
                        첨부파일
                    </label>
                    <div class="write-form-content">

                        <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
                        <div class="write-form-file-row">
                            <?php if($board['bo_use_list_file'] && $i === 0){ ?>
                                <div class="write-form-guide">
                                    현재 게시판 설정 '목록에서 첨부파일 사용'이 작동중입니다. 목록에서 다운로드 가능한 첨부파일은 <strong>첫번째 첨부파일</strong> 항목입니다. 이용에 참고해주시기 바랍니다.
                                </div>
                            <?php } ?>
                            <div class="write-form-file-col">
                                <div class="write-form-file">
                                    <input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="write-file-input">
                                    <span class="write-file-trigger">
                                        파일선택
                                    </span>
                                    <span class="write-file-label">
                                        선택된 파일이 없습니다.
                                    </span>
                                    <?php if ($w == 'u' && $file[$i]['file']) { ?>
                                    <span class="write-file-remover">
                                        <input type="checkbox" name="bf_file_del[<?php echo $i; ?>]" value="1" id="bf_file_del<?php echo $i ?>" class="hidden-checkbox selec_chk">
                                        <label class="checkbox-fake" for="bf_file_del<?php echo $i ?>">
                                            <div class="checkbox-fake-icon"></div>
                                            <div class="checkbox-fake-text"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</div>
                                        </label>
                                    </span>
                                    <?php } ?>
                                </div>
                            </div>
                            <?php if ($is_file_content) { ?>
                            <div class="write-form-file-col write-form-file-desc">
                                <input type="text" name="bf_content[]" value="<?php echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="write-form-text" size="50" placeholder="파일 설명을 입력해주세요.">
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ($is_use_captcha) { ?>
            <div class="write-form-item write-form-item-multiple type1 required">
                <div class="write-form-item-inner">
                    <label for="reg_mb_id" class="write-form-label">
                        자동등록방지
                    </label>
                    <div class="write-form-content">
                    
                        <?php echo $captcha_html ?>

                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="write-form-item write-form-item-multiple type1">
                <div class="write-form-item-inner">

                    <div class="write-form-content">
                        <div class="write-form-btns">
                            <a href="<?php echo get_pretty_url($bo_table); ?>" class="btn-board cancel">취소</a>
                            <button type="submit" id="btn_submit" accesskey="s" class="btn-board write">작성완료</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
</airticle>

<script>
    var formSelectWrapper = '.category-select';
    var formSelect = '.real-select';
    var formSelectTrigger = '.selected-value';
    var formSelectResult = '.selected-text';
    var formFake = '.fake-select';
    var formFakeOption = '.fake-select li';

    function selectToggle(){
        $(formSelectWrapper).toggleClass('focus');
    }
    // function closeSlect(){
    //     $(formSelectWrapper).removeClass('focus');
    // }

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

    //파일 첨부기능
    var inputs = $('.write-form-file');
    inputs.each(function(){
        var $this = $(this);
        var fileInput = $this.find('.write-file-input');
        var label = $this.find('.write-file-label');
        fileInput.on('load change onmouseout', function(){
            if (!$(this).val()) {
                $this.removeClass('attatched');
                label.text('선택된 파일이 없습니다.');
            } else {
                var fineName = $(this).val().replace(/^.*[\\\/]/, '');
                $this.addClass('attatched');
                label.text(fineName);
            }
        });
    });

    //자동저장 닫기
    $(document).mouseup(function(e) {
        var container = $('#btn_autosave, #autosave_pop');
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $('#autosave_pop').css('display', 'none');
        }
    });

    //원본 스크립트
    <?php if($write_min || $write_max) { ?>
    // 글자수 제한
    var char_min = parseInt(<?php echo $write_min; ?>); // 최소
    var char_max = parseInt(<?php echo $write_max; ?>); // 최대
    check_byte("wr_content", "char_count");

    $(function() {
        $("#wr_content").on("keyup", function() {
            check_byte("wr_content", "char_count");
        });
    });
    <?php } ?>

    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
</script>