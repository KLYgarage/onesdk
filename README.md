# One.co.id PHP SDK

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/KLYgarage/onesdk/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/KLYgarage/onesdk/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/KLYgarage/onesdk/badges/build.png?b=master)](https://scrutinizer-ci.com/g/KLYgarage/onesdk/build-status/master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/KLYgarage/onesdk/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

This SDK is used for easier intergration and API usage of ONE.co.id SDK. For publisher only. Usage still limited, check docs for supported features.

## Installation

If you are using composer you could get it with `composer require kly/onesdk` and you are all set. Load up the autoloader and Call the classes or factory you need.

## Usage

Example Usage:

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
