# simplewpnonce
WP Nonce in a oriented object way


##Usage:

1. Create Nonce with Expire and Get.
```php
use Ludioao\SimpleWpNonce\SimpleWpNonce as SimpleWpNonce;
$timeToExpire = 3600; // 1h - per default: WP uses 1 day.
$simpleNonce = new SimpleWpNonce('testing_job', $timeToExpire); 
$nonce = $simpleNonce->createNonce();
```

2. Create Nonce Url
```php
$url   = $simpleNonce->createNonceUrl( 'http://inpsyde.com/' );
```

3. Create a nonce input field. The return is an input field.
```php
$simpleNonce->createNonceField();
```

4. Verify a nonce:
```php
$nonceValue = $_REQUEST['nonce'];
$simpleNonce = new SimpleWpNonce('testing_job');
if ( $nonce_obj->verifyNonce( $nonceValue ) )
    // Source is true. It means that is verified.
else 
    // Source is false. It means that source is unknown.
```

5. Check if user is coming from another admin page.
 ```php
 /* 
 * This is check the current URL 
 * 
 */
 if ($simpleNonce->checkAdminReferral())
    /* Its ok */
 else 
    /* Its wrong :-( */
 ```
