<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
              <i class="bubble-help btn-sidebar" data-content="trailerforsakring-a-1">?</i>
                <p><strong>Hittade vi rätt!</strong><br/>
                Titta så att uppgifterna stämmer innan du går vidare.</p>
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <div class="regnr-input-wrapper">
                    <input id="regnr-input" type="text" name="regnr" value="{{ $vehicle['regnr'] }}" disabled>
                </div>
            </div>
        </div>
        <div id="regnr-data" class="bubble bubble-type-d center">
            <div class="bubble-contents regnr-content">
              <h4>{{ $vehicle['make'] }} {{ $vehicle['model'] }} {{ $vehicle['year'] }}</h4>
              <p>
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
