<?php

//move to template file?
function default_contact_form() {
	$str = '<div class="ppTopErrors">';
	$str .=	'<div class="em" data-error="名前" ></div>';
	$str .=	'<div class="em" data-error="フリガナ" ></div>';
	$str .=	'<div class="em" data-error="メール" ></div>';
	$str .=	'<div class="em" data-error="電話" ></div>';
	$str .=	'<div class="em" data-error="問い" ></div>';
	$str .= '</div>';
	$str .=	'<dl>';
	$str .=	'<dt><span>必須</span>お名前</dt><dd><input name="名前" type="text" placeholder="例)石材　太郎" data-ppForm="text" required></dd>';
	$str .=	'<dt><span>必須</span>ふりがな</dt><dd><input name="フリガナ" type="text" placeholder="例)せきざい　たろう" required data-ppForm="kana"><div class="em" data-error="フリガナ" ></div></dd>';
	$str .=	'<dt><span>必須</span>メールアドレス</dt><dd><input name="メール" type="text" placeholder="例)石材太郎" required data-ppForm="email"></dd>';
	$str .=	'<dt><span>必須</span>電話番号</dt><dd><input name="電話" type="text" required data-ppForm="phone"></dd>';
	$str .=	'<dt><span class="nini">任意</span>お問い合せ内容</dt><dd><textarea name="問い" data-ppForm="jchars"></textarea></dd>';
	$str .=	'</dl>';

	return $str;
}