<style>
    body{
        font-family: 'gadugi', sans-serif !important;
        font-weight: normal;
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    #entete {
        height: 100px;
    }

    #logo {
        width: 30%;
        position: relative;
        float: left;
        margin-top: -25px;
    }

    h1 {
        margin-top: 100px;
        font-size: 30px;
        line-height: 30px;
        text-align: center;
    }

    #date {
        margin: 50px 90px 0 100px;
        font-size: 22px;
        padding-left: 650px;
    }

    .uppercase {
        text-transform: uppercase;
    }

    #body-text {
        margin: 75px 90px 0 90px;
    }

    p {
        font-size: 20px;
        line-height: 20px;
        text-align: justify;
    }

    #date-bas {
        margin-top: 100px;
    }

    #signature {
        margin: 100px 90px 0 100px;
        font-size: 20px;
        padding-left: 625px;
    }
</style>

<body>
    <div id="entete">
        <div id="logo">
            <img src="./storage/images/logos/Logo_FormaCube-Noir-500x100.png" alt="logo-FormaCube">
        </div>
    </div>
    <div id="date">
        Namur, le {{ $date_courte }}
    </div>
    <h1>
        <span class="uppercase">ATTESTATION DE PARTICIPATION &Agrave; LA S&Eacute;ANCE D'INFORMATION</span><br />
        «{{$formation->nom}}» du {{ $recrutement->date->isoFormat('D MMMM Y') }}
    </h1>
    <div id="body-text">
        <p>Par la pr&eacute;sente, «FormaCube» attestons que @if($candidat->genre === 'homme') Monsieur @elseif($candidat->genre === 'femme') Madame @else @endif
            {{ strtoupper($candidat->nom) }} {{ $candidat->prenom }} a bien particip&eacute; &agrave; la s&eacute;ance d'information pour la formation «{{$formation->nom}}» qui a lieu
            le {{ $recrutement->date->isoFormat('D MMMM Y') }} chez «FormaCube».</p>
        <p>Nous restons disponibles pour toute information compl&eacute;mentaire.</p>
        <p id="date-bas">Fait à Namur, le {{ $date_longue }}</p>
    </div>
    <div id="signature">
        «FormaCube»
    </div>
</body>
