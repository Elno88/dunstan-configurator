<div class="frame">
    <div class="frame-contents">
        <div class="frame-caption">
            <h1>Bättre pris, bättre omfattning</h1>
            <ul class="frame-caption-usps">
                <li><span style="text-decoration:underline;" class="btn-sidebar" data-content="trygghetsgaranti">Trygghetsgaranti</span></li>
                <li><span style="text-decoration:underline;" class="btn-sidebar" data-content="poang">Kunskap ger poäng</span></li>
            </ul>
        </div>
        <div class="hidden-md">
            <div class="bubble bubble-type-b input-radio right">
                <input id="hast-a" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-a-1">
                <label class="bubble-contents" for="hast-a">
                    <div class="stroke-left">
                        <h2>Min häst är inte försäkrad</h2>
                        <p>Vi hjälper dig att räkna ut rätt pris</p>
                    </div>
                </label>
            </div>
            <div class="bubble bubble-type-b input-radio left">
                <input id="hast-b" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-b-1">
                <label class="bubble-contents" for="hast-b">
                    <div class="stroke-left">
                        <h2>Min häst är försäkrad</h2>
                        <p>Välj ditt bolag och få ett pris</p>
                    </div>
                </label>
            </div>
        </div>
        <div class="flex justify-center gap-4 w-full hidden-xs">
            <div>
                <input id="hast-a" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-a-1" style="display: none;">
                <label for="hast-a">
                    <div class="box">
                        <img src="{{ asset('img/1.svg') }}" alt="Min häst är inte försäkrad">
                        <h4>Min häst är inte försäkrad</h4>
                        <p>Vi hjälper dig att <br> räkna ut rätt pris</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="hast-a" style="margin-top: 30px !important;">Gå vidare</button>
                    </div>
                </label>
            </div>
            <div>
                <input id="hast-b" type="radio" class="btn-select" name="hastforsakring" value="hastforsakring-b-1" style="display: none;">
                <label for="hast-b">
                    <div class="box">
                        <img src="{{ asset('img/2.svg') }}" alt="Min häst är försäkrad">
                        <h4>Min häst är försäkrad</h4>
                        <p>Få en prisjämförelse <br> och teckna direkt</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="hast-b" style="margin-top: 30px !important;">Gå vidare</button>
                    </div>
                </label>
            </div>
        </div>
    </div>
</div>
