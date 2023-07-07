<div class="frame frame-results">
    <div class="frame-contents">
        <div class="frame-resultat-header">
            <p id="resultat-forsakring-type">Trailerförsäkring</p>
            <h2 id="resultat-horse-name">{{ $vehicle['make'] ?? null }} {{ $vehicle['model'] ?? null }}</h2>
        </div>
        <div class="frame-resultat">
            <div class="sammanfattning">
                <h3>Fordonsdata</h3>
                <div class="boxed">
                    <table class="table-resultat">
                        <tr>
                            <th>Registreringsnummer</th>
                            <td>{{ $vehicle['regnr'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Fabrikat</th>
                            <td>{{ $vehicle['make'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Fordonsslag</th>
                            <td>{{ $vehicle['model'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Årsmodell</th>
                            <td>{{ $vehicle['year'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Totalvikt</th>
                            <td>{{ $vehicle['total_weight'] ?? 0 }} kg</td>
                        </tr>
                        <tr>
                            <th>Tjänstevikt</th>
                            <td>{{ $vehicle['service_weight'] ?? 0 }} kg</td>
                        </tr>
                    </table>
                </div>
                <h3>Dina val</h3>
                <div class="boxed">
                    <table class="table-resultat">
                        <tr>
                            <th>Försäkringsform</th>
                            <td>{{ $options['form'] ?? 'Grund' }}</td>
                        </tr>
                        <tr>
                            <th>Säkerhetsanordningar</th>
                            <td>{{ $options['safety'] ?? 'Normal' }}</td>
                        </tr>
                        <tr>
                            <th>Förmånsnivå</th>
                            <td>{{ $options['benefit'] ?? 'Nej' }}</td>
                        </tr>
                        <tr>
                            <th>Startdatum</th>
                            <td>
                                <div class="editinplace">
                                    <input type="text" class="edit" value="{{ $options['date'] ?? null }}" name="startdatum" placeholder="0000-00-00">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <h3>Försäkringstagare</h3>
                <div class="boxed">
                    <table class="table-resultat">
                        <tr>
                            <th>Namn</th>
                            <td>{{ $customer['kund']['namn'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Personnr.</th>
                            <td>{{ $customer['kund']['persnr'] ?? $ssn ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Adress</th>
                            <td>
                                {{ $customer['kund']['adress'] ?? '-' }}<br/>
                                {{ $customer['kund']['postnr'] ?? '-' }} {{ $customer['kund']['ort'] ?? '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>E-post</th>
                            <td>
                                <div class="editinplace">
                                    <input type="text" class="edit" value="{{ $customer['kund']['email'] ?? '' }}" name="email" placeholder="Ange din epost">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Telefon</th>
                            <td>
                                <div class="editinplace">
                                    <input type="tel" class="edit" value="{{ $customer['kund']['telefon'] ?? '' }}" name="telefon" placeholder="Ange ditt mobilnummer">
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <h3>Hur vill du betala?</h3>
                <table class="table-resultat">
                    <tr>
                        <th colspan="2">
                            <p style="font-size:16px;">Betalningssätt</p>
                            <ul class="resultat-slide-select options-2" style="margin-bottom:0;">
                                <li class="selected">
                                    <input checked id="radio-faktura" type="radio" name="betalningsmetod" value="faktura">
                                    <label for="radio-faktura">Faktura</label>
                                </li>
                                <li>
                                    <input id="radio-autogiro" type="radio" name="betalningsmetod" value="autogiro">
                                    <label for="radio-autogiro">Autogiro</label>
                                </li>
                                <div class="marker"></div>
                            </ul>
                        </th>
                    </tr>
                    <tr class="autogiro-wrapper" style="display:none;">
                        <td>
                            <input type="text" value="{{ $customer['betalsatt']['autogiro_clearingnr'] ?? '' }}" name="autogiro_clearing" placeholder="Clearing *">
                        </td>
                        <td>
                            <input type="text" value="{{ $customer['betalsatt']['autogiro_kontonr'] ?? '' }}" name="autogiro_account" placeholder="Kontonummer *">
                        </td>
                    </tr>
                    <tr class="autogiro-wrapper" style="display:none;">
                        <td style="text-align:center;font-size:14px;" colspan="2">
                            Osäker på ditt clearingnr? <span class="btn-sidebar" data-content="clearingnr" style="text-decoration:underline;">klicka här!</span>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <p style="font-size:16px;">Betalningstermin</p>
                            <ul class="resultat-slide-select options-3" style="margin-bottom:0;">
                                <li class="selected">
                                    <input checked id="radio-12" type="radio" name="betalningstermin" value="12">
                                    <label for="radio-12">År</label>
                                </li>
                                <li><input id="radio-3" type="radio" name="betalningstermin" value="3">
                                    <label for="radio-3">Kvartal</label>
                                </li>
                                <li><input id="radio-1" type="radio" name="betalningstermin" value="1">
                                    <label for="radio-1">Månad</label>
                                </li>
                                <div class="marker"></div>
                            </ul>
                        </th>
                    </tr>
                </table>
                <div class="resultat-acceptance-checkbox">
                    <label>
                        <input id="" type="checkbox" name="term" value="1">
                        Jag har läst och förstått <a href="#resultat-footer">villkoren</a>,
                        samt godkänner Dunstans <a href="https://dunstan.se/integritetspolicy/" target="_blank">integritetspolicy</a>
                        för behandling av personuppgifter.
                    </label>
                </div>
                <button type="button" class="btn1 btn-full-width btn-bankid">Signera med BankID</button>
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

<div class="popup-overlay">
    <div id="popup-bankid" class="popup">
        <div class="popup-header">
            <div class="popup-close-x"></div>
        </div>
        <img class="bankid-logo" src="{{ asset('img/bankid-logo.png') }}" alt="BankID-logo">
        <h2>Öppna ditt mobila Bank-ID och signera</h2>
        <div class="bankid-wrapper">
            <div class="bankid-spinner">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
            <a href="bankid:///" class="btn1 btn3 bankid-mobile-sign">Öppna Bank-ID</a>
            <div class="bankid-error" style="display:none;margin-top:20px">
                <p class="bold">Ett fel inträffade vid signeringen. Stäng ner det här fönstret och försök igen.</p>
            </div>
        </div>
        <br/>
    </div>
</div>
<script type="text/javascript">
    function updatePrice()
    {
        let data = {
            'form': '{{ $form }}',
            'safety': '{{ $safety }}',
            'date': '{{ $date }}'
        };

        $.post('/step/trailerforsakring-resultat/price', data, function (data) {
            $('#resultat-widget').html(data.html);
            $('#resultat-price-mobile .price').html(data.price);
        }, 'json');
    }

    function bankid_popup_open() {
        $('#popup-bankid').find('.popup-header').css('display', 'block');
        $('#popup-bankid').find('.bankid-error').css('display', 'none');
        $('#popup-bankid').find('.bankid-spinner').css('display', 'block');
        $('body').addClass('popup-open');
        $('.popup-overlay').fadeIn(500);
        $('#popup-bankid').fadeIn(300);
    }

    function validate_step($button) {
        var buttonOriginalText = $button.html();
        var $form = $('#main-form');
        var data = $form.serialize();

        // Hide previos error message
        $('.error-message-wrapper').slideUp(300);

        // Empty errors
        $('.row-error').removeClass('row-error');
        $('.resultat-acceptance-checkbox').removeClass('error');

        $.ajax({
            type: 'post',
            url: '/step/trailerforsakring-sammanfattning',
            cache: false,
            dataType: 'json',
            data: data,
            beforeSend: function () {
                $button.prop('disabled', true);
                $button.addClass('disabled');
                $button.html('<i class="fa fa-spinner fa-spin"></i> ' + buttonOriginalText);
            },
            success: function (data) {
                if (data.status == 1) {
                    // Open bankid popup
                    bankid_popup_open();
                    bankid_sign($button, buttonOriginalText);
                } else {
                    $button.prop('disabled', false);
                    $button.removeClass('disabled');
                    $button.html(buttonOriginalText);

                    // Handle and display errors
                    if (data.errors) {
                        // Check for custom message
                        let show_custom_message = false;
                        let custom_message = '';

                        $.each(data.errors, function (id, message) {
                            if (id === 'autogiro_error') {
                                custom_message = message[0];
                                show_custom_message = true;
                            }

                            if (id === 'startdatum') {
                                custom_message = message[0];
                                show_custom_message = true;
                            }

                            if (id === 'term') {
                                $('.frame-contents')
                                    .find('input[name=' + id + ']')
                                    .closest('.resultat-acceptance-checkbox')
                                    .addClass('error');
                            } else {
                                $('.frame-contents')
                                    .find('input[name=' + id + '], select[name=' + id + ']')
                                    .closest('tr')
                                    .addClass('row-error');
                            }
                        });

                        if (show_custom_message) {
                            $('.validation-error-message').hide();
                            $('.custom-error-message').html(custom_message).show();
                        } else {
                            $('.custom-error-message').hide();
                            $('.validation-error-message').show();
                        }

                        // set error message
                        let timer_index = Math.floor(Math.random() * 100);

                        $('.error-message-wrapper').attr('data-timer-index', timer_index).slideDown(300);
                        setTimeout(function () {
                            $('.error-message-wrapper[data-timer-index=' + timer_index + ']').slideUp(300);
                        }, 10000);
                    }
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                $button.prop('disabled', false);
                $button.removeClass('disabled');
                $button.html(buttonOriginalText);

                $('.validation-error-message').hide();
                $('.custom-error-message').html('Ett fel har inträffat.').show();
                let timer_index = Math.floor(Math.random() * 100);
                $('.error-message-wrapper').attr('data-timer-index', timer_index).slideDown(300);
                setTimeout(function () {
                    $('.error-message-wrapper[data-timer-index=' + timer_index + ']').slideUp(300);
                }, 10000);
            }
        });
    }

    function bankid_status(orderRef, $button, buttonOriginalText, force_retries) {
        $.ajax({
            type: 'post',
            url: '/step/sammanfattning/bankid_status',
            cache: false,
            headers: {
                'cache-control': 'no-cache'
            },
            dataType: 'json',
            data: {
                orderRef,
                force_retries
            },
            success: function (data) {
                if (data.status == 1) {
                    $button.prop('disabled', false);
                    $button.removeClass('disabled');
                    $button.html(buttonOriginalText);

                    if (data.next_step) {
                        window.konfigurator.changeurl(data.next_step, '#'+data.next_step);
                        window.konfigurator.hash();
                    }
                } else if (data.status == 0) {
                    if (force_retries > 0) {
                        force_retries--;

                        let attempt = parseInt('{{ config('services.focus.bankid_status_force_retries') }}') - force_retries;
                        let retry_time  = attempt * 2000;

                        setTimeout(function() {
                            bankid_status(orderRef, $button, buttonOriginalText, force_retries);
                        }, retry_time);
                    } else {
                        $button.prop('disabled', false);
                        $button.removeClass('disabled');
                        $button.html(buttonOriginalText);

                        $('#popup-bankid').find('.bankid-error').css('display', 'block');
                        $('#popup-bankid').find('.bankid-spinner').css('display', 'none');
                    }
                } else {
                    setTimeout(function() {
                        bankid_status(orderRef, $button, buttonOriginalText, force_retries);
                    }, 1000);
                }
            },
            error: function(xhr, textStatus, errorThrown){
                setTimeout(function() {
                    bankid_status(orderRef, $button, buttonOriginalText, force_retries);
                }, 1000);
            }
        });
    }

    function bankid_sign($button, buttonOriginalText) {
        var $form = $('#main-form');
        var data = $form.serialize();

        $.ajax({
            type: 'post',
            url: '/step/sammanfattning/bankid_sign',
            cache: false,
            headers: {
                'cache-control': 'no-cache'
            },
            dataType: 'json',
            data: data,
            success: function (data) {
                if (data.status == 1) {
                    if (data.orderRef) {
                        let force_retries = parseInt('{{ config('services.focus.bankid_status_force_retries') }}');
                        bankid_status(data.orderRef, $button, buttonOriginalText, force_retries);
                    }
                } else {
                    $button.prop('disabled', false);
                    $button.removeClass('disabled');
                    $button.html(buttonOriginalText);

                    $('#popup-bankid').find('.bankid-error').css('display', 'block');
                    $('#popup-bankid').find('.bankid-spinner').css('display', 'none');
                }
            },
            error: function(xhr, textStatus, errorThrown){
                $button.prop('disabled', false);
                $button.removeClass('disabled');
                $button.html(buttonOriginalText);

                // Error message somewhere?
                $('#popup-bankid').find('.bankid-error').css('display', 'block');
                $('#popup-bankid').find('.bankid-spinner').css('display', 'none');

                //$('body').prepend('XHR: '+xhr+', Status: '+textStatus+', Error: '+errorThrown);
                alert(JSON.stringify(xhr));
            }
        });
    }

    $(document).ready(function () {
        updatePrice();

        $('select').selectric();

        $('.popup-close-x').on('click', function() {
            $(this).parent().fadeOut(300);
            $('.popup-overlay').fadeOut(500);
            $('body').removeClass('popup-open');
        });

        $('input[name=betalningsmetod]').on('change', function () {
            let value = $(this).val();
            if (value === 'autogiro') {
                $('.autogiro-wrapper').css('display', 'table-row');
            } else {
                $('.autogiro-wrapper').css('display', 'none');
            }
        });

        // Remove error
        $('select').on('change', function () {
            $(this).closest('tr').removeClass('row-error');
        });

        $('input[name=term]').on('change', function () {
            $(this).closest('.resultat-acceptance-checkbox').removeClass('error');
        });

        $('input[name=betalningstermin]').on('change', function () {
            let selected = $('input[name=betalningstermin]:checked').val();

            let startPrice = $('#resultat-widget-data').data('price');
            let split = startPrice.split(" kr");
            let trimmed = split[0].replace(" ", "");
            let price;
            let count;

            console.log(trimmed);

            if (selected == 3) {
                count = Math.ceil(trimmed * 3 * 0.99);
                price = addSeprators(count)+' kr/kvartal';
            } else if (selected == 12) {
                count = Math.ceil(trimmed * 12 * 0.97);
                price = addSeprators(count)+' kr/år';
            } else {
                price = startPrice;
            }

            $('.resultat-price').text(price);
        });

        function addSeprators(nStr) {
            nStr += '';
            var x = nStr.split('.');
            var x1 = x[0];
            var x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ' ' + '$2');
            }
            return x1 + x2;
        }

        $('.btn-bankid').on('click', function(e){
            e.preventDefault();
            $button = $(this);
            validate_step($button);
        });

        $('.datepicker').dateDropdowns({
            defaultDateFormat: 'dd-mm-yyyy',
            yearLabel: 'ÅR',
            monthLabel: 'MÅNAD',
            dayLabel: 'DAG',
            minYear: '2002',
            daySuffixes: false
        });

        $('select').selectric();

        $('.resultat-slide-select li')
            .on('mousedown', function () {
                $(this).siblings().removeClass('selected');
                $(this).addClass('selected');
            })
            .on('mouseup', function () {
                $(this).siblings().removeClass('selected');
                $(this).addClass('selected');
            });
    });
</script>
