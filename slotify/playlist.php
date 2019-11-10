<?php include "includes/includedFiles.php"?>


<?php

if(isset($_GET['id'])){
    $playlistId = $_GET['id'];

} else {
    header("Location: index.php");
}

$playlist = new Playlist($con, $playlistId);
$owner = new User($con, $playlist->getOwner());
?>

<div class="entityInfo">
    <div class="leftSection">
        <div class="playlistImage">
            <img src="assets/images/icons/playlist.png" alt="">
        </div>

    </div>

    <div class="rightSection">
        <h2><?php echo $playlist->getName()?></h2>
        <p>By <?php echo $playlist->getOwner();?></p>
        <p> <?php echo $playlist->getNumberOfSongs();?> songs</p>
        <button class="button" onclick="deletePlaylist('<?php echo $playlistId;?>')">DELETE PLAYLIST</button>
    </div>
</div>

<div class="trackListContainer">
    <ul class="tracklist">

        <?php
        $songIdArray = $playlist->getSongIds();

        $i = 1;
        foreach ($songIdArray as $songId){

            $playlistSong = new Song($con, $songId);
            $songArtist = $playlistSong->getArtist();

            echo "<li class='tracklistRow'>

                                <div class='trackCount'>
                                <!--make playlistSong->getId() a string by using \"to avoid errors\"
                                when converted into json then parsed, the id comes out as a string-->
                                    <img src='assets/images/icons/play-white.png' alt='Play' class='play' onclick ='setTrack(\"{$playlistSong->getId()}\", tempPlaylist, true)'>
                                    <span class='trackNumber'>$i</span>                        
                                </div>
                                
                                <div class='trackInfo'>
                                    <span class='trackName'>{$playlistSong->getTitle()}</span>
                                    <span class='artistName'>{$songArtist->getName()}</span>
                                 </div>

                                  <div class='trackOptions'>
                               <!--gets song id to know which song to add to playlist-->
                                  <input type='hidden' class='songId' value='{$playlistSong->getId()}'>
                                
                                    <img src='assets/images/icons/more.png' alt='more options' class='optionsButton' onclick='showOptionsMenu(this)'>
                                  </div>
                                  
                                  <div class='trackDuration'>
                                    <span class='duration'>{$playlistSong->getDuration()}</span>
                                  </div>


                            </li>";

            $i+=1;

        }

        ?>

        <script>
            //puts all the songs displayed on current page into tempSongIds array
            var tempSongIds = '<?php echo json_encode($songIdArray);?>';
            //parses the songs from JSON to javascript code and sets it to tempPlaylist array
            tempPlaylist = JSON.parse(tempSongIds);
        </script>

    </ul>
</div>

<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con,$userLoggedIn->getUsername());?>
    <div class="item" onclick="removeFromPlaylist(this, '<?php echo $playlistId?>')">Remove from Playlist</div>
</nav>

