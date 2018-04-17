<?php
require_once('userstatus.php');
$_SESSION['mocurrenturl'] = strtok((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'?');
$_SESSION['mopageid'] = $_REQUEST['id'];
$permissiontoedit = 0;
$merchantid = 0;
$query = "select * from moproducts where Id=" . $_REQUEST['id'];
$result = $mysqli->query($query);
$productname = "商品名稱";
$descriptions = "此處為商品介紹 (限300英文字)";
$productinfo = "";
$producttitle = '';
$price = "";
$categoryid = 0;
$subcategory = "";
if(($result) && ($result->num_rows!==0)) {
    $row=$result->fetch_assoc();
    $merchantid = $row['MerchantId'];
    $productname = $row['Name'];
    $descriptions = $row['Descriptions'];
    $price = $row['Price'];
    $productinfo = $row['ProductInfo'];
    if(!is_null($row['CategoryId'])) {
        $categoryid = $row['CategoryId'];
    }
    if(!is_null($row['SubCategory'])) {
        $subcategory = $row['SubCategory'];
    }
}
if($_SESSION['mousertype']==1) {
    $query = "select MerchantId from mousers where Id=" . $_SESSION['mouserid'];
    $result = $mysqli->query($query);
    if(($result) && ($result->num_rows!==0)) {
        $row = $result->fetch_assoc();
        if($row['MerchantId']==$merchantid) {
            $permissiontoedit = 1;
            $producttitle = 'data-toggle="tooltip" data-placement="bottom" data-original-title="點擊管理商品圖片"';
        }
    }
}
$categoryoptions = '';
$subcategoryoptions = '';
$query="select Id,Name,subcategories from mocategories where TrashedDate is null";
$result = $mysqli->query($query);
if(($result) && ($result->num_rows!==0)) {
    if($permissiontoedit==1) {
        $categoryoptions .= '<option value="-1" disabled';
        if($categoryid==0) {
            $categoryoptions .= ' selected';
        }
        $categoryoptions .= '>類別</option>';
        $subcategoryoptions .= '<option value="" disabled';
        if($subcategory=="") {
            $subcategoryoptions .= ' selected';
        }
        $subcategoryoptions .= '>子類別</option>';
        while($row=$result->fetch_assoc()) {
            $categoryoptions .= '<option value="' . $row['Id'] . '"';
            if($categoryid==$row['Id']) {
                $categoryoptions .= ' selected';
                if((!is_null($row['subcategories'])) && ($row['subcategories']!="")) {
                    $temparray = explode(",",$row['subcategories']);
                    foreach($temparray as $subcategoryrow) {
                        $subcategoryoptions .= '<option value="' . $subcategoryrow . '"';
                        if($subcategoryrow == $subcategory) {
                            $subcategoryoptions .= ' selected';
                        }
                        $subcategoryoptions .= '>' . $subcategoryrow . '</option>';
                    }
                }
            }
            $categoryoptions .= '>' . $row['Name'] . '</option>';
        }
    } else {
        while($row=$result->fetch_assoc()) {
            if($categoryid==$row['Id']) {
                $categoryoptions = $row['Name'];
            }
        }
        $subcategoryoptions = $subcategory;
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
    <script src="js/tinymce.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-confirm.min.js"></script>
    <script src="js/index.js"></script>
    <script>
        <?php if($permissiontoedit==1) { ?>
        tinymce.init({height: 409,
            selector: "#productinfo",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars insertdatetime nonbreaking code fullscreen",
                "insertdatetime media table contextmenu paste directionality textcolor emoticons responsivefilemanager"
            ],
            toolbar1: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | savebutton print preview code",
            toolbar2: "responsivefilemanager image media | link unlink anchor | youtube | qrcode | flickr | picasa | colorpicker forecolor backcolor | fontselect,fontsizeselect,formatselect",
            image_advtab: true,
            external_filemanager_path:"/filemanager/",
            filemanager_title:"File manager",
            external_plugins: { "filemanager" : "/filemanager/plugin.min.js" },
            entity_encoding: 'raw',
            valid_children : "+body[style]",
            paste_auto_cleanup_on_paste : true,
            branding: false,
            paste_postprocess : function(pl, o) {
                // remove &nbsp
                o.node.innerHTML = o.node.innerHTML.replace(/&nbsp;/ig, "");
            },
            extended_valid_elements : "img[class|id|src|border|alt|title|tabindex|hspace|vspace|width|height|align|style|onmouseover|onmousedown|onmouseup|onmouseout|onclick|name|onerror]",
            style_formats: [
                {title: "Headers", items: [
                    {title: "Header 1", format: "h1"},
                    {title: "Header 2", format: "h2"},
                    {title: "Header 3", format: "h3"},
                    {title: "Header 4", format: "h4"},
                    {title: "Header 5", format: "h5"},
                    {title: "Header 6", format: "h6"}
                ]},
                {title: "Inline", items: [
                    {title: "Bold", icon: "bold", format: "bold"},
                    {title: "Italic", icon: "italic", format: "italic"},
                    {title: "Underline", icon: "underline", format: "underline"},
                    {title: "Strikethrough", icon: "strikethrough", format: "strikethrough"},
                    {title: "Superscript", icon: "superscript", format: "superscript"},
                    {title: "Subscript", icon: "subscript", format: "subscript"},
                    {title: "Code", icon: "code", format: "code"}
                ]},
                {title: "Blocks", items: [
                    {title: "Paragraph", format: "p"},
                    {title: "Blockquote", format: "blockquote"},
                    {title: "Div", format: "div"},
                    {title: "Pre", format: "pre"}
                ]},
                {title: "Alignment", items: [
                    {title: "Left", icon: "alignleft", format: "alignleft"},
                    {title: "Center", icon: "aligncenter", format: "aligncenter"},
                    {title: "Right", icon: "alignright", format: "alignright"},
                    {title: "Justify", icon: "alignjustify", format: "alignjustify"}
                ]},
                {title: "Normal", format: "p"}
            ],
            setup: function(editor) {
                editor.addButton('savebutton', {
                    tooltip: 'Save Content',
                    icon: 'save',
                    onclick: function () {
                        savedesccontent();
                    }
                });
                editor.addMenuItem('myitem', {
                    text: 'Save',
                    context: 'file',
                    icon: 'save',
                    onclick: function() {
                        savedesccontent();
                    }
                });
            }
        });

        function savedesccontent() {
            $.post('saveproductinfo.php', {
                productid: <?php echo $_REQUEST['id']; ?>,
                productname: $('#moproductname').val(),
                productlongdesc: tinyMCE.activeEditor.getContent()
            }).done(function(data) {
                console.log(data)
                if (data === "success") {
                    successlogindialog("內容已儲存!")
                }
            });
        }
        <?php } ?>

        $(function(){
            <?php if($permissiontoedit==1) { ?>
            checkCharRemains();
            <?php } ?>
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
            $("#pricechanger").bind( "paste", function() {
                replacenonnumbersanddot();
            });
            $("#pricechanger").mouseout(function() {
                tovalidcurrency();
            });
            $( "#shortdescedit" ).bind( "paste", function() {
                setTimeout( function() {
                    checkCharRemains();
                }, 100);
            });
            $('#categorycard').on('click', function(event) {
                if (!$('#categorybody').is(':visible')) {
                    $('#categorybody').show();
                } else {
                    $('#categorybody').hide();
                }
            });
        });

        function checkCharRemains() {
            var remaining = 150-$('#shortdescedit').val().length;
            if(remaining>0) {
                $('#remainchars').html("<p style='font-size:12px;color:#000'>剩餘 " + remaining.toString() + " 字</p>");
            } else {
                $('#remainchars').html("<p style='font-size:12px;color:#ff0000'>剩餘 " + remaining.toString() + " 字</p>");
            }
        }

        function replacenonnumbersanddot() {
            $('#pricechanger').val($('#pricechanger').val().replace(/[^0-9\.]+/g, ''));
        }

        function tovalidcurrency() {
            $('#pricechanger').val(parseFloat($('#pricechanger').val()).toFixed(2));
        }

        function selectthis(e) {
            var selecteditem = e.attr('id').split('_')[1];
            $.post('newpage.php', {
                selectmerchantbgimg: 1,
                bgimgid: selecteditem,
                merchantid: <?php echo $merchantid; ?>
            }).done(function(data) {
                if (data === "success") {
                    $('.mobgimghref img').removeClass('mobgimgselected');
                    $('#'+e.attr('id') + ' img').addClass('mobgimgselected');
                    loadmerbgimg(<?php echo $merchantid; ?>);
                } else {
                    errordialog('選擇圖片失敗');
                }
            });
        }

        function selectthispd(e) {
            var selecteditem = e.attr('id').split('_')[1];
            $.post('newpage.php', {
                selectproductimg: 1,
                bgimgid: selecteditem,
                productid: <?php echo $_REQUEST['id']; ?>
            }).done(function(data) {
                if(data === "success") {
                    $('.mobgimghref2 img').removeClass('mobgimgselected');
                    $('#'+e.attr('id') + ' img').addClass('mobgimgselected');
                    loadprodbgimg(<?php echo $_REQUEST['id']; ?>);
                } else {
                    errordialog('選擇圖片失敗');
                }
            });
        }

        function goBack() {
            <?php if($permissiontoedit==1) { ?>
            $.confirm({
                icon: 'fa fa-warning',
                title: '返回上一頁',
                content: '離開頁面將會丟失所有未保存的內容，確定離開?',
                typeAnimated: true,
                closeIcon: true,
                buttons: {
                    somethingElse: {
                        text: '<i class="fa fa-check" aria-hidden="true"></i>&nbsp;確定',
                        btnClass: 'btn btn-danger btn-nav btn-error',
                        keys: ['enter'],
                        action: function(){
                            <?php } ?>
                            history.back(1);
                            <?php if($permissiontoedit==1) { ?>
                        }
                    },
                    closeBtn: {
                        text: '<i class="fa fa-times" aria-hidden="true"></i>&nbsp;取消',
                        btnClass: 'btn btn-outline-danger btn-nav btn-error btn-error-cancel',
                        action: function(){
                        }
                    }
                }
            });
            <?php } ?>
        }

        function replacenonnumbers() {
            $('#moquantities').val($('#moquantities').val().replace(/[^0-9]+/g, ''));
            if($('#moquantities').val()=="") {
                $('#moquantities').val(1);
            }
        }

        function minusquantities() {
            replacenonnumbers();
            if(parseInt($('#moquantities').val())>1) {
                $('#moquantities').val(parseInt($('#moquantities').val())-1);
            }
        }

        function addquantities() {
            replacenonnumbers();
            $('#moquantities').val(parseInt($('#moquantities').val())+1);
        }

        <?php if($permissiontoedit==1) { ?>
        function savecontents() {
            tovalidcurrency();
            replacenonnumbers();
            $.post('saveproduct.php', {
                productid: <?php echo $_REQUEST['id']; ?>,
                productname: $('#moproductname').val(),
                productshortdesc: $('#shortdescedit').val(),
                productprice: $('#pricechanger').val(),
                productlongdesc: tinyMCE.activeEditor.getContent()
            }).done(function(data) {
                console.log(data)
                if (data === "success") {
                    successlogindialog("檔案已儲存!")
                }
            });
        }
        <?php } ?>
    </script>
</head>
<body style="height:100%;<?php
$query = "select Id,FileName,FileUrl,selected from momerchantbg where TrashedDate is null and MerchantId=" . $merchantid;
$result = $mysqli->query($query);
$imgmanoutput = "";
if(($result) && ($result->num_rows!==0)) {
    while($row = $result->fetch_assoc()) {
        if($row['selected']==1) {
            $imgmanoutput .= '<div id="mobg_' . $row['Id'] . '" class="col-lg-4 col-md-4 col-xs-6"><a id="mobgimg_' . $row['Id'] . '" href="#" onclick="selectthis($(this))" class="d-block mb-4 h-100 mobgimghref"><button type="button" class="btn btn-sm btn-danger rounded-circle mobgclose" onclick="trashbgimg(' . $row['Id'] . ',2,' . $merchantid . ')" style="width:30px;height:30px"><i class="fa fa-times" aria-hidden="true"></i></button><img class="img-fluid img-thumbnail mobgimg mobgimgselected" src="' . $row['FileUrl'] . '" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $row['FileName'] . '"></a></div>';
            echo "background:linear-gradient(0deg,rgba(255,255,255,1),rgba(255,255,255,0.8),rgba(255,255,255,0.6),rgba(255,255,255,0.2),rgba(255,255,255,0.2)),url(" . $row['FileUrl'] . ");";
        } else {
            $imgmanoutput .= '<div id="mobg_' . $row['Id'] . '" class="col-lg-4 col-md-4 col-xs-6"><a id="mobgimg_' . $row['Id'] . '" href="#" onclick="selectthis($(this))" class="d-block mb-4 h-100 mobgimghref"><button type="button" class="btn btn-sm btn-danger rounded-circle mobgclose" onclick="trashbgimg(' . $row['Id'] . ',2,' . $merchantid . ')" style="width:30px;height:30px"><i class="fa fa-times" aria-hidden="true"></i></button><img class="img-fluid img-thumbnail mobgimg" src="' . $row['FileUrl'] . '" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $row['FileName'] . '"></a></div>';
        }
    }
    echo "background-size:cover;background-attachment:fixed;";
}
$imageurls = array();
$query = "select Id,FileName,FileUrl,selected from moproductimgs where TrashedDate is null and ProductId=" . $_REQUEST['id'];
$result = $mysqli->query($query);
$imgmanoutput2 = "";
$mainimageurl = "";
if(($result) && ($result->num_rows!==0)) {
    while($row = $result->fetch_assoc()) {
        if($row['selected']==1) {
            $mainimageurl = $row['FileUrl'];
            $imgmanoutput2 .= '<div id="mopd_' . $row['Id'] . '" class="col-lg-4 col-md-4 col-xs-6"><a id="mopdimg_' . $row['Id'] . '" href="#" onclick="selectthispd($(this))" class="d-block mb-4 h-100 mobgimghref2"><button type="button" class="btn btn-sm btn-danger rounded-circle mobgclose" onclick="trashbgimg(' . $row['Id'] . ',3,' . $_REQUEST['id'] . ')" style="width:30px;height:30px"><i class="fa fa-times" aria-hidden="true"></i></button><img class="img-fluid img-thumbnail mobgimg2 mobgimgselected" src="' . $row['FileUrl'] . '" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $row['FileName'] . '"></a></div>';
        } else {
            array_push($imageurls,$row['FileUrl']);
            $imgmanoutput2 .= '<div id="mopd_' . $row['Id'] . '" class="col-lg-4 col-md-4 col-xs-6"><a id="mopdimg_' . $row['Id'] . '" href="#" onclick="selectthispd($(this))" class="d-block mb-4 h-100 mobgimghref2"><button type="button" class="btn btn-sm btn-danger rounded-circle mobgclose" onclick="trashbgimg(' . $row['Id'] . ',3,' . $_REQUEST['id'] . ')" style="width:30px;height:30px"><i class="fa fa-times" aria-hidden="true"></i></button><img class="img-fluid img-thumbnail mobgimg2" src="' . $row['FileUrl'] . '" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $row['FileName'] . '"></a></div>';
        }
    }
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
<div id="productcontainer" class="container" style="position:absolute;margin-left: auto;margin-right: auto;left: 0;right: 0;">
    <span style="float:right;cursor:pointer;z-index:3;right:15px;top:10px;position:absolute;font-size:14px;"><?php if($permissiontoedit==1) { ?><span style="color:#28a745"><i class="fa fa-list" aria-hidden="true" style="margin-right:4px"></i>&nbsp;<select style="width:90px;margin-right:8px;border:1.3px solid #30a64a;border-radius:30px;padding-left:8px"><?php echo $categoryoptions; ?></select><select style="width:90px;margin-right:8px;border:1.3px solid #30a64a;border-radius:30px;padding-left:8px"><?php echo $subcategoryoptions; ?></select></span><?php } else { ?><span style="color:#fff;background-color:#28a745;padding:8px 10px 8px 10px;margin-right:8px;border-radius:5px"><i class="fa fa-list" aria-hidden="true" style="margin-right:4px"></i>&nbsp;<?php echo $categoryoptions; ?><span style="background-color:#fff;color:#28a745;padding:4px 6px 4px 6px;margin-left:4px;margin-right:-4px;border-radius:5px"><?php echo $subcategoryoptions; ?></span></span><?php } ?><a href="#" onclick="goBack()" style="color:#28a745"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i>&nbsp;返回</a><?php if($permissiontoedit==1) { ?>&nbsp;&nbsp;&nbsp;<a href="#" onclick="savecontents()" style="color:#28a745"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;儲存修改</a><?php } ?></span>
    <div class="row" style="border-radius:5px;background-color:#fff;margin-bottom:15px">
        <button id="productimg0" class="col-md-4 moproducts grow" <?php echo $producttitle; ?> onclick="openproductimgmanager()" style="background:url(<?php if($mainimageurl!="") { echo $mainimageurl; } else { echo "/img/emptyimage.jpg"; } ?>);background-repeat:no-repeat;background-size:cover;max-width:380px;max-height:380px;width:auto;height:auto;border-top-left-radius:5px;border-bottom-left-radius:5px;border:none"></button>
        <div class="col-md-2">
            <div class="row">
                <button id="productimg1" <?php echo $producttitle; ?> onclick="openproductimgmanager()" class="col-md-12 mosubproducts grow-sm" style="background:url(<?php if(isset($imageurls[0])) { echo $imageurls[0]; } else { echo "/img/emptyimage.jpg"; } ?>);background-repeat:no-repeat;background-size:cover;border:none"></button>
                <button id="productimg2" <?php echo $producttitle; ?> onclick="openproductimgmanager()" class="col-md-12 mosubproducts grow-sm" style="background:url(<?php if(isset($imageurls[1])) { echo $imageurls[1]; } else { echo "/img/emptyimage.jpg"; } ?>);background-repeat:no-repeat;background-size:cover;border:none"></button>
                <button id="productimg3" <?php echo $producttitle; ?> onclick="openproductimgmanager()" class="col-md-12 mosubproducts grow-sm" style="background:url(<?php if(isset($imageurls[2])) { echo $imageurls[2]; } else { echo "/img/emptyimage.jpg"; } ?>);background-repeat:no-repeat;background-size:cover;border:none"></button>
            </div>
        </div>
        <div class="col-md-6 align-self-center" style="padding-left:0px;padding-right:40px;margin-top:20px">
            <div class="row">
                <h4><span style="font-size:20px;color:#28a745;"><i class="fa fa-shopping-bag" aria-hidden="true"></i></span>&nbsp;<?php if($permissiontoedit==1) { echo "<input id='moproductname' type='text' placeholder='商品名稱' value='" . $productname . "' required>"; } else { echo $productname; }?></h4>
            </div>
            <div class="row">
                <?php if($permissiontoedit==1) { echo "<textarea id='shortdescedit' style='height:120px;width:95%;border:1px solid #d2d2d2;resize:none' onkeyup='checkCharRemains()'>" . $descriptions . "</textarea></div><div class='row'><span id='remainchars'></span>"; } else { echo "<p>" . $descriptions . "</p>"; }?>
            </div>
            <div class="row">
                <h5>MOP$<?php if($permissiontoedit==1) { echo "<input id='pricechanger' type='text' placeholder='0.00' style='width:100px' value='" . $price . "' onkeyup='replacenonnumbersanddot()' required>"; } else { echo $price; } ?></h5>
            </div>
            <div class="row">
                <div class="col-1 align-self-center" style="padding:0;text-align:center">
                <button type="button" class="btn btn-sm btn-success rounded-circle" style="width:31px;height:31px" onclick="minusquantities()"><i class="fa fa-minus" aria-hidden="true"></i></button>
                </div>
                <div class="col-2 align-self-center" style="padding:0;text-align:center">
                    <div class="input-group"><input id="moquantities" type="text" placeholder="數量" class="form-control input-login" value="1"><span class="input-group-addon input-login-addon"><i class="fa fa-pencil" aria-hidden="true"></i></span></div>
                </div>
                <div class="col-1 align-self-center" style="padding:0;text-align:center">
                    <button type="button" class="btn btn-sm btn-success rounded-circle" style="width:31px;height:31px" onclick="addquantities()"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>
                </div>
            <div class="row" style="margin-top:10px">
                <button type="button" class="loginbtn btn btn-lg btn-outline-success btn-nav" onclick="<?php if($loggedin==1) { } else { echo "logindialog()"; } ?>" style="margin-right:10px">立即購買</button>
                <button type="button" class="loginbtn btn btn-lg btn-success btn-nav" onclick="<?php if($loggedin==1) { } else { echo "logindialog()"; } ?>"><i class="fa fa-cart-plus" aria-hidden="true"></i>&nbsp;加入購物車</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div style="background-color:#fff;height:100%;border-radius:5px;padding:15px">

            </div>
        </div>
        <div class="col-md-9">
            <div style="background-color:#fff;height:100%;border-radius:5px;padding:8px 8px 14px 8px">
                <div class="row">
                    <div class="col-12" style="margin-bottom:5px">
                        <ul class="nav nav-pills text-success">
                            <li class="nav-item">
                                <a class="nav-link active" href="/"><i class="fa fa-info" aria-hidden="true"></i>&nbsp;&nbsp;產品詳細</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="#"><i class="fa fa-commenting" aria-hidden="true"></i>&nbsp;&nbsp;用戶評價</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <?php if($permissiontoedit==1) { ?>
                        <textarea id="productinfo"><?php echo $productinfo; ?></textarea>
                        <?php } else {
                            echo $productinfo;
                        } ?>
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
<div id="merchantcontainer" style="display:none"></div>
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
                            <a href="#" class="d-block mb-4 h-100" onclick="$('#imagetype').val('merchantbg');$('#picUploadModal').modal();">
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
    <div class="modal fade" id="picManageModal2" tabindex="-1" role="dialog" aria-labelledby="picManageModal2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="modal-title" style="font-size:18px"><i class="fa fa-camera" aria-hidden="true" style="color:#28a745"></i>&nbsp;商品圖片管理</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row text-center text-lg-left">
                        <div class="col-lg-4 col-md-4 col-xs-6">
                            <a href="#" class="d-block mb-4 h-100" onclick="$('#imagetype').val('productbg');$('#picUploadModal').modal()">
                                <img class="img-fluid img-thumbnail mobgimg2 addpicbtn2" src="/img/addpic.png" alt="" data-toggle="tooltip" data-placement="bottom" data-original-title="上傳圖片">
                            </a>
                        </div>
                        <?php echo $imgmanoutput2; ?>
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
                        <input type="hidden" id="imagetype" name="imagetype" value="productbg">
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