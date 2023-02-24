<div class="frame">
    <div class="frame-contents">

        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="hastforsakring-a-10">?</i>
                <p>Fyll i din e-post och telefonnummer så att vi kan kontakta dig.</p>
            </div>
        </div>

        <div class="bubble bubble-type-d center" style="margin-bottom: 5%;">
            <div class="bubble-contents">
                <input type="email" value="{{ $email ?? '' }}" name="email" placeholder="E-postadress">
            </div>
        </div>

        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="tel" value="{{ $telefon ?? '' }}" name="telefon" placeholder="Telefonnr.">
            </div>
        </div>

        <div class="navigation">
       		<button type="button" class="btn1 btn-next">Visa prisförslag</button>
       		<button type="button" class="btn2 btn-next" data-skip="1">Hoppa över och visa prisförslag</button>
    	</div>
        
    </div>
</div>
