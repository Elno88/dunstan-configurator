<div class="frame" style="width: 90% !important;">
    <div class="frame-contents">

        <div class="frame-caption">
            <h1>Få ett prisförslag</h1>
        </div>

{{--
        <div class="hidden-mdx">hidden mobile</div>
        <div class="hidden-xs">hidden desktop</div>
--}}

        <div class="row">
            <div class="col-md-4 col-sm-12">
                <input id="hast-select" type="radio" class="btn-select" name="forsakring" value="hastforsakring" style="display: none;">
                <label for="hast-select">
                    <div class="box">
                        <img src="{{ asset('img/1.svg') }}" alt="Hästförsäkring">
                        <h4>Häst&shy;försäkring</h4>
                        <p>Få prisförslag & teckna direkt.</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="hast-select">Se pris</button>
                    </div>
                </label>
            </div>
            <div class="col-md-4 col-sm-12">
                <input id="gard-select" type="radio" class="btn-select" name="forsakring" value="gardsforsakring" style="display: none">
                <label for="gard-select">
                    <div class="box">
                        <img src="{{ asset('img/4.svg') }}" alt="Hästgårdsförsäkring">
                        <h4>Hästgårds&shy;försäkring</h4>
                        <p>Få en offert.</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="gard-select">Få offert</button>
                    </div>
                </label>
            </div>
            <div class="col-md-4 col-sm-12">
                <input id="trailer-select" type="radio" class="btn-select" name="forsakring" value="trailerforsakring" style="display: none">
                <label for="trailer-select">
                    <div class="box">
                        <img src="{{ asset('img/3.svg') }}" alt="Hästtrailerförsäkring">
                        <h4>Häst&shy;trailerförsäkring</h4>
                        <p>Få ett prisförslag & teckna direkt.</p>
                        <button type="button" class="btn1 btn-next box-button" data-id="trailer-select">Se pris</button>
                    </div>
                </label>
            </div>
        </div>

    </div>
</div>
