<?php

$TEMPLATES = array();

$TEMPLATES['name'] = '<dt><span style="font-weight: bold;">必須</span>お名前</dt>
<dd><input name="名前" required type="text" placeholder="例)石材　太郎" data-ppform="text" /></dd>
<div class="em" data-error="名前" ></div>';

$TEMPLATES['furi'] = '<dt><span style="font-weight: bold;">必須</span>ふりがな</dt>
<dd><input name="フリガナ" required type="text" placeholder="例)せきざい　たろう" data-ppform="kana"></dd>
<div class="em" data-error="フリガナ" ></div>';

$TEMPLATES['mail'] = '<dt><span style="font-weight: bold;">必須</span>メールアドレス</dt>
<dd><input name="メール" required type="text" placeholder="例)石材太郎" data-ppform="email" /></dd>
<div class="em" data-error="メール" ></div>';

$TEMPLATES['phone'] = '<dt><span style="font-weight: bold;">必須</span>電話番号</dt>
<dd><input name="電話" required type="text" data-ppform="phone" /></dd>
<div class="em" data-error="メール" ></div>';

$TEMPLATES['address'] = '<dt><span>必須</span>住所</dt>
<dd><span>・郵便番号</span><input id="yuzip" name="zip" type="text" data-ppform="zip"><div class="em" data-error="zip" ></div><br>
<span>・都道府県</span>
    
    <div class="custom">
    
    <select id="ken" name="都道府県" required="required" data-ppform="text"  >
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
    
    <span>・市区町村</span><input id="address" name="chiiki" type="text" data-ppform="text" >
    <span>・番地アパート名</span><input name="ApaNum" type="text" data-ppform="text" ></dd>';

$TEMPLATES['comment'] = '<dt><span class="nini">任意</span>お問い合せ内容</dt>
<dd><textarea name="問い" data-ppform="jchars"></textarea></dd>
<div class="em" data-error="問い" ></div>';
