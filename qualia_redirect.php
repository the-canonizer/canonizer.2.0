<?php
$url = "https://canonizer.com/topic/592-Are-You-Qualia-Blind/1-Agreement";
if(isset($_GET['Qnum']) && $_GET['Qnum']!=''){
    $urlMapper = array(
        "1"=>"https://canonizer.com/topic/591-Which-one-is-not-like-the-othr/1-Agreement",
        "2"=>"https://canonizer.com/topic/593-Reality-vs-Knowledge-of-Realty/1-Agreement",
        "3"=>"https://canonizer.com/topic/594-Physical-Knowledge/1-Agreement",
        "4"=>"https://canonizer.com/topic/595-Abstract-Word-for-Red/1-Agreement",
        "5"=>"https://canonizer.com/topic/596-What-Has-a-Redness-Quality/1-Agreement",
        "6"=>"https://canonizer.com/topic/597-Can-Qualia-Be-Mistaken/1-Agreement",
        "7"=>"https://canonizer.com/topic/598-What-color-is-the-light/1-Agreement",
        "8"=>"https://canonizer.com/topic/599-Objectively-Observe-Red-Qualia/1-Agreement",
        "9"=>"https://canonizer.com/topic/600-Possible-to-Eff-Ineffable/1-Agreement",
        "10"=>"https://canonizer.com/topic/601-Objectively-Observe-Qualia/1-Agreement",
        "11"=>"https://canonizer.com/topic/602-Data-frm-Objective-Observation/1-Agreement",
        "12"=>"https://canonizer.com/topic/603-Current-Observation-Issues/1-Agreement",
        "13"=>"https://canonizer.com/topic/604-What-Color-are-Things/1-Agreement",
        "14"=>"https://canonizer.com/topic/605-Which-systems-are-conscious/1-Agreement"
    );
    if(isset($urlMapper[$_GET['Qnum']])){
        $url = $urlMapper[$_GET['Qnum']];
    }
}
header("Location: $url");

?>
