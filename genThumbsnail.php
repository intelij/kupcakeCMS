
<?php
Class genThumbsnail {      
  
	function create_square_image($original_file, $destination_file=NULL, $square_size = 90) { 
		
		if(isset($destination_file) and $destination_file!=NULL){
			if(!is_writable($destination_file)){
				echo '<p style="color:#FF0000">Oops, the destination path is not writable. Make that file or its parent folder wirtable.</p>';  
				
			}
		}
	   
	$destination_file = str_replace("../..", $_SERVER['DOCUMENT_ROOT'], $destination_file); 
	$original_file = str_replace("../..", $_SERVER['DOCUMENT_ROOT'], $original_file); 
	  
	   // add shell command
	$image_magick = "convert '$original_file'  -strip -quality 80% -resize 90x90^  -gravity center -extent 90x90  '$destination_file'";  

    	$output = exec($image_magick);



	}   
	

	
}

