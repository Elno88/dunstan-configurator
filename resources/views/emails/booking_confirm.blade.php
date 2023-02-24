<!DOCTYPE html>
<html lang="sv-SE">
<head>
</head>
<body style="margin:0;padding:0;background: #f9fcf8;">
	<table style="border-collapse:collapse;border:0;width: 800px;margin: 80px auto;">
		<tr>
			<td style="font-family:'Poppins',sans-serif;text-align:center;font-size:14px; padding: 0;">
				<img src="https://dunstan.se/wp-content/uploads/2022/03/hero-email-01.jpg" width="800" height="305" alt="Dunstan">
			</td>
		</tr>
		<tr>
			<td style="padding:50px 60px 60px;font-family:'Poppins',sans-serif;line-height:1.4;background:#fff">

				<p style="font-size:28px">Hej!</p>

				<p style="font-weight:500;">Vi är glada att just du valt Dunstan, det enda bolaget med 100 % fokus på hästgårdslivet. Vårt mål är att skapa tjänster som gör din vardag enklare och bättre och vi ska göra allt och lite till för att leva upp till dina förväntningar.</strong></p>

				<p>Mer information om dina försäkringar hittar du på <a href="https://dunstan.se/mina-sidor/" target="_blank">Mina Sidor</a> på dunstan.se.</p>

				<h4>Vad händer nu?</h4>

				<p>Nu kommer våra försäkringsspecialister gå igenom din försäkring för att kontrollera att alla uppgifter är korrekta och att produkterna överensstämmer med dina val. Behöver informationen kompletteras kontaktar vi er, annars kommer ni inom kort få ett mejl med ert försäkringsbrev samt en faktura med valt betalsätt.</p>

                @if(isset($details['horse_usage']) && $details['horse_usage'] == 2)
                <div style="padding: 20px 40px; background-color: #f7f7f7; border-radius: 5px;">
                    <p><strong>Vid ersättningsanspråk från försäkringen ska något av följande kunna uppvisas:</strong></p>

                    <p><strong>Vid resorbering (uteblivet föl)</strong><br/>
                    Dräktighetsintyg ej äldre än tre dagar från försäkringens tecknande samt intyg från veterinär att stoet har resorberat. Eller intyg från veterinär som kan intygar att stoet inte fått något föl denna säsong. Detta ska intygas efter beräknad fölning.</p>

                    <p><strong>Vid bevisad kastning</strong><br/>
                    En veterinär, ID-kontrollant eller person som utför nödslakt eller kadaverhämtning ska skriftligen intyga att de har sett samt identifierat det döda fölet. Om sto är behandlad för efterbörd så räcker det som intyg från behandlande veterinär.</p>
                </div>
                @endif

                <p>Om du har några frågor eller funderingar är du varmt välkommen att kontakta oss!</p>

				<p>Mejl: <a style="color:#093b35;text-decoration:none;" href="mailto:info@dunstan.se">info@dunstan.se</a><br/>
				Tel: <a style="color:#093b35;text-decoration:none;" href="tel:0101798400">010-17 98 400</a></p>

			</td>
		</tr>
		<tr>
			<td style="font-family:'Poppins',sans-serif;text-align:center;font-size:14px; padding: 10px;color: #999999;">
				<p>Dunstan AB | Östra Storgatan 67, 553 21 Jönköping | tel: <a style="color:#093b35;text-decoration:none;" href="tel:0101798400">010-17 98 400</a> | mejl: <a style="color:#093b35;text-decoration:none;" href="mailto:info@dunstan.se">info@dunstan.se</a></p>
			</td>
		</tr>
	</table>
</body>
</html>
