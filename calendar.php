<?php
// 変更ボタンが押された場合の処理
if (isset($_GET['check']) && $_GET['check'] == 'ok') {

    // GETで飛んできた開店情報の整理
    $status = '';
    foreach ($_GET['status'] as $s_value) {
        $status .= ',' . $s_value;
    }

    // 変更された情報の保存
    $fp = fopen('./csv/calendar.csv', 'a');
    fputs($fp, $_GET['year'] . $status . "\n");
    fclose($fp);

    // 同月があった場合の削除
    $fp = fopen('./csv/calendar.csv', 'r');
    while ($row = fgets($fp)) {
        $list[] = explode(',', $row);
    }
    fclose($fp);

    foreach ($list as $key => $value) {
        foreach ($list as $key2 => $value2) {
            if ($key2 != $key) {
                if ($list[$key][0] == $list[$key2][0]) {
                    unset($list[$key]);
                    $list = array_values($list);
                    break 2;
                }
            }
        }
    }

    $fp = fopen('./csv/calendar.csv', 'w');
    foreach ($list as $key => $value) {
        fputs($fp, implode(',', $list[$key]));
    }
    fclose($fp);

    // 管理者画面へ遷移する
    header('location:./master.php');
    exit;
}

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
$prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) - 1, 1, date('Y', $timestamp)));
$next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp) + 1, 1, date('Y', $timestamp)));

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

$fp = fopen('./csv/calendar.csv', 'r');
$num1 = -1;
while ($calendar_row = fgets($fp)) {
    $calendar[] = explode(',', $calendar_row);
    $num1 = $num1 + 1;
}
fclose($fp);

for ($day = 1; $day <= $day_count; $day++, $youbi++) {

    // 2017-07-3
    $date = $ym . '-' . $day;

    if ($today == $date) {
        // 今日の日付の場合は、class="today"をつける
        $week .= '<td class="today ">' . $day;
    } else {
        $week .= '<td>' . $day;
    }

    foreach ($calendar as $calendar_key => $calendar_value) {
        if ($calendar[$calendar_key][0] == $html_title) {
            if ($calendar[$calendar_key][$day] == 'open') {
                $week .= '<div><select name="status[]"><option value="open">営業日</option><option value="rest">休業日</option><option value="am">午前のみ</option></select></div>' . '</td>';
                break;
            } elseif ($calendar[$calendar_key][$day] == 'rest') {
                $week .= '<div><select name="status[]"><option value="rest">休業日</option><option value="open">営業日</option><option value="am">午前のみ</option></select></div>' . '</td>';
                break;
            } elseif ($calendar[$calendar_key][$day] == 'am') {
                $week .= '<div><select name="status[]"><option value="am">午前のみ</option><option value="open">営業日</option><option value="rest">休業日</option></select></div>' . '</td>';
                break;
            } else {
                $week .= '<div><select name="status[]"><option value="open">営業日</option><option value="rest">休業日</option><option value="am">午前のみ</option></select></div>' . '</td>';
                break;
            }
        }elseif($num1 == $calendar_key){
            $week .= '<div><select name="status[]"><option value="open">営業日</option><option value="rest">休業日</option><option value="am">午前のみ</option></select></div>' . '</td>';
        }
    }

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
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+JP" rel="stylesheet">
    <link rel="stylesheet" href="./css/calendar.css" type="text/css">
    <title>PHPカレンダー</title>
    <!-- <style>
        .container {
            font-family: 'Noto Sans JP', sans-serif;
            margin-top: 80px;
        }

        h3 {
            margin-bottom: 30px;
        }

        th {
            height: 30px;
            text-align: center;
        }

        td {
            height: 100px;
        }

        td div {
            padding-top: 10px;
            padding-left: 30px;
        }

        .today {
            background: orange;
        }

        th:nth-of-type(1),
        td:nth-of-type(1) {
            color: red;
        }

        th:nth-of-type(7),
        td:nth-of-type(7) {
            color: blue;
        }
    </style> -->
</head>

<body>
    <form method="get">
        <div class="container">
            <h3><a href="?ym=<?php echo $prev; ?>">&lt;</a> <?php echo $html_title; ?> <a href="?ym=<?php echo $next; ?>">&gt;</a></h3>
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
        <input type="hidden" name="year" value="<?php echo $html_title; ?>">
        <button id="check" type="submit" name="check" value="ok">変更</button>
    </form>
</body>

</html>