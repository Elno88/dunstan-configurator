<div class="frame">
    <div class="frame-contents">

        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
              <i class="bubble-help btn-sidebar" data-content="olycksfallsforsakring-a">?</i>
                <p><strong>Välkommen!</strong><br/>
                  Fyll i dina uppgifter nedan för att få ett prisförslag på din olycksfallsförsäkring!</p>
            </div>
        </div>

        <div class="bubble bubble-type-d center" style="margin-bottom:5%;">
            <label class="bubble-label">E-post</label>
            <div class="bubble-contents">
                <input type="email" value="{{ $email ?? '' }}" name="email" placeholder="Ange din e-post">
            </div>
        </div>

        <div class="bubble bubble-type-d center" style="margin-bottom:5%;">
            <label class="bubble-label">Telefonnummer</label>
            <div class="bubble-contents">
                <input type="tel" value="{{ $phone ?? '' }}" name="phone" placeholder="Ange ditt telefonnummer">
            </div>
        </div>

        <div class="bubble bubble-type-d center" style="margin-bottom:5%;">
            <label class="bubble-label">Personnummer</label>
            <div class="bubble-contents">
                <input type="tel" placeholder="ÅÅÅÅMMDD-XXXX (12 siffror)" name="ssn" value="{{ $ssn ?? '' }}">
            </div>
        </div>

        <button type="button" class="btn1 btn-next">Nästa</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=ssn]').mask('00000000-0000');
    });
</script>
