# Bon Matin - SOEN 344

Basically a walk-in clinic appointment reservation web app.

## Installation

### Pre-requisites
- `composer` executable
- `yarn` & `npm`, _ps: we'll use yarn_
- A MySQL DB & Apache. Use any XAMPP like service _I recommended `laragon` on pc or `valet`/`valet+` on mac_
- PHP >= 7.1.3, preferable 7.2
- PHP extensions you might not have be default: `bcmath`
- `xdebug` is always a plus

### First Setup Steps

1. once you clone the repo run `composer reset`, it will run a batch of the starter commands.
1. Open the `.env` and configure it to work with what you need, _namely the DB connection_
1. Create a mysql database matching the connection details in your `.env`
1. Create the db schema and seed it with random values with `composer mfs`
1. verify your setup is functional with alias `composer diag` or the actual 
   command `php artisan self-diagnosis`
1. Access the web app through your preferred local web server setup
(_I like valet+ on macs & laragon on PC, ghetto way: php artisan serve + manual MySQL install _)

### Bonus Commands
- Quickly recreate the db tables and seed it with random data with `composer mfs`
- When everything goes to hell run `composer fix` or `composer install:hard` if you don't want to rerun migrations
- Regenerate the frontend resources with `composer frontend` (requires yarn) if you're working with vue or scss


## Usage

### Website
Open the site, select one of the different login types. _(Only Doc/Nurse work for now)_

### API
Look at the `api` route group in the `routes/web.php` file for a quick view of whats available at the moment.
I heavily use `Postman` since I haven't redone much UI yet, holler at me if you want a copy of my half-baked collection.

### Console
Tinker is the primary tool for testing queries, php logic, and debugging weird code, 
open with `php artisan tinx`. Basically an interactive php shell that makes it easy to test stuff like
observer creation rules without having to build out the Resource/Model Controller