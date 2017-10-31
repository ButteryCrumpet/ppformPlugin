<?php

class PPFormJSONParser {
    private $templates;
    private $requiredHTML = '<span style="font-weight: bold;">必須</span>';
    private $notRequiredHTML = '<span class="nini">任意</span>';
    private $requiredAttr = 'required';

    function __construct() {
        $this->generatetemplates();
    }

    public function getTemplate($type, $placeholder, $required) {
        if ( ! isset($this->templates[$type])) {
            return false;
        }

        $placeholderRplc = 'placeholder="' . $placeholder . '"';
        $requiredRplc = ($required) ? $this->requiredAttr : '';
        $requiredHTMLRplc = ($required) ? $this->requiredHTML : $this->notRequiredHTML;

        $template = $this->templates[$type];
        $template = str_replace('{placeholder}', $placeholderRplc, $template);
        $template = str_replace('{required}', $requiredRplc, $template);
        $template = str_replace('{reqHTML}', $requiredHTMLRplc, $template);

        return $template;

    }

    public function generateTextInput($data) {
        //Label Name ?Value? Validation Placeholder Required Textarea
        $placeholder = 'placeholder="' . $data['placeholder'] . '"';
        $requiredAttr = ($data['required']) ? $this->requiredAttr : '';
        $requiredHTML = ($data['required']) ? $this->requiredHTML : $this->notRequiredHTML;
        
        $html = '<dt>' . $requiredHTML . $data['label'] . '</dt>';
        $html .= '<dd>';
        $html .= ($data['textArea']) ? '<textarea ' : '<input type="text" ';
        $html .= $placeholder . ' ';
        $html .= 'name="' . $data['name'] . '" ';
        $html .= $requiredAttr . ' ';
        $html .= 'data-ppform="' . $data['validation'] . '" ';
        $html .= ($data['textarea']) ? '><textarea>' : '>';
        $html .= '<p class="em" data-error="'. $data['name'] . '" ></p>';
        $html .= '</dd>';

        return $html;
    }

    public function generateMultiInput($data) {
        $name = $data['type'] === 'Checkbox' ? $data['name'] .'[]' : $data['name'];

        $html = $html = '<dt>' . $data['label'] . '</dt>';
        $html .= '<dd>';
        foreach ($data['buttons'] as $button) {
            $html .= '<label>' . $button['label'] . '</label>';
            $html .= '<input type="' . $data['type'] .  '" name="' . $name . '" value="' .$button['value']. '" ';
            $html .= 'data-ppform="' .  $data['validation'] . '" ';
            $html .= ($button['default']) ? 'checked >' : ' >'; 
        }
        $html .= '<p class="em" data-error="'. $data['name'] . '" ></p>';
        $html .= '</dd>';

        return $html;
    }

    public function generateSelectInput($data) {
        $requiredAttr = ($data['required']) ? $this->requiredAttr : '';
        $requiredHTML = ($data['required']) ? $this->requiredHTML : $this->notRequiredHTML;
        $count = 0;
        $html = '<dt>' . $requiredHTML . $data['label'] . '</dt>';
        $html .= '<dd>';
        $html .= '<select name="' . $data['name'] . '" ' . $requiredAttr . ' data-ppform="' . $data['validation'] .'"  ';
        $html .= 'data-ppform="' .  $data['validation'] . '" >';
        foreach ($data['optgroups'] as $optgroup) {
            $html .= '<optgroup label="' . $optgroup['label'] . '" >';
            foreach ($optgroup['buttons'] as $selection) {
                $html .= '<option value="' . $selection['value'] . ' "';
                $html .= ($selection['default']) ? 'selected >' : ' >';
                $html .= $selection['label'] . '</option>';
            }
            $html .= '</optgroup>';

        }
        $html .= '</select>';
        $html .= '<p class="em" data-error="'. $data['name'] . '" ></p>';
        $html .= '</dd>';
        return $html;
    }

    private function generatetemplates() {
        $this->templates['Name'] = '<dt>{reqHTML}お名前</dt>
        <dd><input name="名前" {required} type="text" {placeholder}　太郎" data-ppform="text" /></dd>
        <p class="em" data-error="名前" ></p>';
        
        $this->templates['Furigana'] = '<dt>{reqHTML}ふりがな</dt>
        <dd><input name="フリガナ" {required} type="text" {placeholder}　たろう" data-ppform="kana">
        <p class="em" data-error="フリガナ" ></p></dd>';
        
        $this->templates['Mail'] = '<dt>{reqHTML}メールアドレス</dt>
        <dd><input name="メール" {required} type="text" {placeholder} data-ppform="email" />
        <p class="em" data-error="メール" ></p></dd>';
        
        $this->templates['Phone'] = '<dt>{reqHTML}電話番号</dt>
        <dd><input name="電話" {required} type="text" {placeholder} data-ppform="phone" />
        <p class="em" data-error="電話" ></p></dd>';
        
        $this->templates['Address'] = '<dt>{reqHTML}住所</dt>
        <dd><span>・郵便番号</span><input {required} id="yuzip" name="郵便番号" type="text" data-ppform="zip">
        <div class="em" data-error="郵便番号" ></div><br>
        <span>・都道府県</span>
            
            <div class="custom">
            
            <select id="ken" name="都道府県" {required} data-ppform="text"  >
            <optgroup label="北海道・東北地方">
            <option value="北海道">北海道</option>
            <option value="青森県">青森県</option>
            <option value="岩手県">岩手県</option>
            <option value="秋田県">秋田県</option>
            <option value="宮城県">宮城県</option>
            <option value="山形県">山形県</option>
            <option value="福島県">福島県</option>
            </optgroup>
            <optgroup label="関東地方">
            <option value="栃木県">栃木県</option>
            <option value="群馬県">群馬県</option>
            <option value="茨城県">茨城県</option>
            <option value="埼玉県">埼玉県</option>
            <option value="東京都">東京都</option>
            <option value="千葉県">千葉県</option>
            <option value="神奈川県">神奈川県</option>
            </optgroup>
            <optgroup label="中部地方">
            <option value="山梨県">山梨県</option>
            <option value="長野県">長野県</option>
            <option value="新潟県">新潟県</option>
            <option value="富山県">富山県</option>
            <option value="石川県">石川県</option>
            <option value="福井県">福井県</option>
            <option value="静岡県">静岡県</option>
            <option value="岐阜県">岐阜県</option>
            <option value="愛知県">愛知県</option>
            </optgroup>
            <optgroup label="近畿地方">
            <option value="三重県">三重県</option>
            <option value="滋賀県">滋賀県</option>
            <option value="京都府">京都府</option>
            <option value="大阪府">大阪府</option>
            <option value="兵庫県">兵庫県</option>
            <option value="奈良県">奈良県</option>
            <option value="和歌山県">和歌山県</option>
            </optgroup>
            <optgroup label="四国地方">
            <option value="徳島県">徳島県</option>
            <option value="香川県">香川県</option>
            <option value="愛媛県">愛媛県</option>
            <option value="高知県">高知県</option>
            </optgroup>
            <optgroup label="中国地方">
            <option value="鳥取県">鳥取県</option>
            <option value="島根県">島根県</option>
            <option value="岡山県">岡山県</option>
            <option value="広島県">広島県</option>
            <option value="山口県">山口県</option>
            </optgroup>
            <optgroup label="九州・沖縄地方">
            <option value="福岡県">福岡県</option>
            <option value="佐賀県">佐賀県</option>
            <option value="長崎県">長崎県</option>
            <option value="大分県">大分県</option>
            <option value="熊本県">熊本県</option>
            <option value="宮崎県">宮崎県</option>
            <option value="鹿児島県">鹿児島県</option>
            <option value="沖縄県">沖縄県</option>
            </optgroup>
            </select>
            </div>
            
            <span>・市区町村</span><input id="address" name="市区町村" type="text" {required} {placeholder} data-ppform="text" >
            <span>・番地アパート名</span><input name="番地アパート名" type="text"  {required} {placeholder} data-ppform="text" ></dd>';
        
        $this->templates['Comment'] = '<dt>{reqHTML}お問い合せ内容</dt>
        <dd><textarea name="問い" {required} {placeholder} data-ppform="jchars"></textarea><div class="em" data-error="問い" ></div></dd>';
    }
}