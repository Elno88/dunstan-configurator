<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="hastforsakring-a-1">?</i>
                <p><span style="font-weight:600;">I vilken kategori passar din häst bäst?</span><br/>
                Om du känner dig osäker på något, kan du när som helst klicka på en ?-ikon för att få mer information. Förresten, visste du att vi inte har någon fast självrisk eller självriskperiod?</p>
            </div>
        </div>

        <div class="bubble bubble-type-d input-radio center">
            <ul class="bubble-select">
                @foreach($horse_usage as $key => $usage)
                    <li class="@if(isset($selected_horse_usage) && $selected_horse_usage == $key) selected @endif"><input class="bubble-select-next" id="hastforsakring-b-4-{{ $key }}" type="radio" name="horse_usage" value="{{ $key }}" @if(isset($selected_horse_usage) && $selected_horse_usage == $key) checked @endif ><label for="hastforsakring-b-4-{{ $key }}">{{ $usage }}</label></li>
                @endforeach
            </ul>
        </div>

        <div style="display: none;">
            <button type="button" class="btn1 btn-next">Nästa</button>
        </div>

    </div>
</div>
