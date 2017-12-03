<?php
require_once('userstatus.php');
$_SESSION['mocurrenturl'] = strtok((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'?');
$_SESSION['mopageid'] = $_REQUEST['id'];
$permissiontoedit = 0;
if($_SESSION['mousertype']==1) {
    $query = "select MerchantId from mousers where Id=" . $_SESSION['mouserid'];
    $result = $mysqli->query($query);
    if(($result) && ($result->num_rows!==0)) {
        $row = $result->fetch_assoc();
        if((int)$row['MerchantId']==(int)$_REQUEST['id']) {
            $permissiontoedit = 1;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <title>Demo</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-confirm.min.css">
    <link rel="stylesheet" type="text/css" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" type="text/css" href="css/general.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-confirm.min.js"></script>
    <script src="js/index.js"></script>
    <script>
        $(function(){
            $('[data-toggle="tooltip"]').tooltip();
            $( ".dropdown" ).mouseover(function() {
                <?php
                $pageshtml = '';
                $query="SELECT Id,Name FROM mopages WHERE TrashedDate IS NULL ORDER BY PageOrder ASC;";
                $result=$mysqli->query($query);
                if(($result) && ($result->num_rows!==0)) {
                    $prevpageid=0;
                    $lastrowcounter = 0;
                    $numofrows = $result->num_rows;
                    while($row = $result->fetch_assoc()) {
                        if($_SESSION['mousertype']==2) {
                            $pageshtml = str_replace("movepagedown(" . $prevpageid . "," . $prevpageid . ")","movepagedown(" . $prevpageid . "," . $row['Id'] . ")",$pageshtml);
                            echo "$('#pageid" . $row['Id'] . "menu').css('left',-($('#pageid" . $row['Id'] . "ctrls').width()/2-$('#pageid" . $row['Id'] . "btn').width()/2)-3);";
                            $pageshtml .= '<li id="pageid' . $row['Id'] . 'dropdown" class="nav-item dropdown mopages"><a id="pageid' . $row['Id'] . 'btn" class="nav-link" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $row['Name'] . '</a><div id="pageid' . $row['Id'] . 'menu" class="dropdown-menu pagebuttons" aria-labelledby="pageid' . $row['Id'] . '_controls">
          <a id="pageid' . $row['Id'] . 'ctrls" class="dropdown-item" href="#"><button type="button" class="btn btn-sm btn-success rounded-circle" onclick="movepageup(' . $row['Id'] . ',' . $prevpageid . ')" style="width:30px;height:30px" ';
                            if($prevpageid==0) {
                                $pageshtml .= 'disabled';
                            }
                            $pageshtml .= '><i class="fa fa-arrow-left" aria-hidden="true"></i></button>&nbsp;<button type="button" class="btn btn-sm btn-success rounded-circle" onclick="movepagedown(' . $row['Id'] . ',' . $row['Id'] . ')" style="width:30px;height:30px" ';
                            if($lastrowcounter==$numofrows-1) {
                                $pageshtml .= 'disabled';
                            }
                            $pageshtml .= '><i class="fa fa-arrow-right" aria-hidden="true"></i></button>&nbsp;<button type="button" class="btn btn-sm btn-danger rounded-circle" onclick="trashpages(' . $row['Id'] . ')" style="width:30px;height:30px"><i class="fa fa-times" aria-hidden="true"></i></button></a>
        </div></li>';
                            $prevpageid=$row['Id'];
                        } else {
                            $pageshtml .= '<li class="nav-item"><a class="nav-link" href="#">' . $row['Name'] . '</a></li>';
                        }
                        $lastrowcounter++;
                    }
                }
                ?>
            });
        });

        function selectthis(e) {
            var selecteditem = e.attr('id').split('_')[1];
            $.post('newpage.php', {
                selectmerchantbgimg: 1,
                bgimgid: selecteditem,
                merchantid: <?php echo $_REQUEST['id']; ?>
            }).done(function(data) {
                if (data === "success") {
                    $('.mobgimghref img').removeClass('mobgimgselected');
                    $('#'+e.attr('id') + ' img').addClass('mobgimgselected');
                    loadmerbgimg(<?php echo $_REQUEST['id']; ?>);
                } else {
                    errordialog('選擇圖片失敗');
                }
            });
        }
    </script>
</head>
<body style="height:100%;<?php
$query = "select bg.Id,bg.FileName,bg.FileUrl,bg.selected,mer.LogoUrl from momerchantbg bg inner join momerchants mer on bg.MerchantId=mer.Id where bg.TrashedDate is null and mer.Id=" . $_REQUEST['id'];
$result = $mysqli->query($query);
$imgmanoutput = "";
$logourl = "";
if(($result) && ($result->num_rows!==0)) {
    while($row = $result->fetch_assoc()) {
        if($row['selected']==1) {
            $imgmanoutput .= '<div id="mobg_' . $row['Id'] . '" class="col-lg-4 col-md-4 col-xs-6"><a id="mobgimg_' . $row['Id'] . '" href="#" onclick="selectthis($(this))" class="d-block mb-4 h-100 mobgimghref"><button type="button" class="btn btn-sm btn-danger rounded-circle mobgclose" onclick="trashbgimg(' . $row['Id'] . ',2,' . $_REQUEST['id'] . ')" style="width:30px;height:30px"><i class="fa fa-times" aria-hidden="true"></i></button><img class="img-fluid img-thumbnail mobgimg mobgimgselected" src="' . $row['FileUrl'] . '" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $row['FileName'] . '"></a></div>';
            echo "background:linear-gradient(0deg,rgba(255,255,255,1),rgba(255,255,255,0.8),rgba(255,255,255,0.6),rgba(255,255,255,0.2),rgba(255,255,255,0.2)),url(" . $row['FileUrl'] . ");";
        } else {
            $imgmanoutput .= '<div id="mobg_' . $row['Id'] . '" class="col-lg-4 col-md-4 col-xs-6"><a id="mobgimg_' . $row['Id'] . '" href="#" onclick="selectthis($(this))" class="d-block mb-4 h-100 mobgimghref"><button type="button" class="btn btn-sm btn-danger rounded-circle mobgclose" onclick="trashbgimg(' . $row['Id'] . ',2,' . $_REQUEST['id'] . ')" style="width:30px;height:30px"><i class="fa fa-times" aria-hidden="true"></i></button><img class="img-fluid img-thumbnail mobgimg" src="' . $row['FileUrl'] . '" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $row['FileName'] . '"></a></div>';
        }
        $logourl = $row['LogoUrl'];
    }
    echo "background-size:cover;background-attachment:fixed;";
}
?>">
<div class="container">
    <?php require_once('navbar.php'); ?>
    <div class="row" style="margin-top:5px">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-8">
                    <ul class="nav nav-pills justify-content-center text-success" style="margin-bottom:15px">
                        <li class="nav-item">
                            <a class="nav-link active" href="/"><i class="fa fa-home" aria-hidden="true"></i>&nbsp;&nbsp;首頁</a>
                        </li>
                        <?php echo $pageshtml;
                        if($_SESSION['mousertype']==2) { ?>
                        <li class="nav-item">
                            <a id="newpage" class="nav-link" href="#"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;新增頁面</a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="categorycarddisplay" class="col-md-3" style="margin-bottom:10px;z-index:100">
            <div id="categorycard" class="card" style="border-style:none">
                <p class="card-header" style="height:40px;background-color:#218838;color:#fff;font-size:13px;border-style:none"><i class="fa fa-bars" aria-hidden="true"></i>&nbsp;全部商品分類</p>
                <div id="categorybody" class="card-body" style="background-color:#28a745;color:#fff;border-bottom-left-radius:5px;border-bottom-right-radius:5px;font-size:13px;display:none">
                    <?php if($_SESSION['mousertype']==2) { ?><strong><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;新增類別</strong><?php } ?>
                </div>
            </div>
        </div>
    </div> <!--MAIN-->
</div>
<div id="photogallery" style="display:none"></div>
<?php if($permissiontoedit==1) { ?><button id="merchantbgimgbtn" type="button" onclick="openbgimgmanager()" class="btn btn-lg btn-success rounded-circle" style="position:absolute;weight:50px;height:50px;right:50px;top:120px;" onclick=""><i class="fa fa-wrench" aria-hidden="true"></i></button><?php } ?>
<div id="productcontainer" style="display:none"></div>
<div id="merchantcontainer" class="container" style="position:absolute;margin-left: auto;margin-right: auto;left: 0;right: 0;">
    <div id="merchantlogo" class="row">
        <div class="col-6 col-md-3">
            <img src="<?php echo $logourl; ?>" style="width:100%;margin-bottom:20px">
        </div>
    </div>
    <div class="row">
        <div class="card col-md-12">
            <div class="card-header">
                title
            </div>
            <div class="card-block">
                <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                </blockquote>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="card col-md-12">
            <div class="card-header">
                title
            </div>
            <div class="card-block">
                <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                </blockquote>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="card col-md-12">
            <div class="card-header">
                title
            </div>
            <div class="card-block">
                <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                </blockquote>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="card col-md-12">
            <div class="card-header">
                title
            </div>
            <div class="card-block">
                <blockquote class="card-blockquote">
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                    <footer>Someone famous in <cite title="Source Title">Source Title</cite></footer>
                </blockquote>
            </div>
        </div>
    </div>

    <br>
    <footer>
        <p class="text-center">test footer</p>
    </footer>
    </div>
<div id="navbackground">

</div>

<div class="modal fade right" id="categorymenu">
    <div class="modal-dialog" role="document" style="width:80%;color:#fff">
        <div class="modal-content">
            <div class="modal-header" style="height:40px;background-color:#218838;border-style:none;border-top-left-radius:3px">
                <p class="modal-title"><i class="fa fa-bars" aria-hidden="true"></i>&nbsp;全部商品分類</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="background-color:#28a745;border-bottom-left-radius:3px">
                <?php if($_SESSION['mousertype']==2) { ?><strong><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;新增類別</strong><?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if($permissiontoedit==1) { ?>
<div class="modal fade" id="picManageModal" tabindex="-1" role="dialog" aria-labelledby="picManageModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title" style="font-size:18px"><i class="fa fa-camera" aria-hidden="true" style="color:#28a745"></i>&nbsp;背景圖片管理</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row text-center text-lg-left">
                    <div class="col-lg-4 col-md-4 col-xs-6">
                        <a href="#" class="d-block mb-4 h-100" onclick="$('#picUploadModal').modal()">
                            <img class="img-fluid img-thumbnail mobgimg addpicbtn" src="/img/addpic.png" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="上傳圖片">
                        </a>
                    </div>
                    <?php echo $imgmanoutput; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-success btn-nav" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;關閉</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="picUploadModal" tabindex="-1" role="dialog" aria-labelledby="picUploadModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title" style="font-size:18px"><i class="fa fa-picture-o" aria-hidden="true" style="color:#28a745"></i>&nbsp;上傳圖片</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-idden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="adminbgimageform" action="imageupload.php" method="post" enctype="multipart/form-data" onsubmit="waitingdialog()">
                    請選擇需要上傳的文件：
                    <input type="file" name="fileToUpload" id="fileToUpload">
                    <input type="hidden" name="imagetype" value="merchantbg">
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success btn-nav" form="adminbgimageform" value="submit"><i class="fa fa-cloud-upload" aria-hidden="true"></i>&nbsp;上傳</button>
                <button type="button" class="btn btn-outline-success btn-nav" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>&nbsp;關閉</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
</body>
</html>