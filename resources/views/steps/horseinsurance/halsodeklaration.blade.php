<div class="frame frame-results">
    <div class="frame-contents">

        <div class="frame-resultat-header">
            <p id="resultat-forsakring-type">{{ $horse_usage_label ?? 'Försäkring' }}</p>
            <h2 id="resultat-horse-name">{{ $horse_name ?? 'Hästnamn' }}</h2>
        </div>

        <div class="frame-resultat">

            <div class="frame-resultat-yesno">

                <h2 style="text-align: center;">Nästan färdig!</h2>

                <p>Du behöver svara på några snabba frågor för att vi ska kunna göra en bedömning av hur hästens hälsa påverkar försäkringens omfattning.</p>

                <div class="box-trygghetsgaranti">
                	<img class="badge-trygghetsgaranti" src="{{ asset('img/trygghetsgaranti-badge.png') }}" alt="Dunstan Trygghetsgaranti">
                	<h4>Vad innebär Dunstans trygghetsgaranti?</h4>
                	<p>Dunstan erbjuder trygghetsgaranti vid flytt från annat försäkringsbolag.<br>Det betyder att om din häst tidigare omfattats av ett dolda fel-skydd övertar vi det skyddet.<br>För att trygghetsgarantin ska gälla ska Dunstan fått tagit del av tidigare försäkringsomfattning, skadehistorik samt en hälsodeklaration. Alla som tecknar Dunstan Foster & Föl samt försäkrade föl i Dunstan omfattas av trygghetsgarantin och dolda fel-skydd.</p>
                </div>

	            <div class="boxed" style="padding-bottom: 40px">

	                @if(isset($document_type) && $document_type == 'foal')
	                    <p>Foster och föl kan tecknas tidigast från 40 dagars dräktighet och senast 60 dagar innan beräknad fölning. Dräktighetsintyget får vara högst tre dagar gammalt vid försäkringens tecknande och ska bifogas om stoet resorberar eller har en ej bevisad kastning. Alla som tecknar Dunstan Foster & Föl samt försäkrade föl i Dunstan omfattas av trygghetsgarantin och dolda fel-skydd.</p>
	                @else
	                    <p>Du behöver fylla i hälsoenkäten för att vi ska kunna göra en bedömning av hur hästens hälsa påverkar försäkringens omfattning.</p>

	                @endif

	                    @foreach($questions as $question)

	                        @php
	                            $villkor_show = true;
	                            $villkor_class = '';
	                            if(isset($questions_villkor[$question['id']])){
	                                $villkor_show = false;
	                                $villkor_class = 'hidden';
	                            }
	                            if(
	                                isset($questions_villkor[$question['id']]) &&
	                                isset($questions_villkor[$question['id']]['question']) &&
	                                isset($answers['questions'][$questions_villkor[$question['id']]['question']]) &&
	                                in_array($answers['questions'][$questions_villkor[$question['id']]['question']], $questions_villkor[$question['id']]['answers'])
	                            ){
	                                $villkor_show = true;
	                            }

	                            // Fix
	                            if($question['id'] == 15){
	                                $villkor_class = 'hidden';
	                            }

	                            if(isset($document_type) && $document_type == 'foal'){
	                                $villkor_class = 'hidden';
	                            }
	                        @endphp

	                        <div class="question-wrapper {{ $villkor_class ?? '' }}" data-question-id="{{ $question['id'] }}" @if(!$villkor_show) style="display:none" @endif>

	                            <h4>{{ $question['namn'] }}@if($question['obligatorisk'] == 1 || (isset($questions_villkor[$question['id']]) && $questions_villkor[$question['id']]['required']))<span class="required" title="obligatorisk">*</span>@endif</h4>
	                            @switch($question['typ'])
	                                @case('checkbox')
	                                    @if(isset($question['data']['options']))
	                                        @foreach($question['data']['options'] as $key => $option)
	                                            <label class="container">
	                                                <input class="yesno-check" type="checkbox" name="questions[{{ $question['id'] }}][{{$key}}]" value="{{ $option }}" @if(isset($answers['questions'][$question['id']][$key])) checked @endif/>
	                                                <span class="check"></span>
	                                                {{ rtrim($option, '*') }}
	                                            </label>
	                                        @endforeach
	                                    @endif
	                                    @break;
	                                @case('radio')
	                                    @if(isset($question['data']['options']))
	                                        @foreach($question['data']['options'] as $key => $option)
	                                            <label class="container">
	                                                <input class="yesno-check" type="radio" name="questions[{{ $question['id'] }}]" value="{{ $option }}" @if(isset($answers['questions'][$question['id']]) && $answers['questions'][$question['id']] == $option) checked @endif />
	                                                <span class="check"></span>
	                                                {{ rtrim($option, '*') }}
	                                            </label>

	                                        @endforeach
	                                    @endif
	                                    @break
	                                @case('textarea')
	                                    <textarea name="questions[{{ $question['id'] }}]" rows="4">{{ $answers['questions'][$question['id']] ?? '' }}</textarea>
	                                    @break
	                                @case('text')
	                                    <input type="text" id="" name="questions[{{ $question['id'] }}]" value="{{ $answers['questions'][$question['id']] ?? '' }}" />
	                                    @break
	                                @case('date')
	                                    <input class="datepicker" type="text" id="" name="questions[{{ $question['id'] }}]" value="{{ $answers['questions'][$question['id']] ?? '' }}" />
	                                    @break
	                            @endswitch

		                	</div>

	                	    @if((isset($document_type) && $document_type == 'health') &&
							    (config('services.focus.live') == true && $question['id'] == 55) ||
							    (config('services.focus.live') == false && $question['id'] == 55))

	                            <div class="question-text-wrapper" data-question-id="{{ $question['id'] }}" @if(!$villkor_show) style="display:none" @endif>
									<p>För att göra det enklare för dig att byta till Dunstan erbjuder vi fri flytträtt. Det betyder din försäkring inte drabbas av undantag för tidigare kända eller behandlade sjukdomar/skador om din häst är helt återställd och frisk.</p>
	                            </div>
							@endif

	                    @endforeach

	                </div>

                    <h3 style="font-size:14px;margin-bottom:0;">Bra att veta!</h3>
<!--                   <p style="font-size:12px;font-style: italic;">Tänk på att uppge alla tidigare skador eller sjukdomar som din häst har haft vid införsäkran. 						Vi har då möjlighet att ge rådet att du ska stanna kvar i ditt nuvarande bolag (om sådant finns) om vi anser att skadorna är så allvarliga 							att en reservation kommer att påföras.</p>
 -->
                    <p style="font-size:12px;font-style: italic;">Inkommer uppgifter om skador och sjukdomar som funnits innan införsäkringen, som inte informerats om vid tecknade hos Dunstan, kan det innebära att ersättning inte utgår och att en reservation påförs.</p>

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

@include('steps.horseinsurance.resultat.empty_footer')

@include('steps.horseinsurance.resultat.popup')

@include('steps.horseinsurance.resultat.pris_scripts')

<script type="text/javascript">
    $(document).ready(function(){

        // Visa villkor
        $('.question-wrapper').find('.yesno-check').on('change', function(){
            let answer = $(this).val();
            let question_id = $(this).closest('.question-wrapper').attr('data-question-id');
            let questions_villkor = {!! json_encode($questions_villkor) !!};

            for(q_id in questions_villkor) {
                if(questions_villkor[q_id].question == question_id){
                    if(questions_villkor[q_id].answers.includes(answer)){
                        $('.question-wrapper[data-question-id='+q_id+']').css('display', 'block');
                        $('.question-text-wrapper[data-question-id='+q_id+']').css('display', 'block');
                    } else {
                        $('.question-wrapper[data-question-id='+q_id+']').css('display', 'none');
                        $('.question-text-wrapper[data-question-id='+q_id+']').css('display', 'none');
                    }
                }
            }
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
    });
</script>
