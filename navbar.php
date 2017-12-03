<nav id="largescreen" class="navbar navbar-light">
        <div class="col">
            <div class="row">
            <a class="navbar-brand" href="#" style="margin-left:-31px">DEMONSTRATION</a>
            </div>
        </div>
        <div class="col-md-5">
            <div class="input-group" style="margin-left:20px">
                <input type="text" class="form-control" placeholder="請輸入關鍵字" style="font-size:13px;border-style:solid;border-color:#28a745;border-width:1.5px;border-radius:30px">
                <span class="input-group-addon" style="position:relative;left:-45px;font-size:13px;color:#fff;background-color:#28a745;border-color:#28a745;border-radius:30px;width:80px;height:39px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" aria-hidden="true"></i></span>
            </div>
        </div>
        <div class="col">
            <?php if($loggedin==1) { ?>
    <div class="dropdown">
        <button id="profilemenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button" class="loginbtn btn btn-outline-success btn-nav float-right dropdown-toggle" style="margin-right:-45px"><i class='fa fa-user' aria-hidden='true'></i>&nbsp;<?php echo $_SESSION['mousername']; ?></button>
        <div id="profiledropdownmenularge" class="dropdown-menu" aria-labelledby="profilemenu" style="position:absolute;top:40px!important">
            <?php if($permissiontoedit==1) { ?>
                <a class="dropdown-item" href="#" onclick="location.href='newproduct.php'"><i class="fa fa-plus-square" aria-hidden="true" style="width:15px"></i>&nbsp;添加新商品</a>
                <div class="dropdown-divider"></div>
            <?php } else if ($_SESSION['mousertype']==2) { ?>
                <a class="dropdown-item" href="#" onclick=""><i class="fa fa-plus-square" aria-hidden="true" style="width:15px"></i>&nbsp;添加新商戶</a>
                <div class="dropdown-divider"></div>
            <?php } ?>
            <a class="dropdown-item" href="#"><i class="fa fa-cog" aria-hidden="true" style="width:15px"></i>&nbsp;個人設定</a>
            <a class="logoutbtn dropdown-item" href="#"><i class="fa fa-sign-out" aria-hidden="true" style="width:15px"></i>&nbsp;登出</a>
        </div>
    </div>
<?php } else { ?>
    <button id="profilebtn" type="button" class="loginbtn btn btn-outline-success btn-nav float-right" style="margin-right:-45px"><i class='fa fa-sign-in' aria-hidden='true'></i>&nbsp;登陸</button>
<?php } ?>
<button type="button" class="btn btn-outline-success mx-2 btn-nav float-right"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;0</button>
</div>
</nav>
<nav id="smallscreen" class="navbar-light">
    <div class="row justify-content-center">
        <a class="navbar-brand" href="#">DEMONSTRATION</a>
    </div>
    <div class="row justify-content-center" style="margin-bottom:10px">
        <?php if($loggedin==1) { ?>
            <div class="dropdown">
                <button id="profilemenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button" class="loginbtn btn btn-outline-success btn-nav float-right dropdown-toggle"><i class='fa fa-user' aria-hidden='true'></i></button>
                <div id="profiledropdownmenusmall" class="dropdown-menu" aria-labelledby="profilemenu" style="position:absolute;top:40px!important">
                    <?php if($permissiontoedit==1) { ?>
                    <a class="dropdown-item" href="#" onclick="location.href='newproduct.php'"><i class="fa fa-plus-square" aria-hidden="true" style="width:15px"></i>&nbsp;添加商品</a>
                    <div class="dropdown-divider"></div>
                    <?php } else if ($_SESSION['mousertype']==2) { ?>
                    <a class="dropdown-item" href="#" onclick=""><i class="fa fa-plus-square" aria-hidden="true" style="width:15px"></i>&nbsp;添加新商戶</a>
                    <div class="dropdown-divider"></div>
                    <?php } ?>
                    <a class="dropdown-item" href="#"><i class="fa fa-cog" aria-hidden="true" style="width:15px"></i>&nbsp;個人設定</a>
                    <a class="logoutbtn dropdown-item" href="#"><i class="fa fa-sign-out" aria-hidden="true" style="width:15px"></i>&nbsp;登出</a>
                </div>
            </div>
        <?php } else { ?>
            <button id="profilebtn2" type="button" class="loginbtn btn btn-outline-success mx-1 btn-nav"><i class='fa fa-sign-in' aria-hidden='true'></i>&nbsp;登陸</button>
        <?php } ?>
        <button type="button" class="btn btn-outline-success mx-1 btn-nav" style="font-size:13px;"><i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;0</button>
        <button id="showsearchbar" type="button" class="btn btn-outline-success mx-1 rounded-circle" style="font-size:13px;height:39px;width:39px"><i class="fa fa-search" aria-hidden="true"></i></button>
        <button id="showcategory" type="button" class="btn btn-outline-success mx-1 rounded-circle" style="font-size:13px;height:39px;width:39px"><i class="fa fa-bars" aria-hidden="true"></i></button>
    </div>
    <div id="smallsearchbar" class="row justify-content-center" style="display:none">
        <div class="input-group" style="margin-left:15px">
            <input type="text" class="form-control" placeholder="請輸入關鍵字" style="margin-right:-27px;font-size:13px;border-style:solid;border-color:#28a745;border-width:1.5px;border-radius:30px">
            <span class="input-group-addon" style="position:relative;left:-15px;font-size:13px;color:#fff;background-color:#28a745;border-color:#28a745;border-radius:30px;width:80px;height:39px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-search" aria-hidden="true"></i></span>
        </div>
    </div>
</nav>