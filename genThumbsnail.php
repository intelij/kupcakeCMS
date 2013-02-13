###Image Generator using

```php
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

	
         // exec("convert assets/20th_century_fox/get_the_gringo/images/SV-04425.JPG  -strip -quality 80% -resize 90x90^  -gravity center -extent 90x90 assets/20th_century_fox/get_the_gringo/images/THUMBS2_SV-04425.JPG", $blaArray, $responsev);
	
	// echo $output; 
	
	// echo "<script>javascript:alert('Generating Thumbs!');</script>";

	}   
	
	//create_square_image("sample.jpg","thumbs_sample.jpg",90);   
	
}

```
