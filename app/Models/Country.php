<?php

namespace Btcc\Models;

use Collective\Html\HtmlFacade;
use Illuminate\Database\Eloquent\Model;

/**
 * Btcc\Models\Package
 * @property integer        $id
 * @property string         $name
 * @property integer        $start_balance
 * @property string         $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereStartBalance($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Btcc\Models\Package whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Country extends Model {

    public static $countries
        = [

            2   => "Afghanistan",
            5   => "Aland Islands",
            6   => "Albania",
            67  => "Algeria",
            12  => "American Samoa",
            7   => "Andorra",
            3   => "Angola",
            4   => "Anguilla",
            13  => "Antarctica",
            15  => "Antigua and Barbuda",
            10  => "Argentina",
            11  => "Armenia",
            1   => "Aruba",
            16  => "Australia",
            17  => "Austria",
            18  => "Azerbaijan",
            27  => "Bahamas",
            26  => "Bahrain",
            24  => "Bangladesh",
            35  => "Barbados",
            30  => "Belarus",
            20  => "Belgium",
            31  => "Belize",
            21  => "Benin",
            32  => "Bermuda",
            37  => "Bhutan",
            33  => "Bolivia",
            28  => "Bosnia and Herzegovina",
            39  => "Botswana",
            38  => "Bouvet Island",
            34  => "Brazil",
            109 => "British Indian Ocean Territory",
            36  => "Brunei",
            25  => "Bulgaria",
            23  => "Burkina Faso",
            19  => "Burundi",
            123 => "Cambodia",
            48  => "Cameroon",
            41  => "Canada",
            54  => "Cape Verde",
            22  => "Caribbean Netherlands",
            59  => "Cayman Islands",
            40  => "Central African Republic",
            220 => "Chad",
            45  => "Chile",
            46  => "China",
            58  => "Christmas Island",
            43  => "Cocos (Keeling) Islands",
            52  => "Colombia",
            53  => "Comoros",
            50  => "Congo",
            49  => "Congo, Democratic Republic",
            51  => "Cook Islands",
            55  => "Costa Rica",
            47  => "Côte d’Ivoire",
            103 => "Croatia",
            56  => "Cuba",
            57  => "Curacao",
            42  => "Cyberbunker",
            60  => "Cyprus",
            61  => "Czech Republic",
            65  => "Denmark",
            63  => "Djibouti",
            64  => "Dominica",
            66  => "Dominican Republic",
            226 => "East Timor",
            68  => "Ecuador",
            69  => "Egypt",
            204 => "El Salvador",
            92  => "Equatorial Guinea",
            70  => "Eritrea",
            73  => "Estonia",
            74  => "Ethiopia",
            75  => "European Union",
            78  => "Falkland Islands",
            80  => "Faroe Islands",
            77  => "Fiji Islands",
            76  => "Finland",
            79  => "France",
            97  => "French Guiana",
            189 => "French Polynesia",
            14  => "French Southern territories",
            82  => "Gabon",
            90  => "Gambia",
            84  => "Georgia",
            62  => "Germany",
            86  => "Ghana",
            87  => "Gibraltar",
            93  => "Greece",
            95  => "Greenland",
            94  => "Grenada",
            89  => "Guadeloupe",
            98  => "Guam",
            96  => "Guatemala",
            85  => "Guernsey",
            88  => "Guinea",
            91  => "Guinea-Bissau",
            99  => "Guyana",
            104 => "Haiti",
            101 => "Heard Island and McDonald Islands",
            102 => "Honduras",
            100 => "Hong Kong",
            105 => "Hungary",
            113 => "Iceland",
            108 => "India",
            106 => "Indonesia",
            111 => "Iran",
            112 => "Iraq",
            110 => "Ireland",
            107 => "Isle of Man",
            114 => "Israel",
            115 => "Italy",
            116 => "Jamaica",
            119 => "Japan",
            117 => "Jersey",
            118 => "Jordan",
            120 => "Kazakstan",
            121 => "Kenya",
            124 => "Kiribati",
            127 => "Kuwait",
            122 => "Kyrgyzstan",
            128 => "Laos",
            138 => "Latvia",
            129 => "Lebanon",
            135 => "Lesotho",
            130 => "Liberia",
            131 => "Libya",
            133 => "Liechtenstein",
            136 => "Lithuania",
            137 => "Luxembourg",
            139 => "Macao",
            148 => "Macedonia",
            144 => "Madagascar",
            160 => "Malawi",
            161 => "Malaysia",
            145 => "Maldives",
            149 => "Mali",
            150 => "Malta",
            147 => "Marshall Islands",
            158 => "Martinique",
            156 => "Mauritania",
            159 => "Mauritius",
            162 => "Mayotte",
            146 => "Mexico",
            81  => "Micronesia, Federated States",
            143 => "Moldova",
            142 => "Monaco",
            153 => "Mongolia",
            152 => "Montenegro",
            157 => "Montserrat",
            141 => "Morocco",
            155 => "Mozambique",
            151 => "Myanmar",
            163 => "Namibia",
            173 => "Nauru",
            172 => "Nepal",
            170 => "Netherlands",
            8   => "Netherlands Antilles",
            164 => "New Caledonia",
            174 => "New Zealand",
            168 => "Nicaragua",
            165 => "Niger",
            167 => "Nigeria",
            169 => "Niue",
            166 => "Norfolk Island",
            154 => "Northern Mariana Islands",
            185 => "North Korea",
            171 => "Norway",
            175 => "Oman",
            176 => "Pakistan",
            181 => "Palau",
            188 => "Palestine",
            177 => "Panama",
            182 => "Papua New Guinea",
            187 => "Paraguay",
            179 => "Peru",
            180 => "Philippines",
            178 => "Pitcairn",
            183 => "Poland",
            186 => "Portugal",
            184 => "Puerto Rico",
            190 => "Qatar",
            191 => "Réunion",
            192 => "Romania",
            193 => "Russia",
            194 => "Rwanda",
            29  => "Saint Barthelemy",
            200 => "Saint Helena",
            125 => "Saint Kitts and Nevis",
            132 => "Saint Lucia",
            140 => "Saint Martin",
            207 => "Saint Pierre and Miquelon",
            241 => "Saint Vincent and the Grenadines",
            248 => "Samoa",
            205 => "San Marino",
            210 => "Sao Tome and Principe",
            195 => "Saudi Arabia",
            197 => "Senegal",
            208 => "Serbia",
            217 => "Seychelles",
            203 => "Sierra Leone",
            198 => "Singapore",
            216 => "Sint Maarten",
            212 => "Slovakia",
            213 => "Slovenia",
            202 => "Solomon Islands",
            206 => "Somalia",
            250 => "South Africa",
            199 => "South Georgia and the South Sandwich Islands",
            126 => "South Korea",
            209 => "South Sudan",
            72  => "Spain",
            134 => "Sri Lanka",
            196 => "Sudan",
            211 => "Suriname",
            201 => "Svalbard and Jan Mayen",
            215 => "Swaziland",
            214 => "Sweden",
            44  => "Switzerland",
            218 => "Syria",
            232 => "Taiwan",
            223 => "Tajikistan",
            233 => "Tanzania",
            222 => "Thailand",
            221 => "Togo",
            224 => "Tokelau",
            227 => "Tonga",
            228 => "Trinidad and Tobago",
            229 => "Tunisia",
            230 => "Turkey",
            225 => "Turkmenistan",
            219 => "Turks and Caicos Islands",
            231 => "Tuvalu",
            234 => "Uganda",
            235 => "Ukraine",
            9   => "United Arab Emirates",
            83  => "United Kingdom",
            236 => "United States Minor Outlying Islands",
            237 => "Uruguay",
            238 => "USA",
            239 => "Uzbekistan",
            246 => "Vanuatu",
            240 => "Vatican (Holy See)",
            242 => "Venezuela",
            245 => "Vietnam",
            243 => "Virgin Islands, British",
            244 => "Virgin Islands, U.S.",
            247 => "Wallis and Futuna",
            71  => "Western Sahara",
            249 => "Yemen",
            251 => "Zambia",
            252 => "Zimbabwe",
        ];

    public static function pluck()
    {
        $collection = collect(static::$countries);
        return $collection->map(function($item,$key) { return ['id'=>$key,'name'=>$item]; })->pluck('name','id');
    }

}