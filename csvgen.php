<?php

// output filename
$csvfile = 'import.csv';

// base path (works best if you copy the script to the root
// of the directory you want to index, and set this to '.'.)
$startpath = '.';

// define the array and fields
$csvarray = array();
$csvarray[] = array('Dublin Core:Title','Dublin Core:Subject','Dublin Core:Subject2','Dublin Core:Subject3','Dublin Core:Description','Dublin Core:Creator','Dublin Core:Date','Dublin Core:Contributor','Dublin Core:Rights','Dublin Core:Relation','Dublin Core:Format','Dublin Core:Language','Dublin Core:Type','Dublin Core:Identifier','Dublin Core:Coverage','Dublin Core:Text','Dublin Core:Original Format','File URL');

// this could have been better.
$datemap = array("01"=>"January","02"=>"February","03"=>"March","04"=>"April","05"=>"May","06"=>"June","07"=>"July","08"=>"August","09"=>"September","10"=>"October","11"=>"November","12"=>"December");

// default vaules for each field
$dctitle = ''; // folderpath/date-based
$dcsubject = 'American newspapers--Michigan.';
$dcsubject2 = 'Kalkaska County (Mich.)';
$dcsubject3 = 'Kalkaska (Mich.)';
$dccreator = 'Contributors to the newspaper.';
$dcdate = ''; // this ends up being a rewritten form of the filename
$dccontributor = 'Kalkaska County Library, Kalkaska (Mich.)';
$dcrights = 'Excluding issues now in the public domain (1879-1924), Morning Star Publishing Company retains the copyright on the content of this newspaper. Depending on agreements made with writers and photographers, the creators of the content may still retain copyright. Please do not republish without permission.';
$dcrelation = 'Microfilmed reproduction of this newspaper issue is held at the Kalkaska County Library, Kalkaska (Mich.).';
$dcformat = 'PDF';
$dclanguage = 'English';
$dctype = 'Document';
$dcidentifier = ''; // this ends up being the filename
$dccoverage = 'Kalkaska County, Michigan';
$dctext = 'OCR Text pulled using pdf extractor plugin, compatible with omeka 2.1.';
$dcoriginalformat = 'Newsprint';
// base url
$url = 'http://minecraft.tadl.org/kcl/';

if ($folderpath = opendir($startpath)) {
    while (($folder = readdir($folderpath)) !== false) {
        if ((is_dir($folder)) && ($folder{0} != '.')) {
            if ($yearpath = opendir($folder)) {
                while (($year = readdir($yearpath)) !== false) {
                    if ((is_dir($folder . '/' . $year)) && ($year{0} != '.')) {
                        if ($monthpath = opendir($folder . '/' . $year)) {
                            while (($month = readdir($monthpath)) !== false) {
                                if ((is_dir($folder . '/' . $year . '/' . $month)) && ($month{0} != '.')) {
                                    if ($filepath = opendir($folder . '/' . $year . '/' . $month)) {
                                        while (($file = readdir($filepath)) !== false) {
                                            if (is_file($folder . '/' . $year . '/' . $month . '/' . $file)) {
                                                $fname = preg_split("/[0-9] /", $folder);
                                                $collection = $fname[1];
                                                $baredate = str_replace('.pdf', '', $file);
                                                $datearray = explode("-", $baredate);
                                                $fileyear = $datearray[2];
                                                $filemonth = $datearray[0];
                                                $fileday = $datearray[1];
                                                $dcdescription = 'Issue of "' . $collection . '" Newspaper.';
                                                $csvarray[] = array($collection . ", " . $datemap[$filemonth] ." ". $fileday .", ". $fileyear, $dcsubject, $dcsubject2, $dcsubject3, $dcdescription, $dccreator, $fileyear ."-". $filemonth ."-". $fileday, $dccontributor, $dcrights, $dcrelation, $dcformat, $dclanguage, $dctype, $file, $dccoverage, $dctext, $dcoriginalformat, $url . rawurlencode($folder) ."/". rawurlencode($year) ."/". rawurlencode($month) ."/". $file);
                                            }
                                        }
                                        closedir($filepath);
                                    }
                                }
                            }
                            closedir($monthpath);
                        }
                    }
                }
                closedir($yearpath);
            }
        }
    }
    closedir($folderpath);
}

$fp = fopen($csvfile, 'w');

foreach ($csvarray as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);

?>
