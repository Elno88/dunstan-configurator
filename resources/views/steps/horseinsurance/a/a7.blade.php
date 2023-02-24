<div class="frame">
    <div class="frame-contents">

        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="hastforsakring-a-7">?</i>
                <p>Ange {{ $horse_name ?? 'hästen' }}s chipnummer eller reg. nummer i fältet nedan.</p>
            </div>
        </div>

        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="tel" value="{{ $chip_number ?? '' }}" placeholder="00000000" name="chip_number">
            </div>
        </div>

        <div class="navigation">
        	<button type="button" class="btn1 btn-next">Nästa</button>
        	<button type="button" class="btn2 btn-next" data-skip="1">Hoppa över det här steget</button>
    	</div>

    </div>
</div>
