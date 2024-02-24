<?php
if (env('NOFOLLOW_NOINDEX') == null) {
    $nofollow_noindex = 'yes';
} else {
    $nofollow_noindex = env('NOFOLLOW_NOINDEX');
}
return [
    'languages' => [
        'English' => 'en',
        'Spanish' => 'es',
        'Arabic' => 'ar',
        'Deutsche' => 'de',
        'Français' => 'fr',
        'Português' => 'br',
        'Nederlands' => 'nl',
        'Polskie' => 'pl',
        'Filipino' => 'ph',
        'Italiano' => 'it',
        'čeština' => 'cs',
        'Türk' => 'tr',
        'Svenska' => 'sv',
        'עברית' => 'he',
        'русский' => 'ru',
        'Suomalainen' => 'fi',
        '日本人' => 'ja',
        '한국어' => 'ko',
        'Indonesian' => 'id',
        'norsk' => 'no',
        'Română' => 'ro',
        'Indonasian' => 'id',
        'ไทย' => 'th',
        'العربية' => 'ar',
        'Hrvatski' => 'hr',
        'dansk' => 'da',
        'ქართული' => 'ka',
        'Gaeilge' => 'ga',
        'Tiếng Việt' => 'vi',
        'فارسی' => 'fa',
        '中文' => 'zh',
        'Malay' => 'ms',
    ],
    'only_emd_languages' => [
        'English' => 'en',
        'Arabic' => 'ar',
        // 'Bulgarian' => 'bg',
        // 'Bengali' => 'bn',
        'Brazil' => 'br',
        // 'Catalan' => 'ca',
        'Czech' => 'cs',
        'Danish' => 'da',
        'German' => 'de',
        'Georgia' => 'ka',
        // 'Greek' => 'el',
        'Spanish' => 'es',
        // 'Estonian' => 'et',
        'Finnish' => 'fi',
        'French' => 'fr',
        // 'Gujarati' => 'gu',
        'Hebrew' => 'he',
        // 'Hindi' => 'hi',
        'Croatian' => 'hr',
        'Indonesian' => 'id',
        'Italian' => 'it',
        'Irish' => 'ga',
        'Japanese' => 'ja',
        // 'Kannada' => 'kn',
        'Korean' => 'ko',
        // 'Lithuanian' => 'lt',
        // 'Latvian' => 'lv',
        // 'Malayalam' => 'ml',
        // 'Marathi' => 'mr',
        'Malay' => 'ms',
        'Dutch' => 'nl',
        'Norwegian' => 'no',
        'Persian' => 'fa',
        // 'Punjabi' => 'pa',
        'Polish' => 'pl',
        // 'Portuguese' => 'pt',
        'Romanian' => 'ro',
        'Russian' => 'ru',
        // 'Slovak' => 'sk',
        // 'Slovenian' => 'sl',
        // 'Serbian' => 'sr',
        'Swedish' => 'sv',
        // 'Tamil' => 'ta',
        // 'Telugu' => 'te',
        'Thai' => 'th',
        // 'Tagalog' => 'tl',
        'Turkish' => 'tr',
        // 'Ukrainian' => 'uk',
        // 'Urdu' => 'ur',
        // 'Uzbek' => 'uz',
        'Vietnamese' => 'vi',
        'Chinese' => 'zh',
    ],
    'native_languge' => 'en',
    'nofollow_noindex' => $nofollow_noindex,
    'version' => 'v2.0.9',
    'commit_no' => 9,
    'emd_google_login_callback' => env('EMD_GOOGLE_CALLBACK_URL', null),
    'emd_google_client_id' => env('EMD_GOOGLE_CLIENT_ID', null),
    'emd_google_client_secret' => env('EMD_GOOGLE_CLIENT_SECRET', null),
    'emd_paypro_dynamic_plan_key' => env('PAYPRO_DYNAMIC_PLAN_KEY', null),
    // 'user_ip_get' => 'x-forwarded-for', // for AWS Server use this variable
    'user_ip_get' => 'x-real-ip', // for Cloud ways Server use this variable
];