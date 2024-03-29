<div class="frame frame-results">
    <div class="frame-contents">
        <div class="frame-resultat-header">
            <p id="resultat-forsakring-type">{{ $horse_usage_label ?? 'Försäkring' }}</p>
            <h2 id="resultat-horse-name">{{ $horse_name ?? 'Hästnamn' }}</h2>
        </div>
        <div class="frame-resultat">
            <div class="tack-ansokan">

            	<div class="tack-ansokan-thumb">
	            	<img src="{{ asset('img/dunstan_tack-hero.png') }}" alt="Grattis till din nya försäkring">
	            </div>

                <h3 style="text-align: center;">Varmt välkommen som kund hos Dunstan</h3>

                <p>Grattis till din nya försäkring. Vi hoppas att du och {{ $horse_name ?? '' }} ska trivas hos oss. Om du tittar i din inkorg ska du ha fått ett mejl med lite nyttig information om när din försäkring börjar gälla och vad som händer härnäst.</p>

                <br/>

                <div class="btn-wrapper">
	                <button class="btn1" onclick="location.href='https://forsakra.dunstan.se/#hastforsakring'">Teckna en till försäkring</button>
	                <button class="btn1 btn3" onclick="location.href='https://dunstan.se/mina-sidor/'">Gå till Mina Sidor</button>
	            </div>

              <!-- TrustBox widget - Review Collector -->
              <div id="trustbox" class="trustpilot-widget" style="margin-top:50px" data-locale="sv-SE" data-template-id="56278e9abfbbba0bdcd568bc" data-businessunit-id="635b9d35f97e5c2c7dfeb1ba" data-style-height="52px" data-style-width="100%">
                <a href="https://se.trustpilot.com/review/dunstan.se" target="_blank" rel="noopener">Trustpilot</a>
              </div>
              <!-- End TrustBox widget -->
              <script type="text/javascript">
                  const trustbox = document.getElementById('trustbox');
                  window.Trustpilot.loadFromElement(trustbox);
              </script>
            </div>
        </div>
    </div>
</div>

@include('steps.horseinsurance.resultat.footer')
@include('steps.horseinsurance.resultat.popup')

<script type="text/javascript">
    $(document).ready(function(){
        @if(isset($ecommerce_data_send) && $ecommerce_data_send == true)
            let ecommerce_data = {!! json_encode($ecommerce_data) !!};
            dataLayer.push({ ecommerce: null });
            dataLayer.push(ecommerce_data);
        @endif
    });
</script>
