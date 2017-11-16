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
$query = "select bg.FileUrl,bg.selected from momerchantbg bg inner join momerchants mer on bg.MerchantId=mer.Id where bg.TrashedDate is null and mer.Id=" . $_REQUEST['id'];
$result = $mysqli->query($query);
if(($result) && ($result->num_rows)) {
    while($row=$result->fetch_assoc()) {
        if($row['selected']==1) {
            $output = $row['FileUrl'];
        }
    }
}
echo $output;
?>