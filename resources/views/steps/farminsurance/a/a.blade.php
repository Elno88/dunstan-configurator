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


        <div class="row">
            <div class="col-md-6 col-sm-12">

                <input id="gard-b" type="radio" class="btn-select" name="gardsforsakring" value="gardsforsakring-b-1" style="display: none;">
                <label for="gard-b">
                    <div class="box">
                        <img src="{{ asset('img/2.svg') }}" alt="Hästförsäkring">

                        <h3>Jämför<br> hästgårdsförsäkring</h3>
                        <p>Få en prisjämförelse.</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="gard-b">Välj</button>
                    </div>
                </label>
            </div>
            <div class="col-md-6 col-sm-12">
                <input id="gard-a" type="radio" class="btn-select" name="gardsforsakring" value="gardsforsakring-a-1" style="display: none;">
                <label for="gard-a">
                    <div class="box">
                        <img src="{{ asset('img/4.svg') }}" alt="Hästförsäkring">

                        <h3>Ny<br> hästgårdsförsäkring</h3>
                        <p>Vi hjälper dig att räkna ut rätt pris</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="gard-a">Välj</button>

                    </div>
                </label>
            </div>
        </div>

    </div>
</div>
