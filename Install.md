## INSTALLATION ##

> ### 1 Rename config.php.example to config.php ###

> Run following command:
> > _$> mv config.php.example config.php_


> ### 2 Edit config.php ###

> a) Specify database connection settings at "dbParams" section:
> > dbname
> > username
> > password
> > host (if remote)


> b) Specify directory to store config files (section "path"), it must be writeable

> c) Specify diff programm (section "diffprog"). It must be installed in your system.
> > Tested with diff and colordiff. You can try others if you want.


> d) Specify database adapter from one of the following:

> for MySQL: DbSync\_DbAdapter\_Mysql (requires pdo\_mysql)

> e) Specify config files adapter from one of the following:

> for YAML: DbSync\_FileAdapter\_SfYaml (requires Symfony Yaml)

> ### 3 Make files schema.sh, data.sh, trigger.sh executable ###

> Run following command:
> > _$> chmod +x schema.sh data.sh trigger.sh_

## DEPENDENCIES ##

  1. PHP 5.2.6 or newer


> 2 PDO extension

> 3 PEAR extension symfony/YAML (http://pear.symfony-project.com/)

> If you have installed symfony/YAML without pear than edit include path in config.php
> and replace "SymfonyComponents/YAML/sfYaml.php" with your path

> 4 Diff or CollorDiff installed