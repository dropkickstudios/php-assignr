php-assignr
===========

This is a starter example showing how to use the [assignr.com API](http://assignr.com/help) to pull data from your assignr.com site using PHP. 

Your web server must support PHP scripting.

This sample script will do the following:

* The assignr.com API limits results to 50 records at a time. This script will make multiple calls to the API and display the results in a single table. 
* The API calls to assignr.com will be cached on your server for 5 minutes. 

### get-games.php

The `get-games.php` script will pull game data from your assignr.com site, and display the data in a table. Feel free to add new data elements, modify the CSS, or make changes to fit your needs.


### Setup

* Create a directory on your server that can store the cached content from assignr.com. This file must be writeable by the web server, and should be located outside of your "document root"... you should not be able to view the documents in this folder from a web browser. 
* Modify the provided script:
  * Set your user name and API key (`$username` and `$api_key`)
  * Set the cache directory (`$cache_directory`)
  * Set the search criteria (`$search_criteria`). You can limit the games displayed by using the [assignr.com search language](http://assignr.com/help/games/search_games).
  * Modify the HTML and/or CSS as needed
* Copy the get-games.php file to a directory on your web browser. 


### Creating, Updating and Deleting Games

The `create-game.php`, `update-game.php` and `delete-game.php` files provide examples of how to create a new game, update an existing game, and delete an existing game from assignr.com. Each file has a function that builds a response using the PHP Curl library. 

If you are simply using the API to display data, you will not need to use these three files.

When you create, update or delete a game using the API, you will want to ensure that your API call completes successfully. For example, if you create a new game, it must conform to the validation rules as set forth in the assignr.com API documentation. Please consult the [assignr.com API documentation](http://assignr.com/help/api/api-games) for more information.


## Modifications

If you have used this to build something creative, feel free to share! We accept pull requests... please add your contribution as a new file, and include any setup instructions for your creation.

## Questions

If you have any questions, please contact [assignr.com support](http://assignr.com/static/contact).