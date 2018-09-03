<?php
$source = $_POST["source"];

$content = @file_get_contents($source) or die('Cannot read this data...');

define('NL', "\n");  // 換列符號

// 讀取XML格式到陣列內
$res = xml_parser_create();
xml_parse_into_struct($res, $content, $a_data, $a_value);
xml_parser_free($res);

$str1 = print_r($a_data, TRUE);
$str2 = print_r($a_value, TRUE);

$html = <<< HEREDOC
<html>
<head>
<meta charset="utf-8">
<title>RSS文章閱讀器</title>
</head>
<body>
<h1>查看xml_parser擷取之陣列內容</h1>
<pre>
{$str1}<hr>{$str2}
</pre>
</body>
</html>
HEREDOC;

echo $html;
?>