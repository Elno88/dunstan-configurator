<div class="frame frame-results">
    <div class="frame-contents">
        <div class="frame-resultat-header">
            <div id="resultat-forsakring-type">Trailerförsäkring</div>
            <h2 id="resultat-horse-name">{{ $vehicle['make'] ?? null }} {{ $vehicle['model'] ?? null }}</h2>
        </div>
        <div class="frame-resultat">
            <div class="frame-resultat-inputs">
                <h2>Välj och anpassa din försäkring</h2>

                <h4>Försäkringsform <i class="bubble-help btn-sidebar" data-content="trailerforsakring-resultat-1">?</i></h4>
                <p>
                    Välj omfattning på försäkringen. Se i listan nedan vad som ingår.
                </p>
                <ul class="resultat-slide-select form-slide-select options-2">
                    <li class="{{ $form === 'Grund' ? 'selected' : null }}">
                        <input id="form-1" class="filter" type="radio" name="form" value="Grund" {{ $form === 'Grund' ? 'checked' : null }}>
                        <label for="form-1">Grund</label>
                    </li>
                    <li class="{{ $form === 'Premium' || empty($form) ? 'selected' : null }}">
                        <input id="form-2" class="filter" type="radio" name="form" value="Premium" {{ $form === 'Premium' || empty($form) ? 'checked' : null }}>
                        <label for="form-2">Premium</label>
                    </li>
                    <div class="marker"></div>
                </ul>
                <div style="">
                    <div class="resultat-select-caption active" data-type="form">
                        <div class="bullet-lists">
                            <ul class="bullet-list active" data-type="Grund" style="width:45%;">
                                <li>Stöld och rån</li>
                                <li>Tvistemål</li>
                                <li>Brand</li>
                                <li>Blixtnedslag</li>
                                <li>Explosion</li>
                                <li>Kortslutning</li>
                            </ul>
                            <ul class="bullet-list {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium" style="width:45%;">
                                <li>Trafikolycka</li>
                                <li>Skadegörelse</li>
                                <li>Skador orsakade av häst</li>
                                <li>Incident på resa</li>
                            </ul>
                        </div>
                        <div class="compare-table-wrapper">
                            <table class="compare-table compare-table-Premium">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Grund</th>
                                    <th>Premium</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>Stöld och rån</th>
                                    <td><i class="icon icon-check {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Tvistemål</th>
                                    <td><i class="icon icon-check {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Brand</th>
                                    <td><i class="icon icon-check {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Blixtnedslag</th>
                                    <td><i class="icon icon-check {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Explosion</th>
                                    <td><i class="icon icon-check {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Kortslutning</th>
                                    <td><i class="icon icon-check {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Trafikolycka</th>
                                    <td><i class="icon icon-nope {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Skadegörelse</th>
                                    <td><i class="icon icon-nope {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Skador orsakade av häst</th>
                                    <td><i class="icon icon-nope {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                <tr>
                                    <th>Incident på resa</th>
                                    <td><i class="icon icon-nope {{ $form === 'Grund' ? 'active' : null }}" data-type="Grund"></i></td>
                                    <td><i class="icon icon-check {{ $form === 'Premium' ? 'active' : null }}" data-type="Premium"></i></td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="compare-table-more">
                                <span>Visa mer</span>
                            </div>
                        </div>
                    </div>
                </div>

                <h4>Är hästtrailern försedd med säkerhetsbommar? <i class="bubble-help btn-sidebar" data-content="trailerforsakring-resultat-2">?</i></h4>
                <p>&nbsp;</p>
                <ul class="resultat-slide-select safety-slide-select options-2">
                    <li class="{{ $safety === 'Normal' || empty($safety) ? 'selected' : null }}">
                        <input id="safety-1" class="filter" type="radio" name="safety" value="Normal" {{ $safety === 'Normal' || empty($safety) ? 'checked' : null }}>
                        <label for="safety-1">Nej</label>
                    </li>
                    <li class="{{ $safety === 'Säkerhetsbommar' ? 'selected' : null }}">
                        <input id="safety-2" class="filter" type="radio" name="safety" value="Säkerhetsbommar" {{ $safety === 'Säkerhetsbommar' ? 'checked' : null }}>
                        <label for="safety-2">Ja</label>
                    </li>
                    <div class="marker"></div>
                </ul>

                <div class="resultat-formansniva" style="display: block;">
                    <h4>Förmånsninvå <i class="bubble-help btn-sidebar" data-content="trailerforsakring-resultat-3">?</i></h4>
                    <p>Nuvarande försäkringar hos Dunstan:  <strong><span id="benefit">{{ $benefit }}</span></strong></p>
                </div>

                <div style="margin-top: 30px; position: relative; display:block; left: 22%; width:50%; border-top: 1px solid var(--color-yellow)"></div>
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

@include('steps.trailerinsurance.resultat.footer')
@include('steps.trailerinsurance.resultat.scripts')

<script type="text/javascript">
    function updatePrice()
    {
        var $form = $('#main-form');
        var data = $form.serialize();

        $.post('/step/trailerforsakring-resultat/price', data, function (data) {
            $('#resultat-widget').html(data.html);
            $('#resultat-price-mobile .price').html(data.price);
            $('#benefit').html(data.benefit);
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

        $('.frame-resultat-inputs').on('click', 'input[name=safety]', function () {
            updatePrice();

            $('.safety-slide-select').find('input[name=safety]').each(function () {
                $(this).prop('checked', false);
                $(this).parent().removeClass('selected');
            });

            $(this).prop('checked', true);
            $(this).parent().addClass('selected');
        });

        $('.frame-resultat-inputs').on('click', 'input[name=form]', function () {
            updatePrice();

            $('.form-slide-select').find('input[name=form]').each(function () {
                $(this).prop('checked', false);
                $(this).parent().removeClass('selected');
            });

            $(this).prop('checked', true);
            $(this).parent().addClass('selected');

            $('.resultat-select-caption[data-type=form] ul').removeClass('active');
            $('.resultat-select-caption[data-type=form] .compare-table i').removeClass('active');

            if ($(this).val() === 'Grund') {
                $('.resultat-select-caption[data-type=form] ul[data-type="Grund"]').addClass('active');
                $('.resultat-select-caption[data-type=form] i[data-type="Grund"]').addClass('active');
            } else if ($(this).val() === 'Premium') {
                $('.resultat-select-caption[data-type=form] ul[data-type="Grund"]').addClass('active');
                $('.resultat-select-caption[data-type=form] ul[data-type="Premium"]').addClass('active');

                $('.resultat-select-caption[data-type=form] i[data-type="Grund"]').removeClass('active');
                $('.resultat-select-caption[data-type=form] i[data-type="Premium"]').addClass('active');
            }
        });

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
