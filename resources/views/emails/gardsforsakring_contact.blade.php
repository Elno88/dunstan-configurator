E-post: {{ $details['email'] ?? '' }} <br/>
@if(isset($details['phone']) && !empty($details['phone']))
    Telefon: {{ $details['phone'] ?? '' }} <br/>
    Kund önskar att bli kontaktad mellan kl: {{ $details['phone_time'] ?? '' }} <br/>
@endif
Förnamn: {{ $details['firstname'] ?? '' }} <br/>
Efternamn: {{ $details['lastname'] ?? '' }} <br/>
Gatuadress: {{ $details['street'] ?? '' }} <br/>
Postnummer: {{ $details['zip'] ?? '' }} <br/>
Ort: {{ $details['city'] ?? '' }} <br/>
