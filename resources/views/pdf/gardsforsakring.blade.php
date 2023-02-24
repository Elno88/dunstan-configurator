<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Gårdsförsäkring</title>
        <style>
            hr {
                max-width:200px;
                color:#000;
                margin-left:0;
            }
            .bold {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <h1>
            Data från
            {{
                $insurances['farmInsurance']['insurance']['insuranceCompany'] ??
                $insurances['condoInsurance']['insurance']['insuranceCompany'] ??
                $insurances['villaInsurance']['insurance']['insuranceCompany'] ??
                $insurances['accidentInsurance']['insurance']['insuranceCompany'] ??
                ''
            }}
        </h1>
        Hämtat: {{ now()->format('Y-m-d H:i:s') }}<br/>
        Personnummer:
        {{
            $insurances['farmInsurance']['personalInformation']['PERSONAL_NUMBER'] ??
            $insurances['condoInsurance']['personalInformation']['PERSONAL_NUMBER'] ??
            $insurances['villaInsurance']['personalInformation']['PERSONAL_NUMBER'] ??
            $insurances['accidentInsurance']['personalInformation']['PERSONAL_NUMBER'] ??
            ''
        }}

        <br/>

        @if(isset($insurances['farmInsurance']))
            <h2>Gård</h2>
            Försäkringsbolag: {{ $insurances['farmInsurance']['insurance']['insuranceCompany'] ?? '' }}<br/>
            Startdatum: {{ $insurances['farmInsurance']['insurance']['startDate'] ?? '' }}<br/>
            Förnyelsedatum: {{ $insurances['farmInsurance']['insurance']['renewalDate'] ?? '' }}<br/>
            <br/>
            Försäkringsställe: {{ $insurances['farmInsurance']['insurance']['insuranceObjectPropertyCode'] ?? '' }}<br/>
            <hr/>
            Rabatt: {{ $insurances['farmInsurance']['insurance']['discountAmount'] ?? '' }}<br/>
            Rabatt procent: {{ $insurances['farmInsurance']['insurance']['discountPercentage'] ?? '' }}<br/>
            <span class="bold">Pris per år:</span> {{ $insurances['farmInsurance']['insurance']['premiumAmountYearRounded'] ?? '' }}<br/>
        @endif

        <br/>

        @if(isset($insurances['condoInsurance']))
            <h2>Hemförsäkring</h2>
            Försäkringsbolag: {{ $insurances['condoInsurance']['insurance']['insuranceCompany'] ?? '' }}<br/>
            Startdatum: {{ $insurances['condoInsurance']['insurance']['startDate'] ?? '' }}<br/>
            Förnyelsedatum: {{ $insurances['condoInsurance']['insurance']['renewalDate'] ?? '' }}<br/>
            <br/>
            Address: {{ $insurances['condoInsurance']['insurance']['insuranceObjectStreetAddress'] ?? '' }}<br/>
            Postnummer: {{ $insurances['condoInsurance']['insurance']['insuranceObjectPostalCode'] ?? '' }}<br/>
            Stad: {{ $insurances['condoInsurance']['insurance']['insuranceObjectCity'] ?? '' }}<br/>
            Antal boende: {{ $insurances['condoInsurance']['insurance']['numberOfResidents'] ?? '' }}<br/>
            Centralslutet larm: {{ isset($insurances['condoInsurance']['insurance']['connectedAlarmDiscount']) && !empty($insurances['condoInsurance']['insurance']['connectedAlarmDiscount']) ? 'Ja' : 'Nej' }}<br/>
            Personligt lösöre: {{ $insurances['condoInsurance']['insurance']['insuredMovablesAmount'] ?? '' }}<br/>
            @php
                $hem_reseskydd = 'Nej';
                if(isset($insurances['condoInsurance']['addons']) && !empty($insurances['condoInsurance']['addons'])){
                    foreach($insurances['condoInsurance']['addons'] as $addon){
                        if($addon['addOnId'] == 'addOnTravel'){
                            $hem_reseskydd = 'Ja, '.$addon['addOnPrice'].' kr';
                            break;
                        }
                    }
                }
            @endphp
            Utökad reseförsäkring: {{ $hem_reseskydd ?? '' }}<br/>
            <br/>
            @php
                $hem_sjalvrisk = null;
                if(isset($insurances['condoInsurance']['deductibles']) && !empty($insurances['condoInsurance']['deductibles'])){
                    foreach($insurances['condoInsurance']['deductibles'] as $deductibles){
                        if($deductibles['deductibleId'] == 'deductibleMovables'){
                            $hem_sjalvrisk = $deductibles['deductibleAmount'].' SEK';
                            break;
                        }
                    }
                }
            @endphp
            Självrisk: {{ $hem_sjalvrisk ?? '' }}<br/>
            <hr>
            Rabatt: {{ $insurances['condoInsurance']['insurance']['discountAmount'] ?? '' }}<br/>
            Rabatt procent: {{ $insurances['condoInsurance']['insurance']['discountPercentage'] ?? '' }}<br/>
            <span class="bold">Pris per år:</span> {{ $insurances['condoInsurance']['insurance']['premiumAmountYearRounded'] ?? '' }}<br/>
        @endif

        <br/>

        @if(isset($insurances['villaInsurance']))
            <h2>Villa/hus</h2>
            Försäkringsbolag: {{ $insurances['villaInsurance']['insurance']['insuranceCompany'] ?? '' }}<br/>
            Startdatum: {{ $insurances['villaInsurance']['insurance']['startDate'] ?? '' }}<br/>
            Förnyelsedatum: {{ $insurances['villaInsurance']['insurance']['renewalDate'] ?? '' }}<br/>
            <br/>
            Adress: {{ $insurances['villaInsurance']['insurance']['insuranceObjectStreetAddress'] ?? '' }}<br/>
            Postnummer: {{ $insurances['villaInsurance']['insurance']['insuranceObjectPostalCode'] ?? '' }}<br/>
            Försäkringsställe: {{ $insurances['villaInsurance']['insurance']['insuranceObjectPropertyCode'] ?? '' }}<br/>
            Centralslutet larm: {{ isset($insurances['villaInsurance']['insurance']['connectedAlarmDiscount']) && !empty($insurances['villaInsurance']['insurance']['connectedAlarmDiscount']) ? 'Ja' : 'Nej' }}<br/>
            Boyta: {{ $insurances['villaInsurance']['insurance']['livingArea'] ?? '' }}<br/>
            Biyta: {{ $insurances['villaInsurance']['insurance']['ancillaryArea'] ?? '' }}<br/>
            Byggår: {{ $insurances['villaInsurance']['insurance']['constructionYear'] ?? '' }}<br/>
            Våtenheter: {{ $insurances['villaInsurance']['insurance']['numberOfBathrooms'] ?? '' }}<br/>

            @php
                $villa_allrisk = 'Nej';
                if(isset($insurances['villaInsurance']['addons']) && !empty($insurances['villaInsurance']['addons'])){
                    foreach($insurances['villaInsurance']['addons'] as $addon){
                        if($addon['addOnId'] == 'addOnAccidentBuilding'){
                            $villa_allrisk = 'Ja';
                            break;
                        }
                    }
                }
            @endphp
            Allrisk byggnad: {{ $villa_allrisk ?? '' }}<br/>

            @if(isset($insurances['villaInsurance']['deductibles']) && !empty($insurances['villaInsurance']['deductibles']))
                @foreach($insurances['villaInsurance']['deductibles'] as $deductibles)
                    {{ $deductibles['deductibleName'] ?? '' }}: {{ $deductibles['deductibleAmount'] ?? '' }}<br/>
                @endforeach
            @endif

            <br/>
            @if(isset($insurances['villaInsurance']['insurance']['additionalBuildings']) && !empty($insurances['villaInsurance']['insurance']['additionalBuildings']))
                <h3>Övriga byggnader</h3>
                @foreach($insurances['villaInsurance']['insurance']['additionalBuildings'] as $building)
                    Byggnadstyp: {{ $building['buildingType'] ?? '' }}<br/>
                    Byggyta: {{ $building['area'] ?? '' }}<br/>
                @endforeach
                <br/>
            @endif
            <hr>
            Rabatt: {{ $insurances['villaInsurance']['insurance']['discountAmount'] ?? '' }}<br/>
            Rabatt procent: {{ $insurances['villaInsurance']['insurance']['discountPercentage'] ?? '' }}<br/>
            <span class="bold">Pris per år:</span> {{ $insurances['villaInsurance']['insurance']['premiumAmountYearRounded'] ?? '' }}<br/>
        @endif

        <br/>

        @if(isset($insurances['accidentInsurance']))
            <h2>Olycksfall</h2>
            Försäkringsbolag: {{ $insurances['accidentInsurance']['insurance']['insuranceCompany'] ?? '' }}<br/>
            Startdatum: {{ $insurances['accidentInsurance']['insurance']['startDate'] ?? '' }}<br/>
            Förnyelsedatum: {{ $insurances['accidentInsurance']['insurance']['renewalDate'] ?? '' }}<br/>
            <br/>
            Namn: {{ $insurances['accidentInsurance']['insurance']['insuranceHolderName'] ?? '' }} - {{ $insurances['accidentInsurance']['personalInformation']['PERSONAL_NUMBER'] ?? '' }}<br/>
            <hr>
            Rabatt: {{ $insurances['accidentInsurance']['insurance']['discountAmount'] ?? '' }}<br/>
            Rabatt procent: {{ $insurances['accidentInsurance']['insurance']['discountPercentage'] ?? '' }}<br/>
            <span class="bold">Pris per år:</span> {{ $insurances['accidentInsurance']['insurance']['premiumAmountYearRounded'] ?? '' }}<br/>
        @endif

        <br/>
        <br/>

        @if(
            isset($insurances['farmInsurance']) ||
            isset($insurances['condoInsurance']) ||
            isset($insurances['villaInsurance']) ||
            isset($insurances['accidentInsurance'])
        )

            @php
                $price = 0;
                foreach($insurances as $insurance){
                    if(isset($insurance['insurance']['premiumAmountYearRounded']) && !empty($insurance['insurance']['premiumAmountYearRounded'])){
                        $price += $insurance['insurance']['premiumAmountYearRounded'];
                    }
                }
            @endphp

            <h4>Totalt: {{ $price ?? ' - ' }}</h4>
        @endif

    </body>
</html>




