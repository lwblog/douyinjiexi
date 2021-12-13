<?php
header("Content-Type: text/json;charset=utf-8");
 
if (empty($_GET['url'])) {
    echo '请输入正确网址，格式为：douyin.php?url=https://v.douyin.com/JDQGVKN/';
} else {
    getParams($_GET['url']);
}
function getParams($url){
    $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    $curl      = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_ENCODING, '');
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
    $data = curl_exec($curl);
    curl_close($curl);
    // echo $data;
    preg_match('/video\/(.*?)\/.*?mid=(.*?)&/i', $data, $result);
    // print_r($result);
    sleep(1);
    getItemInfo($result[1],$result[2]);
}

function getItemInfo($item_id, $mid) {
    $url = "https://www.iesdouyin.com/web/api/v2/aweme/iteminfo/?item_ids=".$item_id;
    $headers =[
        'authority'=> 'www.iesdouyin.com',
        'user-agent'=> 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1',
        'content-type'=> 'application/x-www-form-urlencoded',
        'accept'=> '*/*',
        'referer'=> 'https://www.iesdouyin.com/share/video/'.$item_id.'/?region=CN&mid='.$mid.'&u_code=15b9142gf&titleType=title&utm_source=copy_link&utm_campaign=client_share&utm_medium=android&app=aweme',
        'accept-language'=> 'zh-CN,zh;q=0.9,en-GB;q=0.8,en;q=0.7'
    ];
    $UserAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
    $curl      = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, 0);//返回response头部信息
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, $UserAgent);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
    $data = curl_exec($curl);
    curl_close($curl);
    $json=json_decode($data,true);
    // print_r($json['item_list'][0]["video"]["play_addr"]["url_list"][0]);
    $name=$json['item_list'][0]["desc"];
    $video=$json['item_list'][0]["video"]["play_addr"]["url_list"][0];
    $video=str_replace("playwm","play",$video);
    download($name,$video);
    echo $name,$video;
}

function download($name,$video){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $video);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1");
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    $data = curl_exec($curl);
    curl_close($curl);
    file_put_contents("./douyin/" . $name . ".mp4", $data);
    
}