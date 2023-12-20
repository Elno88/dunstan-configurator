<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents bubble-hide-mobile">
                <i class="bubble-help btn-sidebar" data-content="hastforsakring-b-2">?</i>
                <p class="mb-md-01">Klicka på den häst du vill försäkra.</p>
            </div>
        </div>
        <div class="bubble bubble-type-d input-radio center">
            <ul class="bubble-select bubble-select-type-b">
                @foreach($insurances as $key => $insurance)
                    <li @if(isset($selected_insurance) && $selected_insurance == $key) class="selected" @endif><input class="bubble-select-next" id="hastforsakring-b-2-{{ $key }}" type="radio" name="insurance" value="{{ $key }}" @if(isset($selected_insurance) && $selected_insurance == $key) checked @endif >
                        <label for="hastforsakring-b-2-{{ $key }}">
                            {{ $insurance['insuranceName'] ?? 'Data saknas' }}<br/>
                            <span style="font-weight:bold;font-size:24px;">{{ $insurance['animalName'] ?? 'Data saknas' }}</span>
                            <!--{{ $insurance['premiumAmountYearRounded'] ?? '-' }} SEK-->
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>
        <div style="display: none;">
            <button type="button" class="btn1 btn-next">Nästa</button>
        </div>
    </div>
</div>
