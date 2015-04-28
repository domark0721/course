<?php
	$mongo = new MongoClient("mongodb://root:10038@localhost");
	$db=$mongo->course;
	$collection=$db->content; //$collection相當於mysql的table
	                            // $mon = $collection->find();
?>