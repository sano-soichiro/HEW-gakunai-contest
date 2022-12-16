<?php
if (isset($_GET['check']) && strpos($_GET['check'], 'update') === 0) {
    $culm = trim($_GET['check'], 'update');
    $fp = fopen('./csv/news.csv', 'r');
    while ($row = fgets($fp)) {
        $news_list[] = explode(',', $row);
    }
    fclose($fp);
    $news_value = $news_list[$culm][1];
} elseif (isset($_GET['check']) && strpos($_GET['check'], 'checked') === 0) {
    $culm = trim($_GET['check'], 'checked');
    $fp = fopen('./csv/news.csv', 'r');
    while ($row = fgets($fp)) {
        $news_list[] = explode(',', $row);
    }
    fclose($fp);
    $day = date('n月j日');
    $fp = fopen('./csv/news.csv', 'w');
    foreach ($news_list as $key => $news_value) {
        if ($key == $culm) {
            fputs($fp, $day . "," . $_GET['news'] . "\n");
        } else {
            fputs($fp, $news_list[$key][0] . "," . $news_list[$key][1]);
        }
    }
    fclose($fp);
    $news_list = [];
    $fp = fopen('./csv/news.csv', 'r');
    while ($row = fgets($fp)) {
        $news_list[] = explode(',', $row);
    }
    fclose($fp);
} else {
    $fp = fopen('./csv/news.csv', 'r');
    while ($row = fgets($fp)) {
        $news_list[] = explode(',', $row);
    }
    fclose($fp);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Averia+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Ballet&family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Bebas+Neue&family=Darker+Grotesque:wght@300;400;500;600;700;800;900&family=IM+Fell+English:ital@0;1&family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Niconne&family=Noto+Sans+JP:wght@100;300;400;500;700;900&family=Noto+Serif+JP:wght@200;300;400;500;600;700;900&family=Oswald:wght@200;300;400;500;600;700&family=Rajdhani:wght@300;400;500;600;700&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/news.css" type="text/css">
    <title>Document</title>
</head>

<body>
    <div id="head_div">
        <h1>NEWS管理画面</h1>
        <form method="get">
            <?php if (isset($_GET['check']) && strpos($_GET['check'], 'update') === 0) { ?>
                <div id="change_button">
                    <input type="text" name="news" value="<?php echo $news_value; ?>">
                    <button type="submit" name="check" value="checked<?php echo $culm; ?>">確定</button>
                </div>
            <?php } else { ?>
                <?php foreach ($news_list as $key => $news) { ?>
                    <div id="in_div">
                        <?php foreach ($news_list[$key] as $list_key => $news_value) { ?>
                            <p><?php echo $news_value; ?></p>
                            <?php if ($list_key == 1) { ?>
                                <button type="submit" name="check" value="update<?php echo $key; ?>">編集</button>
                            <?php } ?>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } ?>
        </form>
    </div>
    <div id="a_tag"><a href="./kanri_home.html">管理者画面へ→</a></div>
</body>

</html>