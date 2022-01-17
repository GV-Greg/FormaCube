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
        height: 125px;
        margin: 0 90px 0 100px;
    }

    .logo {
        width: 30%;
    }

    #logo-interface {
        position: relative;
        float: left;
        margin-top: -25px;
    }

    #informations {
        margin: 25px 90px 0 100px;
        font-size: 22px;
        line-height: 18px;
    }

    #date {
        margin: 50px 90px 0 100px;
        font-size: 22px;
        padding-left: 650px;
    }

    h1 {
        margin-top: 100px;
        font-size: 30px;
        line-height: 20px;
        text-align: center;
    }

    .uppercase {
        text-transform: uppercase;
    }

    #body-text {
        margin: 75px 90px 0 90px;
    }

    p {
        font-size: 20px;
        line-height: 18px;
        text-align: justify;
    }

    #signature {
        margin: 300px 90px 0 100px;
        font-size: 20px;
        padding-left: 650px;
    }

    #signature-vincianne {
        width: 85%;
    }
</style>

<body>
    <div id="entete">
        <div id="logo-interface">
            <img src="./images/Interface3Namur-logo.png" alt="logo-interface3namur" class="logo" >
        </div>
    </div>
    <div id="informations">
        Contact : Vincianne Rouard - Coordinatrice administrative et financi&egrave;re<br />
        T&eacute;l / Fax : 081/63.34.90<br />
        E-mail : v-rouard@interface3namur.be
    </div>
    <div id="date">
        Namur, le {{ $date }}
    </div>
    <h1>
        <span class="uppercase">ATTESTATION DE PARTICIPATION &Agrave; LA S&Eacute;ANCE D'INFORMATION</span><br />
        «{{$formation->nom}}» du {{ $recrutement->date->isoFormat('D MMMM Y') }}
    </h1>
    <div id="body-text">
        <p>Par la pr&eacute;sente, nous vous attestons que @if($candidat->genre === 'homme') Monsieur @elseif($candidat->genre === 'femme') Madame @else @endif
            {{ strtoupper($candidat->nom) }} {{ $candidat->prenom }} a bien particip&eacute; &agrave; la s&eacute;ance d'information pour la formation «{{$formation->nom}}» qui a lieu
            le {{ $recrutement->date->isoFormat('D MMMM Y') }} chez Interface3.Namur.</p>
        <p>Nous restons disponibles pour toute information compl&eacute;mentaire.</p>
    </div>
    <div id="signature">
        <img id="signature-vincianne" src="./images/signatures/signature-vincianne.png" alt="signature-vincianne">
        Vincianne Rouard<br />
        Coordinatrice administrative et financi&egrave;re
    </div>
</body>
