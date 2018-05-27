<?php
// Creating the playlist
$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

// push playlist into an array
$resultArray = array();
while ($row = mysqli_fetch_array($songQuery)) {
  array_push($resultArray, $row['id']);
}

$jsonArray = json_encode($resultArray);
 ?>

<script>
// On page load, assign current playlist and create new Audio element

$(document).ready(function() {
  var newPlaylist = <?php echo $jsonArray; ?>;
  audioElement = new Audio;
  setTrack(newPlaylist[0], newPlaylist, false);
  audioElement.audio.volume = .25;
  updateVolumeProgressBar(audioElement.audio);

  $("#nowPlayingMiddle").on("mousedown touchstart mousemove touchmove", function(e) {
    e.preventDefault(); // Don't do your normal behavior (don't highlight on mouse down, which is what would normally happen)
  });
  $("#nowPlayingRight").on("mousedown touchstart mousemove touchmove", function(e) {
    e.preventDefault();
  });

  $(".playbackBar .progressBar").mousedown(function() {
    mouseDown = true;
  });

  $(".playbackBar .progressBar").mousemove(function(e) {
    if (mouseDown) {
      // set time of song depending on position of mouse
      timeFromOffset(e, this);
    }
  });

  $(".playbackBar .progressBar").mouseup(function(e) {
    timeFromOffset(e, this);
  });


  // volume bar sliding
  $(".volumeBar .progressBar").mousedown(function() {
    mouseDown = true;
  });

  $(".volumeBar .progressBar").mousemove(function(e) {
    if (mouseDown) {
      var percentage = e.offsetX / $(this).width();
      if (percentage >=0 && percentage <= 1) {
        // set volume slider depending on position of mouse
        audioElement.audio.volume = percentage;
      }
    }
  });

  $(".volumeBar .progressBar").mouseup(function(e) {
    var percentage = e.offsetX / $(this).width();
    if (percentage >=0 && percentage <= 1) {
      // set volume slider depending on position of mouse
      audioElement.audio.volume = percentage;
    }
  });

  $(document).mouseup(function() {
    mouseDown = false;
  });

});

function timeFromOffset(mouse, progressBar) {
  var percentage = (mouse.offsetX / $(progressBar).width()) * 100;
  var seconds = audioElement.audio.duration * (percentage / 100);
  audioElement.setTime(seconds);
};

function nextSong() {
  if (repeat === true) {
    audioElement.setTime(0);
    playSong();
    return;
  }
  if (currentIndex == currentPlaylist.length - 1) {
    currentIndex = 0;
  } else {
    currentIndex++;
  }

  var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
  setTrack(trackToPlay, currentPlaylist, true);
};

function previousSong() {
  if (audioElement.audio.currentTime >= 3 || currentIndex === 0) {
    audioElement.setTime(0);
  } else {
    currentIndex = currentIndex - 1;
    setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
  }
};

// swaps repeat to true or false. then if repeat is true, set image name to "repeat-active". set new icon path attribute
function setRepeat() {
  repeat = !repeat;
  var imageName = repeat ? "repeat-active.png" : "repeat.png";
  $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
};

function setMute() {
  // .muted returns a true or false of if the audio element is muted or not. swap This and then change behavior
  audioElement.audio.muted  = !audioElement.audio.muted;
  var imageName = audioElement.audio.muted ? "volume-muted.png" : "volume.png";
  $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
};

function setShuffle() {
  shuffle = !shuffle;
  var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
  $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

  if (shuffle === true) {
    // randomize playlist
    shuffleArray(shufflePlaylist);
    // when shuffle is turned on, set the index to whatever the currently playing song is in the shuffle array
    currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);

  } else {
    // shuffle has been deactivated, go back to regular playlist
    currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id)
  }
};

function shuffleArray(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
    return a;
};

function setTrack(trackId, newPlaylist, play) {
  // If they selected a new song that came with a new playlist, set the current playlist as new newPlaylist
  // create copy of new playlist and shuffle it
  if (newPlaylist != currentPlaylist) {
      currentPlaylist = newPlaylist;
      shufflePlaylist = currentPlaylist.slice();
      shuffleArray(shufflePlaylist);
  }

  if (shuffle === true) {
    currentIndex = shufflePlaylist.indexOf(trackId);
  } else {
    //takes the current index inside the array of the ID passed into the indexOf function
    currentIndex = currentPlaylist.indexOf(trackId);
  }
  pauseSong();

  $.post("includes/handlers/ajax/getSongJson.php", {songId: trackId}, function(data) { //URL, assign parameter, what we want to do with said parameter

    // assign track info to a JSON object (all song values)
    var track = JSON.parse(data);
    $(".trackName span").text(track.title);

    $.post("includes/handlers/ajax/getArtistJson.php", {artistId: track.artist}, function(data) {
      var artist = JSON.parse(data);
      $(".trackInfo .artistName span").text(artist.name);
      $(".trackInfo .artistName span").attr("onclick", "openPagePushState('artist.php?id=" + artist.id + "')");
    });

    $.post("includes/handlers/ajax/getAlbumJson.php", {albumId: track.album}, function(data) {
      var album = JSON.parse(data);
      $(".content .albumLink img").attr("src", album.artworkPath);
      $(".content .albumLink img").attr("onclick", "openPagePushState('album.php?id=" + album.id + "')");
      $(".trackInfo .trackName span").attr("onclick", "openPagePushState('album.php?id=" + album.id + "')");
    });

    audioElement.setTrack(track);

    if (play) {
      playSong();
    };
  });

};

function playSong() {
  // update play value for the song
  if (audioElement.audio.currentTime == 0) {
    $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
  }

  $(".controlButton.play").hide();
  $(".controlButton.pause").show();
  audioElement.play();
};

function pauseSong() {
  $(".controlButton.pause").hide();
  $(".controlButton.play").show();
  audioElement.pause();
}

</script>

<div id="nowPlayingBarContainer">
  <div id="nowPlayingBar">
    <div id="nowPlayingLeft">
      <div class="content">
        <span class="albumLink">
          <img role="link" tabindex="0" src="" class="albumArtwork" alt="Album Artwork">
        </span>

        <div class="trackInfo">
          <span class="trackName">
            <span role="link" tabindex="0"></span>
          </span>

          <span class="artistName">
            <span role="link" tabindex="0"></span>
          </span>

        </div>


      </div>
    </div>

    <div id="nowPlayingMiddle">
      <div class="content playerControls">
        <div class="buttons">
          <button class="controlButton shuffle" title="Shuffle Button" onclick="setShuffle()">
            <img src="assets/images/icons/shuffle.png" alt="shuffle">
          </button>
          <button class="controlButton previous" title="Previous Button" onclick="previousSong()">
            <img src="assets/images/icons/previous.png" alt="previous">
          </button>
          <button class="controlButton play" title="Play Button" onclick="playSong()">
            <img src="assets/images/icons/play.png" alt="play">
          </button>
          <button class="controlButton pause" title="Pause Button" style="display: none;" onclick="pauseSong()">
            <img src="assets/images/icons/pause.png" alt="pause">
          </button>
          <button class="controlButton next" title="Next Button" onclick="nextSong()">
            <img src="assets/images/icons/next.png" alt="next">
          </button>
          <button class="controlButton repeat" title="Repeat Button" onclick="setRepeat()">
            <img src="assets/images/icons/repeat.png" alt="repeat">
          </button>
        </div>

        <div class="playbackBar">
          <span class="progressTime current">0.00</span>
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
        <button class="controlButton volume" title="Volume Button" onclick="setMute()">
          <img src="assets/images/icons/volume.png" alt="volume">
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
