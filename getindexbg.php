<?php
$lifetime=1800;
if (session_id() === "") {
    session_start([
        'cookie_lifetime' => $lifetime,
    ]);
}
setcookie(session_name(),session_id(),time()+$lifetime);
require_once('db.php');

$output = '<ol class="carousel-indicators">';
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
$output .= $selector;
$output .= '</ol><div class="carousel-inner" role="listbox">' . $imgoutput . '</div>
        <a class="carousel-control-prev" href="#bgCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#bgCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>';
echo $output;
?>