<?php
require_once('userstatus.php');
$output = "";
$pageid = 0;
if($loggedin==1) {
    if(isset($_POST['deletemobgimg']) && isset($_POST['mobgimgid'])) { // for bgimg management
        if($_POST['deletemobgimg']==1) {
            $query = "SELECT * FROM mousers WHERE Id=" . $_SESSION['mouserid'] . " AND UserType=2";
            $result = $mysqli->query($query);
            if(($result) && ($result->num_rows!==0)) {
                $query = "UPDATE mohomepagebg SET TrashedDate=NOW() WHERE Id=" . $_POST['mobgimgid'];
                $mysqli->query($query);
                $output = "success";
            } else {
                $output = "failed";
            }
        } else if($_POST['deletemobgimg']==2) {
            $query = "select MerchantId from mousers where Id=" . $_SESSION['mouserid'];
            $result = $mysqli->query($query);
            if(($result) && ($result->num_rows!==0)) {
                $row = $result->fetch_assoc();
                if ((int)$_POST['merchantid'] == (int)$row['MerchantId']) {
                    $query="select Id,selected from momerchantbg where Id=" . $_POST['mobgimgid'];
                    $result2=$mysqli->query($query);
                    if(($result2) && ($result2->num_rows!==0)) {
                        $row = $result2->fetch_assoc();
                        $query = "UPDATE momerchantbg SET TrashedDate=NOW() WHERE Id=" . $_POST['mobgimgid'];
                        $mysqli->query($query);
                        if($row['selected']==0) {
                            $output = "success";
                        } else {
                            $query = "update momerchantbg set selected=0 where MerchantId=" . $_POST['merchantid'];
                            $mysqli->query($query);
                            $query = "select Id from momerchantbg where MerchantId=" . $_POST['merchantid'] . " and TrashedDate is null order by Id desc limit 1";
                            $result3 = $mysqli->query($query);
                            $row3 = $result3->fetch_assoc();
                            $query = "update momerchantbg set selected=1 where Id=" . $row3['Id'];
                            $mysqli->query($query);
                            $output = $row3['Id'];
                        }
                    } else {
                        $output = "failed";
                    }
                } else {
                    $output = "failed";
                }
            } else {
                $output = "failed";
            }
        } else if($_POST['deletemobgimg']==3) {
            $query = "select usr.MerchantId from mousers usr inner join moproducts prod on usr.MerchantId=prod.MerchantId and prod.Id=" . $_POST['merchantid'] . " WHERE usr.Id=" . $_SESSION['mouserid'];
            $result = $mysqli->query($query);
            if(($result) && ($result->num_rows!==0)) {
                $query="select Id,selected from moproductimgs where Id=" . $_POST['mobgimgid'];
                $result2=$mysqli->query($query);
                if(($result2) && ($result2->num_rows!==0)) {
                    $row = $result2->fetch_assoc();
                    $query = "UPDATE moproductimgs SET TrashedDate=NOW() WHERE Id=" . $_POST['mobgimgid'];
                    $mysqli->query($query);
                    if($row['selected']==0) {
                        $output = "success";
                    } else {
                        $query = "update moproductimgs set selected=0 where ProductId=" . $_POST['merchantid'];
                        $mysqli->query($query);
                        $query = "select Id from moproductimgs where ProductId=" . $_POST['merchantid'] . " and TrashedDate is null order by Id desc limit 1";
                        $result3 = $mysqli->query($query);
                        $row3 = $result3->fetch_assoc();
                        $query = "update moproductimgs set selected=1 where Id=" . $row3['Id'];
                        $mysqli->query($query);
                        $output = $row3['Id'];
                    }
                } else {
                    $output = "failed";
                }
            } else {
                $output = "failed";
            }
        } else {
            $output = "failed";
        }
    } else if(isset($_POST['newpage']) && isset($_POST['newpagename'])) { //for page management
        $query = "SELECT * FROM mousers WHERE Id=" . $_SESSION['mouserid'] . " AND UserType=2";
        $result = $mysqli->query($query);
        if(($result) && ($result->num_rows!==0)) {
            $newpagename = $mysqli->real_escape_string($_POST['newpagename']);
            $query = "INSERT INTO mopages (Name) VALUES ('" . $newpagename . "');";
            $mysqli->query($query);
            $pageid = $mysqli->insert_id;
            $query = "UPDATE mopages SET PageOrder=" . $pageid . " WHERE Id=" . $pageid;
            $mysqli->query($query);
            $query = "INSERT INTO mopagecontents (mopageid,HtmlContent) VALUES ("  . $pageid . ",'<p>Hello, World!</p>')";
            $mysqli->query($query);
            $output = "success";
        } else {
            $output = "failed";
        }
    } else if(isset($_POST['trashpage']) && isset($_POST['pageid'])) {
        $query = "SELECT * FROM mousers WHERE Id=" . $_SESSION['mouserid'] . " AND UserType=2";
        $result = $mysqli->query($query);
        if(($result) && ($result->num_rows!==0)) {
            $pageid = $_POST['pageid'];
            $query = "UPDATE mopages SET TrashedDate=NOW() WHERE Id=" . $pageid;
            $mysqli->query($query);
            $output = "success";
        } else {
            $output = "failed";
        }
    } else if(isset($_POST['selectmerchantbgimg']) && isset($_POST['bgimgid'])) {
        $query = "select MerchantId from mousers where Id=" . $_SESSION['mouserid'];
        $result = $mysqli->query($query);
        if(($result) && ($result->num_rows!==0)) {
            $row = $result->fetch_assoc();
            if((int)$_POST['merchantid']==(int)$row['MerchantId']) {
                $query = "update momerchantbg set selected=0 where MerchantId=" . $_POST['merchantid'];
                $mysqli->query($query);
                $query = "update momerchantbg set selected=1 where Id=" . $_POST['bgimgid'];
                $mysqli->query($query);
                $output = "success";
            } else {
                $output = "failed";
            }
        } else {
            $output = "failed";
        }
    } else if(isset($_POST['selectproductimg']) && isset($_POST['bgimgid'])) {
        $query = "select usr.MerchantId from mousers usr inner join moproducts prod on usr.MerchantId=prod.MerchantId and prod.Id=" . $_POST['productid'] . " WHERE usr.Id=" . $_SESSION['mouserid'];
        $result = $mysqli->query($query);
        if(($result) && ($result->num_rows!==0)) {
            $query = "update moproductimgs set selected=0 where ProductId=" . $_POST['productid'];
            $mysqli->query($query);
            $query = "update moproductimgs set selected=1 where Id=" . $_POST['bgimgid'];
            $mysqli->query($query);
            $output = "success";
        } else {
            $output = "failed";
        }
    } else if(isset($_POST['pageorderup']) && isset($_POST['pageid']) && isset($_POST['prevpageid'])) {
        $query = "SELECT * FROM mousers WHERE Id=" . $_SESSION['mouserid'] . " AND UserType=2";
        $result = $mysqli->query($query);
        if(($result) && ($result->num_rows!==0)) {
            $pageorder = 0;
            $prevpageorder = 0;
            $query = "SELECT PageOrder FROM mopages WHERE Id=" . $_POST['pageid'] . " AND TrashedDate IS NULL";
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            $pageorder = $row['PageOrder'];
            $query = "SELECT PageOrder FROM mopages WHERE Id=" . $_POST['prevpageid'] . " AND TrashedDate IS NULL";
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            $prevpageorder = $row['PageOrder'];
            $query = "UPDATE mopages SET PageOrder=" . $prevpageorder . " WHERE Id=" . $_POST['pageid'];
            $mysqli->query($query);
            $query = "UPDATE mopages SET PageOrder=" . $pageorder . " WHERE Id=" . $_POST['prevpageid'];
            $mysqli->query($query);
            $output = "success";
        } else {
            $output = "failed";
        }
    } else if(isset($_POST['pageorderdown']) && isset($_POST['pageid']) && isset($_POST['nextpageid'])) {
        $query = "SELECT * FROM mousers WHERE Id=" . $_SESSION['mouserid'] . " AND UserType=2";
        $result = $mysqli->query($query);
        if(($result) && ($result->num_rows!==0)) {
            $pageorder = 0;
            $nextpageorder = 0;
            $query = "SELECT PageOrder FROM mopages WHERE Id=" . $_POST['pageid'] . " AND TrashedDate IS NULL";
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            $pageorder = $row['PageOrder'];
            $query = "SELECT PageOrder FROM mopages WHERE Id=" . $_POST['nextpageid'] . " AND TrashedDate IS NULL";
            $result = $mysqli->query($query);
            $row = $result->fetch_assoc();
            $nextpageorder = $row['PageOrder'];
            $query = "UPDATE mopages SET PageOrder=" . $nextpageorder . " WHERE Id=" . $_POST['pageid'];
            $mysqli->query($query);
            $query = "UPDATE mopages SET PageOrder=" . $pageorder . " WHERE Id=" . $_POST['nextpageid'];
            $mysqli->query($query);
            $output = "success";
        } else {
            $output = "failed";
        }
    } else if(isset($_POST['newcategory']) && isset($_POST['newcategoryname'])) {
        $query = "insert into mocategories (Name) values ('" . $mysqli->real_escape_string($_POST['newcategoryname']) . "')";
        if($mysqli->query($query)===TRUE) {
            $output = "success";
        } else {
            $output = "failed";
        }
    } else if(isset($_POST['trashcategory']) && isset($_POST['categoryid'])) {
        $query = "update mocategories set TrashedDate=NOW() where Id=" . $_POST['categoryid'];
        if($mysqli->query($query)===TRUE) {
            $output = "success";
        } else {
            $output = "failed";
        }
    } else {
        $output = "failed";
    }
} else {
    $output = "failed";
}
echo $output;