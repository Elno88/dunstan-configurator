<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
              <i class="bubble-help btn-sidebar" data-content="trailerforsakring-a-1">?</i>
                <p><strong>Hittade vi rätt?</strong><br/>
                Titta så att uppgifterna stämmer innan du går vidare.</p>
            </div>
        </div>
        <div class="bubble bubble-type-e center" style="margin-bottom: 20px !important;">
            <div class="bubble-contents" style="zoom: .5 !important;">
                <div class="regnr-input-wrapper">
                    <input id="regnr-input" type="text" name="regnr" value="{{ $vehicle['regnr'] }}" disabled>
                </div>
            </div>
        </div>
        <div id="regnr-data" class="bubble bubble-type-d center">
            <div class="bubble-contents regnr-content text-center" style="padding: 40px 0 20px 0 !important; min-width: 340px; max-width: 340px;">
                <div style="margin-top:0; position: fixed; left: 37%; width:25%; border-top: 1px solid var(--color-yellow)"></div>
                <h4 style="margin: 10px !important;">{{ $vehicle['make'] }} {{ $vehicle['model'] }} </h4>
                <h4 style="margin: 10px !important;">{{ $vehicle['year'] }}</h4>
                <div style="margin-top: 0; position: fixed; left: 37%; width:25%; border-top: 1px solid var(--color-yellow)"></div>
              <p style="margin-top: 40px;">
                  Tjänstevikt:
                   <span>{{ number_format($vehicle['total_weight'], 0, ' ', '') }} kg</span>
              </p>
              <p>
                  Totalvikt:
                  <span>{{ number_format($vehicle['service_weight'], 0, ' ', '') }} kg</span>
              </p>
            </div>
        </div>
        <button type="button" class="btn1 btn-next">Nästa</button>
    </div>
</div>
