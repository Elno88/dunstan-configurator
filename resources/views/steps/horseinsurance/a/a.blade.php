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

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <input id="hast-b" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-b-1" style="display: none;">
                <label for="hast-b">
                    <div class="box">
                        <img src="{{ asset('img/2.svg') }}" alt="Hästförsäkring">
                        <h3>Jämför<br> hästförsäkring</h3>
                        <p>Få en prisjämförelse och teckna direkt</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="hast-b">Välj</button>
                    </div>
                </label>
            </div>
            <div class="col-md-6 col-sm-12">
                <input id="hast-a" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-a-1" style="display: none;">
                <label for="hast-a">
                    <div class="box">
                        <img src="{{ asset('img/1.svg') }}" alt="Ny Hästförsäkring">
                        <h3>Ny<br> hästförsäkring</h3>
                        <p>Vi hjälper dig att räkna ut rätt pris</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="hast-a">Välj</button>

                    </div>
                </label>
            </div>
        </div>

    </div>
</div>

