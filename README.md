# Music Streaming Site

A music streaming website custom built from the ground up with HTML, CSS, PHP, JavaScript, and Ajax. Utilization of MySql database to store values.

Account creation with values called inside pages

## You can see a current Demo of the project Here: http://danielcmorris.com/music-streaming-project 


# Steps

## Register Page:
Registration page that can swap between creating a new account or logging into an existing account. Checks are in place for duplicate account names/emails and an md5 encryption of passwords.

![alt text](http://danielcmorris.com/music-streaming-project/assets/images/page-register.png)


## Browse Page:
A simple page that calls the songs from the database and displays their artwork, title, and artist.

![alt text](http://danielcmorris.com/music-streaming-project/assets/images/page-browse.png)

## Album Page:
A page that contains all songs for the selected album. Songs are able to be added to a created playlist, played, or paused. Any song played will persist through navigation.

![alt text](http://danielcmorris.com/music-streaming-project/assets/images/page-album.png)

## Persistent header/Play Bar:
Inside content changes while play bar stays consistent and continues to play the current song / hold the current playlist. Inside content is loaded dynamically via an encoded URI while URLs are rewritten via popstate events to make the user experience seem like they are navigating through pages.
Play bar includes play/pause buttons, back/forward buttons, volume slider (with mute functionality), bar depicting percentage of the current song completed, a "shuffle" feature which randomizes the current playlist, and a "repeat" feature that will repeat the current song instead of playing the next song in the playlist.

## "Your Music" Page:
Page where you can create custom playlists and then select the options menu of any song to add it to any created playlist.

## Playlist Page:
Page containing any songs that you have added to the playlist with an additional option to completely delete the playlist itself.

![alt text](http://danielcmorris.com/music-streaming-project/assets/images/page-playlist.png)

## Profile Page:
Page containing 2 buttons (currently) which the user can select to edit their account details or log out of the website.

## User Detail Page:
Change your account email and/or password.

![alt text](http://danielcmorris.com/music-streaming-project/assets/images/page-details.png)

## Search Page:
A page where you can type any phrase into the search bar and search results for any songs, artists, or albums matching that phrase will be returned within 1 second of releasing the last character typed automatically.

![alt text](http://danielcmorris.com/music-streaming-project/assets/images/page-search.png)
