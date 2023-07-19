<div class="frame">
    <div class="frame-contents">

        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
              <i class="bubble-help btn-sidebar" data-content="trailerforsakring-a">?</i>
                <p><strong>Välkommen!</strong><br/>
                Börja med att ange trailerns registrerings&shy;nummer i rutan nedan så hämtar vi uppgifter om den.</p>
            </div>
        </div>

        <div class="bubble bubble-type-e center">
            <div class="bubble-contents" style="padding: 0 !important;">
                <div class="regnr-input-wrapper">
                    <input id="regnr-input" type="text" name="regnr" value="{{ old('regnr') }}" placeholder="ABC 123" maxlength="6">
                </div>
            </div>
        </div>
        <button type="button" class="btn1 btn-next">Nästa</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#regnr-input').mask('AAA 99A');
    });
</script>
