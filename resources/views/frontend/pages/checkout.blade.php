@extends('frontend.layouts.master')

@section('title','Checkout page')

@section('main-content')

    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="javascript:void(0)">Checkout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->
            
    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
                @php
                    $selectedCountry = old('country', ($lastOrder ? $lastOrder->country : null) ?? ($user ? ($user->country ?? 'NP') : 'NP'));
                @endphp
                <form class="form" method="POST" action="{{route('cart.order')}}">
                    @csrf
                    <div class="row"> 

                        <div class="col-lg-8 col-12">
                            <div class="checkout-form">
                                <h2>Make Your Checkout Here</h2>
                                <p>Please register in order to checkout more quickly</p>
                                <!-- Form -->
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>First Name<span>*</span></label>
                                            <input type="text" name="first_name" placeholder="" value="{{old('first_name', ($lastOrder ? $lastOrder->first_name : null) ?? $firstName ?? '')}}">
                                            @error('first_name')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Last Name<span>*</span></label>
                                            <input type="text" name="last_name" placeholder="" value="{{old('last_name', ($lastOrder ? $lastOrder->last_name : null) ?? $lastName ?? '')}}">
                                            @error('last_name')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Email Address<span>*</span></label>
                                            <input type="email" name="email" placeholder="" value="{{old('email', ($lastOrder ? $lastOrder->email : null) ?? ($user ? $user->email : ''))}}">
                                            @error('email')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Phone Number <span>*</span></label>
                                            <input type="number" name="phone" placeholder="" required value="{{old('phone', $lastOrder ? $lastOrder->phone : '')}}">
                                            @error('phone')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Country<span>*</span></label>
                                            <select name="country" id="country" class="form-control">
                                                <option value="">Select Country</option>
                                                <option value="AF" {{$selectedCountry == 'AF' ? 'selected' : ''}}>Afghanistan</option>
                                                <option value="AX" { == 'AX' ? 'selected' : ''}>Åland Islands</option>
                                                <option value="AL" { == 'AL' ? 'selected' : ''}>Albania</option>
                                                <option value="DZ" { == 'DZ' ? 'selected' : ''}>Algeria</option>
                                                <option value="AS" { == 'AS' ? 'selected' : ''}>American Samoa</option>
                                                <option value="AD" { == 'AD' ? 'selected' : ''}>Andorra</option>
                                                <option value="AO" { == 'AO' ? 'selected' : ''}>Angola</option>
                                                <option value="AI" { == 'AI' ? 'selected' : ''}>Anguilla</option>
                                                <option value="AQ" { == 'AQ' ? 'selected' : ''}>Antarctica</option>
                                                <option value="AG" { == 'AG' ? 'selected' : ''}>Antigua and Barbuda</option>
                                                <option value="AR" { == 'AR' ? 'selected' : ''}>Argentina</option>
                                                <option value="AM" { == 'AM' ? 'selected' : ''}>Armenia</option>
                                                <option value="AW" { == 'AW' ? 'selected' : ''}>Aruba</option>
                                                <option value="AU" { == 'AU' ? 'selected' : ''}>Australia</option>
                                                <option value="AT" { == 'AT' ? 'selected' : ''}>Austria</option>
                                                <option value="AZ" { == 'AZ' ? 'selected' : ''}>Azerbaijan</option>
                                                <option value="BS" { == 'BS' ? 'selected' : ''}>Bahamas</option>
                                                <option value="BH" { == 'BH' ? 'selected' : ''}>Bahrain</option>
                                                <option value="BD" { == 'BD' ? 'selected' : ''}>Bangladesh</option>
                                                <option value="BB" { == 'BB' ? 'selected' : ''}>Barbados</option>
                                                <option value="BY" { == 'BY' ? 'selected' : ''}>Belarus</option>
                                                <option value="BE" { == 'BE' ? 'selected' : ''}>Belgium</option>
                                                <option value="BZ" { == 'BZ' ? 'selected' : ''}>Belize</option>
                                                <option value="BJ" { == 'BJ' ? 'selected' : ''}>Benin</option>
                                                <option value="BM" { == 'BM' ? 'selected' : ''}>Bermuda</option>
                                                <option value="BT" { == 'BT' ? 'selected' : ''}>Bhutan</option>
                                                <option value="BO" { == 'BO' ? 'selected' : ''}>Bolivia</option>
                                                <option value="BA" { == 'BA' ? 'selected' : ''}>Bosnia and Herzegovina</option>
                                                <option value="BW" { == 'BW' ? 'selected' : ''}>Botswana</option>
                                                <option value="BV" { == 'BV' ? 'selected' : ''}>Bouvet Island</option>
                                                <option value="BR" { == 'BR' ? 'selected' : ''}>Brazil</option>
                                                <option value="IO" { == 'IO' ? 'selected' : ''}>British Indian Ocean Territory</option>
                                                <option value="VG" { == 'VG' ? 'selected' : ''}>British Virgin Islands</option>
                                                <option value="BN" { == 'BN' ? 'selected' : ''}>Brunei</option>
                                                <option value="BG" { == 'BG' ? 'selected' : ''}>Bulgaria</option>
                                                <option value="BF" { == 'BF' ? 'selected' : ''}>Burkina Faso</option>
                                                <option value="BI" { == 'BI' ? 'selected' : ''}>Burundi</option>
                                                <option value="KH" { == 'KH' ? 'selected' : ''}>Cambodia</option>
                                                <option value="CM" { == 'CM' ? 'selected' : ''}>Cameroon</option>
                                                <option value="CA" { == 'CA' ? 'selected' : ''}>Canada</option>
                                                <option value="CV" { == 'CV' ? 'selected' : ''}>Cape Verde</option>
                                                <option value="KY" { == 'KY' ? 'selected' : ''}>Cayman Islands</option>
                                                <option value="CF" { == 'CF' ? 'selected' : ''}>Central African Republic</option>
                                                <option value="TD" { == 'TD' ? 'selected' : ''}>Chad</option>
                                                <option value="CL" { == 'CL' ? 'selected' : ''}>Chile</option>
                                                <option value="CN" { == 'CN' ? 'selected' : ''}>China</option>
                                                <option value="CX" { == 'CX' ? 'selected' : ''}>Christmas Island</option>
                                                <option value="CC" { == 'CC' ? 'selected' : ''}>Cocos [Keeling] Islands</option>
                                                <option value="CO" { == 'CO' ? 'selected' : ''}>Colombia</option>
                                                <option value="KM" { == 'KM' ? 'selected' : ''}>Comoros</option>
                                                <option value="CG" { == 'CG' ? 'selected' : ''}>Congo - Brazzaville</option>
                                                <option value="CD" { == 'CD' ? 'selected' : ''}>Congo - Kinshasa</option>
                                                <option value="CK" { == 'CK' ? 'selected' : ''}>Cook Islands</option>
                                                <option value="CR" { == 'CR' ? 'selected' : ''}>Costa Rica</option>
                                                <option value="CI" { == 'CI' ? 'selected' : ''}>Côte d’Ivoire</option>
                                                <option value="HR" { == 'HR' ? 'selected' : ''}>Croatia</option>
                                                <option value="CU" { == 'CU' ? 'selected' : ''}>Cuba</option>
                                                <option value="CY" { == 'CY' ? 'selected' : ''}>Cyprus</option>
                                                <option value="CZ" { == 'CZ' ? 'selected' : ''}>Czech Republic</option>
                                                <option value="DK" { == 'DK' ? 'selected' : ''}>Denmark</option>
                                                <option value="DJ" { == 'DJ' ? 'selected' : ''}>Djibouti</option>
                                                <option value="DM" { == 'DM' ? 'selected' : ''}>Dominica</option>
                                                <option value="DO" { == 'DO' ? 'selected' : ''}>Dominican Republic</option>
                                                <option value="EC" { == 'EC' ? 'selected' : ''}>Ecuador</option>
                                                <option value="EG" { == 'EG' ? 'selected' : ''}>Egypt</option>
                                                <option value="SV" { == 'SV' ? 'selected' : ''}>El Salvador</option>
                                                <option value="GQ" { == 'GQ' ? 'selected' : ''}>Equatorial Guinea</option>
                                                <option value="ER" { == 'ER' ? 'selected' : ''}>Eritrea</option>
                                                <option value="EE" { == 'EE' ? 'selected' : ''}>Estonia</option>
                                                <option value="ET" { == 'ET' ? 'selected' : ''}>Ethiopia</option>
                                                <option value="FK" { == 'FK' ? 'selected' : ''}>Falkland Islands</option>
                                                <option value="FO" { == 'FO' ? 'selected' : ''}>Faroe Islands</option>
                                                <option value="FJ" { == 'FJ' ? 'selected' : ''}>Fiji</option>
                                                <option value="FI" { == 'FI' ? 'selected' : ''}>Finland</option>
                                                <option value="FR" { == 'FR' ? 'selected' : ''}>France</option>
                                                <option value="GF" { == 'GF' ? 'selected' : ''}>French Guiana</option>
                                                <option value="PF" { == 'PF' ? 'selected' : ''}>French Polynesia</option>
                                                <option value="TF" { == 'TF' ? 'selected' : ''}>French Southern Territories</option>
                                                <option value="GA" { == 'GA' ? 'selected' : ''}>Gabon</option>
                                                <option value="GM" { == 'GM' ? 'selected' : ''}>Gambia</option>
                                                <option value="GE" { == 'GE' ? 'selected' : ''}>Georgia</option>
                                                <option value="DE" { == 'DE' ? 'selected' : ''}>Germany</option>
                                                <option value="GH" { == 'GH' ? 'selected' : ''}>Ghana</option>
                                                <option value="GI" { == 'GI' ? 'selected' : ''}>Gibraltar</option>
                                                <option value="GR" { == 'GR' ? 'selected' : ''}>Greece</option>
                                                <option value="GL" { == 'GL' ? 'selected' : ''}>Greenland</option>
                                                <option value="GD" { == 'GD' ? 'selected' : ''}>Grenada</option>
                                                <option value="GP" { == 'GP' ? 'selected' : ''}>Guadeloupe</option>
                                                <option value="GU" { == 'GU' ? 'selected' : ''}>Guam</option>
                                                <option value="GT" { == 'GT' ? 'selected' : ''}>Guatemala</option>
                                                <option value="GG" { == 'GG' ? 'selected' : ''}>Guernsey</option>
                                                <option value="GN" { == 'GN' ? 'selected' : ''}>Guinea</option>
                                                <option value="GW" { == 'GW' ? 'selected' : ''}>Guinea-Bissau</option>
                                                <option value="GY" { == 'GY' ? 'selected' : ''}>Guyana</option>
                                                <option value="HT" { == 'HT' ? 'selected' : ''}>Haiti</option>
                                                <option value="HM" { == 'HM' ? 'selected' : ''}>Heard Island and McDonald Islands</option>
                                                <option value="HN" { == 'HN' ? 'selected' : ''}>Honduras</option>
                                                <option value="HK" { == 'HK' ? 'selected' : ''}>Hong Kong SAR China</option>
                                                <option value="HU" { == 'HU' ? 'selected' : ''}>Hungary</option>
                                                <option value="IS" { == 'IS' ? 'selected' : ''}>Iceland</option>
                                                <option value="IN" { == 'IN' ? 'selected' : ''}>India</option>
                                                <option value="ID" { == 'ID' ? 'selected' : ''}>Indonesia</option>
                                                <option value="IR" { == 'IR' ? 'selected' : ''}>Iran</option>
                                                <option value="IQ" { == 'IQ' ? 'selected' : ''}>Iraq</option>
                                                <option value="IE" { == 'IE' ? 'selected' : ''}>Ireland</option>
                                                <option value="IM" { == 'IM' ? 'selected' : ''}>Isle of Man</option>
                                                <option value="IL" { == 'IL' ? 'selected' : ''}>Israel</option>
                                                <option value="IT" { == 'IT' ? 'selected' : ''}>Italy</option>
                                                <option value="JM" { == 'JM' ? 'selected' : ''}>Jamaica</option>
                                                <option value="JP" { == 'JP' ? 'selected' : ''}>Japan</option>
                                                <option value="JE" { == 'JE' ? 'selected' : ''}>Jersey</option>
                                                <option value="JO" { == 'JO' ? 'selected' : ''}>Jordan</option>
                                                <option value="KZ" { == 'KZ' ? 'selected' : ''}>Kazakhstan</option>
                                                <option value="KE" { == 'KE' ? 'selected' : ''}>Kenya</option>
                                                <option value="KI" { == 'KI' ? 'selected' : ''}>Kiribati</option>
                                                <option value="KW" { == 'KW' ? 'selected' : ''}>Kuwait</option>
                                                <option value="KG" { == 'KG' ? 'selected' : ''}>Kyrgyzstan</option>
                                                <option value="LA" { == 'LA' ? 'selected' : ''}>Laos</option>
                                                <option value="LV" { == 'LV' ? 'selected' : ''}>Latvia</option>
                                                <option value="LB" { == 'LB' ? 'selected' : ''}>Lebanon</option>
                                                <option value="LS" { == 'LS' ? 'selected' : ''}>Lesotho</option>
                                                <option value="LR" { == 'LR' ? 'selected' : ''}>Liberia</option>
                                                <option value="LY" { == 'LY' ? 'selected' : ''}>Libya</option>
                                                <option value="LI" { == 'LI' ? 'selected' : ''}>Liechtenstein</option>
                                                <option value="LT" { == 'LT' ? 'selected' : ''}>Lithuania</option>
                                                <option value="LU" { == 'LU' ? 'selected' : ''}>Luxembourg</option>
                                                <option value="MO" { == 'MO' ? 'selected' : ''}>Macau SAR China</option>
                                                <option value="MK" { == 'MK' ? 'selected' : ''}>Macedonia</option>
                                                <option value="MG" { == 'MG' ? 'selected' : ''}>Madagascar</option>
                                                <option value="MW" { == 'MW' ? 'selected' : ''}>Malawi</option>
                                                <option value="MY" { == 'MY' ? 'selected' : ''}>Malaysia</option>
                                                <option value="MV" { == 'MV' ? 'selected' : ''}>Maldives</option>
                                                <option value="ML" { == 'ML' ? 'selected' : ''}>Mali</option>
                                                <option value="MT" { == 'MT' ? 'selected' : ''}>Malta</option>
                                                <option value="MH" { == 'MH' ? 'selected' : ''}>Marshall Islands</option>
                                                <option value="MQ" { == 'MQ' ? 'selected' : ''}>Martinique</option>
                                                <option value="MR" { == 'MR' ? 'selected' : ''}>Mauritania</option>
                                                <option value="MU" { == 'MU' ? 'selected' : ''}>Mauritius</option>
                                                <option value="YT" { == 'YT' ? 'selected' : ''}>Mayotte</option>
                                                <option value="MX" { == 'MX' ? 'selected' : ''}>Mexico</option>
                                                <option value="FM" { == 'FM' ? 'selected' : ''}>Micronesia</option>
                                                <option value="MD" { == 'MD' ? 'selected' : ''}>Moldova</option>
                                                <option value="MC" { == 'MC' ? 'selected' : ''}>Monaco</option>
                                                <option value="MN" { == 'MN' ? 'selected' : ''}>Mongolia</option>
                                                <option value="ME" { == 'ME' ? 'selected' : ''}>Montenegro</option>
                                                <option value="MS" { == 'MS' ? 'selected' : ''}>Montserrat</option>
                                                <option value="MA" { == 'MA' ? 'selected' : ''}>Morocco</option>
                                                <option value="MZ" { == 'MZ' ? 'selected' : ''}>Mozambique</option>
                                                <option value="MM" { == 'MM' ? 'selected' : ''}>Myanmar [Burma]</option>
                                                <option value="NA" { == 'NA' ? 'selected' : ''}>Namibia</option>
                                                <option value="NR" { == 'NR' ? 'selected' : ''}>Nauru</option>
                                                <option value="NP" {{$selectedCountry == 'NP' ? 'selected' : ''}}>Nepal</option>
                                                <option value="NL" { == 'NL' ? 'selected' : ''}>Netherlands</option>
                                                <option value="AN" { == 'AN' ? 'selected' : ''}>Netherlands Antilles</option>
                                                <option value="NC" { == 'NC' ? 'selected' : ''}>New Caledonia</option>
                                                <option value="NZ" { == 'NZ' ? 'selected' : ''}>New Zealand</option>
                                                <option value="NI" { == 'NI' ? 'selected' : ''}>Nicaragua</option>
                                                <option value="NE" { == 'NE' ? 'selected' : ''}>Niger</option>
                                                <option value="NG" { == 'NG' ? 'selected' : ''}>Nigeria</option>
                                                <option value="NU" { == 'NU' ? 'selected' : ''}>Niue</option>
                                                <option value="NF" { == 'NF' ? 'selected' : ''}>Norfolk Island</option>
                                                <option value="MP" { == 'MP' ? 'selected' : ''}>Northern Mariana Islands</option>
                                                <option value="KP" { == 'KP' ? 'selected' : ''}>North Korea</option>
                                                <option value="NO" { == 'NO' ? 'selected' : ''}>Norway</option>
                                                <option value="OM" { == 'OM' ? 'selected' : ''}>Oman</option>
                                                <option value="PK" { == 'PK' ? 'selected' : ''}>Pakistan</option>
                                                <option value="PW" { == 'PW' ? 'selected' : ''}>Palau</option>
                                                <option value="PS" { == 'PS' ? 'selected' : ''}>Palestinian Territories</option>
                                                <option value="PA" { == 'PA' ? 'selected' : ''}>Panama</option>
                                                <option value="PG" { == 'PG' ? 'selected' : ''}>Papua New Guinea</option>
                                                <option value="PY" { == 'PY' ? 'selected' : ''}>Paraguay</option>
                                                <option value="PE" { == 'PE' ? 'selected' : ''}>Peru</option>
                                                <option value="PH" { == 'PH' ? 'selected' : ''}>Philippines</option>
                                                <option value="PN" { == 'PN' ? 'selected' : ''}>Pitcairn Islands</option>
                                                <option value="PL" { == 'PL' ? 'selected' : ''}>Poland</option>
                                                <option value="PT" { == 'PT' ? 'selected' : ''}>Portugal</option>
                                                <option value="PR" { == 'PR' ? 'selected' : ''}>Puerto Rico</option>
                                                <option value="QA" { == 'QA' ? 'selected' : ''}>Qatar</option>
                                                <option value="RE" { == 'RE' ? 'selected' : ''}>Réunion</option>
                                                <option value="RO" { == 'RO' ? 'selected' : ''}>Romania</option>
                                                <option value="RU" { == 'RU' ? 'selected' : ''}>Russia</option>
                                                <option value="RW" { == 'RW' ? 'selected' : ''}>Rwanda</option>
                                                <option value="BL" { == 'BL' ? 'selected' : ''}>Saint Barthélemy</option>
                                                <option value="SH" { == 'SH' ? 'selected' : ''}>Saint Helena</option>
                                                <option value="KN" { == 'KN' ? 'selected' : ''}>Saint Kitts and Nevis</option>
                                                <option value="LC" { == 'LC' ? 'selected' : ''}>Saint Lucia</option>
                                                <option value="MF" { == 'MF' ? 'selected' : ''}>Saint Martin</option>
                                                <option value="PM" { == 'PM' ? 'selected' : ''}>Saint Pierre and Miquelon</option>
                                                <option value="VC" { == 'VC' ? 'selected' : ''}>Saint Vincent and the Grenadines</option>
                                                <option value="WS" { == 'WS' ? 'selected' : ''}>Samoa</option>
                                                <option value="SM" { == 'SM' ? 'selected' : ''}>San Marino</option>
                                                <option value="ST" { == 'ST' ? 'selected' : ''}>São Tomé and Príncipe</option>
                                                <option value="SA" { == 'SA' ? 'selected' : ''}>Saudi Arabia</option>
                                                <option value="SN" { == 'SN' ? 'selected' : ''}>Senegal</option>
                                                <option value="RS" { == 'RS' ? 'selected' : ''}>Serbia</option>
                                                <option value="SC" { == 'SC' ? 'selected' : ''}>Seychelles</option>
                                                <option value="SL" { == 'SL' ? 'selected' : ''}>Sierra Leone</option>
                                                <option value="SG" { == 'SG' ? 'selected' : ''}>Singapore</option>
                                                <option value="SK" { == 'SK' ? 'selected' : ''}>Slovakia</option>
                                                <option value="SI" { == 'SI' ? 'selected' : ''}>Slovenia</option>
                                                <option value="SB" { == 'SB' ? 'selected' : ''}>Solomon Islands</option>
                                                <option value="SO" { == 'SO' ? 'selected' : ''}>Somalia</option>
                                                <option value="ZA" { == 'ZA' ? 'selected' : ''}>South Africa</option>
                                                <option value="GS" { == 'GS' ? 'selected' : ''}>South Georgia</option>
                                                <option value="KR" { == 'KR' ? 'selected' : ''}>South Korea</option>
                                                <option value="ES" { == 'ES' ? 'selected' : ''}>Spain</option>
                                                <option value="LK" { == 'LK' ? 'selected' : ''}>Sri Lanka</option>
                                                <option value="SD" { == 'SD' ? 'selected' : ''}>Sudan</option>
                                                <option value="SR" { == 'SR' ? 'selected' : ''}>Suriname</option>
                                                <option value="SJ" { == 'SJ' ? 'selected' : ''}>Svalbard and Jan Mayen</option>
                                                <option value="SZ" { == 'SZ' ? 'selected' : ''}>Swaziland</option>
                                                <option value="SE" { == 'SE' ? 'selected' : ''}>Sweden</option>
                                                <option value="CH" { == 'CH' ? 'selected' : ''}>Switzerland</option>
                                                <option value="SY" { == 'SY' ? 'selected' : ''}>Syria</option>
                                                <option value="TW" { == 'TW' ? 'selected' : ''}>Taiwan</option>
                                                <option value="TJ" { == 'TJ' ? 'selected' : ''}>Tajikistan</option>
                                                <option value="TZ" { == 'TZ' ? 'selected' : ''}>Tanzania</option>
                                                <option value="TH" { == 'TH' ? 'selected' : ''}>Thailand</option>
                                                <option value="TL" { == 'TL' ? 'selected' : ''}>Timor-Leste</option>
                                                <option value="TG" { == 'TG' ? 'selected' : ''}>Togo</option>
                                                <option value="TK" { == 'TK' ? 'selected' : ''}>Tokelau</option>
                                                <option value="TO" { == 'TO' ? 'selected' : ''}>Tonga</option>
                                                <option value="TT" { == 'TT' ? 'selected' : ''}>Trinidad and Tobago</option>
                                                <option value="TN" { == 'TN' ? 'selected' : ''}>Tunisia</option>
                                                <option value="TR" { == 'TR' ? 'selected' : ''}>Turkey</option>
                                                <option value="TM" { == 'TM' ? 'selected' : ''}>Turkmenistan</option>
                                                <option value="TC" { == 'TC' ? 'selected' : ''}>Turks and Caicos Islands</option>
                                                <option value="TV" { == 'TV' ? 'selected' : ''}>Tuvalu</option>
                                                <option value="UG" { == 'UG' ? 'selected' : ''}>Uganda</option>
                                                <option value="UA" { == 'UA' ? 'selected' : ''}>Ukraine</option>
                                                <option value="AE" { == 'AE' ? 'selected' : ''}>United Arab Emirates</option>
                                                <option value="Uk">United Kingdom</option>
                                                <option value="UY" { == 'UY' ? 'selected' : ''}>Uruguay</option>
                                                <option value="UM" { == 'UM' ? 'selected' : ''}>U.S. Minor Outlying Islands</option>
                                                <option value="VI" { == 'VI' ? 'selected' : ''}>U.S. Virgin Islands</option>
                                                <option value="UZ" { == 'UZ' ? 'selected' : ''}>Uzbekistan</option>
                                                <option value="VU" { == 'VU' ? 'selected' : ''}>Vanuatu</option>
                                                <option value="VA" { == 'VA' ? 'selected' : ''}>Vatican City</option>
                                                <option value="VE" { == 'VE' ? 'selected' : ''}>Venezuela</option>
                                                <option value="VN" { == 'VN' ? 'selected' : ''}>Vietnam</option>
                                                <option value="WF" { == 'WF' ? 'selected' : ''}>Wallis and Futuna</option>
                                                <option value="EH" { == 'EH' ? 'selected' : ''}>Western Sahara</option>
                                                <option value="YE" { == 'YE' ? 'selected' : ''}>Yemen</option>
                                                <option value="ZM" { == 'ZM' ? 'selected' : ''}>Zambia</option>
                                                <option value="ZW" { == 'ZW' ? 'selected' : ''}>Zimbabwe</option>
                                            </select>
                                            @error('country')
                                                <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Address Line 1<span>*</span></label>
                                            <input type="text" name="address1" placeholder="" value="{{old('address1', $lastOrder ? $lastOrder->address1 : '')}}">
                                            @error('address1')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Address Line 2</label>
                                            <input type="text" name="address2" placeholder="" value="{{old('address2', $lastOrder ? $lastOrder->address2 : '')}}">
                                            @error('address2')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Postal Code</label>
                                            <input type="text" name="post_code" placeholder="" value="{{old('post_code', $lastOrder ? $lastOrder->post_code : '')}}">
                                            @error('post_code')
                                                <span class='text-danger'>{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                </div>
                                <!--/ End Form -->
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="order-details">
                                <!-- Order Widget -->
                                <div class="single-widget">
                                    <h2>CART  TOTALS</h2>
                                    <div class="content">
                                        <ul>
										    <li class="order_subtotal" data-price="{{Helper::totalCartPrice()}}">Cart Subtotal<span>${{number_format(Helper::totalCartPrice(),2)}}</span></li>
                                            <li class="shipping">
                                                Shipping Cost
                                                @if(count(Helper::shipping())>0 && Helper::cartCount()>0)
                                                    <select name="shipping" class="nice-select">
                                                        <option value="">Select your address</option>
                                                        @foreach(Helper::shipping() as $shipping)
                                                        <option value="{{$shipping->id}}" class="shippingOption" data-price="{{$shipping->price}}">{{$shipping->type}}: ${{$shipping->price}}</option>
                                                        @endforeach
                                                    </select>
                                                @else 
                                                    <span>Free</span>
                                                @endif
                                            </li>
                                            
                                            @if(session('coupon'))
                                            <li class="coupon_price" data-price="{{session('coupon')['value']}}">You Save<span>${{number_format(session('coupon')['value'],2)}}</span></li>
                                            @endif
                                            @php
                                                $total_amount=Helper::totalCartPrice();
                                                if(session('coupon')){
                                                    $total_amount=$total_amount-session('coupon')['value'];
                                                }
                                            @endphp
                                            @if(session('coupon'))
                                                <li class="last"  id="order_total_price">Total<span>${{number_format($total_amount,2)}}</span></li>
                                            @else
                                                <li class="last"  id="order_total_price">Total<span>${{number_format($total_amount,2)}}</span></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <!--/ End Order Widget -->
                                <!-- Order Widget -->
                                <div class="single-widget">
                                    <h2>Payments</h2>
                                    <div class="content">
                                        <div class="checkbox">
                                            {{-- <label class="checkbox-inline" for="1"><input name="updates" id="1" type="checkbox"> Check Payments</label> --}}
                                            <form-group>
                                                <input name="payment_method"  type="radio" value="cod"> <label> Cash On Delivery</label><br>
                                                <input name="payment_method"  type="radio" value="paypal"> <label> PayPal</label> 
                                            </form-group>
                                            
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Order Widget -->
                                <!-- Payment Method Widget -->
                                <div class="single-widget payement">
                                    <div class="content">
                                        <img src="{{('backend/img/payment-method.png')}}" alt="#">
                                    </div>
                                </div>
                                <!--/ End Payment Method Widget -->
                                <!-- Button Widget -->
                                <div class="single-widget get-button">
                                    <div class="content">
                                        <div class="button">
                                            <button type="submit" class="btn">proceed to checkout</button>
                                        </div>
                                    </div>
                                </div>
                                <!--/ End Button Widget -->
                            </div>
                        </div>
                    </div>
                </form>
        </div>
    </section>
    <!--/ End Checkout -->
    
    <!-- Start Shop Services Area  -->
    <section class="shop-services section home">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-rocket"></i>
                        <h4>Free shiping</h4>
                        <p>Orders over $100</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-reload"></i>
                        <h4>Free Return</h4>
                        <p>Within 30 days returns</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-lock"></i>
                        <h4>Sucure Payment</h4>
                        <p>100% secure payment</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service">
                        <i class="ti-tag"></i>
                        <h4>Best Peice</h4>
                        <p>Guaranteed price</p>
                    </div>
                    <!-- End Single Service -->
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Services -->
    
    <!-- Start Shop Newsletter  -->
    <section class="shop-newsletter section">
        <div class="container">
            <div class="inner-top">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2 col-12">
                        <!-- Start Newsletter Inner -->
                        <div class="inner">
                            <h4>Newsletter</h4>
                            <p> Subscribe to our newsletter and get <span>10%</span> off your first purchase</p>
                            <form action="mail/mail.php" method="get" target="_blank" class="newsletter-inner">
                                <input name="EMAIL" placeholder="Your email address" required="" type="email">
                                <button class="btn">Subscribe</button>
                            </form>
                        </div>
                        <!-- End Newsletter Inner -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Shop Newsletter -->
@endsection
@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
	</style>
@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
	<script>
		function showMe(box){
			var checkbox=document.getElementById('shipping').style.display;
			// alert(checkbox);
			var vis= 'none';
			if(checkbox=="none"){
				vis='block';
			}
			if(checkbox=="block"){
				vis="none";
			}
			document.getElementById(box).style.display=vis;
		}
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') ); 
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0; 
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

		});

	</script>

@endpush