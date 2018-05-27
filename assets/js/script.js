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


$(document).click(function(click) {
  var target = $(click.target);

  // If what you click on doesnt have the class Item and it doesn have the class optionsMenu, hide the menu
  if (!target.hasClass("item") && !target.hasClass("optionsButton")) {
    hideOptionsMenu();
  }
});



// for menu options on songs
$(window).scroll(function() {
  hideOptionsMenu();
});

// select tag with playlist class (in album.php). When it is changed, do the function
$(document).on("change", "select.playlist", function() {
  var select = $(this);
  var playlistId = select.val();
  var songId = select.prev(".songId").val(); //prev takes immediate ancestor

  $.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId}).done(function(error) {

    if (error != "") {
      alert(error);
      return;
    }

    hideOptionsMenu();
    select.val("");
  });
});


// Update Email Function for updatedetails.php
function updateEmail(emailClass) {
  var emailValue = $("." + emailClass).val();

  $.post("includes/handlers/ajax/updateEmail.php", {email: emailValue, username: userLoggedIn}).done(function(response) {
    $("." + emailClass).nextAll(".message").text(response);
  });
};



//Update Password function for updatedetails.php
function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
  var oldPassword = $("." + oldPasswordClass).val();
  var newPassword1 = $("." + newPasswordClass1).val();
  var newPassword2 = $("." + newPasswordClass2).val();

  $.post("includes/handlers/ajax/updatePassword.php",
  {oldPassword: oldPassword, newPassword1: newPassword1, newPassword2: newPassword2, username: userLoggedIn}).done(function(response) {
    $("." + oldPasswordClass).nextAll(".message").text(response);
  });
};




function logout() {
  $.post("includes/handlers/ajax/logout.php", function() {
    location.reload();
  });
};

function openPage(url) {

  if (timer != null) {
    clearTimeout(timer);
  }

  if(url.indexOf("?") === -1) {
    url = url + "?";
  }
  //built in encode to encode the URI and replaces them with their URL equivalent
  var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
  $("#mainContent").load(encodedUrl); //creates jquery object around mainContent block that you can then manipulate

  $("body").scrollTop(0);

  //history.pushState(null, null, url);
};

window.addEventListener("popstate", function() {
  var url = location.href;
  openPage(url);
});

function openPagePushState(url) {
  openPage(url);
  history.pushState(null, null, url);
};


function removeFromPlaylist(button, playlistId) {
  var songId = $(button).prevAll(".songId").val();

  $.post("includes/handlers/ajax/removeFromPlaylist.php", {playlistId: playlistId, songId: songId}).done(function(error) {
    if (error != "") {
      alert(error);
      return;
    }
    // do somethin when ajax returns
    openPagePushState("playlist.php?id=" + playlistId);
  });
};



function createPlaylist() {
  var popup = prompt("Please enter the name of your playlist.");
  if (popup != null) {

    $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn}).done(function(error) {
      if (error != "") {
        alert(error);
        return;
      }
      // do somethin when ajax returns
      openPagePushState("yourMusic.php");
    });

  }
};



function hideOptionsMenu() {
  var menu = $(".optionsMenu");
  if (menu.css("display") != "none") { // If display is showing
    menu.css("display", "none");
  }
};




// function to show the options menu when the ellipsis on a song is clicked
function showOptionsMenu(button) {
  var songId = $(button).prevAll(".songId").val();

  var menu = $(".optionsMenu");
  var menuWidth = menu.width();
  menu.find(".songId").val(songId);

  var scrollTop = $(window).scrollTop(); // distance from top of window to top of document
  var elementOffset = $(button).offset().top; // distance from top of document

  var top = elementOffset - scrollTop; // distance from the button to top of document - distance of current scroll view to the top of the top
  var left = $(button).position().left;

  menu.css({"top": top + "px", "left": left - menuWidth + "px", "display": "inline"});
};




function deletePlaylist(playlistId) {
  var prompt = confirm("Are you sure you want to delete this playlist?");

  if (prompt === true) {
    $.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId}).done(function(error) {
      if (error != "") {
        alert(error);
        return;
      }
      // do somethin when ajax returns
      openPagePushState("yourMusic.php");
    });
  }
};




function formatTime(seconds) {
  var time = Math.round(seconds);
  var minutes = Math.floor(time / 60);
  var seconds = time - minutes * 60;

  var extraZero = (seconds < 10) ? "0" : "";

  return minutes + ":" + extraZero + seconds;
};




function updateTimeProgressBar(audio) {
  $(".progressTime.current").text(formatTime(audio.currentTime));
  $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

  var progress = (audio.currentTime / audio.duration) * 100;
  $(".playbackBar .progress").css("width", progress + "%");
};




function updateVolumeProgressBar(audio) {
  var volume = audio.volume * 100;
  $(".volumeBar .progress").css("width", volume + "%");
};




function Audio() {
  this.currentlyPlaying;
  this.audio = document.createElement('audio');

  this.audio.addEventListener("ended", function() {
    nextSong();
  });

  this.audio.addEventListener("canplay", function () {  //audio event has a "canplay" event built in
    // 'this' refers to the object that the event was called on. In this case the audio object
    var duration = formatTime(this.duration);
    $(".progressTime.remaining").text(duration);
  });

  // Update time left in song
  this.audio.addEventListener('timeupdate', function() {
    if (this.duration) {
      updateTimeProgressBar(this);
    }
  });

  this.audio.addEventListener("volumechange", function() {
    updateVolumeProgressBar(this);
  });

  // sets the current track
  this.setTrack = function(track) {
    this.currentlyPlaying = track;
    this.audio.src = track.path;
  };

  this.play = function() {
    this.audio.play();
  };

  this.pause = function() {
    this.audio.pause();
  };

  this.setTime = function(seconds) {
    this.audio.currentTime = seconds;
  };
};




function playFirstSong() {
  setTrack(tempPlaylist[0], tempPlaylist, true);
};
