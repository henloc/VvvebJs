<?php
// Initialize the session

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}

//include 'editor.html.php';
$html = file_get_contents('editor.html.php');
$files = "";
//search for html files in demo and my-pages folders
$htmlFiles = glob('{pages/*.html,demo/*\/*.html, demo/*.html}',  GLOB_BRACE);
foreach ($htmlFiles as $file) {
    if (in_array($file, array('new-page-blank-template.html', 'editor.html.php'))) continue;//skip template files
    $pathInfo = pathinfo($file);
    $filename = $pathInfo['filename'];
    $folder = preg_replace('@/.+?$@', '', $pathInfo['dirname']);
    $subfolder = preg_replace('@^.+?/@', '', $pathInfo['dirname']);
    if ($filename == 'index' && $subfolder) {
        $filename = $subfolder;
    }
    $url = $pathInfo['dirname'] . '/' . $pathInfo['basename'];
    $name = ucfirst($filename);

    $files .= "{name:'$name', file:'$filename', title:'$name',  url: '$url', folder:'$folder'},";
}


//replace files list from html with the dynamic list from demo folder
$html = str_replace('(pages)', "([$files])", $html);

echo $html;
