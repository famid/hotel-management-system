<?php

use App\Models\AdminSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * @param null $array
 * @return array|bool
 */
function allSetting($array = null)
{
    if (!isset($array[0])) {
        $allSettings = AdminSetting::get();
        if ($allSettings) {
            $output = [];
            foreach ($allSettings as $setting) {
                $output[$setting->slug] = $setting->value;
            }

            return $output;
        }

        return null;
    } elseif (is_array($array)) {
        $allSettings = AdminSetting::whereIn('slug', $array)->get();
        if ($allSettings) {
            $output = [];
            foreach ($allSettings as $setting) {
                $output[$setting->slug] = $setting->value;
            }

            return $output;
        }

        return null;
    } else {
        $allSettings = AdminSetting::where(['slug' => $array])->first();
        if ($allSettings) {
            $output = $allSettings->value;

            return $output;
        }

        return null;
    }
}


/**
 * @param $file
 * @param $destinationPath
 * @param null $oldFile
 * @return bool|string
 */
function uploadFile($file, $destinationPath, $oldFile = null)
{
    if ($oldFile != null) {
        deleteFile($destinationPath, $oldFile);
    }

    $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
    $uploaded = Storage::put($destinationPath . $fileName, file_get_contents($file->getRealPath()));
    if ($uploaded == true) {
        $name = $fileName;
        return $name;
    }
    return false;
}

/**
 * @param $destinationPath
 * @param $file
 */
function deleteFile($destinationPath, $file)
{
    if ($file != null) {
        try {
            Storage::delete($destinationPath . $file);
        } catch (\Exception $e) {

        }
    }
}

/**
 * @return string
 */
function logoPath()
{
    return 'public/logo/';
}

/**
 * @return string
 */
function logoViewPath()
{
    return 'storage/logo/';
}

/**
 * @return string
 */
function imagePath()
{
    return 'public/image/';
}

/**
 * @return string
 */
function imageViewPath()
{
    return 'storage/image/';
}

/**
 * @return string
 */
function emailImageViewPath()
{
    return 'storage/email-image/';
}

/**
 * @return string
 */
function appImagePath()
{
    return 'public/app-image/';
}

/**
 * @return string
 */
function appImageViewPath()
{
    return 'storage/app-image/';
}

/**
 * @param null $input
 * @return array|mixed
 */
function userStatus($input = null)
{
    $output = [
        USER_PENDING_STATUS => __('Inactive'),
        USER_ACTIVE_STATUS => __('Active'),
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

/**
 * @param null $input
 * @return array|mixed
 */
function appointmentStatus($input = null)
{
    $output = [
        APPOINTMENT_PENDING_STATUS => __('Pending'),
        APPOINTMENT_SUCCESS_STATUS => __('Confirmed'),
        APPOINTMENT_REJECT_STATUS => __('Rejected'),
        APPOINTMENT_PENDING_CANCELLATION_STATUS => __('Pending Cancellation'),
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

/**
 * @param null $input
 * @return string|string[]
 */
function weekDays($input = null)
{
    $output = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday'
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

/**
 * @param null $input
 * @return array|mixed
 */
function weekDaysWithLanguage($input = null)
{
    $output = [
        1 => __('Monday'),
        2 => __('Tuesday'),
        3 => __('Wednesday'),
        4 => __('Thursday'),
        5 => __('Friday'),
        6 => __('Saturday'),
        7 => __('Sunday')
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

/**
 * @param int $length
 * @return string
 */
function randomNumber($length = 10)
{
    $x = '123456789';
    $c = strlen($x) - 1;
    $response = '';
    for ($i = 0; $i < $length; $i++) {
        $y = rand(0, $c);
        $response .= substr($x, $y, 1);
    }

    return $response;
}

/**
 * @param $name
 * @return string|string[]|null
 */
function menuName($name)
{
    return preg_replace('/([a-z])([A-Z])/s', '$1 $2', ucfirst($name));
}

/**
 * @return string[]
 */
function country()
{
    $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

    return $countries;
}

/**
 * @param null $input
 * @return string|string[]
 */
function currencySymbol($input = null)
{
    $output = [
        'USD' => "$",
        'EUR' => "&euro;",
    ];

    if (is_null($input)) {
        return $output;
    } else {
        return $output[$input];
    }
}

//Sets language
/**
 * @param null $language
 */
function setLang($language = null)
{
    if (!is_null($language)) {
        $lang = strtolower($language);
        $languages = language();
        if (!in_array($lang, $languages)) {
            $lang = 'en';
        }
    } elseif (Auth::check() && isset(Auth::user()->language) && !empty(Auth::user()->language)) {
        $lang = Auth::user()->language;
    } else {
        $lang = 'en';
    }

    app()->setLocale($lang);
}

// returns all multilingual folder
/**
 * @param null $a
 * @param string $langType
 * @return array|mixed
 */
function language($a = null, $langType = "json")
{//json
    $directories = array();
    $path = base_path('resources/lang');
    if ($langType == 'json') {
        $path .= '/';
        $initial = opendir($path);
        if ($dh = $initial) {
            while (($file = readdir($dh)) !== false) {
                if (strlen($file) == 7 && substr($file, 2) == '.json') {
                    $ab = substr($file, 0, 2);
                    $directories[$ab] = $ab;
                }
            }
            closedir($dh);
        }
    } else {
        $initial = scandir($path);
        foreach ($initial as $init) {
            if ($init != '.' && $init != '..' && strlen($init) == 2 && strpos($init, '.') != true) {
                $directories[$init] = $init;
            }
        }
    }
    if ($a == null) {
        return $directories;
    } else {
        return $directories[$a];
    }
}

/**
 * @param null $name
 * @return array|mixed
 */
function languageFullName($name = null)
{
    $full_name = [
        "en" => __("English"),
        "pt" => __("Portuguese"),
    ];
    asort($full_name);

    return isset($full_name[$name]) ? $full_name[$name] : $full_name;
}
