# Simple Wp Nonce
A wrapper to WP Nonce in a oriented object way. ;)


##Usage:

Create Nonce with Expire and Get the nonce.
```php
use Ludioao\SimpleWpNonce\SimpleWpNonce as SimpleWpNonce;
$timeToExpire = 3600; // 1h - per default: WP uses 1 day.
$simpleNonce = new SimpleWpNonce('testing_job', $timeToExpire); 
$nonce = $simpleNonce->createNonce();
```

Create Nonce Url
```php
$url   = $simpleNonce->createNonceUrl( 'http://inpsyde.com/' );
```

Create a nonce input field. The return is an input field.
```php
$simpleNonce->createNonceField();
```

Verify a nonce:
```php
$nonceValue = $_REQUEST['nonce'];
$simpleNonce = new SimpleWpNonce('testing_job');
if ( $nonce_obj->verifyNonce( $nonceValue ) )
    // Source is true. It means that is verified.
else 
    // Source is false. It means that source is unknown.
```

Check if user is coming from another admin page.
```php
 /* 
 * This is check the current URL 
 * 
 */
 if ($simpleNonce->checkAdminReferral())
    // Its ok 
 else 
    // Its wrong :-( 
 ```
