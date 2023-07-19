<div class="frame">
    <div class="frame-contents">

        <div class="frame-caption">
            <h1>Försäkra din hästgård</h1>
            <ul class="frame-caption-usps">
                <li>Heltäckande skydd</li>
                <li>Skräddarsydd för din verksamhet</li>
                <li>Kunskap som ger poäng</li>
            </ul>
        </div>

        {{--
            <div class="bubble bubble-type-a left">
                <div class="bubble-contents">
                    <i class="bubble-help btn-sidebar" data-content="gardsforsakring">?</i>
                    <p><span style="font-weight:600;">Nu börjar vi!</span> Vi kan antingen hämta dina nuvarande försäkringsuppgifter och återkomma med ett prisförslag, eller så kontaktar vi dig och tar det därifrån. Valet är ditt!</p>
                </div>
            </div>
        --}}

        <div class="hidden-md">
            <div class="bubble bubble-type-b input-radio left">
                <input id="gard-b" type="radio" class="btn-select" name="gardsforsakring" value="gardsforsakring-b-1">
                <label class="bubble-contents" for="gard-b">
                    <div class="stroke-left">
                        <h3>Jämför din hästgårdsförsäkring</h3>
                        <p>Se prisjämförelse och få en anpassad offert.</p>
                    </div>
                </label>
            </div>
            <div class="bubble bubble-type-b input-radio right">
                <input id="gard-a" type="radio" class="btn-select" name="gardsforsakring" value="gardsforsakring-a-1">
                <label class="bubble-contents" for="gard-a">
                    <div class="stroke-left">
                        <h3>Teckna ny hästgårdsförsäkring</h3>
                        <p>Prata med våra rådgivare och få en detaljerad offert.</p>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex justify-center gap-4 w-full hidden-xs">
            <div>
                <input id="gard-b" type="radio" class="btn-select" name="gardsforsakring" value="gardsforsakring-b-1" style="display: none;">
                <label for="gard-b">
                    <div class="box">
                        <img src="{{ asset('img/2.svg') }}" alt="Hästförsäkring">
                        <h4>Jämför hästgårdsförsäkring</h4>
                        <p>Få en prisjämförelse.</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="gard-b">Välj</button>
                    </div>
                </label>
            </div>
            <div>
                <input id="gard-a" type="radio" class="btn-select" name="gardsforsakring" value="gardsforsakring-a-1" style="display: none;">
                <label for="gard-a">
                    <div class="box">
                        <img src="{{ asset('img/4.svg') }}" alt="Hästförsäkring">
                        <h4>Ny hästgårdsförsäkring</h4>
                        <p>Vi hjälper dig att räkna ut rätt pris</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="gard-a">Välj</button>
                    </div>
                </label>
            </div>
        </div>
    </div>
</div>
