<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
<div class="container">
	<div class="col-md-6">
    	<a class="navbar-brand" id="chatty" href="#">Chatty</a>
	</div>
	<div class="col-md-6"></div>
</div>
</nav>

<div class="container">
	<div class="col-md-9">
	<?php
	if (isset($logHtml)) {
	 	echo $logHtml; 
	}
	 ?>	
	
	</div>
	<div class="col-md-3">
		
	</div>
	<div class="col-md-9">
		
		<div class="well" style="min-height:150px;">
			<p><span class="bold btn btn-primary btn-xs btn-block">Gallery</span></p>
			<?php if($noPhotoGal == 3) echo "User has no photos"; ?>

			<ul id="showImg">
			<script id="img_list_template" type="text/x-handlebars-template">
				{{#each this}}
				<li class="imagess"><img class="imgGrande" src='{{imgPath}}' alt='' width='140px' height='100px'><br><a href="" title="">Delete this photo</a></li>

				
				{{/each}}
				<p style="clear:both"><a class="glyphicon glyphicon-chevron-left btn btn-primary" href="imageGallery.php?u=<?php echo "$user";?>"></a></p>

			</script>

			</ul>

			<ul id="showImgs">
			<?php if(isset($genImg)) echo $genImg; ?>	
			<?php if(isset($friImg)) echo $friImg; ?>	
			<?php if(isset($famImg)) echo $famImg; ?>	
			<p style="clear:both"><a class="glyphicon glyphicon-chevron-left btn btn-primary" href="javascript:history.back()"></a></p>
			</ul>

			
		</div>
	</div>
</div>

    <!-- handlebars link -->
    <script src="js/handlebars-v2.0.0.js"></script>
