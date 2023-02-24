<?php

return [
    'steps' => [
        // Index
        \App\Steps\Index\Index::class,

        // Hästförsäkring
        \App\Steps\Horseinsurance\A\A::class,
        \App\Steps\Horseinsurance\A\A1::class,
        \App\Steps\Horseinsurance\A\A2::class,
        \App\Steps\Horseinsurance\A\A3::class,
        \App\Steps\Horseinsurance\A\A4::class,
        \App\Steps\Horseinsurance\A\A5::class,
        \App\Steps\Horseinsurance\A\A6::class, // Fölingsdatum, enbart foster o föl
        \App\Steps\Horseinsurance\A\A7::class,
        \App\Steps\Horseinsurance\A\A8::class,
        \App\Steps\Horseinsurance\A\A9::class,
        \App\Steps\Horseinsurance\A\A10::class,
        \App\Steps\Horseinsurance\A\AFFBetackning::class, // Nya för foster o föl (DFF), Betäckt, typ av bestäckning, seminstation (3 frågor)
        \App\Steps\Horseinsurance\A\AFFForsakring::class, // Nya för foster o föl (DFF), Försäkring

        // Jämför hästförsäkring
        \App\Steps\Horseinsurance\B\B1::class,
        \App\Steps\Horseinsurance\B\B2::class,
        \App\Steps\Horseinsurance\B\B3::class,
        \App\Steps\Horseinsurance\B\B4::class,
        \App\Steps\Horseinsurance\B\B5::class,
        \App\Steps\Horseinsurance\B\B6::class,
        \App\Steps\Horseinsurance\B\B7::class,
        \App\Steps\Horseinsurance\B\B8::class, // Föningsdatum, enbart foster o föl
        \App\Steps\Horseinsurance\B\B9::class,
        \App\Steps\Horseinsurance\B\B10::class,
        \App\Steps\Horseinsurance\B\B11::class,
        \App\Steps\Horseinsurance\B\B12::class,
        \App\Steps\Horseinsurance\B\BFFBetackning::class, // Nya för foster o föl (DFF), Betäckt, typ av bestäckning, seminstation (3 frågor)
        \App\Steps\Horseinsurance\B\BFFForsakring::class, // Nya för foster o föl (DFF), Försäkring


        // Hästförsäkring Resultat
        \App\Steps\Horseinsurance\Resultat::class,
        //\App\Steps\Horseinsurance\Fosterofol::class,
        \App\Steps\Horseinsurance\Halsodeklaration::class,
        \App\Steps\Horseinsurance\Sammanfattning::class,
        \App\Steps\Horseinsurance\Tack::class,

        // Kontakt
        \App\Steps\Horseinsurance\Kontakt::class,
        \App\Steps\Horseinsurance\KontaktTack::class,

        // Gårdsförsäkring insurley
        \App\Steps\Farminsurance\A\A::class,
        \App\Steps\Farminsurance\A\A1::class,
        \App\Steps\Farminsurance\B\B1::class,
        \App\Steps\Farminsurance\B\B2::class,
        \App\Steps\Farminsurance\B\B3::class,
        \App\Steps\Farminsurance\Kontakt::class,
        \App\Steps\Farminsurance\KontaktTack::class,
    ]
];
