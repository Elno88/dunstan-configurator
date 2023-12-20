<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents bubble-hide-mobile">
                <i class="bubble-help btn-sidebar" data-content="hastforsakring-a-8">?</i>
                <p class="font-heading-mobile mb-md-1">
                    Är {{ $horse_name ?? 'hästen' }} född i Sverige?
                </p>
            </div>
        </div>
        <div class="bubble bubble-type-d input-radio center">
            <ul class="bubble-select">
                @foreach(['Ja', 'Nej'] as $key => $born)
                    <li @if (isset($selected_born) && $selected_born == $born) class="selected" @endif><input class="bubble-select-next" id="hastforsakring-a-8-{{ $key }}" type="radio" name="born" value="{{ $born }}" @if(isset($selected_born) && $selected_born == $born) checked @endif><label for="hastforsakring-a-8-{{ $key }}">{{ $born }}</label></li>
                @endforeach
            </ul>
        </div>
        <div style="display: none;">
            <button type="button" class="btn1 btn-next">Nästa</button>
        </div>
    </div>
</div>
