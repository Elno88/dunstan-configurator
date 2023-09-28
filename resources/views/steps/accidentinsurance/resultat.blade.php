<div class="frame frame-results">
    <div class="frame-contents">
        <div class="frame-resultat-header">
            <div id="resultat-forsakring-type">Olycksfallsförsäkring</div>
            <h2 id="resultat-horse-name">{{ $customer['kund']['namn'] }}</h2>
        </div>
        <div class="frame-resultat">
            <div class="frame-resultat-inputs">
                <h2 class="text-center">Vad ingår?</h2>
                <div class="resultat-select-caption no-fade active">
                    <div class="bullet-lists">
                        <ul class="bullet-list active" style="width:100%;">
                            <li style="font-weight:400;">
                                Medicinsk eller ekonomisk invaliditet:
                                <strong style="font-weight:900;">1 000 000 kr</strong>
                            </li>
                            <li style="font-weight:400;">
                                Tandskada:
                                <strong style="font-weight:900;">50 000 kr</strong>
                            </li>
                            <li style="font-weight:400;">
                                Kostnader som uppstår på grund av olycksfallsskada:
                                <strong style="font-weight:900;">30 000 kr</strong>
                            </li>
                            <li style="font-weight:400;">
                                Ärr och andra utseendemässiga förändringar:
                                <strong style="font-weight:900;">Ingår</strong>
                            </li>
                            <li style="font-weight:400;">
                                Ersättning vid sjukhusvistelse per dygn:
                                <strong style="font-weight:900;">180 kr / dygn</strong>
                            </li>
                            <li style="font-weight:400;">
                                Ersättningen för kläder såsom ridjacka, ridbyxor, kördress och ridhjälm, samt utrustning och handikapphjälpmedel:
                                <strong style="font-weight:900;">25 000 kr</strong>
                            </li>
                            <li style="font-weight:400;">
                                Kristerapi:
                                <strong style="font-weight:900;">10 000 kr</strong>
                            </li>
                            <li style="font-weight:400;">
                                Dödsfall:
                                <strong style="font-weight:900;">50 000 kr</strong>
                            </li>
                        </ul>
                    </div>
                </div>
                <div style="margin-top: 40px; position: relative; display:block; left: 22%; width:50%; border-top: 1px solid var(--color-yellow)"></div>
                <br>
                <div class="resultat-bottom-wrapper">
                    <div class="startdatum-wrapper">
                        <h3>Välj startdatum</h3>
                        <input class="datepicker" type="text" value="{{ $date ?? '' }}" placeholder="åååå-mm-dd" name="startdatum">
                    </div>
                    <div class="uppsagning-options">
                        <h4>Vill du ha hjälp med att säga upp din nuvarande försäkring när den löper ut?</h4>
                        <ul>
                            <li>
                                <input id="uppsagning-1" type="radio" name="uppsagning" value="0" {{ $uppsagning === '0' || !isset($uppsagning) ? 'checked' : null }}>
                                <label for="uppsagning-1">Nej, jag tar hand om det själv</label>
                                <div class="check"></div>
                            </li>
                            <li>
                                <input id="uppsagning-0" type="radio" name="uppsagning" value="1" {{ $uppsagning === '1' ? 'checked' : null }}>
                                <label for="uppsagning-0">Ja, det låter bra!</label>
                                <div class="check"></div>
                            </li>
                        </ul>
                        <div class="resultat-select-caption uppsagning-caption">
                            <p style="font-weight:500;">Hur fungerar det?</p>
                            <p>Du får en trygg övergång med trygghetsgaranti där vi överser att allt går rätt till. Du kommer att bli kontaktad av oss inom några dagar. Vi låter dig digitalt signera en uppsägningsfullmakt som vi sedan skickar in precis innan din nya försäkring börjar gälla. Visst är det skönt när det är enkelt?</p>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn1 btn-next btn-full-width resultat-next">Gå vidare</button>
            </div>
        </div>
    </div>
</div>
<div id="resultat-price-mobile">
    Ditt pris <span class="resultat-price"><span class="price"></span> kr/mån</span>
</div>
<div id="resultat-widget" class="price-wrapper">
    {!! $html ?? '' !!}
</div>

@include('steps.accidentinsurance.resultat.footer')
@include('steps.accidentinsurance.resultat.scripts')

<script type="text/javascript">
    function updatePrice()
    {
        var $form = $('#main-form');
        var data = $form.serialize();

        $.post('/step/olycksfallsforsakring-resultat/price', data, function (data) {
            $('#resultat-widget').html(data.html);
            $('#resultat-price-mobile .price').html(data.price);
        }, 'json');
    }

    function formatNumber(x, seperator) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, seperator);
        return parts.join(".");
    }

    $(document).ready(function () {
        updatePrice();

        $('.datepicker').dateDropdowns({
            defaultDateFormat: 'dd-mm-yyyy',
            yearLabel: 'ÅR',
            monthLabel: 'MÅNAD',
            dayLabel: 'DAG',
            maxYear: '{{ today()->addYears(1)->format('Y') }}',
            minYear: '{{ today()->format('Y') }}',
            daySuffixes: false
        });

        $('select').selectric();

        @if ($uppsagning === '1')
            $(".uppsagning-caption").slideDown(200);
        @endif

        $("input[name='uppsagning']").on("change", function () {
            if ($(this).val() === '1') {
                $(".uppsagning-caption").slideDown(200);
            } else {
                $(".uppsagning-caption").slideUp(200);
            }
        });

        $('input[name="startdatum"]').on('change', function () {
            updatePrice();
        });

        $(".compare-table-more").on("click", function() {
            $(".compare-table-more").fadeOut(300);
            $(".compare-table-more").parent().addClass("show");
        });
    });
</script>
