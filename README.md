# PostClick website

_Last update: 07/07/2020_

[[_TOC_]]

## Assumptions
We planned to reuse elements of the Instapage.com website that work for us (so we've created InstaWP framework based on Instapage.com). However, we planned to improve some things.

## General
Project follows `PSR-12` coding standard for PHP. This repository is written in PHP 7.4. We have TypeScript and SASS.
We use NPM and Composer for JS and PHP packages.

## Conventions
We use PSR Naming Conventions (https://www.php-fig.org/bylaws/psr-naming-conventions/). We use PSR-4 for class loading and directory structure (https://www.php-fig.org/psr/psr-4/) and PSR-12 (https://www.php-fig.org/psr/psr-12/) for coding style.

For styling we use classes with `pc-` prefix. We follow browsers convention with adding whitespace to elements, meaning we prefer `margin-bottom` from `margin-top` for elements such as headings or paragraphs.

### InstaWP

This is meant to be Instapage framework for WordPress development. Basically it is built on the best parts of `instapage.com` website (website built by many peoples, with many iteration, maintained and developed through many years). Thanks to this we are sure that we follow the best practices which are manageable in a long run. Basically this directory is meant to be reusable part, so we can just copy this part over to new project and have good starting point for new website development. That is why this part can't have instance specific binding, everything should be parametrised and defined under `app/wp-content/themes/instance-name/src`

--
## Project structure

Main directory contains a lot of standard files like (dependencies definition, code style checkers / linters, unit tests, CI - `gitlab-ci.yml`):
- `composer.*` for defining composer dependencies (PHP dependencies)
- `package*` for defining NPM dependencies (Frontend side in our case)

#### WordPress related structure
1. In `/app` directory we have standard WP Code
2. In `/app/wp-content/themes/postclick/src` directory we have well known InstaWP theme (like V5 and V7 of instapage.com website)
3. In `/app/wp-content/mu-plugins/insta-wp` directory we have InstaWP framework which basically is the same flow & constructions as in V5 and V7 of instapage.com website

#### Docker related structure
In `/docker` directory we have all things related to Docker
1. `/docker/wordpress/Dockerfile` is multistage docker file used for development and production image building.
2. On local development instances we use cert `docker/wordpress/local/cert/` for `wordpress-local.postclick-uat.com` domain. This domains points to 127.0.0.1

#### MISC

Other not discussed above directories:
1. `/ci` Everything connected to continuous integration/deployment:
   - `/ci/gitlab-ci-parts` gitlab-ci.yml file parts, we should have file not longer than 80 lines - it is easier to grasp what is going on when looking on small file
   - `/ci/env` - environment variables and environment settings, you can set here environment variables (without any sensitive information) and change environement settings (replica count, memory, cpu etc.)
2. `/data/database.sql` fresh database dump meant to be used for development

--

#### Javascript conventions

We should try to write things as statically resolved as possible. That is why TypeScript is the preffered
way of writing JS code. In cases where we do not have time to rewrite functionality to TS, it is allowed
to use pure ES6 or even ES5 (but please, discuss this case with Website Team Architect first).

We have three aliases available for importing things:
1. `Components` which will look for modules in `app/wp-content/themes/postclick/src/Components` directory
2. `Modules` which will look for modules in `app/wp-content/themes/postclick/src/Assets/Src/JS`
3. `InstaWP` which will look for modules in `app/wp-content/mu-plugins/insta-wp/src`

##### Comments

For commenting JavaScript code we use JSDoc (https://jsdoc.app/). Please do not write obvious comments.

#### Code organization

We have two most popular units.

##### Components
Components - independent unit meant to be used with some output to frontend layer. 
We have there template (with `.phtml` extensions), JS (in `/JS` subdirectory), 
SCSS (in `/SCSS` subdirectory) and model in `ComponentNameModel.php` file. 
Template is only required components element.

##### Modules
Modules - aspect oriented unit. It can contain any files needed to fulfill given aspect. 
For example we have  `MediaModule` which makes any needed adjustment to Media Library. Modules are located here:
`app/wp-content/mu-plugins/insta-wp/src/Modules` and `app/wp-content/themes/postclick/src/Modules`. 

What is a difference between those two localization of modules?
- InstaWP Modules are framework modules, they shouldn't have any direct connection with PostClick Modules
- PostClick Modules can use InstaWp Modules and can be instance specific

--
## Installation

##### Requirements
To run project locally you need to have installed:
1. Docker
2. Docker-compose
3. NPM
4. Composer
5. Git (also `git-crypt`)

Make sure that you have port 80, 443 and 3306 available (apache and MySQL)
Generate GPG Key (https://spinen.com/using-git-crypt-in-a-git-repository-to-encrypt-sensitive-files-with-gpg-keys/ - 
`Step 2: Generate a gpg key` and `For collaborators` section) and ask repo maintainer for adding you key. 

##### Installation

1. Clone our repo `git clone git@gitlab.instamanagement.club:instapage/postclick-website.git`
2. Navigate in your terminal to repo folder `cd postclick-website`
3. `npm ci`
4. `composer install`
5. `docker-compose up -d`
6. `npm run devWatch`
7. `git-crypt unlock`

## Scripts to help you with daily work

##### Linters
- `npm run lint` - run eslinter, really useful to check TypeScript and JS code correctness.
- `npm run lintFix` - run fixing errors found by eslint.  

##### Building
- `npm run prod` - build scripts as it is done on UAT/UAT-DEV/LIVE servers.
- `npm run dev` - build scripts for development (source maps included etc.).
- `npm run devWatch` - build scripts for development (source maps included etc.) + watch for changes in source files 
(JS + CSS) and autorebuild + autoreload

##### Misc
- `npm run updateDatabase` - import database state from our repository. Useful command when you know that database in 
repository was updated.


## Linters & CodeSniffers

### StyleLint

##### Information
All config files are stored in `.stylelintrc` file. We're using custom rules and extending them with SMACSS order plugin.

##### Installation
1. Run `npm ci`
2. Disable default code editor linter
3. Install `stylelint` plugin to your code editor
4. (optional) run linter from CL with: `npx stylelint <file_path>`

### TypeScript ESLint & Prettier

##### Information
You can find config in `.eslint.json` and `.prettierrc`.

We use ESLint package with TypeScript parser and TSLint plugin. Additionally we simultaneously run Prettier check.
This installation was based on the "Standardizing TypeScript with ESLint NPM Prettier" article (https://killalldefects.com/2019/11/28/standardizing-typescript-with-eslint-npm-prettier/).

##### Installation
1. Run `npm ci`
2. To check run `npm run lint`
3. To autofix errors run `npm run lintFix`
