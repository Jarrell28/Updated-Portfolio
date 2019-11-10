<?php
include "includes/includedFiles.php";

if(isset($_GET['term'])){
    //gets rid of %s and other unwanted characters in url
    $term = urldecode($_GET['term']);



} else {
    $term = "";
}

?>

<div class="searchContainer">
    <h4>Search for an artist, album or song</h4>
<!--    sets focus of input to the end of the word-->
    <input type="text" class="searchInput" value="<?php echo $term;?>" placeholder="Start typing..." onfocus="var val=this.value; this.value=''; this.value= val;">
</div>

<script>
    //focuses input on reload
    $(".searchInput").focus();
//function to reload page while user is searching
    $(function(){

        //when you type, this function cancels out the timer and resets it
        $(".searchInput").keyup(function(){
            clearTimeout(timer);

            //after 2 seconds of not typing execute code under function
            timer = setTimeout(function(){
                //gets value of the search input
                var val =$(".searchInput").val();
                //sets url to input value after 2 seconds of not typing
                openPage("search.php?term=" + val);
            }, 2000);
        })
    })
</script>

<?php if($term == "") exit(); ?>

<div class="trackListContainer borderBottom">
    <h2>SONGS</h2>
    <ul class="tracklist">

        <?php

        //selects all songs where title is like $term for search bar
        //% after means anything after $term
        //%before means anything before $term
        $songsQuery = mysqli_query($con, "SELECT * FROM songs WHERE title LIKE '$term%' LIMIT 10");

        if(mysqli_num_rows($songsQuery) == 0) {
            echo "<span class='noResults'>No songs found matching " .$term . "</span>";
        }

        $songIdArray = array();

        $i = 1;
        while ($row = mysqli_fetch_array($songsQuery)){

            //limits songs showing to 15 on search page
            if($i > 15) {
                break;
            }

            array_push($songIdArray, $row['id']);
            $albumSong = new Song($con, $row['id']);
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


<div class="artistsContainer borderBottom">
    <h2>ARTIST</h2>
    <?php

    //dont forget %
    $artistQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

    if(mysqli_num_rows($artistQuery) == 0) {
        echo "<span class='noResults'>No artists found matching " .$term . "</span>";
    }

    while($row = mysqli_fetch_array($artistQuery)){
        $artistFound = new Artist($con , $row['id']);

        echo "<div class='searchResultRow'>
                    <div class='artistName'>
                    
                        <span role='link' tabindex='0' onclick='openPage(\"artist.php?id={$artistFound->getId()}\")'>{$artistFound->getName()}</span>
                    </div>
                   </div>";

    }

    ?>
</div>

<div class="gridViewContainer">
    <h2>ALBUMS</h2>

    <?php

    $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");

    if(mysqli_num_rows($albumQuery) == 0) {
        echo "<span class='noResults'>No albums found matching " .$term . "</span>";
    }

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

</div>

<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con,$userLoggedIn->getUsername());?>
</nav>
