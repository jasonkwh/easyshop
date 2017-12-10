<?php
$lifetime=1800;
if (session_id() === "") {
    session_start([
        'cookie_lifetime' => $lifetime,
    ]);
}
setcookie(session_name(),session_id(),time()+$lifetime);
require_once('db.php');

$output = '';
$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
if (isset($_REQUEST['selected'])) {
    $imageurls = array();
    $count = 1;
    if($_REQUEST['selected']==0) {
        $query = "select Id,FileName,FileUrl,selected from moproductimgs where TrashedDate is null and ProductId=" . $_REQUEST['id'];
        $result = $mysqli->query($query);
        if(($result) && ($result->num_rows)) {
            while($row = $result->fetch_assoc()) {
                if($row['selected']==1) {
                    $imageurls['A']=$row['FileUrl'];
                } else {
                    $imageurls[substr($alphabet,$count,$count)]=$row['FileUrl'];
                    $count++;
                }
            }
        }
        if(!isset($imageurls['A'])) {
            $imageurls['A'] = '/img/emptyimage.jpg';
        }
        if(!isset($imageurls['B'])) {
            $imageurls['B'] = '/img/emptyimage.jpg';
        }
        if(!isset($imageurls['C'])) {
            $imageurls['C'] = '/img/emptyimage.jpg';
        }
        if(!isset($imageurls['D'])) {
            $imageurls['D'] = '/img/emptyimage.jpg';
        }
        $output = json_encode($imageurls);
    } else {
        $output = 'failed';
    }
} else {
    $output = 'failed';
}
echo $output;
?>