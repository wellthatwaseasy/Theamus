<?php

$HomePage = new HomePage();
$i = $HomePage->redirect();

$q = $tData->query("SELECT * FROM `".$tDataClass->prefix."_pages` WHERE `alias`='".$i['alias']."'");
$row = $q->fetch_assoc();

$views = $row['views'] + 1;
$tData->query("UPDATE `".$tDataClass->prefix."_pages` SET `views`='".$views."' WHERE `alias`='".$i['alias']."'");

echo $HomePage->page_content;