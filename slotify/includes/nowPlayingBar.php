<?php

//Selects 10 random song playlist
$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

$resultArray = array();
while($row = mysqli_fetch_array($songQuery)){
    array_push($resultArray, $row['id']);
}

//convert php array into javascript array via JSON
$jsonArray = json_encode($resultArray);
?>


<script>
    //ajax is a way of executing php without the page needing to reload
    //cannot call php from javascript. have to use ajax
    //php is executed as soon as the page loads, then javascript is executed

    //getting playlist array from json encode function
    $(document).ready(function () {
        var newPlaylist = <?php echo $jsonArray; ?>;
        audioElement = new Audio();
        //sets song id , playlist, and sets playing to false to remember songs
        //and not play on load so if a song is already playing it wont interrupt
        setTrack(newPlaylist[0], newPlaylist, false);
        //sets volume to full width on page load
        updateVolumeProgressBar(audioElement.audio);

        //on any of these events is calls the function
        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e){
            //prevents default behavior on these events
            e.preventDefault();

        });

        //mousedown is a predefined function
        //changes song duration on click
        $(".playbackBar .progressBar").mousedown(function() {
            mouseDown = true;
        });

        //e puts in the mouseclick object, look up e for more info
        $(".playbackBar .progressBar").mousemove(function(e) {
           if(mouseDown){
               //Set time of song, depending on position of mouse
               //refers to progressBar
               timeFromOffset(e, this);
           }
        });

        $(".playbackBar .progressBar").mouseup(function(e) {
            timeFromOffset(e, this);
        });

        //mousedown is a predefined function
        //changes volume on click
        $(".volumeBar .progressBar").mousedown(function() {
            mouseDown = true;
        });

        //e puts in the mouseclick object, look up e for more info
        $(".volumeBar .progressBar").mousemove(function(e) {
            if(mouseDown){
                //sets volume according to mouse position on progressBar
                var percentage = e.offsetX / $(this).width();
                //sets range to prevent errors of percentage being out of range
                if(percentage >=0 && percentage <= 1){
                    //sets audio volume to percentage
                    audioElement.audio.volume = percentage;
                }
            }
        });

        $(".volumeBar .progressBar").mouseup(function(e) {
            //sets volume according to mouse position on progressBar
            var percentage = e.offsetX / $(this).width();
            //sets range to prevent errors of percentage being out of range
            if (percentage >= 0 && percentage <= 1) {
                //sets audio volume to percentage
                audioElement.audio.volume = percentage;
            }
        });

        //have to set it apart from mouseup function so you can let go of mouse anywhere
        $(document).mouseup(function(){
            mouseDown = false;
        });


    });

    //gets the time of song from the offset of the mouse
    function timeFromOffset(mouse, progressBar) {
        //refers to which ever item is clicked
        //width() returns the width of the element clicked
        var percentage = mouse.offsetX / $(progressBar).width() * 100;
        //formula to get the seconds left depending on where the bar was clicked
        var seconds = audioElement.audio.duration * (percentage / 100);
        audioElement.setTime(seconds);


    }

    function prevSong() {
        //if the time of song is more than 3 seconds or the song is the first song
        //it sets the song to replay again/ time to 0
        if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
            audioElement.setTime(0);

        } else {
            //plays previous song
            currentIndex--;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
        }

    }

    function nextSong() {

        //repeat button login
        if(repeat == true) {
            //sets the time of song back to 0 and plays it
            audioElement.setTime(0);
            playSong();
            return;
        }

        //if the current song playings index is equal to the  last song index set it back
        //to 0(first song) when pressing next
        //index starts at 0, length makes counts 10, -1 to relate it to last index of playlist
        if(currentIndex == currentPlaylist.length - 1) {
            currentIndex = 0;
        } else {
            //adds 1 to index/plays next song
            currentIndex++;

        }
        //makes it play the updated currentIndex/song
        //if shuffle is try changes track to shuffled playlist index
        var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
        setTrack(trackToPlay, currentPlaylist, true);

    }


    function setRepeat() {
        //toggles between true and false, toggle logic
        repeat = !repeat;

        //repeat image conditional  statement
        var imageName = repeat ? "repeat-active.png" : "repeat.png";
        //changes image on click
        $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
    }

    function setMute() {
        //toggles mute
        audioElement.audio.muted = !audioElement.audio.muted;
        //mute image conditional  statement
        var imageName = audioElement.audio.muted  ? "volume-mute.png" : "volume.png";
        //changes image on click
        $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
    }

    function setShuffle() {
        //toggles shuffle
        shuffle = !shuffle;
        //mute image conditional  statement
        var imageName = shuffle  ? "shuffle-active.png" : "shuffle.png";
        //changes image on click
        $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

        if(shuffle) {
            //randomize playlist
            shuffleArray(shufflePlaylist);
            //sets the index of current playing to the same index of the shuffled playlist
            //to prevent the same song from being played
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        } else {
            //go back to regular playlist
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
        }
    }

    function shuffleArray(a) {
        //?? grabbed from online to shuffle
        var j, x, i;
        for (i=a.length; i; i--) {
            j = Math.floor(Math.random() * i);
            x = a[i - 1];
            a[i - 1] = a[j];
            a[j] = x;
        }
    }

    //plays the id of song recieved via ajax
    function setTrack(trackId, newPlaylist, play) {

        //if its a different playlist, currentPlaylist becomes that newPlaylist;
        if(newPlaylist != currentPlaylist) {
            currentPlaylist = newPlaylist;
            //returns a copy of the currentPlaylist
            shufflePlaylist = currentPlaylist.slice();
            //by shuffling this copy it does not affect the currentPlaylist
            shuffleArray(shufflePlaylist);
        }

        if(shuffle==true){
            currentIndex = shufflePlaylist.indexOf(trackId);
        }else {
            //gets the index of the current song playing in the playlist
            currentIndex = currentPlaylist.indexOf(trackId);
        }
        pauseSong();
        //uses ajax to get trackId from database
        //this is an ajax call
        //second parameter is the data you want to send
        //songId is the key, trackId is the value
        //the function(data) returns the result of the functions in the url given
        $.post("includes/handlers/ajax/getSongJson.php",{songId: trackId}, function (data) {

            //changes the encoded json from php and parses it so javascript can understand
            var track = JSON.parse(data);

            //track becomes array, it relates to the columns in database
            //track.title, title is a column in database
            $(".trackName span").text(track.title);


            //another ajax function to get artist name from the artists database
            //value has to match the column name in the song database used by track variable
            $.post("includes/handlers/ajax/getArtistJson.php",{artistId: track.artist}, function (data) {
                var artist = JSON.parse(data);
                $(".trackInfo .artistName span").text(artist.name);
                $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id="+ artist.id + "')");
            });

            $.post("includes/handlers/ajax/getAlbumJson.php",{albumId: track.album}, function (data) {
                var album = JSON.parse(data);
                //updates img src attribute
                $(".content .albumLink img").attr("src", album.artworkPath);
                $(".content .albumLink img").attr("onclick", "openPage('album.php?id="+ album.id + "')");
                $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id="+ album.id + "')");
            });

            //gets the song table data from the database
            audioElement.setTrack(track);

            if(play) {
                playSong();
            }

        });


    }

    //create function outside to link it to html onclick attribute
    function playSong() {
        //update play count only when current time of song is 0
        if(audioElement.audio.currentTime == 0){
            //gets the id of the currentlyPlaying song from the database via function from setTrack
            //and updates the plays by 1
            $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
        }
        $(".controlButton.play").hide();
        $(".controlButton.pause").show();
        audioElement.play();
    }

    function pauseSong() {
        $(".controlButton.play").show();
        $(".controlButton.pause").hide();
        audioElement.pause();
    }

</script>


<div id="nowPlayingBarContainer">

    <div id="nowPlayingBar">
        <div id="nowPlayingLeft">

            <div class="content">

            <span class="albumLink">
                <img role="link" tabindex="0" src="" alt="" class="albumArtwork">
            </span>

                <div class="trackInfo">
                <span class="trackName">
                    <span role="link" tabindex="0"></span>
                </span>

                    <span class="artistName">
                    <span role="link" tabindex="0" ></span>
                </span>
                </div>

            </div>

        </div>

        <div id="nowPlayingCenter">

            <div class="content playerControls">

                <div class="buttons">

                    <button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="shuffle">
                    </button>

                    <button class="controlButton previous" title="Previous button" onclick="prevSong()">
                        <img src="assets/images/icons/previous.png" alt="previous">
                    </button>

                    <button class="controlButton play" title="Play button" onclick="playSong()">
                        <img src="assets/images/icons/play.png" alt="play">
                    </button>

                    <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                        <img src="assets/images/icons/pause.png" alt="pause">
                    </button>

                    <button class="controlButton next" title="Next button" onclick="nextSong()">
                        <img src="assets/images/icons/next.png" alt="next">
                    </button>

                    <button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
                        <img src="assets/images/icons/repeat.png" alt="repeat">
                    </button>

                </div>

                <div class="playbackBar">

                    <span class="progressTime current">0:00</span>
                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>

                    </div>
                    <span class="progressTime remaining">0.00</span>

                </div>

            </div>

        </div>

        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="Volume button" onclick="setMute()">
                    <img src="assets/images/icons/volume.png" alt="Volume">
                </button>
                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress"></div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>