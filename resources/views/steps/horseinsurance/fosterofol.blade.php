<div class="frame frame-results">
    <div class="frame-contents">

        <div class="frame-resultat-header">
            <p id="resultat-forsakring-type">{{ $horse_usage_label ?? 'Försäkring' }}</p>
            <h2 id="resultat-horse-name">{{ $horse_name ?? 'Hästnamn' }}</h2>
        </div>

        <div class="frame-resultat">

            <div class="frame-resultat-yesno">

                <h2 style="text-align: center;">Hälsoenkät</h2>

                Foster o fölförsäkring kan tecknas tidigast från 40:e dagars dräktighet och senast 30 dagar före beräknad fölning.
                Dräktighetsintyget får ej vara äldre än tre dagar i samband med tecknandet av försäkringen för att visa att stoet varit dräktig under försäkringsperioden.
                Dräktighetsundersökning ska vara utförd enligt något av följande:
                » blodprovsundersökning utförd tidigast på 110:e dräktighetsdagen och halten östronsulfat överstiger 75 nM per liter.
                » manuell dräktighetsundersökning tidigast på 90:e dräktighetsdagen.
                » ultraljudsundersökning utförd tidigast på 40:e dräktighetsdagen.
                Vid ersättningsanspråk från försäkringen ska något av följande kunna uppvisas:
                Vid resorbering (uteblivet föl)
                Dräktighetsintyg ej äldre än tre dagar från försäkringens tecknande samt intyg från veterinär att stoet har resorberat. Eller intyg från veterinär som kan intygar att stoet inte fått något föl denna säsong. Detta ska intygas efter beräknad fölning
                Vid bevisad kastning
                En veterinär, ID-kontrollant eller person som utför nödslakt eller kadaverhämtning ska skriftligen intyga att de har sett samt identifierat det döda fölet.
                Om sto är behandlad för efterbörd så räcker det som intyg från behandlande veterinär.

            </div>

            <div class="resultat-acceptance-checkbox">
                <label><input id="" type="checkbox" name="term" value="1">Jag lovar på heder och samvete att uppgifterna är fullständiga och sanningsenliga.</label>
            </div>

            <br/>

            <button type="button" class="btn1 btn-full-width btn-next">Gå till översikt</button>
        </div>

    </div>
</div>

<div id="resultat-price-mobile">
    Ditt pris <span class="resultat-price"><span class="price">{{ $price['utpris_formaterad'] ?? '' }}</span></span>
</div>

<div id="resultat-widget" class="price-wrapper">
    {!! $price['html'] ?? '' !!}
</div>

@include('steps.horseinsurance.resultat.footer')
@include('steps.horseinsurance.resultat.popup')

@include('steps.horseinsurance.resultat.pris_scripts')

<script type="text/javascript">
    $(document).ready(function(){});
</script>
