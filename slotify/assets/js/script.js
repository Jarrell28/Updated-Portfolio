var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

//hide options menu on click
$(document).click(function (click) {
    var target = $(click.target);
    //if the element clicked on does not have the class item and class optionsButton
    if(!target.hasClass("item") && !target.hasClass("optionsButton")){
        hideOptionsMenu();
    }

});

//hides options menu when scrolling
$(window).scroll(function(){
    hideOptionsMenu();
});

//when a change is made on the select with a class of playlist
$(document).on("change", "select.playlist", function () {
    var select = $(this);
    //refers to select.playlist
    var playlistId = select.val();
    //prev goes to the immediate previous attr above the select.playlist looking
    //the class songId. only goes up 1
    var songId = select.prev(".songId").val();

    //inserts the song selected into the playlist when clicked
    $.post("includes/handlers/ajax/addToPlaylist.php", {playlistId: playlistId, songId: songId}).done(function (error) {

        if(error != ""){
            alert(error);
            return;
        }
        //hides the menu and sets the value of select back to blank
        hideOptionsMenu();
        select.val("");

    });
});

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
    //targets the element with class of email using jquery
    var oldPassword = $("." + oldPasswordClass).val();
    var newPassword1 = $("." + newPasswordClass1).val();
    var newPassword2 = $("." + newPasswordClass2).val();

    $.post("includes/handlers/ajax/updatePassword.php", {oldPassword: oldPassword, newPassword1: newPassword1, newPassword2: newPassword2, username: userLoggedIn}).done(function (response) {
        //get the next element with class of message
        //response gets the echo from the updatePassword.php
        $("." + oldPasswordClass).nextAll(".message").text(response);
    })
}

function updateEmail(emailClass) {
    //targets the element with class of email using jquery
    var emailValue = $("." + emailClass).val();

    $.post("includes/handlers/ajax/updateEmail.php", {email: emailValue, username: userLoggedIn}).done(function (response) {
        //get the next element with class of message
        //response gets the echo from the updateEmail.php
        $("." + emailClass).nextAll(".message").text(response);
    })
}

function logout() {
    $.post("includes/handlers/ajax/logout.php", function () {
        location.reload();
    })
}


//SEAMLESS PAGE TRANSITIONS-make music play without reloading pages via ajax
function openPage(url) {
    //if we go to new page and timer is active, it clears the timer
    if(timer != null) {
        clearTimeout(timer);
    }
    //if the url does not have a url then we had a url to it, required
    //an index of -1 means it doesnt exist
    if(url.indexOf("?") == -1) {
        url = url + "?";
    }
    //encodes the url and replaces unknown characters to the url equivalent
    //so everything works fine
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    <!--        swaps the main content between pages, prevents reloading-->
    $("#mainContent").load(encodedUrl);
    //when changing pages, automatically scrolls to top
    $("body").scrollTop(0);
    //puts the url into the address bar/ changes url to correct page
    history.pushState(null, null, url);
}

function removeFromPlaylist(button, playlistId) {
    //prevAll goes as far up as it needs to find the class songId
    var songId = $(button).prevAll(".songId").val();

    //ajax code to remove song from playlist
    $.post("includes/handlers/ajax/removeFromPlaylist.php", {playlistId: playlistId, songId: songId}).done(function(error){
        //done executes function when ajax function is done, preferred way to execute ajax
        //works almost the same as success
        if(error != ""){
            alert(error);
            return;
        }
        //opens your music page via ajax
        openPage("playlist.php?id=" + playlistId);
    })
}

function createPlaylist() {
    var popup = prompt("Please enter the name of your playlist");

    //if alert is not empty, use an ajax call
    if(popup != null) {

        //gets username from userLoggedIn via openPage function
        $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn}).done(function(error){
            //done executes function when ajax function is done, preferred way to execute ajax
            //works almost the same as success

            if(error != ""){
                alert(error);
                return;
            }
            //opens your music page via ajax
            openPage("yourMusic.php");
        });

    }
}

function deletePlaylist(playlistId) {
    var prompt = confirm("Are you sure you want to delete this playlist?");

    if(prompt) {
        //gets playlistId from passed in argument
        $.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId}).done(function(error){
            //done executes function when ajax function is done, preferred way to execute ajax
            //works almost the same as success
            if(error != ""){
                alert(error);
                return;
            }
            //opens your music page via ajax
            openPage("yourMusic.php");
        })
    }
}

function hideOptionsMenu(){
    var menu = $(".optionsMenu");
    if(menu.css("display") != "none"){
        menu.css("display", "none");
    }
}

function showOptionsMenu(button){
    //prevAll goes as far up as it needs to find the class songId
    var songId = $(button).prevAll(".songId").val();
    //the option menu to add to playlist
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();
    //find the songId item and sets its value to the songId
    menu.find(".songId").val(songId);

    //scrollTop() gets the position from the top of window and where you are scrolled
    var scrollTop = $(window).scrollTop();
    //gets the position of the button from the top of the document
    var elementOffset = $(button).offset().top;
    //the distance of the button from the top of the document
    var top = elementOffset - scrollTop;
    //how far from the left of the screen the button is
    var left = $(button).position().left;
    //changes the position top and left attr to appear next to the options button
    menu.css({"top": top + "px", "left": left - menuWidth + "px", "display": "inline"});
}

//convert song duration into a better time format
function formatTime(seconds) {
    var time = Math.round(seconds); //rounds up
    var minutes = Math.floor(time / 60); //rounds down
    var seconds = time - (minutes * 60); //gets the seconds left over after minutes
    var extraZero;

    if(seconds < 10) {
        extraZero = "0";
    } else {
        extraZero = "";
    }
    //called conditional statement, similar to php tertiary statement
    // var extraZero = (seconds < 10) ? "0" : "";

    //to concatenate in javascript it uses +
    return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
    //to refer to 2 classes u must remove space in between them and at a period to second class name
    //makes the current time update with the currentTime object from audio
    $(".progressTime.current").text(formatTime(audio.currentTime));
    //makes the time remaining on song subtract while the currentTime is playing
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

    //calculates percentage and updates the progress bar
    var progress = audio.currentTime / audio.duration * 100;
    //put a space in between classes to indicate selecting a child
    $(".playbackBar .progress").css("width", progress + "%");
}

//plays the first song when pressing play button on artist page
function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

function updateVolumeProgressBar(audio){
    //formula for volume slider
    var volume = audio.volume * 100;
    //sets volume
    $(".volumeBar .progress").css("width", volume + "%");
}

//makes a Class in Javascript
function Audio() {
    //properties
    this.currentlyPlaying;
    //creates html5 audio element
    this.audio = document.createElement('audio');

    //ended is a audio event, goes to next song when song ends
    //if repeat is true then it puts the timer to 0
    this.audio.addEventListener("ended", function(){
        nextSong();
    });

    //canplay is an event from an audio element whenever the music is first played
    //updates the time remaining label to match the total duration from the audio object
    this.audio.addEventListener("canplay", function() {
        //this refers to the object that the event was called on, audio object
        var duration = formatTime(this.duration);
        $(".progressTime.remaining").text(duration);
    });

    //custom event listener
    this.audio.addEventListener("timeupdate", function () {
        if(this.duration) {
            //this refers to audio object
            updateTimeProgressBar(this);
        }
    });

    //volumechange is an event from audio element
    this.audio.addEventListener("volumechange", function(){
        //on volume change it calls the function
        updateVolumeProgressBar(this);
    });

    //method gets source of song
    //sets the currentlyPlaying var to the song that is playing via the track var from ajax function
    this.setTrack = function (track) {
        this.currentlyPlaying = track;
        this.audio.src = track.path;

    };

    this.play = function () {
        this.audio.play();
    };

    this.pause = function () {
        this.audio.pause();
    };

    //sets the currentTime via the timeFromOffset function result
    this.setTime = function(seconds){
        this.audio.currentTime = seconds;
    }
}

