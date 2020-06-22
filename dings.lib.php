<?php
// 함수
function dings_selected_options($sfl){
    // get_board_sfl_select_options 참고함
    global $is_admin;

    $options = '';
    $options .= '<option value="wr_subject" '.get_selected($sfl, 'wr_subject', true).'>제목</option>';
    $options .= '<option value="wr_content" '.get_selected($sfl, 'wr_content').'>내용</option>';
    $options .= '<option value="wr_subject||wr_content" '.get_selected($sfl, 'wr_subject||wr_content').'>제목+내용</option>';
    $dingsList = '';
    $dingsList .= '<li>제목</li>';
    $dingsList .= '<li>내용</li>';
    $dingsList .= '<li>제목+내용</li>';
    if ( $is_admin ){
        $options .= '<option value="mb_id,1" '.get_selected($sfl, 'mb_id,1').'>회원아이디</option>';
        $options .= '<option value="mb_id,0" '.get_selected($sfl, 'mb_id,0').'>회원아이디(코)</option>';
        $dingsList .= '<li>회원아이디</li>';
        $dingsList .= '<li>회원아이디(코)</li>';
    }
    $options .= '<option value="wr_name,1" '.get_selected($sfl, 'wr_name,1').'>글쓴이</option>';
    $options .= '<option value="wr_name,0" '.get_selected($sfl, 'wr_name,0').'>글쓴이(코)</option>';
    $dingsList .= '<li>글쓴이</li>';
    $dingsList .= '<li>글쓴이(코)</li>';
    $optionArr[0] = $options;
    $optionArr[1] = $dingsList;

    return $optionArr;
}

// 변수
$thisMode = $thisMode ? $thisMode : 'bbs';
if ($thisMode === 'bbs') $bbs = true;
if ($thisMode === 'card') $card = true;
if ($thisMode === 'download') $download = true;
if ($thisMode === 'gallery') $gallery = true;
if ($thisMode === 'photo') $photo = true;
if ($thisMode === 'qna') $qna = true;


if ($thisMode === 'bbs' || $thisMode === '') {
  // 선택옵션으로 인해 셀합치기가 가변적으로 변함
  $colspan = 5;
  if ($is_checkbox) $colspan++;
  if ($is_good) $colspan++;
  if ($is_nogood) $colspan++;
}

?>