# PHP Echo Nest API

A simple, Object Oriented API wrapper for the EchoNest Api written with PHP5.

Uses [EchoNest API v4](http://developer.echonest.com/docs/v4/).

Requires [php curl](http://php.net/manual/en/book.curl.php).

If the method you need does not exist yet, dont hesitate to request it with an [issue](http://github.com/bshaffer/php-echonest-api/issues)!

## Instanciate a new API

    $echonest = new EchoNestApi();

### Authenticate a user

Authenticate using your EchoNest API Key.  You can obtain one at EchoNest by [Registering an Account](http://developer.echonest.com/account/register)

    $echonest->authenticate($apiKey);

### Deauthenticate a user

Cancels authentication.

    $echonest->deAuthenticate();

Next requests will not be authenticated

## Arists

Searching artists, getting artist information and music
Wrap [EchoNest Artist API](http://developer.echonest.com/docs/v4/artist.html).

    $artistApi = $echonest->getArtistApi();

### Search for artists by name

    $results = $echonest->getArtistApi()->search(array('name' => 'Radiohead'));
    print_r($results);
    
      Array
      (
          [status] => Array
              (
                  [version] => 4.2
                  [code] => 0
                  [message] => Success
              )

          [artists] => Array
              (
                  [0] => Array
                      (
                          [name] => Radiohead
                          [id] => ARH6W4X1187B99274F
                      )

              )

      )

Returns an array of results as described in [http://developer.echonest.com/docs/v4/artist.html#search](http://developer.echonest.com/docs/v4/artist.html#search)

### Get information about an artist

    $bios = $echonest->getArtistApi()->setName('Radiohead')->getBiographies();

Once you set an artists name or id on an artist API, the API will remember that artist and use them for future function calls

    $artistApi = $echonest->getArtistApi();
    $artistApi->setName('Radiohead');
    $bios   = $artistApi->getBiographies();
    $audio  = $artistApi->getAudio();
    $images = $artistApi->getImages();

Each function comes with a variety of options.  Please view the documentation in this project or on http://echonest.com to see all the options available

## Songs

Api calls for getting data about songs.
Wraps [EchoNest Song API](http://developer.echonest.com/docs/v4/song.html).

    $songApi = $echonest->getSongApi();
    
Please view the documentation in this project or on http://echonest.com to see all the options available
  
## Playlists

Api calls for generating playlists
Wraps [EchoNest Playlist API](http://developer.echonest.com/docs/v4/playlist.html).

    $playlistApi = $echonest->getPlaylistApi();
    
Please view the documentation in this project or on http://echonest.com to see all the options available

## Catalogs

API calls for managing personal catalogs
Wraps [EchoNest Catalog API](http://developer.echonest.com/docs/v4/catalog.html).

    $catalogApi = $echonest->getCatalogApi();
    
Please view the documentation in this project or on http://echonest.com to see all the options available

## Tracks

Methods for analyzing or getting info about tracks.
Wraps [EchoNest Track API](http://developer.echonest.com/docs/v4/track.html).

    $trackApi = $echonest->getTrackApi();
    
Please view the documentation in this project or on http://echonest.com to see all the options available

# To Do

Better documentation and test coverage will be coming soon