<?php
$bigbonus = '8.88|0.02，88.88|0.008';

if($bigbonus) {
    $bigbonus = str_replace('，', ',', $bigbonus);
    $bigbonusArr = explode(',', $bigbonus);
    if($bigbonusArr) {
        $rateItems = [];
        foreach($bigbonusArr as $bonus) {
            $bonusArr = explode('|', $bonus);
            if($bonusArr) {
                $num  = $bonusArr[0];
                $rate = $bonusArr[1];
                list($rateNum, $total) = _dealRate($rate);
                // $rateItems[$num] = $rateNum . '/' . $total;
                $randoms = _makeRandom($rateNum, $total);
                print_r($randoms);
            }
        }
        // print_r($rateItems);
    }
}
exit;

/**
 * 产生指定数量、指定区间的随机数
 */
function _makeRandom($num, $max) {
    $randoms = [];
    for($i = 0; $i < $num; $i++) {
        array_push($randoms, rand(1, $max));
    }
    sort($randoms);
    return $randoms;
}

exit;
// list($a, $b) = _dealRate(1);
// echo $a . ' - ' . $b;exit;

function _dealRate($rate) {
    for($i = 0; $i <= 10; $i++) {
        if($rate * pow(10, $i) >= 1) {
            return [$rate * pow(10, $i), pow(10, $i)];
        }
    }
}

$arr = [];
for($i = 0; $i < 5; $i++) {
    array_push($arr, rand(1, 10000));
}
sort($arr);
print_r($arr);
exit;


$prize_arr = array(
    '0' => array('id'=>1,'prize'=>'88.88','v'=>5),
    '1' => array('id'=>2,'prize'=>'0','v'=>995),
    // '2' => array('id'=>3,'prize'=>'音箱设备','v'=>10),
    // '3' => array('id'=>4,'prize'=>'4G优盘','v'=>12),
    // '4' => array('id'=>5,'prize'=>'10Q币','v'=>22),
    // '5' => array('id'=>6,'prize'=>'下次没准就能中哦','v'=>50),
);

// for($i = 0; $i < 100; $i++) {
    foreach ($prize_arr as $key => $val) {
        $arr[$val['id']] = $val['v'];
    }
    $res = [];
    $rid = get_rand($arr); //根据概率获取奖项id
    // if($prize_arr[$rid-1]['prize'] == '8.88')
        $res['yes'] = $prize_arr[$rid-1]['prize']; //中奖项
    unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项
    shuffle($prize_arr); //打乱数组顺序
    for($i=0;$i<count($prize_arr);$i++){
        $pr[] = $prize_arr[$i]['prize'];
    }
    $res['no'] = $pr;
    // echo json_encode($res);
// }



function get_rand($proArr) {
    $result = '';

    //概率数组的总概率精度
    $proSum = array_sum($proArr);

    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);

    return$result;
}
