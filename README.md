Laravel Shibboleth Service Provider
===================================

This package provides an easy way to implement Shibboleth Authentication for 6, 7 and 8. It can either use an existing Shibboleth SP (like apache mod_shib) or it can serve as its own Shib SP. 

## Installation ##

Because this is a fork, you'll need to add the fork to your composer.json file. Add this entry to your repositories array:

```json
    {
      "type": "vcs",
      "url": "https://github.com/UMN-LATIS/laravel-shibboleth"
    },
```

Use [composer][1] to require the latest release into your project:

    composer require "razorbacks/laravel-shibboleth":"dev-umn"


Publish the default configuration file and entitlement migrations:

    php artisan vendor:publish --provider="StudentAffairsUwm\Shibboleth\ShibbolethServiceProvider"

You can also publish the views for the shibalike emulated IdP login:

    php artisan vendor:publish --provider="StudentAffairsUwm\Shibboleth\ShibalikeServiceProvider"


Change the driver to `shibboleth` in
your `config/auth.php` file.

```php
'providers' => [
    'users' => [
        'driver' => 'shibboleth',
        'model'  => App\User::class,
    ],
],
```



## Configuration ##

The published `shibboleth.php` config file allows for a wide range of customization. If you're leveraging the local-sp functionality, you'll need to provide a variety of information about your Shibboleth IdP. If you're leveraging the Apache shibboleth SP, you should only need to verify the Shibboleth attributes that you'll be using. 

For local development, add `SHIB_EMULATE=true` to your .env file. This will enable you to login with any of the users defined in the `emulate_idp_users` array of shibboleth.php. You may add additional users if you wish.

UMN users: the shibboleth.php local-sp config is mostly ready for the UMN IdP. You'll need to provide your private key and cert in the .env file. See the example below.

```env
SHIB_SP_TYPE=local_shib
SHIB_EMULATE=false
SHIB_ENTITY_ID=your-shibboleth-entity
SHIB_ASSERTION_CONSUMER_URL=https://your-hostname/local-sp/ACS
SHIB_LOGOUT_SERVICE_URL=https://your-hostname/local-sp/Logout
SHIB_X509_CERT="Certificate here, no line returns"
SHIB_PRIVATE_KEY="Private Key Here, no Line Returns"
SHIB_IDP_ENTITY=https://idp2.shib.umn.edu/idp/shibboleth
SHIB_IDP_SSO=https://login.umn.edu/idp/profile/SAML2/Redirect/SSO
SHIB_IDP_SLO=https://login.umn.edu/idp/profile/SAML2/Redirect/SLO
SHIB_IDP_X509_SIGNING="MIIDHzCCAgegAwIBAgIUah2ROh5+3z9VKbgAKYi4SezMYjwwDQYJKoZIhvcNAQEL\nBQAwGDEWMBQGA1UEAwwNbG9naW4udW1uLmVkdTAeFw0xNjA2MjkxNzQ4MTRaFw0z\nNjA2MjkxNzQ4MTRaMBgxFjAUBgNVBAMMDWxvZ2luLnVtbi5lZHUwggEiMA0GCSqG\nSIb3DQEBAQUAA4IBDwAwggEKAoIBAQClv5lE5Zxd/r1Yq3/72oszyYiLtZO+dD2y\nn8pyOJPndzewSMWtbvO0UWQssYMx6jZ/MsPbySgnuP/FZCUyISs6oSVzPkSLwulv\nSbbG5+VPouoxR1u2+POWw6KXQ5Yy/ZMIj9w6XF0PWiQx+NCZwV39r+oNgi9SY3zl\nsa00bLfp1+gqho2rzA/jkud2ZCzK58Cerit4CBSma1atSYGLoFIWpG9bk3TFFZXs\ngAiP7hzmzgtt1fD9560psgviUR1iydV+xcVzAz/MVzTyKWdi0Z4lyOocUfkZKh33\nvQYzqq4J0wxjaICdJAzciM+CsOU/HN1hyEqDn8jwgAeWwFckbXFdAgMBAAGjYTBf\nMB0GA1UdDgQWBBTsPJYGoIIIMAoU9dcM4Yjw4RO06TA+BgNVHREENzA1gg1sb2dp\nbi51bW4uZWR1hiRodHRwczovL2xvZ2luLnVtbi5lZHUvaWRwL3NoaWJib2xldGgw\nDQYJKoZIhvcNAQELBQADggEBAFSp+18J5bVS+NDfJzwRYizcchBYSLFdLBwXXYM3\nvxe5JLB2eKOkaxMLmxWYuAvLTXS2tuCmjHsEknl9L8o6ETbYi4yIeXRysmCpANiU\n6T5e0Btwf9CIA35BefOr4MRcnoiRdA0w0NVvjzK/6cVJyyiYp3Ywpp5zmqCHnV5A\n1o5YTNP2ewuMoDdbjdEo+eZFaga12owt642uiLh5TjdRZ3H70HtuXNlDcE7JLOt6\n6aEJfHfnJV15VwUrztmn9cBF9Bx0ognJkZmQUpqvF5jSYPmPaSakENimUHLbqKg6\npJMA7hVJR3RTayarc64cm7vRNPn2FC6WBd39++5+wF2Yhoc="
SHIB_IDP_X509_ENCRYPTION="MIIDHzCCAgegAwIBAgIUSM2i3FZUSYOcPx+9WwQrsSRtYC8wDQYJKoZIhvcNAQEL\nBQAwGDEWMBQGA1UEAwwNbG9naW4udW1uLmVkdTAeFw0xNjA2MjkxNzQ5MzhaFw0z\nNjA2MjkxNzQ5MzhaMBgxFjAUBgNVBAMMDWxvZ2luLnVtbi5lZHUwggEiMA0GCSqG\nSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCLDAv0oprzAfYf1jlqqY3msym9MqE1OSvJ\nK+AryDVcx6GD+U0Bp96fxjvA1w5jvt+0LQnrYkAQChvj0uRxzH1R8VYqfnBlCHgw\n6P1aOrppu7jjiJEvNKDxwVz+lDdHWMM+PBLW3ye7DeZs6U0onxIdiNubmSAw+M6R\nGuC5B/FEsmx32AEk69hPW6syxgzfsp0RLJtAna6ZgppPZ6QsSJgcGHkcNZLLzSJj\nYBpZXgt4Jk7BT03O/41mXiu77sie8gVkiL7so6cg4SvZNks/oPx1uULP6qaHbmRq\n8mTJEoO8WIiNSIPGSCIn/uguizk6ZDwvKQ5dTq9P/MFB5DJ4VeznAgMBAAGjYTBf\nMB0GA1UdDgQWBBR1Yf/+kEdXFb0khrjxSleVRoGS6zA+BgNVHREENzA1gg1sb2dp\nbi51bW4uZWR1hiRodHRwczovL2xvZ2luLnVtbi5lZHUvaWRwL3NoaWJib2xldGgw\nDQYJKoZIhvcNAQELBQADggEBAGsEfHxJWYMyVKHcm4h9lzwzTScjRopdaG9CgsC5\nB4Q2JhZfijiBQwADQH9NLiYA7iIW2qPG8/qmVmcHRa/0JxB16s5EQ984oTX5JL4N\nHA50P1L8CR86zpDr/dbAtePQqB/1+nEMOAyIxXcuJbQF7Slt55X8gk8j5yW6ILGx\n3p4lpQ7yv1z8cLYZrxRrCY4MJqxw1udbJNjUgXQ6kkNZYfxFM4SnaSukVvEk1IyK\nzGLokdPcU8d99asDyUD3czSfGmcPx1CorIqnyWN12MEiO7ganj8ftRpVkpMJ75Sq\nhE9g7oWFs2lrdJWeBrk+rYesB1SfrzYiFh7bgUvEfNj1ZDY="
```

Also, if you use the built in local-sp module for metadata generation by accessing hostname/local-sp/Metadata, **be sure to remove md:NameIDFormat from the metadata** before submitting it to OIT.

### CSRF expiration

If you're using the local SP option, you'll need to modify `app/Http/Middleware/VerifyCsrfToken.php` within your Laravel project, adding an exception for the assertion consumer. 

```
protected $except = [
    '/local-sp/ACS'
];
```

## Usage ##

If everything is right with the world, users may now login via Shibboleth by going to `https://example.com/shibboleth-login`
and logout using `https://example.com/shibboleth-logout` so you can provide a custom link
or redirect based on email address in the login form.

```php
@if (Auth::guest())
    <a href="/shibboleth-login">Login</a>
@else
    <a href="/shibboleth-logout">
        Logout {{ Auth::user()->name }}
    </a>
@endif
```

You may configure server variable mappings in `config/shibboleth.php` such as
the user's first name, last name, entitlements, etc. You can take a look at them
by reading what's been populated into the `$_SERVER` variable after authentication.

```php
<?php print_r($_SERVER);
```

## Local Users

This was designed to work side-by-side with the native authentication system
for projects where you want to have both Shibboleth and local users.
If you would like to allow local registration as well as authenticate Shibboleth
users, then use laravel's built-in auth system.

    php artisan make:auth


[1]:https://getcomposer.org/
