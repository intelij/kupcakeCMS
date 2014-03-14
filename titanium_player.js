// where you create the audioplayer
var audioPlayer = Ti.Media.createAudioPlayer({ 
    url: 'http://www.example.com/podcast.mp3',
    allowBackground: true
});  
Ti.App.addEventListener('audioplay', function(data) { 
     audioPlayer.play();
});
Ti.App.addEventListener('audiostop', function(data) { 
     audioPlayer.stop();
});
Ti.App.addEventListener('audiourl', function(data) { 
     audioPlayer.setUrl(data.url);
});
// add button then use click listener
btnPlay.addEventListener('click', function (e) {
    Ti.App.fireEvent('audioplay', {});
});
// add button then use click listener
btnStop.addEventListener('click', function (e) {
    Ti.App.fireEvent('audiostop', {});
});
// add button then use click listener
btnURL.addEventListener('click', function (e) {
    Ti.App.fireEvent('audiourl', { url: 'http://www.somwhereelse.com/feed.mp3' });
});













Hi
It does work when you are using the multi-context technique (url property).
Here is an example.
app.js
Ti.UI.setBackgroundColor('#fff');
 
var win1 = Ti.UI.createWindow({
    backgroundColor: '#FFF',
    height: Ti.UI.FILL,
    title: 'Web 1',
    url: 'win1.js',
    width: Ti.UI.FILL
});
var tab1 = Titanium.UI.createTab({  
    icon:'KS_nav_views.png',
    title: 'Tab 1',
    window: win1
});
 
var win2 = Ti.UI.createWindow({
    backgroundColor: '#FFF',
    height: Ti.UI.FILL,
    title: 'Win 2',
    url: 'win2.js',
    width: Ti.UI.FILL
});
var tab2 = Titanium.UI.createTab({  
    icon:'KS_nav_views.png',
    title: 'Tab 2',
    window: win2
});
 
var tabGroup = Titanium.UI.createTabGroup();
tabGroup.addTab(tab1);
tabGroup.addTab(tab2);
tabGroup.open();
win1.js
var win = Ti.UI.currentWindow;
 
var lbl = Ti.UI.createLabel({
    height: Ti.UI.SIZE,
    text: 'Test Label',
    width: Ti.UI.SIZE
});
 
win.add(lbl);
 
var audioPlayer = Ti.Media.createAudioPlayer({ 
    url: 'http://appcelerator.qe.test.data.s3.amazonaws.com/KSResources/audio/audio_session.mp3',
    allowBackground: true
});  
Ti.App.addEventListener('audioplay', function(data) { 
     audioPlayer.play();
});
Ti.App.addEventListener('audiostop', function(data) { 
     audioPlayer.stop();
});
Ti.App.addEventListener('audiourl', function(data) { 
     audioPlayer.setUrl(data.url);
});
win2.js
var win = Ti.UI.currentWindow;
 
var btnPlay = Ti.UI.createButton({
    height: Ti.UI.SIZE,
    title: 'Play',
    top: 50,
    width: Ti.UI.SIZE
});
btnPlay.addEventListener('click', function (e) {
    Ti.App.fireEvent('audioplay', {});
});
win.add(btnPlay);
 
var btnStop = Ti.UI.createButton({
    height: Ti.UI.SIZE,
    title: 'Stop',
    top: 150,
    width: Ti.UI.SIZE
});
btnStop.addEventListener('click', function (e) {
    Ti.App.fireEvent('audiostop', {});
});
win.add(btnStop);
 
var btnURL = Ti.UI.createButton({
    height: Ti.UI.SIZE,
    title: 'URL',
    top: 250,
    width: Ti.UI.SIZE
});
btnURL.addEventListener('click', function (e) {
    // choose something
    //Ti.App.fireEvent('audiourl', { url: 'http://...' });
});
win.add(btnURL);
All tested.
