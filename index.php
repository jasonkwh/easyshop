<?php
require_once('userstatus.php');
$_SESSION['mocurrenturl'] = strtok((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'?');
$_SESSION['mopageid'] = $_REQUEST['id'];
$permissiontoedit = 0;
if($_SESSION['mousertype']==2) {
    $permissiontoedit = 1;
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
            if ($(window).innerWidth() >= 768) {
                $('#indexcontainer').offset({
                    <?php if($permissiontoedit==1) { ?>
                    top: $('#photogallery').height() + $('#navbackground').height() - 10
                    <?php } else { ?>
                    top: $('#photogallery').height() + $('#navbackground').height() + 15
                    <?php } ?>
                });
            }
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
                        if($permissiontoedit==1) {
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
    </script>
</head>
<body>

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
                        if($permissiontoedit==1) { ?>
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
                <div id="categorybody" class="card-body" style="background-color:#28a745;color:#fff;border-bottom-left-radius:5px;border-bottom-right-radius:5px;font-size:13px">
                    <span>
                    <?php if($permissiontoedit==1) { ?><div class="row" style="margin-left:0;background-color:#28873c;padding:5px 5px 5px 8px;width:84px;height:30px;border-radius:5px"><i class="fa fa-plus-circle" aria-hidden="true" style="margin-top:3px"></i>&nbsp;<a href="#" onclick="newcategory($(this))" style="color:#fff">新的類別</a></div><?php } ?>
                    </span>
                </div>
            </div>
        </div>
    </div> <!--MAIN-->
</div>
<div id="photogallery" class="container-fluid" style="padding:0px;margin-bottom:<?php if($permissiontoedit==1) { ?>0px<?php } else { ?>20px<?php } ?>;text-align:center;">
    <div id="bgCarousel" class="carousel slide" data-ride="carousel" style="z-index:-100;<?php if($permissiontoedit==1) { ?>opacity:0.4<?php } ?>">
        <ol class="carousel-indicators">
            <?php
            $query = "select * from mohomepagebg where TrashedDate is null";
            $result = $mysqli->query($query);
            $counter = 0;
            $selector = '';
            $imgoutput = '';
            if(($result) && ($result->num_rows)) {
                while($row=$result->fetch_assoc()) {
                    if($counter==0) {
                        $selector .= '<li data-target="#bgCarousel" data-slide-to="' . $counter . '" class="active"></li>';
                        $imgoutput .= '<div class="carousel-item active"><img class="d-block w-100" src="' . $row['FileUrl'] . '" alt="' . $row['FileName'] . '"></div>';
                    } else {
                        $selector .= '<li data-target="#bgCarousel" data-slide-to="' . $counter . '"></li>';
                        $imgoutput .= '<div class="carousel-item"><img class="d-block w-100" src="' . $row['FileUrl'] . '" alt="' . $row['FileName'] . '"></div>';
                    }
                    $counter++;
                }
            }
            echo $selector;
            ?>
        </ol>
        <div class="carousel-inner" role="listbox">
            <?php echo $imgoutput; ?>
        </div>
        <a class="carousel-control-prev" href="#bgCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#bgCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <?php if($permissiontoedit==1) { ?><button id="carouselsettingsbtn" type="button" class="btn btn-success rounded-circle" style="width:70px;height:70px;margin-top:-425px;z-index:100" onclick="openbgimgmanager()"><i class="fa fa-wrench" aria-hidden="true" style="font-size:25px"></i></button><?php } ?>
</div>
<div id="productcontainer" style="display:none"></div>
<div id="merchantcontainer" style="display:none"></div>
<div id="indexcontainer" class="container">
    <div class="row">
        <div class="col-12 col-md-6" style="margin-bottom:15px">
            <div style="background-color:#fff;height:100%;border-radius:5px;padding:8px 8px 14px 8px">
                <div class="row" style="margin-bottom:15px">
                    <div class="col-12">
                        <ul class="nav nav-pills text-success">
                            <li class="nav-item">
                                <a class="nav-link active" href="/"><i class="fa fa-shopping-bag" aria-hidden="true"></i>&nbsp;&nbsp;入駐商家</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <?php
                    $query = "select Id,Name,LogoUrl from momerchants order by Name asc";
                    $result = $mysqli->query($query);
                    if(($result) && ($result->num_rows!==0)) {
                        while($row=$result->fetch_assoc()) {
                            echo '<div class="col-4 col-md-3"><a href="merchant.php?id=' . $row['Id'] . '" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $row['Name'] . '"><img src="' . $row['LogoUrl'] . '" style="width:100%"></a></div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6" style="margin-bottom:15px">
            <div style="background-color:#fff;height:100%;border-radius:5px;padding:8px 8px 14px 8px">
                <div class="row" style="margin-bottom:15px">
                    <div class="col-12">
                        <ul class="nav nav-pills text-success">
                            <li class="nav-item">
                                <a class="nav-link active" href="/"><i class="fa fa-list-ul" aria-hidden="true"></i>&nbsp;&nbsp;每天最新</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">

                </div>
            </div>
        </div>

        <div class="col-md-12" style="margin-bottom:15px">
            <div style="background-color:#fff;height:100%;border-radius:5px;padding:8px 8px 14px 8px">
                <div class="row" style="margin-bottom:15px">
                    <div class="col-12">
                        <ul class="nav nav-pills text-success">
                            <li class="nav-item">
                                <a class="nav-link active" href="/"><i class="fa fa-heart" aria-hidden="true"></i>&nbsp;&nbsp;每月特價</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">

                </div>
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
                <span>
                <?php if($permissiontoedit==1) { ?><div class="row" style="margin-left:0;background-color:#28873c;padding:5px 5px 5px 8px;width:84px;height:30px;border-radius:5px"><i class="fa fa-plus-circle" aria-hidden="true" style="margin-top:3px"></i>&nbsp;<a href="#" onclick="newcategory($(this))" style="color:#fff">新的類別</a></div><?php } ?>
                </span>
            </div>
        </div>
    </div>
</div>

<?php if($permissiontoedit==1) { ?>
<div class="modal fade" id="picManageModal" tabindex="-1" role="dialog" aria-labelledby="picManageModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title" style="font-size:18px"><i class="fa fa-camera" aria-hidden="true" style="color:#28a745"></i>&nbsp;首頁圖片管理</p>
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
                        <?php
                        $query = "select * from mohomepagebg where TrashedDate is null";
                        $result = $mysqli->query($query);
                        if(($result) && ($result->num_rows!==0)) {
                            while($row = $result->fetch_assoc()) {
                                echo '<div id="mobg_' . $row['Id'] . '" class="col-lg-4 col-md-4 col-xs-6"><a href="#" class="d-block mb-4 h-100 mobgimghref"><button type="button" class="btn btn-sm btn-danger rounded-circle mobgclose" onclick="trashbgimg(' . $row['Id'] . ',1,-1)" style="width:30px;height:30px"><i class="fa fa-times" aria-hidden="true"></i></button><img class="img-fluid img-thumbnail mobgimg" src="' . $row['FileUrl'] . '" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $row['FileName'] . '"></a></div>';
                            }
                        }
                        ?>
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
                        <input type="hidden" name="imagetype" value="homepagebg">
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