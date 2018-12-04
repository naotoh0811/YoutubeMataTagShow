<?php


//文字切り取り
function removeStr($data, $remove_array){
    foreach($remove_array as $remove_data){
        $data = str_replace($remove_data, "", $data);
    }
    return $data;
}


//Xdomainではfile_get_contentsが使えないのでcurlを使う
function file_cget_contents($address){
	$ch = curl_init(); // 初期化
	curl_setopt( $ch, CURLOPT_URL, $address ); // URLの設定
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); // 出力内容を受け取る設定
	$result = curl_exec( $ch ); // データの取得
	curl_close($ch); // cURLのクローズ
	return $result;
}


/**
 * youtubeのURLから埋め込みタグを生成する
 *
 * @param   string $param youtubeのURL
 * @return  string        埋め込みタグ
 */
function createvideotag($param)
{
    //とりあえずURLがyoutubeのURLであるかをチェック
    if(preg_match('#https?://www.youtube.com/.*#i',$param,$matches)){
        //parse_urlでhttps://www.youtube.com/watch以下のパラメータを取得
        $parse_url = parse_url($param);
        // 動画IDを取得
        if (preg_match('#v=([-\w]{11})#i', $parse_url['query'], $v_matches)) {
            $video_id = $v_matches[1];
        } else {
            // 万が一動画のIDの存在しないURLだった場合は埋め込みコードを生成しない。
            return false;
        }
        $v_param = '';
        // パラメータにt=XXmXXsがあった時の埋め込みコード用パラメータ設定
        // t=〜〜の部分を抽出する正規表現は記述を誤るとlist=〜〜の部分を抽出してしまうので注意
        if (preg_match('#t=([0-9ms]+)#i', $parse_url['query'], $t_maches)) {
            $time = 0;
            if (preg_match('#(\d+)m#i', $t_maches[1], $minute)) {
                // iframeでは正の整数のみ有効なのでt=XXmXXsとなっている場合は整形する必要がある。
                $time = $minute[1]*60;
            }
            if (preg_match('#(\d+)s#i', $t_maches[1], $second)) {
                $time = $time+$second[1];
            }
            if (!preg_match('#(\d+)m#i', $t_maches[1]) && !preg_match('#(\d+)s#i', $t_maches[1])) {
                // t=(整数)の場合はそのままの値をセット ※秒数になる
                $time = $t_maches[1];
            }
            $v_param .= '?start=' . $time;
        }
        // パラメータにlist=XXXXがあった時の埋め込みコード用パラメータ設定
        if (preg_match('#list=([-\w]+)#i', $parse_url['query'], $l_maches)) {
            if (!empty($v_param)) {
                // $v_paramが既にセットされていたらそれに続ける
                $v_param .= '&list=' . $l_maches[1];
            } else {
                // $v_paramが既にセットされていなかったら先頭のパラメータとしてセット
                $v_param .= '?list=' . $l_maches[1];
            }
        }
        // 埋め込みコードを返す
        return '<iframe width="600" height="338" src="https://www.youtube.com/embed/' . $video_id . $v_param . '" frameborder="0" allowfullscreen></iframe>';
    }
    // パラメータが不正(youtubeのURLではない)ときは埋め込みコードを生成しない。
    return false;
}


?>