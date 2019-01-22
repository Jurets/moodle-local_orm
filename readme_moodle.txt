Idiorm & Paris
==============

A minimalist database toolkit for PHP5.

URL: http://j4mie.github.io/idiormandparis/
License: BSD

Notes
* All vendor files has already pre-installed in ./vendor folder
* Do not use composer to install or upgrade vendor files because the composer installation is bundled
  with an autoloader and required components. But there are already present in moodle installation.
* To upgrade libs to the latest verions do it manually
  - Check if there are any new changes
  - Be careful before upgrading: do not forget to make backup of vendor files
  - Download idiorm from https://github.com/j4mie/idiorm
  - Replace corresponding files in folders ./vendor/idiorm
  - Download idiorm from https://github.com/j4mie/paris
  - Replace corresponding files in folders ./vendor/paris

Some changes from the upstream version have been made:
* To omit docs generation all docs has been built and placed to appropriate folders
