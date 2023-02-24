<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="gardsforsakring-b-3">?</i>
                @if(isset($insurances) && !empty($insurances))
                    <p>Vi har hämtat information om följande försäkringar:</p>
                @else
                    Vi kunde tyvärr inte hitta några försäkringar.
                @endif
            </div>
        </div>

         <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                @foreach($insurances as $key => $insurance)
                	<div class="insurance-info">
	                    @if($key == 'farmInsurance')
	                        Gårdsförsäkring<br/>
	                    @elseif($key == 'villaInsurance')
	                        Villa/hus försäkring<br/>
	                    @elseif($key == 'condoInsurance')
	                        Hemförsäkring<br/>
	                    @elseif($key == 'accidentInsurance')
	                        Olycksfallsförsäkring<br/>
	                    @endif

	                    <span>
	                        @if(isset($insurance['insurance']['premiumAmountYearRounded']) && !empty($insurance['insurance']['premiumAmountYearRounded']))
	                            {{ $insurance['insurance']['premiumAmountYearRounded'] }} SEK
	                        @else
	                            Data saknas
	                        @endif
	                    </span>

	                </div>
                @endforeach
            </div>
        </div>

        <button type="button" class="btn1 btn-next">Gå vidare</button>

    </div>
</div>
