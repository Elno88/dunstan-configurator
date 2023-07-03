<div class="frame frame-results">
    <div class="frame-contents">

        <div class="frame-resultat-header">
            <div id="resultat-forsakring-price">Ditt pris <span class="price">{{ $price['utpris_formaterad'] ?? '' }}</span></div>
            <div id="resultat-forsakring-type">{{ $horse_usage_label ?? 'Trailerförsäkring' }}</div>
            <div id="resultat-horse-name"><!-- Fabrikat och modell här --></div>
        </div>

        <div class="resultat-widget-points mobile">
          <div>Teckna nu och få</div>
          <div class="resultat-points"><span class="resultat-points-int">{{ $price['points'] ?? 0 }}</span> poäng</div>
          @include('steps.horseinsurance.resultat.pris_jamfor')
        </div>

        <div class="frame-resultat" data-horseusage="{{ $horse_usage ?? '0' }}">

          <div class="frame-resultat-inputs">

            <h2>Välj och anpassa din försäkring</h2>

            <h4>Säkerhetsanordningar <i class="bubble-help btn-sidebar" data-content="sakerhetsanordningar">?</i></h4>
            <p>Lorem ipsum dolor sit amet, consectetur dipiscing elit. Integer sollicitudin est sed iaculis luctus. In est ipsum, mattis venenatis mi eget, varius varius nibh.

            <ul class="resultat-slide-select options-{{ count($sjalvrisk_options) }}">
              @foreach($sjalvrisk_options as $key => $sjalvrisk)
                  <li class="@if(!isset($defaults['sjalvrisk_options']) || is_null($defaults['sjalvrisk_options']) || !in_array($sjalvrisk, $defaults['sjalvrisk_options'])) disabled @endif @if(isset($defaults['sjalvrisk']) && $defaults['sjalvrisk'] == $sjalvrisk) selected @endif"><input id="sjalvrisk-{{ $key }}" class="filter" type="radio" name="sjalvrisk" value="{{ $sjalvrisk }}" @if(!isset($defaults['sjalvrisk_options']) || is_null($defaults['sjalvrisk_options']) || !in_array($sjalvrisk, $defaults['sjalvrisk_options'])) disabled @endif @if(isset($defaults['sjalvrisk']) && $defaults['sjalvrisk'] == $sjalvrisk) checked @endif ><label for="sjalvrisk-{{ $key }}">{{ $sjalvrisk }} %</label></li>
              @endforeach
              <div class="marker" @if(!isset($defaults['sjalvrisk_options']) || is_null($defaults['sjalvrisk_options'])) style="display:none;" @endif></div>
            </ul>

            
            <h4>Försäkringsform <i class="bubble-help btn-sidebar" data-content="trailerforsakring">?</i></h4>
            <p>Lorem ipsum dolor sit amet, consectetur dipiscing elit. Integer sollicitudin est sed iaculis luctus. In est ipsum, mattis venenatis mi eget, varius varius nibh.</p>

            <ul class="resultat-slide-select options-{{count($available['veterinarvardsforsakring'])}}">
              @foreach($available['veterinarvardsforsakring'] as $key => $insurance)
                  <li class="@if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == $insurance){{ 'selected' }}@endif"><input id="vvf-{{$insurance}}" type="radio" name="veterinarvardsforsakring" value="{{ $insurance }}" @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == $insurance){{ 'checked' }}@endif ><label for="vvf-{{ $insurance }}">{{ $insurances[$insurance]['name'] }}</label></li>
              @endforeach
              <div class="marker"></div>
            </ul>

            <div class="resultat-select-caption @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 4){{ 'active' }}@endif" data-content="vvf-4" data-type="vet">

              <div class="bullet-lists">

                <p class="resultat-select-caption-title">Grund</p>

                <ul class="bullet-list active" data-type="grund">
                  <li>Stöld och rån</li>
                  <li>Tvistemål</li>
                  <li>Brand</li>
                  <li>Blixtnedslag</li>
                  <li>Explosion</li>
                  <li>Kortslutning</li>
                </ul>

                <ul class="bullet-list active" data-type="premium">
                  <li>Trafikolycka</li>
                  <li>Skadegörelse</li>
                  <li>Skador orsakade av häst</li>
                  <li>Incident på resa</li>
                </ul>

              </div>

              <div class="compare-table-wrapper">

                <table class="compare-table compare-table-premium">
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
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Tvistemål</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Brand</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Blixtnedslag</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Explosion</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Kortslutning</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Trafikolycka</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Skadegörelse</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Skador orsakade av häst</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                    <tr>
                      <th>Incident på resa</th>
                      <td><i class="icon icon-check"></i></td>
                      <td><i class="icon icon-check active"></i></td>
                    </tr>
                  </tbody>
                </table>

                <div class="compare-table-more">
                  <span>Visa mer</span>
                </div>

              </div>

            </div>

            <div class="resultat-select-caption @if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 6){{ 'active' }}@endif" data-content="vvf-6" data-type="vet">

              <div class="bullet-lists">

                <p class="resultat-select-caption-title">Grund</p>

                <ul class="bullet-list active" data-type="grund">
                  <li>Stöld och rån</li>
                  <li>Tvistemål</li>
                  <li>Brand</li>
                  <li>Blixtnedslag</li>
                  <li>Explosion</li>
                  <li>Kortslutning</li>
                </ul>

                <ul class="bullet-list active" data-type="premium">
                  <li>Trafikolycka</li>
                  <li>Skadegörelse</li>
                  <li>Skador orsakade av häst</li>
                  <li>Incident på resa</li>
                </ul>

              </div>

              <div class="compare-table-wrapper">

                <table class="compare-table compare-table-grund">
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
                      <td><i class="icon icon-check active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Tvistemål</th>
                      <td><i class="icon icon-check active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Brand</th>
                      <td><i class="icon icon-check active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Blixtnedslag</th>
                      <td><i class="icon icon-check active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Explosion</th>
                      <td><i class="icon icon-check active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Kortslutning</th>
                      <td><i class="icon icon-check active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Trafikolycka</th>
                      <td><i class="icon icon-nope active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Skadegörelse</th>
                      <td><i class="icon icon-nope  active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Skador orsakade av häst</th>
                      <td><i class="icon icon-nope active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                    <tr>
                      <th>Incident på resa</th>
                      <td><i class="icon icon-nope active"></i></td>
                      <td><i class="icon icon-check"></i></td>
                    </tr>
                  </tbody>
                </table>

                <div class="compare-table-more">
                  <span>Visa mer</span>
                </div>

              </div>

            </div>

            <div class="resultat-formansniva" style="display: block;">

               <!-- Förmånsnivå. Validering mot Focus: moment 22, 26 och 31 -->

              <h4>Förmånsninvå <i class="bubble-help btn-sidebar" data-content="formansniva">?</i></h4>
              <p>Eftersom du har både häst och gårdsförsäkring hos oss ger vi dig ett ännu bättre pris.</p>

            </div>

            <br/>

            <div class="resultat-bottom-wrapper">

              <div class="startdatum-wrapper">

              <h3>Välj startdatum</h3>

              <input id="" class="datepicker" type="text" value="{{ $startdatum ?? '' }}" placeholder="åååå-mm-dd" name="startdatum">

            </div>

            <div class="uppsagning-options">

              <h4>Vill du ha hjälp med att säga upp din nuvarande försäkring när den löper ut?</h4>

              <ul>
                <li>
                  <input id="uppsagning-1" type="radio" name="uppsagning" value="0" checked>
                  <label for="uppsagning-1">Nej, jag tar hand om det själv</label>
                  <div class="check"></div>
                </li>
                <li>
                  <input id="uppsagning-0" type="radio" name="uppsagning" value="1">
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
    Ditt pris <span class="resultat-price"><span class="price">{{ $price['utpris_formaterad'] ?? '' }}</span></span>
</div>

<div id="resultat-widget" class="price-wrapper">
    {!! $price['html'] ?? '' !!}
</div>

@include('steps.trailerinsurance.footer-resultat')
@include('steps.horseinsurance.resultat.popup')

@include('steps.horseinsurance.resultat.pris_scripts')

<script type="text/javascript">
    function update_price()
    {
        var $form = $('#main-form');
        var data = $form.serialize();

        $.post('/step/resultat/get_price', data, function(data){
            $('.price-wrapper').html(data.html);
            $('.price').html(data.utpris_formaterad);
            $('.resultat-points-int').html(data.points);
            $('.forsakring-enabled-wrapper').html(data.html_boxes);
        }, 'json');
        console.log('update_price');
    }

    function formatNumber(x, seperator) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, seperator);
        return parts.join(".");
    }

    $(document).ready(function(){

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

        $('.frame-resultat-inputs').on('click', 'input[name=veterinarvardsforsakring]', function(){

            var $form = $('#main-form');
            var data = $form.serialize();
            var selected = $(this).attr('id');

            $.post('/step/resultat/reload_template', data, function(data){
                $('.frame-resultat-template').html(data.html);
                $('.price-wrapper').html(data.price.html);
                $('.price').html(data.price.utpris_formaterad);
                $('.resultat-points-int').html(data.price.points);
            }, 'json');

            $('.resultat-select-caption[data-type=vet]').removeClass('active');
            $('.resultat-select-caption[data-type=vet][data-content='+selected+']').addClass('active');
        });

        // Update price
        $('.frame-resultat-template').on('change', 'input', function(){
            // update price here
            let name = $(this).attr('name');
            if(name === 'forsakring_enabled[vet]' || name === 'forsakring_enabled[liv]'){
                let value = $(this).val();
                let checked = $(this).prop('checked');
                let forsakring_enabled_length = $('.forsakring-enabled-wrapper').find('input[name^=forsakring_enabled]:checked').length;
                if(forsakring_enabled_length === 0){
                    $('.forsakring-enabled-wrapper').find('input[name^=forsakring_enabled]').each(function(){
                        let value2 = $(this).val();
                        if(value !== value2){
                            $(this).prop('checked', true);
                            $(this).removeClass('selected').addClass('selected');
                            return false;
                        }
                    });
                }

                // remove or add selected
                if(checked){
                    $(this).removeClass('selected').addClass('selected');
                } else {
                    $(this).removeClass('selected');
                }
            }
            update_price();
        });

        // Update price if checkbox is checked/unchecked dummy
        $('.price-wrapper').on('change', 'input', function(e){
            let name = $(this).attr('name');
            let checked = $(this).prop('checked');
            let value = $(this).val();
            let forsakring_enabled_length = $('.price-wrapper').find('input[name^=forsakring_enabled]:checked').length;
            if(forsakring_enabled_length === 0){
                $('.price-wrapper').find('input[name^=forsakring_enabled]').each(function(){
                    let value2 = $(this).val();
                    if(value !== value2){
                        $(this).prop('checked', true);
                        return false;
                    }
                });
            }
            if(name === 'forsakring_enabled_dummy[liv]'){
                $('.forsakring-enabled-wrapper').find('input[name=forsakring_enabled\\\[liv\\\]]').prop('checked', checked).trigger('change');
            } else if(name === 'forsakring_enabled_dummy[vet]'){
                $('.forsakring-enabled-wrapper').find('input[name=forsakring_enabled\\\[vet\\\]]').prop('checked', checked).trigger('change');
            }

            // remove or add selected
            if(checked){
                $(this).removeClass('selected').addClass('selected');
            } else {
                $(this).removeClass('selected');
            }

        });

        $("input[name='uppsagning']").on("change", function() {
          $(".uppsagning-caption").slideToggle(200);
        });

        $(".compare-table-more").on("click", function() {
          $(".compare-table-more").fadeOut(300);
          $(".compare-table-more").parent().addClass("show");
        });

        $('input[name="startdatum"]').on('change', function() {
           update_price();
        });

        $('input[name="swbmedlem"]').on('change', function() {
           update_price();
           console.log('change');
        });


        // Bugfix
        setTimeout(function(){
            $('input[name=veterinarvardsforsakring]:checked').trigger('click');
        }, 500);

    });

</script>
