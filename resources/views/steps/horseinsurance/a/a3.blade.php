<div class="frame">
    <div class="frame-contents">

        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="hastforsakring-a-3">?</i>
                <p>Vad är hästens kön?</p>
            </div>
        </div>

        <div class="bubble bubble-type-d input-radio center">
            <ul class="bubble-select">
                @foreach($genders as $key => $gender)
                    <li @if(isset($selected_gender) && $selected_gender == $gender) class="selected" @endif><input class="bubble-select-next" id="hastforsakring-a-3-{{$key}}" type="radio" name="gender" value="{{ $gender }}" @if(isset($selected_gender) && $selected_gender == $gender) checked @endif><label for="hastforsakring-a-3-{{ $key }}">{{ $gender }}</label></li>
                @endforeach
            </ul>
        </div>

        <div style="display: none;">
            <button type="button" class="btn1 btn-next">Nästa</button>
        </div>

    </div>
</div>
