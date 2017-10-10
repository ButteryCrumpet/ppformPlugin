<?php
/**
@package Wordpress
Plugin Name: ppForms
 */

include 'formLib/form.auto.php';
include 'mailer/mail.class.php';
include 'admin-manager.php';
include 'post-types.php';
include 'settings-manager.php';
include 'form-meta.php';
include 'rewrites.php';
include 'ppform-js.php';

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

//shift this around
function run_ppform() {

	$actions = get_actions();
	$form = get_form(1);

	$html = parse_form($form, $actions['next']);

	$dom = new DOMDocument();
	$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

	$config = parse_ini_file('config.ini', true);	
	$form = new AutoForm('default', $dom, $config);

	if ($action === 'entry') {
		return $form->renderBaseForm(true);
	} else if ($action === 'confirm') {
		if ($form->checkValid()) {
			return $form->renderDefaultConfirmation();
		} else {
			return $form->renderErrorForm();
		}
	} else if ($action === 'submit') {
		if ($form->checkValid()) {
			//format in save, send using id, delete on send etc
			$post = save_response($form->theData);
			if (mail_data($form->theData, $config)) {
				return '<h3>Saved and Sent</h3>';
			} else {
				return "<h4>Error3</h4>";
			}
		} else {
			$errors = '';
			foreach ($form->theErrors as $field => $error) {
				$errors .= $field . '--' . $error . '<br>';
			}
			return "<h4>" . $errors  ."</h4>";
		}
	} else {
		return "<h4>Error1</h4>";
	}
}

//turn into class  SET ACTIONS AFTER VALIDATE
function parse_form($form, $action) {
	$href = get_site_url() . '/' . $action . '/' . get_the_ID() . '/';

	$html = '<form class="ppform" id="ppForm-target" action="' . $href . '" method="POST" data-ppformbase >';
	$html .= $form;
	$html .= '<p class="btn"><input type="submit" href="" value="内容を確認する"></p>';
	$html .= '</form>';

	return $html;
}

function get_form($id) {
	return default_contact_form();
}

//add flexible urls for different schemes
function get_actions() {
	global $wp;
	$params = explode('/', $wp->request);
	if ($params[0] === 'confirm' ) {
		$actions['current'] = 'confirm';
		$actions['next'] = 'submit';
	} else if ($params[0] === 'submit' ) {
		$actions['current'] = 'submit';
		$actions['next'] = 'complete';
	} else {
		$actions['current'] = 'entry';
		$actions['next'] = 'confirm';
	}

	return $actions;
}

//form details should be inserted
//format data and save
function save_response($data) {

	$args = array(
		'post_title' => current_time("Y-m-d H:i:s") . ' ' . rand(1, 1000),
		'post_type' =>'ppform-response',
		'meta_input' => array(
			'data' => $data
		)
	);

	return wp_insert_post( $args );
}

//take response ID and send, validate against time etc and sent meta
function mail_data($data, $config) {

    $input = '';
    foreach ($data as $key => $value) {
        $input .= ucfirst($key). ": ". $value ."\r\n";
    }

    $parsed = file_get_contents($config["formatTemplate"]);
    $formated = str_replace("{data}", $input, $parsed);
    
    foreach ($data as $key => $value) {
        $search = "{".$key."}";
        $formated = str_replace($search, $value, $formated);
    }

    $to_mail = $config[$data[$config["emailInputName"]]];
    if (!isset($to_mail)) {
        $to_mail = $config['defaultTo'];
    }

    $mail_data = array(
        'to' => $to_mail,
        'from' => $data[$config["customerMailInputName"]],
        'CC' => $data["CC"],
        'Bcc' => $config["Bcc"],
        'message' => $formated,
        'subject' => $data['subject'],
	);

    $mail = new Mail($mail_data);
   	return $mail->send();
}

add_shortcode( 'contact-form', 'run_ppform' );
