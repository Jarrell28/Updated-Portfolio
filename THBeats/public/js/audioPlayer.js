// //Audio player
const audio = new Audio();
let i = 0;
audio.src = songArray[i];

//next button
document.querySelector(".fa-step-forward").addEventListener("click", () => {
    i === songArray.length - 1 ? i = 0 : i++;
    audio.src = songArray[i];
    audio.play();
});

//prev button
document.querySelector(".fa-step-backward").addEventListener("click", () => {
    if(i === 0){
        i = songArray.length - 1;
    } else {
        i--;
    }
    audio.src = songArray[i];
    audio.play();
});

//play button
document.querySelector("#play").addEventListener("click", () => {
    audio.play();
    document.querySelector("#play").style.display = 'none';
    document.querySelector("#pause").style.display = 'block';
});

//When audio paused
document.querySelector(".fa-pause-circle").addEventListener("click", () => {
    audio.pause();
    document.querySelector("#play").style.display = 'block';
    document.querySelector("#pause").style.display = 'none';

    //currently playing
    const currentSong = audio.src.replace(/%20/g, " ");

    document.querySelectorAll("tbody tr").forEach(tr => {
        if (currentSong.includes(tr.dataset.song)) {
            tr.querySelector("i").classList.add("fa-play-circle");
            tr.querySelector("i").classList.remove("fa-pause-circle");
        }
    })
});

//When audio plays
audio.addEventListener("playing", () => {
    document.querySelector("#play").style.display = 'none';
    document.querySelector("#pause").style.display = 'block';

    //currently playing
    const currentSong = audio.src.replace(/%20/g, " ");

    document.querySelectorAll("tbody tr").forEach(tr => {
        if(currentSong.includes(tr.dataset.song)){
            tr.style.color ="#dc4b4b";
            tr.querySelector("i").classList.remove("fa-play-circle");
            tr.querySelector("i").classList.add("fa-pause-circle");

            //update now playing section
            document.querySelector("#now-playing").innerHTML = tr.dataset.song;
        }
    })
})

//play selected song
document.querySelector("tbody").addEventListener("click", e => {
    if(e.target.closest(".fa-play-circle")){
        //currently playing
        const currentSong = audio.src;
        const song = e.target.parentNode.parentNode.dataset.song;

        //check if song already playing
        if(currentSong.includes(song)){
            audio.play();
            return;
        }

        const index = songArray.findIndex(i => {
            if(i.includes(song)){
                return i;
            }
        });
        audio.src= songArray[index];
        i = index;
        audio.play();

        //change color of active song
        e.target.parentNode.parentNode.style.color = "#dc4b4b";

        //change play button on song
        e.target.classList.remove("fa-play-circle");
        e.target.classList.add("fa-pause-circle");

    } else if (e.target.closest(".fa-pause-circle")){
        //change pause button and pause song
        e.target.classList.add("fa-play-circle");
        e.target.classList.remove("fa-pause-circle");
        audio.pause();
    }

    return;
});

//When changing songs reset currently playing styles
audio.addEventListener("abort", () => {
    document.querySelectorAll("tbody tr").forEach(tr => {
        tr.style.color = "#fff";
        tr.querySelector("i").classList.remove("fa-pause-circle");
        tr.querySelector("i").classList.add("fa-play-circle");
    })
});

//When audio ends
audio.addEventListener("ended", () => {
    document.querySelectorAll("tbody tr").forEach(tr => {
        tr.querySelector("i").classList.remove("fa-pause-circle");
        tr.querySelector("i").classList.add("fa-play-circle");
    });

    document.querySelector("#pause").style.display = "none";
    document.querySelector("#play").style.display = "block";
});

//progress bar
function updateTimeProgressBar(audio) {
    //makes the current time update with the currentTime object from audio
    document.querySelector(".start-time").innerHTML = formatTime(audio.currentTime);
    //makes the time remaining on song subtract while the currentTime is playing
    document.querySelector(".end-time").innerHTML = formatTime(audio.duration);

    //calculates percentage and updates the progress bar
    var progress = audio.currentTime / audio.duration * 100;
    //put a space in between classes to indicate selecting a child
    document.querySelector(".progress1").style.width = progress + "%";
}

//convert song duration into a better time format
function formatTime(seconds) {
    var time = Math.round(seconds); //rounds up
    var minutes = Math.floor(time / 60); //rounds down
    var seconds = time - (minutes * 60); //gets the seconds left over after minutes
    var extraZero = seconds < 10 ? "0" : "";

    return minutes + ":" + extraZero + seconds;
}

//update progress bar
audio.addEventListener("timeupdate", () => {
    if(audio.duration) {
        updateTimeProgressBar(audio);
    }
});

audio.addEventListener("canplay", () => {
    updateTimeProgressBar(audio);
    updateVolume();
});

//update time when clicking on progress bar
document.querySelector(".progress-bar1").addEventListener("click", e => {
    const width = document.querySelector(".progress-bar1").offsetWidth;
    const percent = e.offsetX / width * 100;
    audio.currentTime = audio.duration * percent / 100;
    // audio.play();
});


//volume bar
function updateVolume() {
    const volumeProgress = document.querySelector(".volume1");
    const volume = audio.volume * 100;
    volumeProgress.style.width = volume + "%";
}

//update volume on click
document.querySelector(".volume-bar1").addEventListener("click", e => {
    const width = document.querySelector(".volume-bar1").offsetWidth;
    const percent = e.offsetX / width * 100;
    audio.volume = percent / 100;
    document.querySelector(".volume1").style.width = audio.volume * 100 + "%";
});