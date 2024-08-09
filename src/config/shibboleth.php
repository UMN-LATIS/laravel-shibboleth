<?php

return array(
    /*
     Leverages these env settings:
    SHIB_SP_TYPE
    SHIB_EMULATE
    SHIB_ENTITY_ID
    SHIB_ASSERTION_CONSUMER_URL
    SHIB_LOGOUT_SERVICE_URL
    SHIB_X509_CERT
    SHIB_PRIVATE_KEY
    SHIB_IDP_ENTITY
    SHIB_IDP_SSO
    SHIB_IDP_SLO
    SHIB_IDP_SLO_RESPONSE
    SHIB_IDP_X509
    SHIB_IDP_X509_SIGNING
    SHIB_IDP_X509_ENCRYPTION
    */

    
    /*
    |--------------------------------------------------------------------------
    | Views / Endpoints
    |--------------------------------------------------------------------------
    |
    | Set your login page, or login routes, here. If you provide a view,
    | that will be rendered. Otherwise, it will redirect to a route.
    |
     */

     // default to using apache mod_shib
    'sp_type' => env('SHIB_SP_TYPE', 'apache_shib'),
    'local_shib' => [
        'idp_login'     => '/local-sp/Login',
        'idp_logout'    => '/local-sp/Logout',
    ],
    'apache_shib' => [
        'idp_login'     => '/Shibboleth.sso/Login',
        'idp_logout'    => env('SHIB_LOGOUT','/Shibboleth.sso/Logout'),
    ],
    // by default, we'll register the necessary routes. In multitennancy cases, you probably don't want to do this.
    "register_routes" => env('SHIB_REGISTER_ROUTES', true),
    // where should the user be redirected after a successful login
    'authenticated' => '/home',
    // authfield defines the field we should use as the primary key to look up users in our database.
    'authfield'     => env('SHIB_AUTH_FIELD', 'umndid'),
    /*
    |--------------------------------------------------------------------------
    | Emulate an IdP
    |--------------------------------------------------------------------------
    |
    | In case you do not have access to your Shibboleth environment on
    | homestead or your own Vagrant box, you can emulate a Shibboleth
    | environment with the help of Shibalike.
    |
    | Do not use this in production for literally any reason.
    |
     */

    'emulate_idp'       => env('SHIB_EMULATE', false),
    //can also be a url to redirect to
    'emulate_idp_login_view'   => env('SHIB_EMULATE_LOGIN_VIEW', 'vendor.shibalike.IdpLogin'),
    'emulate_idp_users' => array(
        'admin' => array(
            'uid'         => 'admin',
            'displayName' => 'Admin User',
            'givenName'   => 'Admin',
            'sn'          => 'User',
            'mail'        => 'admin@uwm.edu',
            'umndid'        => 'fkEWzvkzzksed',
            'emplid'        => '1111111',
        ),
        'staff' => array(
            'uid'         => 'staff',
            'displayName' => 'Staff User',
            'givenName'   => 'Staff',
            'sn'          => 'User',
            'mail'        => 'staff@uwm.edu',
            'umndid'        => 'dktEfcdzzksed',
            'emplid'        => '2222222',
        ),
        'user'  => array(
            'uid'         => 'user',
            'displayName' => 'User User',
            'givenName'   => 'User',
            'sn'          => 'User',
            'mail'        => 'user@uwm.edu',
            'umndid'        => 'vDSAeszf',
            'emplid'        => '3333333',
        ),
    ),

    /*
    |--------------------------------------------------------------------------
    | Server Variable Mapping
    |--------------------------------------------------------------------------
    |
    | Change these to the proper values for your IdP.
    |
     */

    // Note: when using local-sp, these need to be the full unmapped attributes from Shib    
    'user' => [
        // fillable user model attribute => server variable
        'email'       => env('SHIB_EMAIL_FIELD', 'eppn'),
        'name'        => env('SHIB_NAME_FIELD', 'displayName'),
        'first_name'  => env('SHIB_FIRST_NAME', 'givenName'),
        'last_name'   => env('SHIB_LAST_NAME', 'sn'),
        'umndid'  => env("SHIB_DID", 'umnDID'),
        'emplid'  => env('SHIB_EMPL_ID', 'umnEmplId'),
    ],

    /*
    |--------------------------------------------------------------------------
    | User Creation and Groups Settings
    |--------------------------------------------------------------------------
    |
    | Allows you to change if / how new users are added
    |
     */

    'add_new_users' => true, // Should new users be added automatically if they do not exist?


    /*
     * The rest of these settings have to do with the local SP option.
     * You're required to fill out:
     *  sp.entityId
     *  sp.assertionConsumerService.url
     *  sp.singleLogoutService.url
     *  x509cert
     *  privateKey
     * idp.entityId
     * idp.singleSignOnService.url
     * idp.singleSignOutService.url
     * x509certMulti
     * contactPerson
     * contactOrg
     */
    'local_settings' => array (
        // If 'strict' is True, then the PHP Toolkit will reject unsigned
        // or unencrypted messages if it expects them to be signed or encrypted.
        // Also it will reject the messages if the SAML standard is not strictly
        // followed: Destination, NameId, Conditions ... are validated too.
        'strict' => true,

        // Enable debug mode (to print errors).
        'debug' => false,

        // Set a BaseURL to be used instead of try to guess
        // the BaseURL of the view that process the SAML Message.
        // Ex http://sp.example.com/
        //    http://example.com/sp/
        'baseurl' => null,

        // Service Provider Data that we are deploying.
        'sp' => array (
            // Identifier of the SP entity  (must be a URI)
            'entityId' => env('SHIB_ENTITY_ID', ''),
            // Specifies info about where and how the <AuthnResponse> message MUST be
            // returned to the requester, in this case our SP.
            'assertionConsumerService' => array (
                // URL Location where the <Response> from the IdP will be returned
                'url' => env('SHIB_ASSERTION_CONSUMER_URL', '/local-sp/Login'),
                // SAML protocol binding to be used when returning the <Response>
                // message. OneLogin Toolkit supports this endpoint for the
                // HTTP-POST binding only.
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            ),
            // If you need to specify requested attributes, set a
            // attributeConsumingService. nameFormat, attributeValue and
            // friendlyName can be omitted
            // "attributeConsumingService"=> array(
            //         "serviceName" => "SP test",
            //         "serviceDescription" => "Test Service",
            //         "requestedAttributes" => array(
            //             array(
            //                 "name" => "",
            //                 "isRequired" => false,
            //                 "nameFormat" => "",
            //                 "friendlyName" => "",
            //                 "attributeValue" => array()
            //             )
            //         )
            // ),
            // Specifies info about where and how the <Logout Response> message MUST be
            // returned to the requester, in this case our SP.
            'singleLogoutService' => array (
                // URL Location where the <Response> from the IdP will be returned
                'url' => env('SHIB_LOGOUT_SERVICE_URL', '/local-sp/Logout'),
                // SAML protocol binding to be used when returning the <Response>
                // message. OneLogin Toolkit supports the HTTP-Redirect binding
                // only for this endpoint.
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            // Specifies the constraints on the name identifier to be used to
            // represent the requested subject.
            // Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported.
            'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
            // Usually x509cert and privateKey of the SP are provided by files placed at
            // the certs folder. But we can also provide them with the following parameters
            'x509cert' => env('SHIB_X509_CERT', ''),
            'privateKey' => env('SHIB_PRIVATE_KEY', ''),

            /*
            * Key rollover
            * If you plan to update the SP x509cert and privateKey
            * you can define here the new x509cert and it will be
            * published on the SP metadata so Identity Providers can
            * read them and get ready for rollover.
            */
            // 'x509certNew' => '',
        ),

        // Identity Provider Data that we want connected with our SP.
        'idp' => array (
            // Identifier of the IdP entity  (must be a URI)
            'entityId' => env('SHIB_IDP_ENTITY', ''),
            // SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array (
                // URL Target of the IdP where the Authentication Request Message
                // will be sent.
                'url' => env('SHIB_IDP_SSO', ''),
                // SAML protocol binding to be used when returning the <Response>
                // message. OneLogin Toolkit supports the HTTP-Redirect binding
                // only for this endpoint.
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            // SLO endpoint info of the IdP.
            'singleLogoutService' => array (
                // URL Location of the IdP where SLO Request will be sent.
                'url' => env('SHIB_IDP_SLO', ''),
                // URL location of the IdP where the SP will send the SLO Response (ResponseLocation)
                // if not set, url for the SLO Request will be used
                'responseUrl' => env('SHIB_IDP_SLO_RESPONSE', ''),            
                // SAML protocol binding to be used when returning the <Response>
                // message. OneLogin Toolkit supports the HTTP-Redirect binding
                // only for this endpoint.
                'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
            // Public x509 certificate of the IdP
            // 'x509cert' => env('SHIB_IDP_X509', ''),
            /*
            *  Instead of use the whole x509cert you can use a fingerprint in order to
            *  validate a SAMLResponse, but we don't recommend to use that
            *  method on production since is exploitable by a collision attack.
            *  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it,
            *   or add for example the -sha256 , -sha384 or -sha512 parameter)
            *
            *  If a fingerprint is provided, then the certFingerprintAlgorithm is required in order to
            *  let the toolkit know which algorithm was used. Possible values: sha1, sha256, sha384 or sha512
            *  'sha1' is the default value.
            *
            *  Notice that if you want to validate any SAML Message sent by the HTTP-Redirect binding, you
            *  will need to provide the whole x509cert.
            */
            // 'certFingerprint' => '',
            // 'certFingerprintAlgorithm' => 'sha1',

            /* In some scenarios the IdP uses different certificates for
            * signing/encryption, or is under key rollover phase and
            * more than one certificate is published on IdP metadata.
            * In order to handle that the toolkit offers that parameter.
            * (when used, 'x509cert' and 'certFingerprint' values are
            * ignored).
            */
            'x509certMulti' => array(
                 'signing' => array(
                     0 => env('SHIB_IDP_X509_SIGNING', ''),
                 ),
                 'encryption' => array(
                     0 => env('SHIB_IDP_X509_ENCRYPTION', ''),
                 )
            ),
            ),
            'security' => array(

        /** signatures and encryptions offered */

        // Indicates that the nameID of the <samlp:logoutRequest> sent by this SP
        // will be encrypted.
        'nameIdEncrypted' => false,

        // Indicates whether the <samlp:AuthnRequest> messages sent by this SP
        // will be signed.              [The Metadata of the SP will offer this info]
        'authnRequestsSigned' => true,

        // Indicates whether the <samlp:logoutRequest> messages sent by this SP
        // will be signed.
        'logoutRequestSigned' => false,

        // Indicates whether the <samlp:logoutResponse> messages sent by this SP
        // will be signed.
        'logoutResponseSigned' => false,

        /* Sign the Metadata
         False || True (use sp certs) || array (
                                                    keyFileName => 'metadata.key',
                                                    certFileName => 'metadata.crt'
                                                )
        */
        'signMetadata' => true,

        'wantXMLValidation'=>false,
        /** signatures and encryptions required **/

        // Indicates a requirement for the <samlp:Response>, <samlp:LogoutRequest> and
        // <samlp:LogoutResponse> elements received by this SP to be signed.
        'wantMessagesSigned' => false,

        // Indicates a requirement for the <saml:Assertion> elements received by
        // this SP to be signed.        [The Metadata of the SP will offer this info]
        'wantAssertionsSigned' => false,

        // Indicates a requirement for the NameID received by
        // this SP to be encrypted.
        'wantNameIdEncrypted' => false,

        // Authentication context.
        // Set to false and no AuthContext will be sent in the AuthNRequest,
        // Set true or don't present thi parameter and you will get an AuthContext 'exact' 'urn:oasis:names:tc:SAML:2.0:ac:classes:PasswordProtectedTransport'
        // Set an array with the possible auth context values: array ('urn:oasis:names:tc:SAML:2.0:ac:classes:Password', 'urn:oasis:names:tc:SAML:2.0:ac:classes:X509'),
        'requestedAuthnContext' => false,
    ),

    // Contact information template, it is recommended to suply a technical and support contacts
    'contactPerson' => array(
        'technical' => array(
            'givenName' => 'name',
            'emailAddress' => 'no@reply.com'
        ),
        'support' => array(
            'givenName' => 'Support',
            'emailAddress' => 'no@reply.com'
        ),
    ),

    // Organization information template, the info in en_US lang is recomended, add more if required
    'organization' => array(
        'en-US' => array(
            'name' => 'Name',
            'displayname' => 'Display Name',
            'url' => 'http://url'
        ),
    ),

    )

);
