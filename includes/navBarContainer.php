<div id="navBarContainer">
  <nav class="navBar">
    <span role="link" tabindex="0" onclick="openPagePushState('index.php')" class="logo" >
      <img src="assets/images/icons/home-pink.png" alt="">
    </span>

    <div class="group">
      <div class="navItem">
        <span role="link" tabindex="0" onclick="openPagePushState('search.php')" class="navItemLink">Search
          <img src="assets/images/icons/search.png" class="icon" alt="Search">
        </span>
      </div>
    </div>
    <div class="group">
      <div class="navItem">
        <span role="link" tabindex="0" onclick="openPagePushState('browse.php')" class="navItemLink">Browse</span>
      </div>
      <div class="navItem">
        <span role="link" tabindex="0" onclick="openPagePushState('yourMusic.php')" class="navItemLink">Your Music</span>
      </div>
      <div class="navItem">
        <span role="link" tabindex="0" onclick="openPagePushState('settings.php')" class="navItemLink"><?php echo $userLoggedIn->getUsername(); ?></span>
      </div>
    </div>

  </nav>
</div>
