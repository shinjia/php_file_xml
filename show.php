<?php
$source = $_POST["source"];

$content = @file_get_contents($source) or die('Cannot read this data...');

define('NL', "\n");  // 換列符號

// 讀取XML格式到陣列內
$res = xml_parser_create();
xml_parse_into_struct($res, $content, $a_data, $a_value);
xml_parser_free($res);


// 找出每個ITEM
$a_item_index = array();
$cnt = 0;
foreach($a_data as $key=>$a_node1)
{
   if($a_node1["tag"]=="ITEM")
   {
      // 找出每個ITEM項目的頭尾
      if($a_node1["type"]=="open")
      {
         $cnt++;
         $a_item_index[$cnt]["HEAD"] = $key;
      }
      elseif($a_node1["type"]=="close")
      {
         $a_item_index[$cnt]["REAR"] = $key;
      }
   }
}


// 在每個ITEM區間內，找到每篇文章的相關內容
$a_article = array();
$cnt = 0;
foreach($a_item_index as $a_section)
{
   $head = $a_section["HEAD"];
   $rear = $a_section["REAR"];
   if(!empty($rear))
   {
      // 搜尋$a_value，在這區間內(從$head到$tail為止)的內容
      for($i=$head+1; $i<$rear; $i++)
      {
         $tag = $a_data[$i]["tag"];
         if($tag!="ITEM") @$a_article[$cnt][$tag] = $a_data[$i]["value"];
      }
   }
   $cnt++;
}


// 從 $a_article 依照自記格式顯示出各篇文章
$cnt = 0;
$str = '<p>直接點選標題可看詳細內容</p>';
$str .= '<table border="0" style="background-color:#CCBBAA;">';
foreach($a_article as $ary)
{
   $cnt++;
   
   $article_title = $ary["TITLE"];
   $article_descr = $ary["DESCRIPTION"];
   $article_date = (!empty($ary["PUBDATE"])) ? @date("Y-m-d", @strtotime($ary["PUBDATE"])) : "";
   $article_link  = @$ary["LINK"];
   
   $str .= '<tr>'.NL;
   $str .= '<td valign="top">(' . $cnt .') </td>'.NL;
   $str .= '<td style="width:600px; background-color:#FFEEAA;">'.NL;
   $str .= ' <div onclick="show_detail(div_' . $cnt . ');" style="cursor:hand;">';
   $str .= $article_title;
   $str .= ' </div>'.NL;
   $str .= ' <div id="div_' . $cnt . '" style="display:none">'.NL;
   $str .= '  <table width="100%">'.NL;
   $str .= '   <tr>'.NL;
   $str .= '    <td style="border:1px solid #993300; background-color:#FFDD99;">'.NL;
   $str .= '     <p>' . $article_descr . '<br></p>'.NL;
   $str .= '    </td>'.NL;
   $str .= '   </tr>'.NL;
   $str .= '  </table>'.NL;
   $str .= ' </div>'.NL;
   $str .= ' </td>'.NL;
   $str .= '<td valign="top">' . $article_date .'&nbsp;</td>'.NL;
   $str .= '<td valign="top"><a href="'. $article_link .'" target="_blank">原出處</td>'.NL;
   $str .= '</tr>'.NL;
}
$str .= '</table>';

$str = !empty($a_article) ? $str : '<p>找不到部落格的文章</p>';


$html = <<< HEREDOC
<html>
<head>
<meta charset="utf-8">
<title>RSS文章閱讀器</title>
<script language="javascript">
function show_detail(obj)
{
   if(obj.style.display == 'block')
      obj.style.display = 'none';
   else
      obj.style.display = 'block';
}
</script>
</head>
<body>
<h1>部落格文章閱讀器</h1>
{$str}
<p><BR><a href="index.php">回首頁</a></p>
</body>
</html>
HEREDOC;

echo $html;
?>