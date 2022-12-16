<?php
// タイムゾーンを設定
date_default_timezone_set('Asia/Tokyo');

// 前月・次月リンクが押された場合は、GETパラメーターから年月を取得
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    // 今月の年月を表示
    $ym = date('Y-m');
}

// タイムスタンプを作成し、フォーマットをチェックする
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}

// 今日の日付 フォーマット　例）2018-07-3
$today = date('Y-m-j');

// カレンダーのタイトルを作成　例）2017年7月
$html_title = date('Y年n月', $timestamp);

// 前月・次月の年月を取得
// 方法１：mktimeを使う mktime(hour,minute,second,month,day,year)
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));

// 方法２：strtotimeを使う
// $prev = date('Y-m', strtotime('-1 month', $timestamp));
// $next = date('Y-m', strtotime('+1 month', $timestamp));

// 該当月の日数を取得
$day_count = date('t', $timestamp);

// １日が何曜日か　0:日 1:月 2:火 ... 6:土
// 方法１：mktimeを使う
$youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
// 方法２
// $youbi = date('w', $timestamp);


// カレンダー作成の準備
$weeks = [];
$week = '';

// 第１週目：空のセルを追加
// 例）１日が水曜日だった場合、日曜日から火曜日の３つ分の空セルを追加する
$week .= str_repeat('<td></td>', $youbi);

for ( $day = 1; $day <= $day_count; $day++, $youbi++) {

    // 2017-07-3
    $date = $ym . '-' . $day;

    // 開店情報に応じて色を変える
    $flg = 0;
    $fp = fopen('./csv/calendar.csv','r');
    for($i=0;$status = fgets($fp);$i++){
        $status = str_replace(["\n","\r"],['',''],$status);
        $status_list[] = explode(',',$status);
        if($status_list[$i][0] == $html_title){
            if($status_list[$i][$day] == 'open'){
                $week .= '<td class="open">' . $day;
                $flg = 1;
            }elseif($status_list[$i][$day] == 'rest'){
                $week .= '<td class="rest">' . $day;
                $flg = 1;
            }elseif($status_list[$i][$day] == 'am'){
                $week .= '<td class="am">' . $day;
                $flg = 1;
            }else{
                $week .= '<td>' . $day;
                $flg = 1;
            }
            $week .= '</td>';
        }
    }
    if($flg == 0){
        $week .= '<td>' . $day . '</td>';
    }
    fclose($fp);


    // 週終わり、または、月終わりの場合
    if ($youbi % 7 == 6 || $day == $day_count) {

        if ($day == $day_count) {
            // 月の最終日の場合、空セルを追加
            // 例）最終日が木曜日の場合、金・土曜日の空セルを追加
            $week .= str_repeat('<td></td>', 6 - ($youbi % 7));
        }

        // weeks配列にtrと$weekを追加する
        $weeks[] = '<tr>' . $week . '</tr>';

        // weekをリセット
        $week = '';
	}
}

$fp = fopen('./csv/news.csv','r');
while($news = fgets($fp)){
    $news_list[] = explode(',',$news);
}
fclose($fp);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <link rel="stylesheet" href="slick/slick.css">
    <link rel="stylesheet" href="slick/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Yusei+Magic&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Ballet&family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Bebas+Neue&family=Darker+Grotesque:wght@300;400;500;600;700;800;900&family=IM+Fell+English:ital@0;1&family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Niconne&family=Noto+Sans+JP:wght@100;300;400;500;700;900&family=Noto+Serif+JP:wght@200;300;400;500;600;700;900&family=Oswald:wght@200;300;400;500;600;700&family=Rajdhani:wght@300;400;500;600;700&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/HEW.css" type="text/css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="top_list">
        <ul>
            <li><a href="#header">TOP</a></li>
            <li><a href="#setumei_focus">STORY</a></li>
            <li><a href="#main_focus">MENU</a></li>
            <li><a href="#infomation_focus">ACCESS</a></li>
            <li><a href="#calendar_menu_focus">SCHEDULE</a></li>
        </ul>
        <p class="img_p"><img src="./img/logoWhite.png" alt=""></p>
        <div id="news">
            <?php foreach($news_list as $news_key => $news_value){ ?>
                <div class="h_p_<?php echo $news_key; ?>">
                <?php foreach($news_list[$news_key] as $sub_news_key => $sub_news_value){ ?>
                    <p class="h_p_p_<?php echo $sub_news_key; ?>"><?php echo $news_list[$news_key][$sub_news_key]; ?></p>
                <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div id="header">
        <div id="kakugen">
            <h1>GOOD CREPES<br>GREAT DAY!</h1>
            <p class="kakugen_p k_p_1">ぼくがオーストラリア滞在中に感じた、朝の時間の大切さ。お客様にゆっくりとくつろいでもらえる場所。</p>
            <p class="kakugen_p k_p_2">休日の朝、いつもよりちょっと遅めに起きて食べるちょっとだけリッチなクレープ。そんなお店を作りました。</p>
        </div>
        <div id="h_img">
            <!-- <img src="./img/crepe.png"> -->
        </div>
    </div>
    <div id="telop">
        <p>「YORKYS BRUNCH」のパンケーキが</p>
        <p>姿を変えてクレープになった！？</p>
    </div>
    <div id="setumei_focus">

    </div>
    <div id="setumei">
        <div id="setu">
            <h2>STORY</h2>
            <div id="setu_p">
                <p><span class="head">神</span>戸で行列のできるカフェ、<span class="strong str_01">YORKYS BRUNCH</span>。 そこでいつもお客様にこのパンケーキを持ち帰りできたら… とご要望を頂きながらもなかなかお答えすることができませんでした。</p>
                <p>お持ち帰りできてもう少しカジュアルに食べて頂けるもの… オーナーとパティシエたちが研究を重ね、<span class="strong str_02">クレープ</span>にたどり着きます。<span class="strong str_02">クレープ</span>の生地は薄いけれど非常に奥が深く、生地の完成に時間を要しましたが 温度と製法に徹底的にこだわり、独特の食感を生み出すことに成功しました。</p>
                <p>私たちも後で知ることになったのですが、クレープとはパンケーキの一種なのです...！ パンケーキを知り尽くした<span class="strong str_01">YORKYS</span>が作るクレープ。パンケーキの食感とはまた違うもちもちの食感をお楽しみください。</p>
            </div>
        </div>
        <div id="setu_img"><img src="./img/monika-grabkowska-DdowPUqwJDI-unsplash.jpg" alt=""></div>
    </div>
    <div id="main_focus">

    </div>
    <div id="main">
        <div id="main_menu">
            <div id="m_h1"><h1>MENU</h1></div>
            <ul id="menu_list">
                <li class="item item1">
                    <p><img src="./img/01.png" alt=""></p>
                    <div id="js_div">
                        <p class="js_p">あまおう(いちご)</p>
                        <p class="js_price">880円　(税込)</p>
                        <p class="jouhou">「あ」かい、「ま」るい、「お」おきい、「う」まいの四拍子がそろった<span class="ichigo">あまおう</span>をふんだんに使用！！<br>程よい酸味と甘いクリームの組み合わせは王道のうまさ是非ご堪能ください！！</p>
                    </div>
                </li>
                <li class="item item2">
                    <p><img src="./img/02.png" alt=""></p>
                    <div id="js_div">
                        <p class="js_p">チョコバナナ</p>
                        <p class="js_price">780円　(税込)</p>
                        <p class="jouhou">パリパリとした食感と<span class="choko">チョコレート</span>との相性の良い香ばしさ。もちろんあります、みんな大好きチョコバナナ！！チョコの甘さと<span class="banana">バナナ</span>の深みが合わさったハーモニー是非ご堪能ください。</p>
                    </div>
                </li>
                <li class="item item3">
                    <p><img src="./img/03.png" alt=""></p>
                    <div id="js_div">
                        <p class="js_p">キャラメルナッツ</p>
                        <p class="js_price">780円　(税込)</p>
                        <p class="jouhou"><span class="nattu">ナッツ</span>の香ばしさと<span class="caramel">キャラメル</span>の甘さがマッチング！！他では味わえないこのクレープご賞味あれ。</p>
                    </div>
                </li>
                <li class="item item4">
                    <p><img src="./img/04.png" alt=""></p>
                    <div id="js_div">
                        <p class="js_p">ティラミス</p>
                        <p class="js_price">880円　(税込)</p>
                        <p class="jouhou">甘味のなかにある程よい苦み、甘いものが苦手なそこのあなたにもおすすめできる一品となっております。</p>
                    </div>
                </li>
                <li class="item item5">
                    <p><img src="./img/05.png" alt=""></p>
                    <div id="js_div">
                        <p class="js_p">クリームブリュレ</p>
                        <p class="js_price">880円　(税込)</p>
                        <p class="jouhou"><span class="kasutado">カスタード</span>の甘さとと程よく焦がした<span class="caramel">カラメル</span>の苦みが絶妙なバランスとなっております。カスタード好きのあなたにお勧めできる一品となっております。<br>ぜひご賞味あれ！！</p>
                    </div>
                </li>
                <li class="item item6">
                    <p><img src="./img/06.png" alt=""></p>
                    <div id="js_div">
                        <p class="js_p">チーズ蜂蜜</p>
                        <p class="js_price">880円　(税込)</p>
                        <p class="jouhou"><span class="hanny">蜂蜜</span>と<span class="lemon">レモン</span>さらに<span class="cheese">チーズ</span>まで程よく合わさったこの三要素。三位一体となって甘さだけでなく程よい酸味があなたを刺激します！！</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div id="parallax">

    </div>
    <div id="sub_menu">
      <!--   <ul class="crepes">
            <li class="s_item1">あまおう ～いちご～ <br>880円</li>
            <li class="s_item2">クリームブリュレ<br>880円</li>
            <li class="s_item3">チョコバナナ<br>780円</li>
        </ul>
        <ul class="crepes">
            <li class="s_item4">チーズ蜂蜜<br>880円</li>
            <li class="s_item5">キャラメルナッツ<br>780円</li>
            <li class="s_item7">ティラミス<br>880円</li>
        </ul> -->
        <ul class="crepes side">
            <li class="s_item6">ソフトクリーム<br><span>480円　(税込み)</span><img src="./img/nas-mato-jnWGWSWTVqU-unsplash.jpg" alt=""></li>

            <li class="s_item8">カフェラテ等<br>ドリンク数種類<img src="./img/yeh-xintong-KG4Kb5TNxXM-unsplash.jpg" alt=""></li>
        </ul>
    </div>
    <div id="infomation_focus">

    </div>
    <div id="infomation">
        <div id="address_menu">
                <h2>ACCESS</h2>
            <div id="address">
                <div id="address_access">
                    <ul>
                        <li><span class="a_head">店名</span><span class="a_tail a_a_01">YORKYS Creperie</span></li>
                        <li><span class="a_head">住所</span><span class="a_tail">大阪市北区芝田1丁目1番3号<br>阪急三番街北館B2F UMEDA FOOD HALL</span></li>
                        <li><span class="a_head">TEL</span><span class="a_tail a_a_02">06-6292-5622</span></li>
                        <li><span class="a_head">営業時間</span><span class="a_tail a_a_03">10:00−23:00</span></li>
                    </ul>
                    <div id="a_img">
                        <img src="./img/3.jpg" alt="">
                    </div>
                </div>
                <div id="google">
                    <div id="gmap">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d598.6011574863466!2d135.49801463937044!3d34.70570096750979!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6000e69193b5e1bb%3A0xf2b0ea153774995f!2sUMEDA%20FOOD%20HALL!5e0!3m2!1sja!2sjp!4v1613454936213!5m2!1sja!2sjp" width="100%" height="auto" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="calendar_menu_focus">

    </div>
    <div id="calendar_menu">
        <div id="calendar">
            <h2 id="calendar_h2">SCHEDULE</h2>
            <div class="container">
                <h3><a href="?ym=<?php echo $prev; ?> #calendar">&lt;</a> <?php echo $html_title; ?> <a href="?ym=<?php echo $next; ?> #calendar">&gt;</a></h3>
                <div id="calendar_color">
                    <p>休業日・・・</p>
                    <div class="color red"></div>
                    <p>営業日・・・</p>
                    <div class="color blue"></div>
                    <p>午前のみ・・・</p>
                    <div class="color green"></div>
                    <p>未定・・・</p>
                    <div class="color white"></div>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th>日</th>
                        <th>月</th>
                        <th>火</th>
                        <th>水</th>
                        <th>木</th>
                        <th>金</th>
                        <th>土</th>
                    </tr>
                    <?php
                        foreach ($weeks as $week) {
                            echo $week;
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div id="footer">
        <h3>YORKYS Co., Ltd. All Rights Reserved</h3>
    </div>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="slick/slick.min.js"></script>
    <script src="js/app.js"></script>
    <script src="./flexslider/jquery.flexslider.js"></script>
    <script type="text/javascript" charset="utf-8"></script>
</body>
</html>