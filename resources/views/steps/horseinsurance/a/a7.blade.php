<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents bubble-hide-mobile">
                <i class="bubble-help btn-sidebar" data-content="hastforsakring-a-7">?</i>
                <p class="font-heading-mobile mb-md-1">
                    Ange {{ $horse_name ?? 'hästen' }}s  chip- eller id-nummer.
                    <span class="bubble-sub-heading">
                        Du kan välja att fylla i detta senare
                    </span>
                </p>
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
