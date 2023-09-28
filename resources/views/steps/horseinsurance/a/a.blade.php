<div class="frame">
    <div class="frame-contents">
        <div class="frame-caption">
            <h1>Försäkra din häst</h1>
            <ul class="frame-caption-usps">
                <li><span style="text-decoration:underline;" class="btn-sidebar" data-content="trygghetsgaranti">Trygghetsgaranti</span></li>
                <li>Ersättning från första kronan</li>
                <li><span style="text-decoration:underline;" class="btn-sidebar" data-content="poang">Kunskap ger poäng</span></li>
            </ul>
        </div>

        {{--
            <div class="bubble bubble-type-a left">
                <div class="bubble-contents">
                    <i class="bubble-help btn-sidebar" data-content="hastforsakring">?</i>
                    <p><span style="font-weight:600;">Nu börjar vi!</span><br/>
                    Vill du teckna ny hästförsäkring eller jämföra med din nuvarande?</p>
                </div>
            </div>
        --}}

        <div class="hidden-md">
            <div class="bubble bubble-type-b input-radio left">
                <input id="hast-b" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-b-1">
                <label class="bubble-contents" for="hast-b">
                    <div class="stroke-left">
                        <h2>Jämför din nuvarande hästförsäkring</h2>
                        <p>Se en prisjämförelse och teckna direkt</p>
                    </div>
                </label>
            </div>

            <div class="bubble bubble-type-b input-radio right">
                <input id="hast-a" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-a-1">
                <label class="bubble-contents" for="hast-a">
                    <div class="stroke-left">
                        <h2>Teckna en ny hästförsäkring</h2>
                        <p>Vi hjälper dig att räkna ut rätt pris</p>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex justify-center gap-4 w-full hidden-xs">
            <div>
                <input id="hast-b" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-b-1" style="display: none;">
                <label for="hast-b">
                    <div class="box">
                        <img src="{{ asset('img/5.svg') }}" alt="Hästförsäkring">
                        <h4>Jämför hästförsäkring</h4>
                        <div>Få en prisjämförelse <br> och teckna direkt</div>
                        <button type="button" class="btn1 btn-next box-button" data-id="hast-b" style="margin-top: 30px !important;">Välj</button>
                    </div>
                </label>
            </div>
            <div>
                <input id="hast-a" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-a-1" style="display: none;">
                <label for="hast-a">
                    <div class="box">
                        <img src="{{ asset('img/1.svg') }}" alt="Ny Hästförsäkring">
                        <h4>Ny hästförsäkring</h4>
                        <p>Vi hjälper dig att <br> räkna ut rätt pris</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="hast-a" style="margin-top: 30px !important;">Välj</button>
                    </div>
                </label>
            </div>
        </div>
    </div>
</div>

