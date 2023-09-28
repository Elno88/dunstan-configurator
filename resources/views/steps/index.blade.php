<div class="frame index-page" style="width: 90% !important;">
    <div class="frame-contents">

        <div class="frame-caption">
            <h1>Få ett prisförslag</h1>
        </div>

        <div class="w-full hidden-md">
            <div class="bubble bubble-type-b input-radio left">
                <input id="hast-select" type="radio" class="btn-select" name="forsakring" value="hastforsakring">
                <label class="bubble-contents" for="hast-select">
                    <div class="stroke-left">
                        <h2>Häst</h2>
                    </div>
                </label>
            </div>

            <div class="bubble bubble-type-b input-radio middle">
                <input id="gard-select" type="radio" class="btn-select" name="forsakring" value="gardsforsakring">
                <label class="bubble-contents" for="gard-select">
                    <div class="stroke-left">
                        <h2>Hästgård</h2>
                    </div>
                </label>
            </div>

            <div class="bubble bubble-type-b input-radio middle">
                <input id="trailer-select" type="radio" class="btn-select" name="forsakring" value="trailerforsakring">
                <label class="bubble-contents" for="trailer-select">
                    <div class="stroke-left">
                        <h2>Häst&shy;trailer</h2>
                    </div>
                </label>
            </div>

            <div class="bubble bubble-type-b input-radio right">
                <input id="olycksfall-select" type="radio" class="btn-select" name="forsakring" value="olycksfallsforsakring">
                <label class="bubble-contents" for="olycksfall-select">
                    <div class="stroke-left">
                        <h2>Olycksfall</h2>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex icons-home justify-center gap-4 w-full hidden-xs">
            <div>
                <input id="hast-select" type="radio" class="btn-select" name="forsakring" value="hastforsakring" style="display: none;">
                <label for="hast-select">
                    <div class="box">
                        <img src="{{ asset('img/1.svg') }}" alt="Hästförsäkring">
                        <h4>Häst</h4>
                    </div>
                </label>
            </div>
            <div>
                <input id="gard-select" type="radio" class="btn-select" name="forsakring" value="gardsforsakring" style="display: none">
                <label for="gard-select">
                    <div class="box">
                        <img src="{{ asset('img/4.svg') }}" alt="Hästgårdsförsäkring">
                        <h4>Hästgård</h4>
                    </div>
                </label>
            </div>
            <div>
                <input id="trailer-select" type="radio" class="btn-select" name="forsakring" value="trailerforsakring" style="display: none">
                <label for="trailer-select">
                    <div class="box">
                        <img src="{{ asset('img/3.svg') }}" alt="Hästtrailerförsäkring">
                        <h4>Häst&shy;trailer</h4>
                    </div>
                </label>
            </div>
             <div>
                <input id="olycksfall-select" type="radio" class="btn-select" name="forsakring" value="olycksfallsforsakring" style="display: none">
                <label for="olycksfall-select">
                    <div class="box">
                        <img src="{{ asset('img/2.svg') }}" alt="Olycksfallsförsäkring">
                        <h4>Olycksfall</h4>
                    </div>
                </label>
            </div>
        </div>

    </div>
</div>
