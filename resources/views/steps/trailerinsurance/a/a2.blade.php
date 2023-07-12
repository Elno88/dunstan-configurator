<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
              <i class="bubble-help btn-sidebar" data-content="trailerforsakring-a-2">?</i>
                <p><strong>Toppen!</strong><br/>
                    Ange ditt personnummer i fältet nedan.</p>
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <div class="ssn-input-wrapper">
                    <input id="ssn-input" type="tel" name="ssn" value="{{ old('ssn') }}" placeholder="ÅÅÅÅMMDD-XXXX" maxlength="11">
                </div>
            </div>
        </div>
        <div class="pnr-condition">
            <p>Genom att fortsätta godkänner du våra användarvillkor samt att dina personuppgifter hanteras och skyddas enligt vår personuppgiftspolicy.</p>
        </div>
        <button type="button" class="btn1 btn-next">Nästa</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=ssn]').mask('00000000-0000',{placeholder:""});
    });
</script>
