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
_(I like valet+ on macs & laragon on PC, ghetto way: php artisan serve + manual MySQL install)_

### Bonus Commands
- When you see DB issues run `php artisan migrate:fresh --seed` (`composer mfs` for short) to 
  quickly recreate the db tables and seed it with random data
- If you just need more random data in the db run `php artisan db:seed`.
- When everything goes to hell run `composer fix` or `composer install:hard` if you don't want to rerun migrations
- Regenerate the frontend resources with `composer frontend` (requires yarn) if you're working with vue or scss
- `yarn dev` or `yarn watch` for frontend dev build, watcher will recompile when files are changed.


## Usage

### Website
Open the site, select one of the different login types. _(Only Doc/Nurse work for now)_

### API
Look at the `api` route group in the `routes/web.php` file for a quick view of whats available at the moment.
I heavily use `Postman` since I haven't redone much UI yet, holler at me if you want a copy of my half-baked collection.

### Console
Tinker is the primary tool for testing queries, php logic, and debugging weird code, basically your best friend.
Open with `php artisan tinx`. Its an interactive repl php shell that makes it easy to test stuff like
observer creation rules without having to build out the Resource/Model Controller. 

**Currently best way to test CRUD functionality for Milestone#1 features**

## Design Patterns

### Laravel Patterns (aka stuff we mostly use but didn't write)

Accidentally found [book](https://produirebio-normandie.org/wp-content/uploads/2016/01/9781783287987-LARAVEL_DESIGN_PATTERNS_AND_BEST_PRACTICES.pdf)
on laravel design patterns.

- Builder Pattern _ex: Database Manager aka driver support_
- Factory Pattern
- Repository Pattern
- Publisher/Subscriber Pattern
- Dispatcher Pattern
- Strategy Pattern _ex: config loader, encrypt/decrypt, session_
- Provider Pattern _ex: Auth or App where we register observers_
- Iterator Pattern _Collections_
- Facade Pattern 
- Singleton Pattern
- Observer pattern
- Dispatcher Pattern _events system_
- Inversion Of Control / Dependency Injection Patterns _ex: we use it to automatically find models in controllers_
- Command Pattern _ex: migrations_
- SOLID patterns
- MVC pattern

### Patterns we implemented on our own

- [X] Factory Pattern _exposed to dev, used by our seeders, view()_
- [X] Provider Pattern _ex: Auth or App where we register observers_
- [X] Observer pattern _ex: appointment on save rules_
- [X] Dispatcher Pattern _ex: email notification code_
- [ ] Command pattern _setup command to check appointments and set to complete if end time has passed_
- [ ] SOLID Patterns (just need to give examples)
- [ ] Domain Driven Design
- [ ] Builder Pattern _extend Support/Manager_
- [ ] Repository Pattern _easy to implement, abstract eloquent models_
- [ ] Service Pattern _ex: we can make a booking engine service_
- [ ] Facade Pattern _ex: provided by laravel for easy service access_

## Resources
We'll be using laravel and vue.js for this project and a good place to start learning all of this scary new tech is 
[Laracasts](https://laracasts.com/), specifically the [laravel from scratch series](https://laracasts.com/series/laravel-from-scratch-2018)
& [Vue 2 Step by step](https://laracasts.com/series/learn-vue-2-step-by-step)
Here are some more resources you might want to take a look at : 

### Documentation
- [Laravel](https://laravel.com/docs/5.8/)
- [Vue.js](https://vuejs.org/v2/api/)
- [PHP](https://secure.php.net/manual/en/index.php)
- [Bootstrap](https://getbootstrap.com/docs/4.0/getting-started/introduction/)
- [TailwindCSS](https://tailwindcss.com/docs/what-is-tailwind/)
- [DevDocs aka ALL DOCS](https://devdocs.io/)

### Get good at laravel
- 
- https://www.youtube.com/watch?v=U3rPtLW5iuI
- https://www.youtube.com/watch?v=EU7PRmCpx-0&list=PLillGF-RfqbYhQsN5WMXy6VsDMKGadrJ-

### Vue.js
- [Vue](https://laracasts.com/series/learn-vue-2-step-by-step)
- Remind me to add more if you need any

### Architecture and Design Inspiration

#### Laravel Best Practices and Design Pattern Resources
- [Best Practices summary site](http://www.laravelbestpractices.com/#design_patterns)
- [Laravel team on Laravel 4 patterns](https://www.slideshare.net/sparksphill/software-design-patterns-in-laravel-by-phill-sparks) 
  [Presentation Talk](https://www.youtube.com/watch?v=qkIsTtIcTBE)
- [Design Patterns in Laravel Book](https://produirebio-normandie.org/wp-content/uploads/2016/01/9781783287987-LARAVEL_DESIGN_PATTERNS_AND_BEST_PRACTICES.pdf)
- [Code examples of patterns](https://github.com/kdocki/larasign)
- [Here's a video if you don't like reading](https://www.youtube.com/watch?v=qpo5KG0vIyE) 
- [Adapter, Straregy & Factory Patterns in Laravel w/ examples](https://www.youtube.com/watch?v=e4ugSgGaCQ0&index=2&list=PLuCEg9czvGugn72y0kuvxEUvbRc2HHN4J) 
_prepare to 2x speed and hit the left key 8x a sec_
- Design Patterns in PHP and Laravel by Kelt Dockins, ISBN:9781484224502 [link on gdrive coming soon](soon)

#### Bonus
- [Doc appointment DB Design](https://www.vertabelo.com/blog/technical-articles/the-doctor-will-see-you-soon-a-data-model-for-a-medical-appointment-booking-app)
- [Actually best vid, basically 341 in 45m](https://www.youtube.com/watch?v=enTb2E4vEos&index=3&list=PLuCEg9czvGugn72y0kuvxEUvbRc2HHN4J) 