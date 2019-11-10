<?php include "includes/includedFiles.php"?>


<?php

    if(isset($_GET['id'])){
        $albumId = $_GET['id'];

    } else {
        header("Location: index.php");
    }

        $album = new Album($con, $albumId);

        $artist = $album->getArtist();
?>

    <div class="entityInfo">
        <div class="leftSection">
            <img src="<?php echo $album->getArtworkPath();?>" alt="">
        </div>
        
        <div class="rightSection">
            <h2><?php echo $album->getTitle()?></h2>
            <p>By <?php echo $artist->getName();?></p>
            <p> <?php echo $album->getNumberOfSongs();?> songs</p>
        </div>
    </div>

    <div class="trackListContainer">
        <ul class="tracklist">

            <?php
            $songIdArray = $album->getSongIds();

            $i = 1;
            foreach ($songIdArray as $songId){

                $albumSong = new Song($con, $songId);
                $albumArtist = $albumSong->getArtist();

                echo "<li class='tracklistRow'>

                                <div class='trackCount'>
                                <!--make albumSong->getId() a string by using \"to avoid errors\"
                                when converted into json then parsed, the id comes out as a string-->
                                    <img src='assets/images/icons/play-white.png' alt='Play' class='play' onclick ='setTrack(\"{$albumSong->getId()}\", tempPlaylist, true)'>
                                    <span class='trackNumber'>$i</span>                        
                                </div>
                                
                                <div class='trackInfo'>
                                    <span class='trackName'>{$albumSong->getTitle()}</span>
                                    <span class='artistName'>{$albumArtist->getName()}</span>
                                 </div>

                                  <div class='trackOptions'>
                               <!--gets song id to know which song to add to playlist-->
                                  <input type='hidden' class='songId' value='{$albumSong->getId()}'>
                                
                                    <img src='assets/images/icons/more.png' alt='more options' class='optionsButton' onclick='showOptionsMenu(this)'>
                                  </div>
                                  
                                  <div class='trackDuration'>
                                    <span class='duration'>{$albumSong->getDuration()}</span>
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
    </nav>

