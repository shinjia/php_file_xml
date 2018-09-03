<?php

$html = <<< HEREDOC
<html>
<head>
<meta charset="utf-8">
<title>RSS文章閱讀器</title>
</head>
<body>
<h1>RSS (使用 php 處理 XML 檔案範例)</h1>
<h2>部落格文章閱讀器</h2>
<form method="post" action="show.php">
   請輸入RSS檔案來源：
   <input type="text" name="source" value="data.rss" size="40">
   <input type="submit" value="送出">
</form>

<h2>測試：程式開發檢查用</h2>
<form method="post" action="test.php">
   請輸入RSS檔案來源：
   <input type="text" name="source" value="data.rss" size="40">
   <input type="submit" value="送出">
</form>

<h2>應用：全台各地氣象預報資訊</h2>
<p><a href="weather.php" target="_blank">全台各地氣象預報資訊</a></p>
</body>
</html>
HEREDOC;

echo $html;
?>