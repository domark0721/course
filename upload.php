<?php
	// include_once('../api/auth.php'); 
?>
<!doctype html>
<html>
	<head>
		<?php require("meta_com.php"); ?>
		<link href="http://hayageek.github.io/jQuery-Upload-File/uploadfile.min.css" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="css/index.css">
		<title>NUCourse</title>
	</head>
	<body>
		<div class="totalWrapper">
			<?php require("header.php"); ?>

			<div class="container">
				<div id="fileuploader">Upload</div>
			</div>

			<?php require("footer.php"); ?>
		</div>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script src="http://hayageek.github.io/jQuery-Upload-File/jquery.uploadfile.min.js"></script>
		<script>
		$(document).ready(function()
		{
			$("#fileuploader").uploadFile({
				url:"api/videoUpload.php",
				dragDrop: true,
				fileName:"myfile",
				allowedTypes:"jpg,png,gif,doc,pdf,zip",
				returnType:"json",
				showDelete:true,
			    deleteCallback: function(data,pd)
				{
			    for(var i=0;i<data.length;i++)
			    {
			        $.post("api/videoDelete.php",{op:"delete",name:data[i]},
			        function(resp, textStatus, jqXHR)
			        {
			            //Show Message  
			            $("#status").append("<div>File Deleted</div>");      
			        });
			     }      
			    pd.statusbar.hide(); //You choice to hide/not.

			    }
			});
		});
		</script>
	</body>
</html>