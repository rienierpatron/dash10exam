# Dash10 PHP Developer Test

This developer test revolves around a basic native PHP app that features two of the best sports of the world ... rugby and basketball! At least - it currently supports rugby, and your job in this test is to extend it to also feature basketball.

The application includes a number of service classes to provide basic support for:
 - views / templating
 - easy, testable http requests
 - end to end feature tests

It currently pulls player data from a demo rugby API endpoint, and presents a profile card representing that player. Read more about this api, and other endpoints used in this test in the [api docs](./docs/api.md).

![All Blacks Screenshot](https://i.imgur.com/dddgt1D.png)

Once you setup this demo app (see instructions below), load this page from the `allblacks.php?id=1` endpoint - with the `id` querystring representing the player ID we are going to show. Currently, to change the player you have to manually change the ID in the querystring.

##### Images
Player images are named (lowercase) `$firstName-$lastName.png` (e.g. `aaron-smith.png`) and are stored in `static/images/players/allblacks/` for All Black players, and `static/images/players/nba/` for NBA players.

Team logos are stored under `static/images/teams/$team.png`.

##### Further help

There is [installation help](#installation-help) and a [code guide](#code-guide) at the end of this doc.

## Tasks

Below are a list of tasks for you to complete. You can complete as many as you want - obviously the more tasks you complete & the more complex they are, the better you will score. Some tasks are simple, while others are more complex. Some focus on backend skill, while others on frontend.

I encourage you to choose the more complex tasks you feel you are able to complete first to best showcase your skills and experience.

### Task 1: UI & Navigation
*Difficulty: low - medium.*

Upgrade the interface to provide the ability to navigate to other players. There are 8 players currently provided by the API (although you app should allow for this to change in future without breaking).

Implement the new navigation tabs in the below mockup. The small player tabs should represent the players directly before and after the current featured player. Clicking on them should request that player from the API & render their data into the view.

Navigation should be circular - if you reach the last player, show the first player next. And the opposite at the start - show the last player as previous.

- See the navigation on the right hand side of the mockup below.
- The current player should appear 'tab up' with the same colour background as the main card.
- Previous & next players should appear with the black background.
- Font is `18px` (`1.125rem`), and weight `700`.
![Nav Screenshot](https://user-images.githubusercontent.com/2694025/169527260-5000bd90-7200-437e-82eb-b445c28c231a.png)

### Task 2: Efficiency
*Difficulty: low-medium*
1. Improve the efficiency of the app so we don't have to request data from the API every single page load.

Extra for experts:
2. Update the app so you can change players without reloading the page when different players are loaded. Feel free to use whatever frontend frameworks you prefer (eg VueJS/React/JQuery etc), or plain javascript.

### Task 3: NBA is awesome!
*Difficulty: Complex*

It would be really cool to extend our app so it could load in NBA player information into the same template - but with a NBA feel to it.

To do this we are going to need to access other endpoints documented in the [api documentation](./docs/api.md) - the `nba.players` and `nba.stats` endpoints. FYI this demo api has players from `id`: `1` through to `7`.

To complete this task you need to come up with a tidy solution that allows our app to pull data from these different endpoints, combines the data into a suitable format and renders it through the same view (with minor color / style differences).

We really want to avoid code duplication (DRY code is good code!), so make sure to use the same view & you should also be able to use the same single controller class.

Here are some hints:
1. We really want our controller class to be able to request data from either API (rugby or basketball) in exactly the same way - and receive data in a reliable format from either API. That way we don't need to make big changes to our controller code. That complexity should be abstracted away in other classes.
2. Create a player class to represent your player data - forcing the API service class to output data in a strict format for our controller class to consume. This class should know how to do things like determine its image.
3. Pull out the API code from the controller into a service class (store it under an `\App\Api` namespace) which pulls data from the API & provides it to the controller.
  - Create a contract / interface (under `app/Contracts`) that the API class(es) must implement.
  - Ideally create an abstract class which implements the basic methods of the contract - avoiding the need to duplicate code in the different API class implementations.
4. Refactor the controller & view code so it outputs data from the player instance
5. Create a new NBA class which implements the contract and pulls data from the API endpoint, transforming it as required to output the format the controller expects.
6. Create a `nba.php` route which works virtually the same - accepting an `id` querystring parameter to display a specific player from the NBA endpoints
7. Write tests to prove your code works including
  - Unit tests for each API class
  - Feature test for the updated NBA routes
  - Ensure existing tests still pass

There are some documentation for some of the helper classes for writing good tests under the [code guide section](#code-guide).

You will need to tweak a few aspects of the view & CSS (see [code guide](#code-guide) for information on where they are kept) so that your output when in "nba mode" will look like this mockup. (Note: the side nav will only show if you've completed task 1).

![NBA Screenshot](https://user-images.githubusercontent.com/2694025/169531537-ad701a2d-91f2-46de-90d5-79b57b4dfa7c.png)

## Installation Help

This application is built as a native PHP8+ application. To get it up and running all you should require is a version of php8.0 or higher installed.

### Install steps

Once you have downloaded this repository and have your development environment up & running you will need to:

- Copy `.env.example` to `.env`, and update `API_KEY=few823mv__570sdd0342`. This will allow your API connections to access the API.

Once configured you should be able to load the app from its `/allblacks.php` endpoint.
- Loading the default endpoint (optionally with `?id=1`) should show you the screenshot at the top of this doc.
- Running `vendor/bin/phpunit` from the project directory on command line should run the tests & show them all passing.

## Code guide

To keep this test as simple as possible for a wide range of people, it is not based on any framework - just straight native PHP.

However we have provided numerous service classes to help making the app easy to write, easy to test, and reasonably easy to extend.

Here is an overview of the code:

- The main route is just a normal php file. Aside from including the autoloader, it should create an instance of the controller, and echo its `show` method, passing in the querystring `id` parameter
- The controller (stored under `app/Controllers/PlayerController.php`) retrieves data from the API, transforms it & passes it to a new instance of the view which his returned.
- css & images are stored under `static/`

### Views & templating

A basic PHP templating system is implemented by `app/View.php`. Views are normal PHP files stored under `views/` and just contain normal PHP.

To create a new view you can use the `view` helper function and pass the name of the view to it - e.g. `$view = view('players', $data);` will create a new view, using `view/players.view.php`.

The second argument should be an associative array of data to make available to the template. Have a look at `players.view.php` to see the template being used.

### HTTP requests

To make HTTP requests easier, we provide a class to offers a basic wrapper around [Guzzle](https://docs.guzzlephp.org/). This provides a static method `get` which can be called to issue a `GET` request to the API. It receives an url/endpoint, and an optional 2nd argument providing an array of querystring parameters to pass.

It returns an instance of `Http\Response` which provides a couple of useful methods:
- `body` - retrieves the raw body as a string
- `json` - parses the returned response as JSON

Check `App\Controllers\PlayerController` for an example of it in use.

#### Faking responses in tests
One of the most useful aspects of this class is it makes it super easy to fake HTTP responses by calling the `Http::fake` static method prior to executing code which will attempt an HTTP request. Calling `Http::fake($fakeResponse);` with either a string or an array (JSON) will ensure any future requests return this response instead of attempting to execute an actual request. See `tests/Feature/AllBlacksTest.php` for an example of this.

### Testing

We provide a few testing helpers to make it easier to write feature tests for your code. You can call `$response = $this->get($endpoint, $querystring);` to issue a request to the specfied endpoint. E.g. `$response = $this->get('allblacks.php', ['id' => 2]);` will issue a request to the main endpoint, passing `?id=2` as the querystring.

You can then perform assertions on the response object. These assertions work similar to the ones offered in Laravel (docs linked to below).

#### Inspecting the parsed template

- [assertSee](https://laravel.com/docs/9.x/http-tests#assert-see)
- [assertSeeInOrder](https://laravel.com/docs/9.x/http-tests#assert-see-in-order)
- [assertSeeText](https://laravel.com/docs/9.x/http-tests#assert-see-text)
- [assertSeeTextInOrder](https://laravel.com/docs/9.x/http-tests#assert-see-text-in-order)

#### Inspecting data passed to the view

- [assertViewHas](https://laravel.com/docs/9.x/http-tests#assert-view-has)
- [assertViewHasAll](https://laravel.com/docs/9.x/http-tests#assert-view-has-all)

See `tests/Feature/AllBlacksTest.php` for some examples of it in use.
