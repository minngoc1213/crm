<?php

/**
 * libphonenumber-for-php-lite data file
 * This file has been @generated from libphonenumber data
 * Do not modify!
 * @internal
 */

return [
    'generalDesc' => [
        'NationalNumberPattern' => '(?:[58]\\d\\d|684|900)\\d{7}',
        'PossibleLength' => [
            10,
        ],
        'PossibleLengthLocalOnly' => [
            7,
        ],
    ],
    'fixedLine' => [
        'NationalNumberPattern' => '6846(?:22|33|44|55|77|88|9[19])\\d{4}',
        'ExampleNumber' => '6846221234',
        'PossibleLength' => [],
        'PossibleLengthLocalOnly' => [
            7,
        ],
    ],
    'mobile' => [
        'NationalNumberPattern' => '684(?:2(?:48|5[2468]|7[26])|7(?:3[13]|70|82))\\d{4}',
        'ExampleNumber' => '6847331234',
        'PossibleLength' => [],
        'PossibleLengthLocalOnly' => [
            7,
        ],
    ],
    'tollFree' => [
        'NationalNumberPattern' => '8(?:00|33|44|55|66|77|88)[2-9]\\d{6}',
        'ExampleNumber' => '8002123456',
        'PossibleLength' => [],
        'PossibleLengthLocalOnly' => [],
    ],
    'premiumRate' => [
        'NationalNumberPattern' => '900[2-9]\\d{6}',
        'ExampleNumber' => '9002123456',
        'PossibleLength' => [],
        'PossibleLengthLocalOnly' => [],
    ],
    'sharedCost' => [
        'PossibleLength' => [
            -1,
        ],
        'PossibleLengthLocalOnly' => [],
    ],
    'personalNumber' => [
        'NationalNumberPattern' => '52(?:3(?:[2-46-9][02-9]\\d|5(?:[02-46-9]\\d|5[0-46-9]))|4(?:[2-478][02-9]\\d|5(?:[034]\\d|2[024-9]|5[0-46-9])|6(?:0[1-9]|[2-9]\\d)|9(?:[05-9]\\d|2[0-5]|49)))\\d{4}|52[34][2-9]1[02-9]\\d{4}|5(?:00|2[125-9]|33|44|66|77|88)[2-9]\\d{6}',
        'ExampleNumber' => '5002345678',
        'PossibleLength' => [],
        'PossibleLengthLocalOnly' => [],
    ],
    'voip' => [
        'PossibleLength' => [
            -1,
        ],
        'PossibleLengthLocalOnly' => [],
    ],
    'pager' => [
        'PossibleLength' => [
            -1,
        ],
        'PossibleLengthLocalOnly' => [],
    ],
    'uan' => [
        'PossibleLength' => [
            -1,
        ],
        'PossibleLengthLocalOnly' => [],
    ],
    'voicemail' => [
        'PossibleLength' => [
            -1,
        ],
        'PossibleLengthLocalOnly' => [],
    ],
    'noInternationalDialling' => [
        'PossibleLength' => [
            -1,
        ],
        'PossibleLengthLocalOnly' => [],
    ],
    'id' => 'AS',
    'countryCode' => 1,
    'internationalPrefix' => '011',
    'nationalPrefix' => '1',
    'nationalPrefixForParsing' => '([267]\\d{6})$|1',
    'nationalPrefixTransformRule' => '684$1',
    'sameMobileAndFixedLinePattern' => false,
    'numberFormat' => [],
    'intlNumberFormat' => [],
    'mainCountryForCode' => false,
    'leadingDigits' => '684',
    'mobileNumberPortableRegion' => false,
];
