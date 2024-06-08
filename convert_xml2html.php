<?php
// コマンドライン引数からXMLファイル名を取得
if ($argc !== 2) {
    echo "Usage: php convert_xml2html.php <XML_FILE>\n";
    exit(1);
}

$xmlFile = $argv[1];

// Tidyオプションを設定
$tidyConfig = [
    'input-xml' => true,
    'output-xml' => true,
    'wrap' => 0,
    'numeric-entities' => true,
    'bare' => true,
    'fix-uri' => true,
    'join-classes' => true,
    'join-styles' => true,
    'literal-attributes' => true,
    'output-bom' => false,
    'fix-backslash' => true,
    'enclose-text' => true,
    'enclose-block-text' => true,
];

// XMLファイルの読み込み
$xmlContent = file_get_contents($xmlFile);

// TidyライブラリでXMLを修正
$tidy = new tidy;
$tidy->parseString($xmlContent, $tidyConfig, 'utf8');
$tidy->cleanRepair();

// 修正後のXMLを取得
$cleanXml = $tidy->value;

// DOMDocumentを使用してXMLをロード
$dom = new DOMDocument;
$dom->recover = true;
$dom->strictErrorChecking = false;
$dom->loadXML($cleanXml, LIBXML_NOCDATA);

if (!$dom) {
    echo "Failed loading XML\n";
    foreach (libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
    exit;
}

// SimpleXMLオブジェクトに変換
$xml = simplexml_import_dom($dom);

if (!$xml) {
    echo "Failed converting DOM to SimpleXML\n";
    foreach (libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
    exit;
}

// 年ごとにアイテムを分類するための配列
$years = [];

// アイテムを繰り返し処理
foreach ($xml->channel->item as $item) {
    // エラーが発生する場合はスキップ
    if (!$item) {
        continue;
    }

    try {
        // 公開日を取得し、年を抽出
        $pubDate = (string)$item->pubDate;
        $year = date('Y', strtotime($pubDate));

        // 該当年の配列がなければ作成
        if (!isset($years[$year])) {
            $years[$year] = [];
        }

        // 該当年の配列にアイテムを追加
        $years[$year][] = $item;
    } catch (Exception $e) {
        // エラーメッセージをログに書き込む
        file_put_contents('error_log.txt', $e->getMessage() . "\n", FILE_APPEND);
        continue;
    }
}

// 年ごとにHTMLファイルを生成
foreach ($years as $year => $items) {
    // HTMLヘッダー
    $html = "<!DOCTYPE html>\n<html lang='ja'>\n<head>\n<meta charset='UTF-8'>\n<title>ブログ記事 $year</title>\n</head>\n<body>\n";
    $html .= "<h1>$year 年のブログ記事</h1>\n";

    // アイテムごとに記事を追加
    foreach ($items as $item) {
        $title = (string)$item->title;
        $pubDate = (string)$item->pubDate;
        $content = (string)$item->children('content', true)->encoded;

        $html .= "<article>\n";
        $html .= "<h2>$title</h2>\n";
        $html .= "<p>公開日: $pubDate</p>\n";
        $html .= "<div>$content</div>\n";
        $html .= "</article>\n";
        $html .= "<hr>\n";  // 記事間に<hr>タグを追加
    }

    // HTMLフッター
    $html .= "</body>\n</html>";

    // ファイルに書き込み
    file_put_contents("outputs/html/articles_$year.html", $html);
}
?>
