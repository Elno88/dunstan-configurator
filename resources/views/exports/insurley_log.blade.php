<table>
    <thead>
    <tr>
        <th class="bold">Hämtningsdatum inkl. tid</th>
        <th>Namn (försäkringstagare)</th>
        <th>Personnummer</th>
        <th>Adress</th>
        <th>Postnummer</th>
        <th>Stad</th>
        <th>Namn (häst)</th>
        <th>Ras</th>
        <th>Kön</th>
        <th>Chipnummer</th>
        <th>Födelsedatum</th>
        <th>Försäkringsbolag</th>
        <th>Försäkringsnamn</th>
        <th>Försäkringsnummer</th>
        <th>Veterinärvårdsbelopp</th>
        <th>Veterinärvårdsbelopp resterande</th>
        <th>Livvärde</th>
        <th>Startdatum</th>
        <th>Förnyelsedatum</th>
        <th>Betalsätt</th>
        <th>Betaltermin</th>
        <th>Årspremie</th>
    </tr>
    </thead>
    <tbody>
        @foreach($insurley_rows as $row)
            <tr>
                <td>
                    @if(isset($row['log_entry']) && !empty($row['log_entry']))
                        {{ \Carbon\Carbon::parse($row['log_entry'])->format('d/m/Y H:i') }}
                    @endif
                </td>
                <td>{{ $row['insuranceHolderName'] ?? '' }}</td>
                <td>{{ $row['civic_number'] ?? '' }}</td>
                <td>{{ $row['insuranceHolderStreetAddress'] ?? '' }}</td>
                <td>{{ $row['insuranceHolderPostalCode'] ?? '' }}</td>
                <td>{{ $row['insuranceHolderCity'] ?? '' }}</td>
                <td>{{ $row['animalName'] ?? '' }}</td>
                <td>{{ $row['animalBreed'] ?? '' }}</td>
                <td>{{ $row['animalGender'] ?? '' }}</td>
                <td>{{ $row['chipNumber'] ?? '' }}</td>
                <td>
                    @if(isset($row['dateOfBirth']) && !empty($row['dateOfBirth']))
                        @php
                            try {
                                $date = \Carbon\Carbon::parse($row['dateOfBirth'])->format('d/m/Y');
                            } catch (\Exception $e) {
                                $date = '';
                            }
                        @endphp
                        {{ $date }}
                    @endif
                </td>
                <td>{{ $row['insuranceCompany'] ?? '' }}</td>
                <td>{{ $row['insuranceName'] ?? '' }}</td>
                <td>{{ $row['insuranceNumber'] ?? '' }}</td>
                <td>{{ $row['veterinaryCareAmount'] ?? '' }}</td>
                <td>{{ $row['veterinaryCareAmountRemaining'] ?? '' }}</td>
                <td>{{ $row['animalPurchasePrice'] ?? '' }}</td>
                <td>
                    @if(isset($row['startDate']) && !empty($row['startDate']))
                        @php
                            try {
                                $date = \Carbon\Carbon::parse($row['startDate'])->format('d/m/Y');
                            } catch (\Exception $e) {
                                $date = '';
                            }
                        @endphp
                        {{ $date }}
                    @endif
                </td>
                <td>
                    @if(isset($row['renewalDate']) && !empty($row['renewalDate']))
                        @php
                            try {
                                $date = \Carbon\Carbon::parse($row['renewalDate'])->format('d/m/Y');
                            } catch (\Exception $e) {
                                $date = '';
                            }
                        @endphp
                        {{ $date }}
                    @endif
                </td>
                <td>{{ $row['paymentMethod'] ?? '' }}</td>
                <td>{{ $row['premiumFrequency'] ?? '' }}</td>
                <td>{{ $row['premiumAmountYearRounded'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
