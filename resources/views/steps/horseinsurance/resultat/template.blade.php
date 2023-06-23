@if((isset($defaults['livforsakring']) && $defaults['livforsakring'] == 38) || (isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 38))
	<div style="display: none;">
@endif

@php
    $sjalvrisk_options = [25, 50];
    if(isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 38){
        $sjalvrisk_options = [25];
    }
@endphp

<h4>Välj självrisk <i class="bubble-help btn-sidebar" data-content="sjalvrisk">?</i></h4>
<p>Vid skador upp till 5 000 SEK är självrisken 50 %. Fast självrisk med ersättning från första kronan. <span style="text-decoration: underline;" class="btn-sidebar" data-content="sjalvrisk">Läs mer</span>.</p>
<ul class="resultat-slide-select options-{{ count($sjalvrisk_options) }}">
    @foreach($sjalvrisk_options as $key => $sjalvrisk)
        <li class="@if(!isset($defaults['sjalvrisk_options']) || is_null($defaults['sjalvrisk_options']) || !in_array($sjalvrisk, $defaults['sjalvrisk_options'])) disabled @endif @if(isset($defaults['sjalvrisk']) && $defaults['sjalvrisk'] == $sjalvrisk) selected @endif"><input id="sjalvrisk-{{ $key }}" class="filter" type="radio" name="sjalvrisk" value="{{ $sjalvrisk }}" @if(!isset($defaults['sjalvrisk_options']) || is_null($defaults['sjalvrisk_options']) || !in_array($sjalvrisk, $defaults['sjalvrisk_options'])) disabled @endif @if(isset($defaults['sjalvrisk']) && $defaults['sjalvrisk'] == $sjalvrisk) checked @endif ><label for="sjalvrisk-{{ $key }}">{{ $sjalvrisk }} %</label></li>
    @endforeach
    <div class="marker" @if(!isset($defaults['sjalvrisk_options']) || is_null($defaults['sjalvrisk_options'])) style="display:none;" @endif></div>
</ul>

<h4>Veterinärvårdsbelopp <i class="bubble-help btn-sidebar" data-content="veterinarvardsbelopp">?</i></h4>
<p>Veterinärvårdsbelopp är det högsta belopp du kan få ut i ersättning för behandlingskostnader hos en veterinär under ett försäkringsår.</p>
<ul class="resultat-slide-select options-{{ count($available['veterinarvardsbelopp']) }}">
    @foreach($available['veterinarvardsbelopp'] as $key => $range)
        <li class="@if(!isset($defaults['veterinarvardsbelopp']) || is_null($defaults['veterinarvardsbelopp'])) disabled @endif @if(isset($defaults['veterinarvardsbelopp']) && $defaults['veterinarvardsbelopp'] == $range){{ 'selected' }}@endif"><input id="vvb-{{ $key }}" type="radio" name="veterinarvardsbelopp" value="{{ $range }}" @if(!isset($defaults['veterinarvardsbelopp']) || is_null($defaults['veterinarvardsbelopp'])) disabled @endif @if(isset($defaults['veterinarvardsbelopp']) && $defaults['veterinarvardsbelopp'] == $range) checked @endif><label for="vvb-{{ $key }}">{{ number_format($range, 0, ',',' ') }} kr</label></li>
    @endforeach
    <div class="marker" @if(is_null($defaults['veterinarvardsbelopp'])) style="display: none;" @endif></div>
</ul>

<h4>Livförsäkring <i class="bubble-help btn-sidebar" data-content="livforsakring">?</i></h4>
<p>Livförsäkringen kan ge dig ersättning om hästen dör eller måste avlivas p.g.a sjukdom eller skada.</p>
<ul class="resultat-slide-select options-{{count($available['livforsakring']['all'])}}" data-type="livforsakring">
    @foreach($available['livforsakring']['all'] as $key => $insurance)
        <li class="@if(!in_array($insurance, $available['livforsakring'][$defaults['veterinarvardsforsakring']])) disabled @endif @if(isset($defaults['livforsakring']) && $defaults['livforsakring'] == $insurance) selected @endif"><input id="liv-{{ $key }}" type="radio" name="livforsakring" value="{{ $insurance }}" @if(!in_array($insurance, $available['livforsakring'][$defaults['veterinarvardsforsakring']])) disabled @endif @if(isset($defaults['livforsakring']) && $defaults['livforsakring'] == $insurance) checked @endif><label for="liv-{{ $key }}">{{ $insurances[$insurance]['name'] }}</label></li>
    @endforeach
    <div class="marker" @if(is_null($defaults['livforsakring'])) style="display: none;" @endif></div>
</ul>

<div class="resultat-select-caption @if(isset($defaults['livforsakring']) && $defaults['livforsakring'] == 12){{ 'active' }}@endif" data-content="vvl-12" data-type="liv">
    <p><strong>Premium Liv</strong> är för dig som önskar teckna vår allra mest omfattande liv & användbarhets&shy;försäkring.</p>
</div>

<div class="resultat-select-caption @if(isset($defaults['livforsakring']) && $defaults['livforsakring'] == 13){{ 'active' }}@endif" data-content="vvl-13" data-type="liv">
    <p><strong>Special Liv</strong> är för dig som önskar teckna en mer omfattande liv & användbarhets&shy;försäkring för din häst. Detta vår mest kompletta försäkring för trav- och galopphästar.</p>
</div>

<div class="resultat-select-caption @if(isset($defaults['livforsakring']) && $defaults['livforsakring'] == 16){{ 'active' }}@endif" data-content="vvl-16" data-type="liv">
    <p><strong>Breeding Liv</strong> är för dig som önska teckna en omfattande liv & användbarhets&shy;försäkring för avelshästar som vill ha ett bra skydd med användbarhets&shy;försäkring för sto och hingst. Försäkringen kan ge ersättning om hästen dör, insjuknar eller skadas så svårt att den måste avlivas eller helt eller delvis förlorar sin användbarhet.</p>
</div>

<div class="resultat-select-caption @if(isset($defaults['livforsakring']) && $defaults['livforsakring'] == 17){{ 'active' }}@endif" data-content="vvl-17" data-type="liv">
    <p><strong>Grund Liv</strong> är för dig som vill teckna en livförsäkring med en bra och grundläggande omfattning men utan användbarhets&shy;försäkring.</p>
</div>

<div class="resultat-select-caption @if(isset($defaults['livforsakring']) && $defaults['livforsakring'] == 14){{ 'active' }}@endif" data-content="vvl-14" data-type="liv">
    <p><strong>Katastrof</strong> är för dig som vill teckna en enklare liv- och veterinärvårdsförsäkring som ger ersättning vid avlivning eller veterinärvård i samband med olycka.</p>
</div>

<div class="resultat-select-caption @if(isset($defaults['livforsakring']) && $defaults['livforsakring'] == 38){{ 'active' }}@endif" data-content="vvl-38" data-type="liv">
    <p><strong>Foster & Föl</strong> är för dig som vill teckna en omfattande försäkring för ditt tilltänkta föl samt veterinärvård och liv för fölet. Försäkringen kan ge ersättning vid resorption och kastning samt om fölet behöver undersökas, behandlas och vårdas av veterinär vid de flesta skador och sjukdomar de första 30 levnadsdagarna eller blivit enskilt försäkrad.</p>
</div>

@if((isset($defaults['livforsakring']) && $defaults['livforsakring'] == 38) || (isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 38))
	@include('steps.horseinsurance.resultat.safestart')
@endif

<h4>Livvärde: <output class="range-label">{{ number_format($defaults['livvarde'] ?? 0, 0, '.', ' ') }}</output> <i class="bubble-help btn-sidebar" data-content="livvarde">?</i></h4>
<p>Livvärdet är inköpspriset eller det marknadsmässiga belopp som din häst värderas till vid tecknandet av försäkringen.</p>
<div id="livvarde-slider" class="range-slider"></div>
<input type="hidden" name="livvarde" value="{{ $defaults['livvarde'] ?? 0 }}" />

<div class="livvarde-error">
    <p>För att teckna en livförsäkring med ett livvärde över <span class="livvarde-max"></span> kr, vänligen kontakta vårt servicecenter på <a href="tel:0101798400">010-179 84 00</a> eller <a href="mailto:info@dunstan.se">info@dunstan.se</a>, så hjälper våra försäkringsrådgivare dig.</p>
</div>

@if((isset($defaults['livforsakring']) && $defaults['livforsakring'] == 38) || (isset($defaults['veterinarvardsforsakring']) && $defaults['veterinarvardsforsakring'] == 38))

<br/>

<div class="safestart-livvarde-options resultat-select-caption">

	<p>Vill du bibehålla livvärdet efter att fölet fötts?</p>

	<ul>
		<li>
			<input id="livvarde-0" type="radio" name="safestart-liv" value="" checked>
			<label for="livvarde-0">Nej</label>
			<div class="check"></div>
		</li>
		<li>
			<input id="livvarde-1" type="radio" name="safestart-liv" value="">
			<label for="livvarde-1">Ja</label>
			<div class="check"></div>
		</li>
	</ul>

</div>

<div class="safestart-info">
	<h4>Detta ingår: <i class="bubble-help btn-sidebar" data-content="livforsakring">?</i></h4>

	<ul>
		<li>Veterinärvårdsförsäkring: <strong>100 000 kr</strong></li>
		<li>Livförsäkring: <strong>5 000 kr</strong></li>
		<li>Självrisk: <strong>25 %</strong></li>
	</ul>

	<br/>

	<div class="safestart-info-extra" style="display:none;">
		<div class="safestart-info-inner" style="display:none;">

			<strong>När fölet fötts</strong>
			<ul>
				<li>Safe Start Veterinärvårdsförsäkring: <strong>100 000 kr</strong></li>
				<li>Safe Start Livförsäkring: <strong><span class="liv-sum">5 000</span> kr</strong> <span class="liv-split">(5 000 kr + 10 000 kr)</span></li>
				<li>Självrisk: <strong>25 %</strong></li>
			</ul>

		</div>
	</div>
	
</div>
@endif

<div class="forsakring-enabled">
    <hr/>
    <h4>Dina försäkringar</h4>
    <div class="forsakring-enabled-wrapper">
        {!! $price['html_boxes'] ?? '' !!}
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.resultat-slide-select li')
            .on('mousedown', function() {
                $(this).siblings().removeClass('selected');
                $(this).addClass('selected');
            })
            .on('mouseup', function() {
                $(this).siblings().removeClass('selected');
                $(this).addClass('selected');
            });

        $('input[name=livforsakring]').on('change', function(){
            let value = $(this).val();
            $('.resultat-select-caption[data-type=liv]').removeClass('active');
            $('.resultat-select-caption[data-type=liv][data-content=vvl-'+value+']').addClass('active');

            if (value == 12) {
                $('.range-slider').slider('option', 'max', 105000);
                $('.range-slider').slider('value', {{ $available['livvarde'][0] }});
            } else {
                $('.range-slider').slider('option', 'max', {{ $available['livvarde'][1] + 5000 }});
                $('.range-slider').slider('value', {{ $available['livvarde'][0] }});
            }

            $('.livvarde-error').removeClass('active');
            $('.resultat-next').prop('disabled', false);
        });

        $('.safestart-select').on('click', function(){
            let value = $(this).attr('data-selected');

            $('.safestart-select').removeClass('active');
            $(this).addClass('active');
            $('.safestart-caption').removeClass('active');
            $('.safestart-caption[data-content=safestart-'+value+']').addClass('active');
            $('input[name="safestart"]').trigger('click');
        });

        $('.range-slider').slider( {
            @if(empty($defaults['livvarde']))
            disabled: true,
            @endif
            value: parseInt({{ $defaults['livvarde'] ?? $available['livvarde'][0] }}),
            min: parseInt({{ $available['livvarde'][0] }}),
            max: parseInt(105000),
            step: parseInt({{ $available['livvarde_increment'] ?? 5000 }}),
            change: function(event, ui) {
                $('.range-label').text(formatNumber(ui.value,' '));
                $('input[name=livvarde]').val(ui.value);
                if (ui.value >= 5000 ){
                    //$('.safestart-livvarde-options').slideDown(200);
                    //$('.safestart-info-extra').slideDown(200);
                } else {
                    //$('.safestart-livvarde-options').slideUp(200);
                    //$('.safestart-info-extra').slideUp(200);
                }
            },
            slide: function(event, ui) {
                $('.range-label').text(formatNumber(ui.value,' '));
                $('input[name=livvarde]').val(ui.value);
                if (ui.value >= 5000 ){
                	//$('.safestart-livvarde-options').slideDown(200);
                	//$('.safestart-info-extra').slideDown(200);
                } else {
                	//$('.safestart-livvarde-options').slideUp(200);
                	//$('.safestart-info-extra').slideUp(200);
                }
            },
            stop: function(e, ui) {
                let product = $('input[name=livforsakring]:checked').val();

                let max_value = (product == 12) ? 100000 : parseInt({{ $available['livvarde'][1] }});
                var rest = (ui.value - 5000);

                if(ui.value > max_value){
                    $('.livvarde-max').text(max_value);
                    $('.livvarde-error').addClass('active');
                    $('.liv-split').text('');
                    $('.resultat-next').prop('disabled', true);
                } else {
                    $('.livvarde-error').removeClass('active');
                    $('.resultat-next').prop('disabled', false);
                }
                if(ui.value < 5000 ){
                   $('#livvarde-0').click();
                }
                if (ui.value >= 5000 ) {
                	$('.liv-sum').text(formatNumber(ui.value,' '));
                	$('.liv-split').text('(5 000 kr + '+formatNumber(rest,' ')+' kr)');
                }
                update_price();
            }
        }).slider( 'pips', {
            first: 'pips',
            last: 'pips',
        });

        $("input[name='safestart-liv']").on("change", function() {
        	$(".safestart-info-inner").slideToggle(200);
        });

    });
</script>
