# Akismet PHP plugin

## Plugin for Akismet API

How to use:

1. Add to composer.json:

        {
          "require": {
            "createanduse/akismet": "@stable"
          }
        }

2. Run:

        composer update

3. In your code:

        use CreateAndUse\Akismet\Akismet;
        ...
        $akismet = new Akismet($apiKey, $appUrl);

4. Verify key:

        $isVerified = $akismet->verifyKey();

5. Check comment:

      $params = [
        'user_ip' => '127.0.0.1',
        'user_agent' => 'User-Agent',
        'referrer' => 'http://website.lan',
        'permalink' => 'http://website.lan',
        'comment_type' => 'comment',
        'comment_author' => 'username',
        'comment_author_email' => 'john@doe.org',
        'comment_author_url' => 'http://website.lan',
        'comment_content' => 'comment',
        'blog_lang' => 'pl',
        'blog_charset' => 'UTF-8',
      ];
      $isSpam = $akismet->commentCheck($params);

## It's so simple!

More info about params:

https://akismet.com/development/api/#comment-check

https://akismet.com/development/api/#submit-spam

https://akismet.com/development/api/#submit-ham
