var win = Ti.UI.createWindow({
	backgroundColor: 'white'
});
var imgTableLoco = Ti.UI.createTableView({
	width: 270,
	height: 290,
	top: 0,
    backgroundColor: 'black'
});
 
/* Expects parameters of the directory name you wish to save it under, the url of the remote image, 
   and the Image View Object its being assigned to. */
cachedImageView = function(imageDirectoryName, url, imageViewObject)
{
	// Grab the filename
	var filename = url.split('/');
	filename = filename[filename.length - 1];
	// Try and get the file that has been previously cached
	var file = Ti.Filesystem.getFile(Ti.Filesystem.applicationDataDirectory, imageDirectoryName, filename);
 
	if (file.exists()) {
		// If it has been cached, assign the local asset path to the image view object.
		imageViewObject.image = file.nativePath;
	} else {
		// If it hasn't been cached, grab the directory it will be stored in.
		var g = Ti.Filesystem.getFile(Ti.Filesystem.applicationDataDirectory, imageDirectoryName);
		if (!g.exists()) {
			// If the directory doesn't exist, make it
			g.createDirectory();
		};
 
		// Create the HTTP client to download the asset.
		var xhr = Ti.Network.createHTTPClient();
 
		xhr.onload = function() {
			if (xhr.status == 200) {
				// On successful load, take that image file we tried to grab before and 
				// save the remote image data to it.
				file.write(xhr.responseData);
				// Assign the local asset path to the image view object.
				imageViewObject.image = file.nativePath;
			};
		};
 
		// Issuing a GET request to the remote URL
		xhr.open('GET', url);
		// Finally, sending the request out.
		xhr.send();
	};
};
 
 
downloadLocomotoraPicts = function() {
	var xhr = Ti.Network.createHTTPClient();
	xhr.open("GET", "http://api.flickr.com/services/rest/?method=flickr.photosets.getPhotos&api_key=7c84ebc0d4e17a855b038fe30cf2f1df&photoset_id=72157626940698359&format=json&nojsoncallback=1");
	xhr.onload = function() {
		try {
			var dataJson = eval("(" + this.responseText + ")");
			for (var i = 0; i < dataJson.photoset.photo.length; i++) {
				imageURL = "http://farm6.static.flickr.com/" + dataJson.photoset.photo[i].server + "/" + dataJson.photoset.photo[i].id + "_" + dataJson.photoset.photo[i].secret + "_m.jpg";
				
				//MPM Memory info (uncomment if you need it)
				//Ti.API.info("Memory INFO: "+Ti.Platform.availableMemory);
				
				//Create the imgView for each pict
				var row = Ti.UI.createTableViewRow({
					width : 400,
					pId : dataJson.photoset.photo[i].id,
					pTitle : dataJson.photoset.photo[i].title,
					pUrl : imageURL,
					className: 'images', // MPM: Added a className, so Android optimizes the table
				});
				var pict = Ti.UI.createImageView({
					width : 400,
					height : 350,
				});
				// This is the cache part
				cachedImageView('localimages', imageURL, pict);
				// MP: This is your informative. Uncomment if needed. 
				//Ti.API.info(imageURL);
				//Ti.API.info("Memory INFO: "+Ti.Platform.availableMemory);			
				row.height = 'auto';
				//pict.addEventListener('error', function(){
				//	Ti.API.info('got timeout');
				//});
				row.add(pict);
				imgTableLoco.appendRow(row);
			}
		} catch(e) {
			alert(e);
		}
	};
	xhr.send();
}
 
 
imgTableLoco.addEventListener('click', function(e){
	if (e.rowData.pTitle && e.rowData.pUrl) {
		alert(e.rowData.pTitle);
	}	
});
 
win.add(imgTableLoco);
win.open();
downloadLocomotoraPicts();
