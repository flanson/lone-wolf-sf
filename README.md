lone-wolf-sf
============
App to manage Lone Wolf adventure (need the books to play)

### Version
0.0.1

### Todos

 - [ ] put on github (but no passwords)
 - [ ] add on Heroku
    - [ ] add postgresql to Heroku
    - [ ] add grunt + node js + bower
 - [x] dev minimal functions
 - [x] add phpcs + phpMd
    - [x] check if phpcs works ...
    - [x] check if phpMd works ...
 - [ ] add [apcCache][op-code] in local
 - [ ] move templates in app/Resources

##### Build
 - [x] add jenkins / [travis][travis]
    - [x] add build
        - [ ] add [cs fixer][cs-fixer]
        - [ ] add [security check][security-check]
    - [x] configure Sensio Insight account
        - [ ] add [check code quality][code-quality] when on github
 - [ ] replace .css with .less   
 - [ ] add [grunt][grunt] or other building method (after first test on Heroku)
    - [ ] get html5shiv and respond with bower instead of getting js
    - [ ] bower 
        - [ ] bootstrap
        - [ ] html5shiv
        - [ ] respond
        - [ ] jquery
 - [ ] add Tests (with phpSpec ???)
    - [ ] see how to test user login
   
##### Design   
 - [ ] add app controller pattern ? (need to check navigation between screen with a state diagram like way)
    - [ ] if so add State pattern to manage screen change
 - [ ] introduce command pattern ?
 - [ ] enemy should be embedded in Hero (since it's a Value Object)
 - [ ] Extract dependence from controllers see [1][controller-refacto], [2][controller-phpspec]

##### Templates & functionality list   
 - [ ] PB on baseIndex : bodyClass find a better way to define body Class
 - [ ] Careful ! some button take the whole line (even if the graphics doesn't take the full line)
 - [x] Add Errors pages
    - [ ] Add fun image for each error page
        - [x] 500/404
        - [ ] 403 forbidden
        - [ ] 401 Unauthorized
        - [ ] 405 Method Not Allowed
 - [x] Add all translations
 - [ ] Add [loading buttons][loading-buttons]
 - [ ] add map ??


License
----

Private Use

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)

   [grunt]: <http://gruntjs.com/>
   [assets]: <http://symfony.com/blog/new-in-symfony-2-7-the-new-asset-component>
   [loading-buttons]: <http://getbootstrap.com/javascript/#buttons>
   [code-quality]: <https://insight.sensiolabs.com/what-we-analyse>
   [op-code]: <https://en.wikipedia.org/wiki/List_of_PHP_accelerators#Windows_Cache_Extension_for_PHP>
   [controller-refacto]: <http://peterjmit.com/blog/refactoring-a-symfony-2-controller-with-phpspec.html>
   [controller-phpspec]: <http://www.craftitonline.com/2013/09/phpspec-practices-with-symfony2-controllers-part-vi/>
   [cs-fixer]: <http://cs.sensiolabs.org/>
   [travis]: <https://docs.travis-ci.com/>
   [security-check]: <https://security.sensiolabs.org/>
   [api-gen]: <http://www.apigen.org/>
   [php-doc]: <https://www.phpdoc.org/>

