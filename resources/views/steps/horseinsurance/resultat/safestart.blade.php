	<div id="disable-safestart" style="display:none;visibility:hidden;">true</div>
</div>

<!--

<h4>Vilket skydd vill du ha?</h4>
<p>Välj antingen 30 dagar eller <strong>Safe Start</strong> som ger hela 365 dagars skydd efter fölning.</p>

<ul id="safestart-select" class="resultat-slide-select options-2">
    <li class="safestart-select selected" data-selected="no">30 dagar</li>
    <li class="safestart-select" data-selected="yes">365 dagar (Safe Start)</li>
    <div class="marker"></div>
</ul>

<div class="resultat-select-caption safestart-caption active" data-content="safestart-no">
    <p><strong>30 dagars skydd efter fölning</strong> ingår alltid i försäkringen Foster & Föl. För dig som vill omfattas av ett längre skydd och undvika glapp mellan övergången från foster- till fölförsäkring, se 365 dagars skydd (Safe Start)</p>
</div>

<div class="resultat-select-caption safestart-caption"  data-content="safestart-yes">
    <p><strong>365 dagars skydd (Safe Start):</strong> Få en smidig övergång från foster- till fölförsäkring med 365 dagars skydd. Ett föl som försäkrats med Safe Start omfattas av Dunstans mest heltäckande försäkring Premium Veterinärvård och med ett livvärde i Premium Liv & Användbarhet. Livförsäkringen går sedan att uppgradera till ett högre belopp om så önskas.</p>
</div>

<div class="safestart-wrapper" style="display:none;">
    <label>
        <input
            id="safestart"
            type="checkbox"
            value="1"
            name="safestart"
            @if(isset($defaults['safestart']) && $defaults['safestart'] == 1) checked @endif
        /> Vill du köra safestart?
    </label>
</div>

-->

<script>
	$(document).ready( function() {

		var hideme = $('#disable-safestart').text();

		if (hideme) {
			$('body.resultat .body-price label.switch').hide();
		}
		console.log('hide switch');
	})
</script>