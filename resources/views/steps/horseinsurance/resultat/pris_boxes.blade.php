<div class="resultat-widget-list">

    @if((isset($data['veterinarvardsforsakring']) && !empty($data['veterinarvardsforsakring']) && !isset($disabled_checkboxes)) || isset($disabled_checkboxes) && in_array($data['veterinarvardsforsakring'], $data['forsakring_enabled']))
        <div class="body-price body-price-vet @if(isset($data['forsakring_enabled']) && array_key_exists('vet', $data['forsakring_enabled'])) selected @endif">
            <label for="price-vet" class="switch">
                <input
                    id="price-vet"
                    type="checkbox"
                    value="{{ $data['veterinarvardsforsakring'] }}"
                    name="@if(isset($dummy) && $dummy == true){{ 'forsakring_enabled_dummy[vet]' }}@else{{ 'forsakring_enabled[vet]' }}@endif"
                    @if(isset($data['forsakring_enabled']) && array_key_exists('vet', $data['forsakring_enabled'])) checked @endif
                    @if((isset($disabled_checkboxes) && $disabled_checkboxes == true) || in_array($data['veterinarvardsforsakring'], [38,40,41])) style="display:none;" @endif
                />
                <span></span>
            </label>

            <p class="resultat-widget-list-label">Veterinärsvårds&shy;försäkring:</p>
            <h4 class="resultat-widget-list-value first">{{ $data['veterinarvardsforsakring_label'] ?? '' }}</h4>

            <p class="resultat-widget-list-label">Veterinärvårds&shy;belopp:</p>
            <h4 class="resultat-widget-list-value">@if(isset($data['veterinarvardsbelopp']) && !empty($data['veterinarvardsbelopp']))
                    {{ number_format($data['veterinarvardsbelopp'] ?? '', 0, ',', ' ') }} kr
                @else
                    -
                @endif</h4>

            <p class="resultat-widget-list-label">Självrisk:</p>
            <h4 class="resultat-widget-list-value">@if(isset($data['sjalvrisk']) && !empty($data['sjalvrisk']))
                    {{ $data['sjalvrisk'] ?? '' }}%
                @else
                    -
                @endif</h4>
        </div>
    @endif

    @if((isset($data['livforsakring']) && !empty($data['livforsakring']) && !isset($disabled_checkboxes)) || isset($disabled_checkboxes) && in_array($data['livforsakring'], $data['forsakring_enabled']))
        <div class="body-price body-price-liv @if(isset($data['forsakring_enabled']) && array_key_exists('liv', $data['forsakring_enabled'])) selected @endif">
            <label for="price-liv" class="switch">
                <input
                    id="price-liv"
                    type="checkbox"
                    value="{{ $data['livforsakring'] }}"
                    name="@if(isset($dummy) && $dummy == true){{ 'forsakring_enabled_dummy[liv]' }}@else{{ 'forsakring_enabled[liv]' }}@endif"
                    @if(isset($data['forsakring_enabled']) && array_key_exists('liv', $data['forsakring_enabled'])) checked @endif
                    @if((isset($disabled_checkboxes) && $disabled_checkboxes == true) || in_array($data['livforsakring'], [38,40,41])) style="display:none;" @endif
                />
                <span></span>
            </label>
            <p class="resultat-widget-list-label">Liv&shy;försäkring:</p>
            <h4 class="resultat-widget-list-value first">{{ $data['livforsakring_label'] ?? '' }}</h4>

            <p class="resultat-widget-list-label">Liv&shy;värde:</p>
            <h4 class="resultat-widget-list-value">
                @if(isset($data['livvarde']) && !empty($data['livvarde']))
                    {{ number_format($data['livvarde'] ?? '', 0, ',', ' ') }} kr
                @else
                    -
                @endif
            </h4>
        </div>
    @endif
</div>
