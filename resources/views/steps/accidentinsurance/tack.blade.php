<div class="frame frame-results">
    <div class="frame-contents">
        <div class="frame-resultat-header">
            <div id="resultat-forsakring-type">Olycksfallsförsäkring</div>
            <h2 id="resultat-horse-name">{{ $customer['kund']['namn'] }}</h2>
        </div>
        <div class="frame-resultat">
            <div class="tack-ansokan">
              <div class="tack-ansokan-thumb">
                <img src="{{ asset('img/dunstan_tack-hero.png') }}" alt="Grattis till din nya försäkring">
              </div>
                <h3 style="text-align: center;">Grattis till din nya försäkring!</h3>
                <p>
                    Om du tittar i din inkorg ska du ha fått ett mejl med lite nyttig
                    information om när din försäkring börjar gälla och vad som händer härnäst.
                </p>
                <br/>
                <div class="btn-wrapper">
                  <button class="btn1" onclick="location.href='https://forsakra.dunstan.se/'">Teckna en till försäkring</button>
                  <button class="btn1 btn3" onclick="location.href='https://dunstan.se/mina-sidor/'">Gå till Mina Sidor</button>
              </div>
            </div>
        </div>
    </div>
</div>

@include('steps.accidentinsurance.resultat.footer')

<script type="text/javascript">
    $(document).ready(function(){
        // send ecommerce data to GTM
        @if (isset($ecommerce_data_send) && $ecommerce_data_send == true)
            let ecommerce_data = {!! json_encode($ecommerce_data) !!};
            dataLayer.push({ ecommerce: null });
            dataLayer.push(ecommerce_data);
        @endif
    });
</script>
