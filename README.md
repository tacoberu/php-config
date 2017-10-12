PHP Config
==========

Config libs provides interface for loading config. Local, User-base, System-base, App-base.

## Use case

    $config = Config::createByEnvironment('doe', null, getcwd()); // doe is application name
    $config->has('name'); // true
    $config->required('name'); // John
    $config->required('core.name'); // Andrey
    $config->required('lang[5].name'); // germany
    $config->required('lang[de].name'); // germany
    $config->required('core'); // [ name => Andrey, age => 42 ]
    $config->required('nope'); // exception
    $config->get('nope'); // NULL
    $config->get('nope', 'Mark'); // Mark

### Default configuration
    $config = Config::createByEnvironment('doe', __dir__ . '/default.json', getcwd());

### Own Parser
    $config = Config::createByEnvironment('doe', __dir__ . '/default.xml', getcwd());
    $config->addParser(new XmlParser);


## Source of configuration

Configuration is loading from:

### system:

- Linux: /etc/doe.ini
- MacOS: /private/etc/doe.ini
- Win: ?

### user:

- Linux: /home/current-user/.config/doe/config.ini
- MacOS: /Users/current-user/.config/doe/config.ini
- Win: ?

### local:

./doe.ini


## Buildin format

- ini
- json


## TODO

* own loader
* include
* merge parent
