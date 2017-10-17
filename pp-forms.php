<?php
/**
@package Wordpress
Plugin Name: ppForms
 */

define(PPFORM_FILE, plugin_dir_path(__FILE__));

include 'AdminNoticesAddon/bootstrap.php';
include 'formLib/form.auto.php';
include 'mailer/mail.class.php';
include 'admin-manager.php';
include 'post-types.php';
include 'settings-manager.php';
include 'form-meta.php';
include 'rewrites.php';
include 'ppform-scripts.php';
include 'ppform-templates.php';

register_activation_hook(PPFORM_FILE, 'init_settings');

//shift this around
function run_ppform($atts) {

	extract(shortcode_atts(array(
		'form' => 'Test Form',
	), $atts));
	
	$action = get_action();
	$form_data = get_form_data($form);

	$html = parse_form($form_data['template']);
	$dom = new DOMDocument();
	$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
	
	$config = get_form_settings($form_data['id']);
	
	$ppform = new AutoForm('default', $dom, $config);

	if ($action === 'entry') {
		$form = $ppform->renderBaseForm($config['javascript']); 
		return set_action($form, 'confirm');
	}
	
	$valid = $ppform->checkValid();
	
	if ( ! $valid ) {
		$form = $ppform->renderErrorForm();
		return set_action($form, 'confirm');
	}

	if ( $valid ) {
		if ($action === 'confirm') {
			$form = $ppform->renderDefaultConfirmation();
			return set_action($form, 'submit');
		}
		if ($action === 'submit') {

			$message = $config['complete_message'];

			if ($config['form-actions']['save-response'])
				$post = save_response($ppform->theData);

			if ($config['form-actions']['send-email'])
				$sent = mail_data($ppform->theData, $config['mail']);

			return $message . ' ' . var_dump($sent);
		}
	}
	
	return "missed option";
}

function parse_form($form_template) {
	global $TEMPLATES;

	$parts = explode( ',' ,$form_template);
	$html = '<dl>';
	foreach($parts as $part) {
		if (isset($TEMPLATES[$part])) {
			$html .= $TEMPLATES[$part];
			$added = true;
		}
	}

	if (!$added) {
		$html .= $form_template;
	}

	return $html .= '</dl>';
}

function set_action($form_html, $action) {
	$href = get_site_url() . '/' . $action . '/' . get_the_ID() . '/';

	$html = '<div class="contact-form" ><form class="ppform form-area" id="ppForm-target" action="' . $href . '" method="POST" data-ppformbase >';
	$html .= $form_html;
	$html .= '<p class="btn"><input type="submit" href="" value="内容を確認する"></p>';
	$html .= '</form></div>';

	return $html;
}

function get_form_data($formName) {

	$args = array(
		'title' => $formName,
		'post_type' => 'pp-form'
	);

	$query = new WP_Query( $args );
	$form = $query->posts[0];

	$form_data['template'] = $form->post_content;	
	$form_data['id'] = $form->ID;

	return $form_data;
	
}

//add flexible urls for different schemes
function get_action() {
	global $wp;
	$params = explode('/', $wp->request);
	if ($params[0] === 'confirm' ) {
		$action = 'confirm';
	} else if ($params[0] === 'submit' ) {
		$action = 'submit';
	} else {
		$action = 'entry';
	}

	return $action;
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

    $mail_data = array(
        'to' => $config['send-to'],
        'from' => $config['sent-from'],
        'CC' => $config["cc"],
        'Bcc' => $config["bcc"],
        'message' => $formated,
        'subject' => 'default',
	);

	var_dump($mail_data);

    $mail = new Mail($mail_data);
   	return $mail->send();
}

function get_form_settings($form_id) {

	$VALIDATOR_TYPES = array(
		'text' => "GenericField",
		'email' => "EmailField",
		'kana' => "KanaField",
		'phone' => "PhoneNoField",
		'url' => "URLField",
		'zip' => "JapanZipField",
		'jchars' => "JapaneseCharacters",
	);
	
	$ATTRIBUTES = array(
		'validator-type' => "data-ppform",
		'required' => "required",
		'ppForm' => "data-ppform",
		'ppFormBase' => "data-ppformbase",
		'errorEle' => "data-error",
	);

	$defaults = array(
        'javascript' => true,
        'complete_message' => 'Thank you for your contribution',
        'invalid_error' => 'は不正です',
        'required_error' => 'を入力してください',
    );

	$settings = get_option('ppform', $defaults);
	$mail_settings = get_post_meta( $form_id, 'emailopt', true );

	$config = array();
	$config['form-actions'] = get_post_meta($form_id, 'form-action', true );
	$config['mail'] = $mail_settings;
	$config['javascript'] = $settings['javascript'];
	$config['complete_message'] = $settings['complete_message'];
	$config['validator-types'] = $VALIDATOR_TYPES;
	$config['attributes'] = $ATTRIBUTES;
	$config['error-message'] = array(
		'error-class' => "hasError",
		'require-message' => $settings['required_error'],
		'invalid-message' => $settings['invalid_error'],
	);

	return $config;
}

add_shortcode( 'ppform', 'run_ppform' );
