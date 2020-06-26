# jmw
Wrapper for simplifying creation of controlling points for websites based on Clean URLs.

# Usage
You just need to setup config and entry point: <br />
.htaccess
```
RewriteRule ^(.*)$ index.php?page=$1 [L,QSA]
```

index.php
```php
JMW::SetConfig($config['jmw']);
new JMW($_GET['page'] ?? '');
```

config
```php
$config['jmw'] = [
    'controller' => [
        'default_namespace' => 'namespace\\of\\entry\\controller\\',
        'error' => \class\for\handling\http\errors\Error::class
    ],

    'router' => [
        //url start path (actually the name of the controller in snake case)
        'default_start' => 'site'
    ],

    'dirs' => [
        // directory where the templates (views) are located (it is used in default implementation of View class)
        'templates' => 'templates/path/folder/'
    ]
]
```

And now wrapper ships with two controllers `Nester` or `Page` for easy routing handling. Nester first searches for public methods in class then for class in `namespace\of\child\class\\[name_of_child_class]` <br />
For the config used above, we have to create class Site:
```php
final class Site extends Nester {
    public function __construct(Router $router) {
        parent::__construct($router);
    }

    public function index() {
        // what to do by default
        $this->renderView('view_file_name_without_php_extension', [
            'some' => 'data',
            'passed' => 'to view'
        ]);
    }
}
```
By using default implementation of `View`, we need to create file `index.php` in templates path.
We can now use in such a template varibale `$data` to access data passed in `renderView()` method.
```php
<html>
  <head>
    <title><?=$data['some']?></title>
  </head>
  <body>
    <?=$data['passed']?>
  </body>
</html
```
Now we set up a controller for handling paths:
 - http://server.address/
 - http://server.address/site
 - http://server.address/site/index
<br />

It is possible to create routing (by default wrapper handles paths separated by / and changes split string from snake case to pascal case and creates instace of controller with such a name).
For the example above it would looks like this (if we spacify defalut namespace) (* is a wildcard for any string):
```php
[
  '*' => 'site'
]
```
Or without default namespace:
```php
[
  '*' => \namespace\to\the\class\Site::class
]
```
If you use class in routing then this class will be called but if you use string then it will be changed from snake case to pascal case. <br />
More advanced example (by default Router uses `elements` variable for nesting and `controller` variable for handling current path):

```php
[
  '*' => [
    'controller' => \namespace\to\the\class\Site::class
    'elements' => [
      'other' => \other\namespace\Controller::class,
      'page' => 'nice-controller'
    ]
]
```
Thanks to this routing paths would be handled like this (always going through all parent controllers):
- http://server.address/ => `new \namespace\to\the\class\Site()`
- http://server.address/other => `new \other\namespace\Controller()`
- http://server.address/page => `new \default\namespace\NiceController()`
