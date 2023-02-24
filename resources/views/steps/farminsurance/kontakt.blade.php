<div class="frame frame-results">
	<div class="frame-contents">

		<div class="frame-resultat-header">
			<p id="resultat-forsakring-type">Prisförslag</p>
			<h2 id="resultat-horse-name">Gårdsförsäkring</h2>
		</div>

	    <div class="frame-resultat">

	    	<h2 style="margin-bottom:0;">Nästan klara!</h2>
        	<h4 style="text-align:center;margin: 20px 0 40px">Nu behöver vi bara veta hur du vill bli kontaktad, så återkommer vi till dig med ett prisförslag så snart som möjligt. Vi rekommenderar att du anger både ditt telefonnummer och din e-post.</h4>

	    	<div class="kontakt-form form-gard">

	    		<div class="col-100">

					<input style="margin-bottom:  20px;" id="my-email" type="email" name="email" value="" class="" placeholder="E-postadress" required>
					<input id="my-tel" type="tel" name="phone" value="" class="" placeholder="Telefonnummer">

					<div class="phone-times" style="display:none;">
						<p>När på dagen vill du bli uppringd?</p>
						<label><input class="time-checkbox" type="checkbox" name="phone_time" value="8-12" checked> 08-12</label>
						<label><input class="time-checkbox" type="checkbox" name="phone_time" value="13-16"> 13-16</label>
						<label><input class="time-checkbox" type="checkbox" name="phone_time" value="16-19"> 16-19</label>
					</div>

				</div>

                <div id="field-firstname" class="col-50">
                    <input type="text" name="firstname" value="" class="" placeholder="Förnamn">
                </div>
                <div id="field-lastname" class="col-50">
                    <input type="text" name="lastname" value="" class="" placeholder="Efternamn">
                </div>
                <div id="field-street" class="col-100">
                    <input type="text" name="street" value="{{ $address_street ?? '' }}" class="" placeholder="Gatuadress">
                </div>
                <div id="field-zip" class="col-50">
                    <input type="text" name="zip" value="{{ $address_zip ?? '' }}" class="" placeholder="Postnummer">
                </div>
                <div id="field-city" class="col-50">
                    <input type="text" name="city" value="{{ $address_city ?? '' }}" class="" placeholder="Ort">
                </div>

	    		<div id="form-footer" class="col-100">

	    			@if(isset($farminsurance_step) && $farminsurance_step == 'gardsforsakring-b-1' && !empty($pdf))
	                	<p>Om du är nyfiken på att se vilken information som du kommer att dela med oss, <a id="download-pdf" style="color:#000;" href="#" target="_blank">klicka här</a>.</p>
	            	@endif

	    			<div class="resultat-acceptance-checkbox border-top">
	            		<label><input id="" type="checkbox" name="term" value="1">Jag godkänner Dunstans <a href="https://dunstan.se/integritetspolicy/" target="_blank">integritetspolicy</a> för behandling av personuppgifter.</label>
	        		</div>
	        		<br/>
	    			<button class="btn1 btn-next">Skicka</button>
	    		</div>

	    	</div>

	    </div>

	</div>
</div>

<script>

    @if(isset($farminsurance_step) && $farminsurance_step == 'gardsforsakring-b-1' && !empty($pdf))
        $('#download-pdf').on('click', function(e){
            e.preventDefault();
            const linkSource = 'data:application/pdf;base64,{{ $pdf }}';
            const downloadLink = document.createElement("a");
            const fileName = "gårdsförsäkring.pdf";
            downloadLink.href = linkSource;
            downloadLink.download = fileName;
            downloadLink.target = "_blank";
            downloadLink.click();
        });
    @endif

	$('#my-tel').on('input', function() {
		if ($.trim($('#my-tel').val()).length > 0) {
			$('.phone-times').show(200);
		}
	});

	$('.time-checkbox').on('click', function() {
		$('.time-checkbox').prop('checked', false);
		$(this).prop('checked', true);
	});

</script>
