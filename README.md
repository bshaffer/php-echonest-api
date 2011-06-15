# PHP Echo Nest API

A simple, Object Oriented API wrapper for the EchoNest Api written with PHP5.
This library is modeled after the [php-github-api](https://github.com/ornicar/php-github-api) library built by [ornicar](https://github.com/ornicar)

Uses [EchoNest API v4](http://developer.echonest.com/docs/v4/).

Requires

 * PHP 5.2 or 5.3.
 * [php curl](http://php.net/manual/en/book.curl.php) but it is possible to write another transport layer..

If the method you need does not exist yet, dont hesitate to request it with an [issue](http://github.com/bshaffer/php-echonest-api/issues)!

## Autoload

The first step to use php-echonest-api is to register its autoloader:

    require_once '/path/to/lib/EchoNest/Autoloader.php';
    EchoNest_Autoloader::register();

Replace the `/path/to/lib/` path with the path you used for php-echonest-api installation.

> php-echonest-api follows the PEAR convention names for its classes, which means you can easily integrate php-echonest-api classes loading in your own autoloader.

## Instanciate a new EchoNest Client

    $echonest = new EchoNest_Client();

From this object you can now access all of the different EchoNest APIs (listed below)

### Authenticate a user

Authenticate using your EchoNest API Key.  You can obtain one at EchoNest by [Registering an Account](http://developer.echonest.com/account/register)

    $echonest->authenticate($apiKey);

### Deauthenticate a user

Cancels authentication.

    $echonest->deAuthenticate();

Next requests will not be authenticated

## Artists

For searching artists, getting artist information and music.
Wraps [EchoNest Artist API](http://developer.echonest.com/docs/v4/artist.html).

    $artistApi = $echonest->getArtistApi();

### Search for artists by name

    $results = $echonest->getArtistApi()->search(array('name' => 'Radiohead'));
    print_r($results);

      Array
      (
          [0] => Array
              (
                  [name] => Radiohead
                  [id] => ARH6W4X1187B99274F
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

Api calls for generating playlists.
Wraps [EchoNest Playlist API](http://developer.echonest.com/docs/v4/playlist.html).

    $playlistApi = $echonest->getPlaylistApi();

Please view the documentation in this project or on http://echonest.com to see all the options available

## Catalogs

API calls for managing personal catalogs.
Wraps [EchoNest Catalog API](http://developer.echonest.com/docs/v4/catalog.html).

    $catalogApi = $echonest->getCatalogApi();

Please view the documentation in this project or on http://echonest.com to see all the options available

## Tracks

Methods for analyzing or getting info about tracks.
Wraps [EchoNest Track API](http://developer.echonest.com/docs/v4/track.html).

    $trackApi = $echonest->getTrackApi();

Please view the documentation in this project or on http://echonest.com to see all the options available

## The Response

The API tries to return to you the information you typically need, and spare you information such as status codes, messages, etc, if
the response was successful.  Below is an example of a fully rendered response.

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

Often times, the status information is not needed.  However, if you would like the API to return the full response (this is often
needed when dealing with pagers, as the "total" and "start" parameters are passed outside of the "artists" array, for example),
set the *raw* option to `true` for your API.

    // pass options to getter
    $response = $echonest->getArtistApi(array('raw' => true))->search(array('name' => 'Radiohead'));

    // set options manually
    $artistApi = $echonest->getArtistApi();
    $artistApi->setOption('raw', true);
    $response = $artistApi->search(array('name' => 'Radiohead'));
    
If you think this is dumb, let me know and I will consider making `raw` the default.


# To Do

Better documentation and test coverage will be coming soon