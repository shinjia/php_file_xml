﻿<?php
$source = isset($_POST['source']) ? $_POST['source'] :'';

$content = @file_get_contents($source);
if($content)
{
   // 讀取XML格式到陣列內
   $res = xml_parser_create();
   xml_parse_into_struct($res, $content, $a_data, $a_value);
   xml_parser_free($res);
   
   
   // 找出每個ITEM
   $a_item_index = array();
   $cnt = 0;
   foreach($a_data as $key=>$a_node1)
   {
      if($a_node1['tag']=='ITEM')
      {
         // 找出每個ITEM項目的頭尾
         if($a_node1['type']=='open')
         {
            $cnt++;
            $a_item_index[$cnt]['HEAD'] = $key;
         }
         elseif($a_node1['type']=='close')
         {
            $a_item_index[$cnt]['REAR'] = $key;
         }
      }
   }
   
   
   // 在每個ITEM區間內，找到每篇文章的相關內容
   $a_article = array();
   $cnt = 0;
   foreach($a_item_index as $a_section)
   {
      $head = $a_section['HEAD'];
      $rear = $a_section['REAR'];
      if(!empty($rear))
      {
         // 搜尋$a_value，在這區間內(從$head到$tail為止)的內容
         for($i=$head+1; $i<$rear; $i++)
         {
            $tag = $a_data[$i]['tag'];
            @$a_article[$cnt][$tag] = $a_data[$i]['value'];
         }
      }
      $cnt++;
   }
   
   
   // 從 $a_article 依照自記格式顯示出各篇文章
   $cnt = 0;
   $data = '<p>直接點選標題可看詳細內容</p>';
   $data .= '<table border="0" style="background-color:#CCBBAA;">';
   foreach($a_article as $ary)
   {
      $cnt++;
      
      $article_title = $ary['TITLE'];
      $article_descr = $ary['DESCRIPTION'];
      $article_date = !empty($ary['PUBDATE']) ? @date('Y-m-d', @strtotime($ary['PUBDATE'])) : '';
      $article_link  = $ary["LINK"];

      $data .= <<< HEREDOC
<tr>
  <td valign="top">◇</td>
  <td style="width:640px; background-color:#FFEEAA;">
    <div onclick="show_detail('div_{$cnt}');" style="cursor:hand;">
{$article_title}
    </div>
    <div id="div_{$cnt}" style="display:none">
      <table width="100%">
        <tr>
          <td style="border:1px solid #993300; background-color:#FFDD99;">
            <p>{$article_descr}<br></p>
          </td>
        </tr>
      </table>
    </div>
  </td>
  <td valign="top">{$article_date}&nbsp;</td>
  <td valign="top"><a href="{$article_link}" target="_blank">原出處</td>
</tr>
HEREDOC;
   }
   $data .= '</table>';
   
   $data = !empty($a_article) ? $data : '<p>找不到部落格的文章</p>';
}
else
{
   $data = '請確定網路對外連線正常，並指定下列任一地區。';
}


$html = <<< HEREDOC
<html>
<head>
<meta charset="utf-8">
<title>全台各地氣象預報資訊</title>
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
<h1>全台各地氣象預報資訊</h1>
{$data}

地區選擇：
<form method="post" name="form1">
   <table border="0">
      <tr>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_01.xml" onclick="form1.submit();">台北市</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_02.xml" onclick="form1.submit();">高雄市</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_03.xml" onclick="form1.submit();">基隆北海岸</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_04.xml" onclick="form1.submit();">台北</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_05.xml" onclick="form1.submit();">桃園</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_06.xml" onclick="form1.submit();">新竹</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_07.xml" onclick="form1.submit();">苗栗</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_08.xml" onclick="form1.submit();">台中</td>
      </tr>
      <tr>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_09.xml" onclick="form1.submit();">彰化</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_10.xml" onclick="form1.submit();">南投</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_11.xml" onclick="form1.submit();">雲林</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_12.xml" onclick="form1.submit();">嘉義</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_13.xml" onclick="form1.submit();">台南</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_14.xml" onclick="form1.submit();">高雄</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_15.xml" onclick="form1.submit();">屏東</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_16.xml" onclick="form1.submit();">恆春</td>
      </tr>
      <tr>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_17.xml" onclick="form1.submit();">宜蘭</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_18.xml" onclick="form1.submit();">花蓮</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_19.xml" onclick="form1.submit();">台東</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_20.xml" onclick="form1.submit();">澎湖</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_21.xml" onclick="form1.submit();">金門</td>
         <td><input type="radio" name="source" value="http://www.cwb.gov.tw/rss/forecast/36_22.xml" onclick="form1.submit();">馬祖</td>
         <td></td>
         <td></td>
      </tr>
   </table>
</form>
</body>
</html>
HEREDOC;

echo $html;
?>