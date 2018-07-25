# One.co.id PHP SDK

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KLYgarage/onesdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KLYgarage/onesdk/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/KLYgarage/onesdk/badges/build.png?b=master)](https://scrutinizer-ci.com/g/KLYgarage/onesdk/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/KLYgarage/onesdk/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

This SDK is used for easier intergration and API usage of ONE.co.id SDK. For publisher only. Usage still limited, check docs for supported features.

## Prerequisite
- PHP >=5.3.3

## Installation

If you are using composer you could get it with `composer require kly/onesdk` and you are all set. Load up the autoloader and Call the classes or factory you need.

## Usage

To use this SDK, there are several basic steps that should be done :

1. Load credentials

2. Make Publisher Object

These are some examples code to perform those steps :

### Load credentials

```function loadTestEnv()
{
    if (!empty(getenv('CLIENT_ID')) && !empty(getenv('CLIENT_SECRET'))) {
        return array(
            'CLIENT_ID' => getenv('CLIENT_ID'),
            'CLIENT_SECRET' => getenv('CLIENT_SECRET'),
            'ACCESS_TOKEN' => getenv('ACCESS_TOKEN')
        );
    }

    $envPath = realpath(__DIR__ . '/.env');

    if (file_exists($envPath)) {
        $env = array_reduce(
            array_filter(
                explode(
                    "\n",
                    file_get_contents($envPath)
                )
            ),
            function ($carry, $item) {
                list($key, $value) = explode('=', $item, 2);
                $carry[$key] = $value;
                return $carry;
            },
            array()
        );
    
        return $env;
    }

    return null;
}
```

### Create publisher object

```$env = \loadTestEnv();
        if (empty($env)) {
            $this->markTestSkipped("no .env defined. Need client ID and secret to continue this test, modify .env.example to .env on test/.env to run test");
        }

        $this->publisher = new Publisher(
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET']
        );
```

There are several features currently supported for ```Publisher``` :

*Submit an article

`$this->publisher->submitArticle($article);`

*List articles

`$this->publisher->listArticle();`


## Steps to contribute :

1. Fork the original repository to your github repository. 

2. Clone from your repository

	```git clone https://github.com/your_username/onesdk.git```

3. To keep up to date with original repository, run this command 
   
   ```git remote add upstream https://github.com/KLYgarage/onesdk.git```

   ```git pull upstream master```

4. Create branch. **Remember, the name of branch should express what you're doing.** 
   
   ```git checkout -b my-topic-branch```

5. Don't forget to install composer dependencies
   
   ```composer install```

6. Modify the .env.example file, to reflect correct credentials. 

   ```
   CLIENT_ID=$CLIENT_ID
   
   CLIENT_SECRET=$CLIENT_SECRET

   To get ACCESS_TOKEN, run the following commands using curl :

   curl -X POST "https://dev.one.co.id/oauth/token" \
	-H "Accept: application/json" \
    -d "grant_type"="client_credentials" \
    -d "client_id"="YOUR CLIENT ID" \
    -d "client_secret"="YOUR CLIENT SECRET";
    
    ```

7. Save the .env.example as .env

8. When you are ready to propose changes to the original repository,  it's time to create pull request. To 
   create pull request, run the following commands :

   ```git push -u origin my-topic-branch```

9. Go to your github account, on tab pull request, add your comment. Be detailed, use imperative, emoticon
   to make it clearer.

10. Watch for feedbacks. 

## PHP CS Fixer

PHP CS Fixer is intended to fix coding standard. So, **Remember!** to always run PHP CS Fixer before you create pull request.
  
  ``` composer run cs-fix ```

## Testing
### How to Run Tests
Open a command prompt or terminal, navigate to project directory and run command `composer run test`
~~~
> php ./phpunit --bootstrap ./test/bootstrap.php ./test/
PHPUnit 4.8.36 by Sebastian Bergmann and contributors.

..................

Time: 14 seconds, Memory: 10.00MB

OK (18 tests, 98 assertions)
~~~
To see what test is running you can use command `composer run test:verbose`
~~~
> php ./phpunit --bootstrap ./test/bootstrap.php ./test/
PHPUnit 4.8.36 by Sebastian Bergmann and contributors.

Starting test 'One\Test\Unit\PublisherTest::testSubmitArticleWithoutAttachment'.
.
Starting test 'One\Test\Unit\PublisherTest::testSubmitArticleWithPhotos'.
.
Starting test 'One\Test\Unit\PublisherTest::testSubmitArticleWithPage'.
.
Starting test 'One\Test\Unit\PublisherTest::testSubmitArticleWithGallery'.
.
Starting test 'One\Test\Unit\PublisherTest::testSubmitArticleWithVideo'.

Time: 12.34 seconds, Memory: 10.00MB

OK (18 tests, 98 assertions)
~~~
### What to Remember When Writing a Test
1. Make sure to create test case for every core function on the class.
2. Always compare data you expected or created before you make a request with the actual data that you get from a response.
3. Use the correct assertion.
Avoid using assertEquals to compare arrays because sometimes you will get array (response from server) values sorted in different   order from your expected array. In example:
~~~
$array = [
  '0'=>'500',
  '1'=>'A'
];

$arrayFromResponse = [
  '0'=>'A',
  '1'=>'500
];
~~~
Rather than sorting `$arrayFromResponse` to make the order equal, we can use `assertTrue` combine with `array_diff`
~~~
assertTrue(empty(array_diff($array, $arrayFromResponse)));
~~~
