# Kunstmaan code quality configuration

The default code quality configuration files for Kunstmaan projects. 

## Installation

```bash
$ composer require --dev kunstmaan/code-quality-config
```

## Usage

During the installation the package will copy the code quality config files.

### Php-cs-fixer

The package will generate a default config file which works for the majority of our projects.

**.php_cs**

```php
<?php
require 'vendor/autoload.php';

return Kunstmaan\CodeQuality\PhpCsFixer\Config::fromFolders('src');
```

You can also override rules per-project without overriding the core rules like this:

```php
<?php
require 'vendor/autoload.php';

return Kunstmaan\CodeQuality\PhpCsFixer\Config::fromFolders(['src'])->mergeRules([
   'php_unit_strict' => false,
]);
```

### Grumphp

**grumphp.yml**

```yaml
imports:
    - { resource: vendor/kunstmaan/code-quality-config/base-grumphp.yml }

parameters:
    convention.git_commit_message_matchers: { 'Must contain JIRA issue number': '/(^JIRA-\d+:|^\[no\-issue\]) [A-Z].+/' } #TODO: replace jira key or leave this parameter empty ([])
```

By default we setup the commit message matcher so it must contain an jira issue number. After the install you should 
update this regex to your project key. Or replace the parameter with an empty array (`[]`) to disable this check.
