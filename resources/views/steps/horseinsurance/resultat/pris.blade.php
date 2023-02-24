<div id="resultat-widget-data" data-horseusage="{{ $horse_usage ?? '0' }}" data-price="{{ $utpris ?? '' }}">
    <div class="resultat-widget-contents">
        <div class="inner">

        	@if(isset($points))
                <div class="resultat-widget-points">
                    <div>Teckna nu och få</div>
                    <div class="resultat-points">{{ $points ?? 0 }} poäng</div>
                </div>
            @endif

            @include('steps.horseinsurance.resultat.pris_boxes', ['dummy' => true])

            @include('steps.horseinsurance.resultat.pris_jamfor')

            <header class="resultat-widget-header">
            	<div class="inner">
	                <p style="margin-bottom:6px;">Ditt pris<p>
	                <p class="resultat-price">{{ $utpris_formaterad ?? '' }}</p>
	            </div>
            </header>

        </div>
    </div>
</div>
