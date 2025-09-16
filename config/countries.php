<?php
// config/countries.php

return [

    'supported' => [

        // --- 🇪🇺 European Union (27) ---
        'AT' => ['name' => 'Austria',         'emoji' => '🇦🇹', 'prefix' => '+43'],
        'BE' => ['name' => 'Belgium',         'emoji' => '🇧🇪', 'prefix' => '+32'],
        'BG' => ['name' => 'Bulgaria',        'emoji' => '🇧🇬', 'prefix' => '+359'],
        'HR' => ['name' => 'Croatia',         'emoji' => '🇭🇷', 'prefix' => '+385'],
        'CY' => ['name' => 'Cyprus',          'emoji' => '🇨🇾', 'prefix' => '+357'],
        'CZ' => ['name' => 'Czech Republic',  'emoji' => '🇨🇿', 'prefix' => '+420'],
        'DK' => ['name' => 'Denmark',         'emoji' => '🇩🇰', 'prefix' => '+45'],
        'EE' => ['name' => 'Estonia',         'emoji' => '🇪🇪', 'prefix' => '+372'],
        'FI' => ['name' => 'Finland',         'emoji' => '🇫🇮', 'prefix' => '+358'],
        'FR' => ['name' => 'France',          'emoji' => '🇫🇷', 'prefix' => '+33'],
        'DE' => ['name' => 'Germany',         'emoji' => '🇩🇪', 'prefix' => '+49'],
        'GR' => ['name' => 'Greece',          'emoji' => '🇬🇷', 'prefix' => '+30'],
        'HU' => ['name' => 'Hungary',         'emoji' => '🇭🇺', 'prefix' => '+36'],
        'IE' => ['name' => 'Ireland',         'emoji' => '🇮🇪', 'prefix' => '+353'],
        'IT' => ['name' => 'Italy',           'emoji' => '🇮🇹', 'prefix' => '+39'],
        'LV' => ['name' => 'Latvia',          'emoji' => '🇱🇻', 'prefix' => '+371'],
        'LT' => ['name' => 'Lithuania',       'emoji' => '🇱🇹', 'prefix' => '+370'],
        'LU' => ['name' => 'Luxembourg',      'emoji' => '🇱🇺', 'prefix' => '+352'],
        'MT' => ['name' => 'Malta',           'emoji' => '🇲🇹', 'prefix' => '+356'],
        'NL' => ['name' => 'Netherlands',     'emoji' => '🇳🇱', 'prefix' => '+31'],
        'PL' => ['name' => 'Poland',          'emoji' => '🇵🇱', 'prefix' => '+48'],
        'PT' => ['name' => 'Portugal',        'emoji' => '🇵🇹', 'prefix' => '+351'],
        'RO' => ['name' => 'Romania',         'emoji' => '🇷🇴', 'prefix' => '+40'],
        'SK' => ['name' => 'Slovakia',        'emoji' => '🇸🇰', 'prefix' => '+421'],
        'SI' => ['name' => 'Slovenia',        'emoji' => '🇸🇮', 'prefix' => '+386'],
        'ES' => ['name' => 'Spain',           'emoji' => '🇪🇸', 'prefix' => '+34'],
        'SE' => ['name' => 'Sweden',          'emoji' => '🇸🇪', 'prefix' => '+46'],

        // --- 🌍 Africa (Francophone ~21) ---
        'BJ' => ['name' => 'Benin',             'emoji' => '🇧🇯', 'prefix' => '+229'],
        'BF' => ['name' => 'Burkina Faso',      'emoji' => '🇧🇫', 'prefix' => '+226'],
        'BI' => ['name' => 'Burundi',           'emoji' => '🇧🇮', 'prefix' => '+257'],
        'CM' => ['name' => 'Cameroon',          'emoji' => '🇨🇲', 'prefix' => '+237'],
        'CF' => ['name' => 'Central African Republic', 'emoji' => '🇨🇫', 'prefix' => '+236'],
        'TD' => ['name' => 'Chad',              'emoji' => '🇹🇩', 'prefix' => '+235'],
        'KM' => ['name' => 'Comoros',           'emoji' => '🇰🇲', 'prefix' => '+269'],
        'CG' => ['name' => 'Congo - Brazzaville','emoji' => '🇨🇬', 'prefix' => '+242'],
        'CD' => ['name' => 'Congo - Kinshasa',  'emoji' => '🇨🇩', 'prefix' => '+243'],
        'CI' => ['name' => 'Côte d’Ivoire',     'emoji' => '🇨🇮', 'prefix' => '+225'],
        'DJ' => ['name' => 'Djibouti',          'emoji' => '🇩🇯', 'prefix' => '+253'],
        'GA' => ['name' => 'Gabon',             'emoji' => '🇬🇦', 'prefix' => '+241'],
        'GN' => ['name' => 'Guinea',            'emoji' => '🇬🇳', 'prefix' => '+224'],
        'GW' => ['name' => 'Guinea-Bissau',     'emoji' => '🇬🇼', 'prefix' => '+245'],
        'MG' => ['name' => 'Madagascar',        'emoji' => '🇲🇬', 'prefix' => '+261'],
        'ML' => ['name' => 'Mali',              'emoji' => '🇲🇱', 'prefix' => '+223'],
        'MR' => ['name' => 'Mauritania',        'emoji' => '🇲🇷', 'prefix' => '+222'],
        'NE' => ['name' => 'Niger',             'emoji' => '🇳🇪', 'prefix' => '+227'],
        'RW' => ['name' => 'Rwanda',            'emoji' => '🇷🇼', 'prefix' => '+250'],
        'SN' => ['name' => 'Senegal',           'emoji' => '🇸🇳', 'prefix' => '+221'],
        'SC' => ['name' => 'Seychelles',        'emoji' => '🇸🇨', 'prefix' => '+248'],
        'TG' => ['name' => 'Togo',              'emoji' => '🇹🇬', 'prefix' => '+228'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Country
    |--------------------------------------------------------------------------
    | Fallback si aucun pays détecté automatiquement
    */
    'default' => 'DE',

    'africa_iso2' => [
        'DZ','AO','BJ','BW','BF','BI','CM','CV','CF','TD','KM','CG','CD','CI','DJ','EG','GQ','ER','ET',
        'GA','GM','GH','GN','GW','KE','LS','LR','LY','MG','MW','ML','MR','MU','MA','MZ','NA','NE','NG',
        'RW','RE','SH','ST','SN','SC','SL','SO','ZA','SS','SD','TZ','TG','TN','UG','EH','ZM','ZW',
    ],

];
