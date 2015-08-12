# Schema Sync Tool #
  * Usage **./dbsync.sh schema `[action] [ [tableName] ... ] [--option]`**
  * Usage **php dbsync.phar schema `[action] [ [tableName] ... ] [--option]`**

_if tableName not specified action applied to all tables/configs_

Actions:
```
delete  Delete table and config
        Use --db to delete only form database
        Use --file to delete only config file
diff    Show diff between database table schema and schema config file
help    Help message
init    Create config file(s)
pull    Override current table config(s) file by new created from database
push    Override database schema by current schema config file
        Use --show to only display alter code
status  Check sync status (Ok/Unsyncronized)
```

# Data Sync Tool #

  * Usage **./dbsync.sh data `[action] [ [tableName] ... ] [--option]`**
  * Usage **php dbsync.phar data `[action] [ [tableName] ... ] [--option]`**

_if tableName not specified action applied to all tables/configs_

Actions:
```
diff    Show diff between database table schema and schema config file
help    Help message
init    Create config file(s)
merge   Merge data rows from config file to database table
pull    Override current table config(s) file by new created from database
push    Override database data by current data config file
        Use --force to truncate table first
status  Check sync status (Ok/Unsyncronized)
```

# Trigger Sync Tool #

  * Usage **./dbsync.sh trigger `[action] [ [triggerName] ... ] [--option]`**
  * Usage **php dbsync.phar trigger `[action] [ [triggerName] ... ] [--option]`**

_if trigger not specified action applied to all triggers/configs_

Actions:
```
delete  Delete trigger and config
        Use --db to delete only from database
        Use --file to delete only config file
diff    Show diff between database trigger and config file
help    Help message
init    Create database trigger config in specified path
pull    Override current trigger config file by new created from database.
push    Override database trigger by current config file
        Use --show to only display sql code
status  Check triggers status (Ok/Unsyncronized)
        Use --table [[tableName] ... ] to display triggers for certain table(s)
```

# Dependencies #
  * PHP 5.0 or newer is required
  * PDO extension is required
  * [sfYaml](http://components.symfony-project.org/yaml/)