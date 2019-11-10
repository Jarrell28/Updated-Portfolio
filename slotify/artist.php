<?php include "includes/includedFiles.php";

    if(isset($_GET['id'])){
    $artistId = $_GET['id'];

    } else {
    header("Location: index.php");

    }

    $artist = new Artist($con, $artistId);

?>

<div class="entityInfo borderBottom">

    <div class="centerSection">
        <div class="artistInfo">
            <h1 class="artistName">
                <?php echo $artist->getName()?>
            </h1>

            <div class="headerButtons">
                <button class="button green">PLAY</button>
            </div>
        </div>
    </div>

</div>

<div class="trackListContainer borderBottom">
    <ul class="tracklist">

        <?php
        $songIdArray = $artist->getSongIds();

        $i = 1;
        foreach ($songIdArray as $songId){

            //limits songs showing to 5 on the artist page
            if($i > 5) {
                break;
            }

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


<div class="gridViewContainer">

    <?php

    $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE artist='$artistId'");

    while($row = mysqli_fetch_array($albumQuery)) {

        echo "<div class='gridViewItem'>
                        <span  role = 'link' tabindex='0' onclick='openPage(\"album.php?id={$row['id']}\")' class='navItemLink'>
                            <img src='{$row["artworkPath"]}'>
    
                            <div class='gridViewInfo'>
                            {$row['title']}
                            </div>
                       </span>

                    </div>";

    }



    ?>

    <nav class="optionsMenu">
        <input type="hidden" class="songId">
        <?php echo Playlist::getPlaylistsDropdown($con,$userLoggedIn->getUsername());?>
    </nav>
