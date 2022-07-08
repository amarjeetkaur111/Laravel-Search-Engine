
## Installation
1/ Install with Composer

```bash
composer require amark/laravelsearchengine
```

2/ Add the service provider to config/app.php

```php
'providers' => [
    '...',
    'AmarK\LaravelSearchEngine\LaravelSearchEngineProvider'
];
```

### Creating your custom search engine
1. If you create your engine at https://cse.google.com/cse/ you will find the ID after you click at Settings
2. Just check the URL you have like https://cse.google.com/cse/setup/basic?cx=search_engine_id and the string after cx= is your search engine ID
     
!! Attention !! If you change style of your Custom search engine, the ID can be changed

### Get your API key
1. go to https://console.developers.google.com, than
2. click on the menu on the right side of the GoogleAPI logo and click on 'Create project'
3. enter the name of the new project - it is up to you, you can use 'Google CSE'
4. wait until project is created - the indicator is color circle on the top right corner around the bell icon
5. API list is shown - search for 'Google Custom Search API' and click on it
6. click on 'Enable' icone on the right side of Custom Search API headline
7. click on the 'Credentials' on the left menu under the 'Library' section
8. click on the 'Create credentials' and choose 'API key'
9. your API key is shown, so copy and paste it here

### Save the configuration values
Save search engine ID and api ID in your config/laravelSearchEngine.php

## Usage

Create an object and call the function getResults to get first 10 results
```php
$textresult = new LaravelSearchEngine(); // initialize
$results = $textresult->getResults('some phrase'); // get first 10 results for query 'some phrase' 
```

```php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use AmarK\LaravelSearchEngine\LaravelSearchEngine;

class GoogleSearchController extends Controller
{

  public function index(){
    $textResult = new LaravelSearchEngine(); // initialize
    $results = $textResult->getResults('some phrase'); // get first 10 results for query 'some phrase' 
  }
}
```

```php
$parameters = array(
    'start' => 10 // start from the 10th results,
    'num' => 10 // number of results to get, 10 is maximum and also default value
)

$textresult = new LaravelSearchEngine(); // initialize
$results = $textresult->getResults('some phrase', $parameters); // get second 10 results for query 'some phrase'
```

```php
$textResult = new LaravelSearchEngine(); // initialize
$results = $textResult->getResults('some phrase'); // get first 10 results for query 'some phrase'
$rawResults = $textResult->getRawResults(); // get complete response from Google
```

For getting the number of results only use
```php
$textResult = new LaravelSearchEngine(); // initialize
$results =  $textResult->getResults('some phrase'); // get first 10 results for query 'some phrase'
$noOfResults = $textResult->getTotalNumberOfResults(); // get total number of results (it can be less than 10)
```

If you have more engines / more api keys, you can override the config variables with following functions

```php
$textResult = new LaravelSearchEngine(); // initialize

$textResult->setEngineId('someEngineId'); // sets the engine ID
$textResult->setApiKey('someApiId'); // sets the API key

$results =  $textResult->getResults('some phrase'); // get first 10 results for query 'some phrase'
```

