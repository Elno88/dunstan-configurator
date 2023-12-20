<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents bubble-hide-mobile">
                <i class="bubble-help btn-sidebar" data-content="hastforsakring-a-9">?</i>
                <p class="font-heading-mobile mb-md-1">
                    Snart i mål!
                    <span class="bubble-sub-heading">
                        Efter du fyllt i ditt personnummer är du redo att själv
                        anpassa din försäkring utifrån dina egna behov!
                    </span>
                </p>
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="tel" placeholder="ååååmmdd-xxxx" name="civic_number" value="{{ $civic_number ?? '' }}">
            </div>
        </div>
        <div class="pnr-condition">
        <p>
            Genom att fortsätta godkänner du våra användarvillkor samt att dina personuppgifter hanteras och skyddas enligt vår personuppgiftspolicy.
        </p>
    </div>
        <button type="button" class="btn1 btn-next">Nästa</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('input[name=civic_number]').mask('00000000-0000');
    });
</script>
