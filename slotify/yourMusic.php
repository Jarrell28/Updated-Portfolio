<?php
include "includes/includedFiles.php";



?>


<div class="playlistsContainer">

    <div class="gridViewContainer">
        <h2>PLAYLIST</h2>

        <div class="buttonItems">
            <button class="button green" onclick="createPlaylist()">NEW PLAYLIST</button>
        </div>

        <?php

        //User class was created on includedFiles.php
        $username = $userLoggedIn->getUsername();
        $playlistQuery = mysqli_query($con, "SELECT * FROM playlists WHERE owner = '$username' ");

        if(mysqli_num_rows($playlistQuery) == 0) {
            echo "<span class='noResults'>You don't have any playlists yet</span>";
        }

        while($row = mysqli_fetch_array($playlistQuery)) {

            $playlist = new Playlist($con, $row );

            echo "<div class='gridViewItem' role='link' tabindex='0' onclick='openPage(\"playlist.php?id={$playlist->getId()}\")'>
    
                            <div class='playlistImage'>
                                <img src='assets/images/icons/playlist.png' alt=''>
                            </div>
                
                        <div class='gridViewInfo'>
                            {$playlist->getName()}
                            </div>

                    </div>";

        }

        ?>




    </div>

</div>
